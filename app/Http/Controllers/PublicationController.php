<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\PublicationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

/**
 * @method bool hasRole(string|array $role)
 */
class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            // Admin melihat semua publikasi untuk review
            $publications = Publication::with(['publicationType', 'student', 'student.studentProfile'])
                ->orderBy('created_at', 'desc')
                ->paginate(20)->withQueryString();
        } else {
            // Mahasiswa hanya melihat publikasi mereka sendiri
            $publications = Publication::where('student_id', Auth::id())
                ->with(['publicationType', 'student', 'student.studentProfile'])
                ->orderBy('created_at', 'desc')
                ->paginate(15)->withQueryString();
        }

        return view('publications.index', compact('publications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('publications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'journal_name' => 'nullable|string|max:255',
            'journal_url' => 'nullable|url|max:500',
            'indexing' => 'nullable|string|max:255',
            'doi' => 'nullable|string|max:255',
            'issn' => 'nullable|string|max:50',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'volume' => 'nullable|string|max:50',
            'issue' => 'nullable|string|max:50',
            'pages' => 'nullable|string|max:50',
            'sumber_artikel' => 'required|in:Skripsi,Magang,Riset',
            'tipe_publikasi' => 'required|array|min:1',
            'tipe_publikasi.*' => 'string|in:Skripsi,HKI,Paten Sederhana,Paten,Artikel,Buku',
            'file_path' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            
            // Publication status and LoA validation
            'publication_status' => 'required|in:draft,submitted,accepted,published',
            'loa_date' => 'nullable|date',
            'loa_number' => 'nullable|string|max:255',
            'submission_date_to_publisher' => 'nullable|date',
            'expected_publication_date' => 'nullable|date',
            'publication_notes' => 'nullable|string',
            'publisher_name' => 'nullable|string|max:255',
            'journal_name_expected' => 'nullable|string|max:255',
            'publication_agreement_notes' => 'nullable|string',
            'loa_file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
            
            // HKI validation
            'hki_publication_date' => 'nullable|date|required_if:tipe_publikasi.*,HKI',
            'hki_creator' => 'nullable|string|max:255|required_if:tipe_publikasi.*,HKI',
            'hki_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240|required_if:tipe_publikasi.*,HKI',
            
            // Book validation
            'book_title' => 'nullable|string|max:255|required_if:tipe_publikasi.*,Buku',
            'book_publisher' => 'nullable|string|max:255|required_if:tipe_publikasi.*,Buku',
            'book_year' => 'nullable|integer|min:1900|max:2030|required_if:tipe_publikasi.*,Buku',
            'book_edition' => 'nullable|string|max:255',
            'book_editor' => 'nullable|string|max:255',
            'book_isbn' => 'nullable|string|max:255|required_if:tipe_publikasi.*,Buku',
            'book_pdf' => 'nullable|file|mimes:pdf|max:10240|required_if:tipe_publikasi.*,Buku',
        ]);

        // Validasi tambahan untuk tipe publikasi sesuai sumber artikel
        $sumberArtikel = $request->sumber_artikel;
        $tipePublikasi = $request->tipe_publikasi;

        if ($sumberArtikel === 'Skripsi') {
            if (!in_array('Artikel', $tipePublikasi) || count($tipePublikasi) !== 1) {
                return back()->withErrors(['tipe_publikasi' => 'Untuk Skripsi, tipe publikasi harus Artikel saja.'])->withInput();
            }
        } elseif (in_array($sumberArtikel, ['Magang', 'Riset'])) {
            $validTipe = ['Skripsi', 'HKI', 'Paten Sederhana', 'Paten', 'Artikel', 'Buku'];
            foreach ($tipePublikasi as $tipe) {
                if (!in_array($tipe, $validTipe)) {
                    return back()->withErrors(['tipe_publikasi' => 'Tipe publikasi tidak valid untuk ' . $sumberArtikel])->withInput();
                }
            }
        }

        $file = $request->file('file_path');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('publications', $fileName, 'public');

        $data = [
            'student_id' => Auth::id(),
            'title' => $request->title,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'journal_name' => $request->journal_name,
            'journal_url' => $request->journal_url,
            'indexing' => $request->indexing,
            'doi' => $request->doi,
            'issn' => $request->issn,
            'publisher' => $request->publisher,
            'publication_date' => $request->publication_date,
            'volume' => $request->volume,
            'issue' => $request->issue,
            'pages' => $request->pages,
            'sumber_artikel' => $request->sumber_artikel,
            'tipe_publikasi' => $request->tipe_publikasi,
            'file_path' => $filePath,
            'submission_date' => now(),
            'publication_status' => $request->publication_status,
            'loa_date' => $request->loa_date,
            'loa_number' => $request->loa_number,
            'submission_date_to_publisher' => $request->submission_date_to_publisher,
            'expected_publication_date' => $request->expected_publication_date,
            'publication_notes' => $request->publication_notes,
            'publisher_name' => $request->publisher_name,
            'journal_name_expected' => $request->journal_name_expected,
            'publication_agreement_notes' => $request->publication_agreement_notes,
        ];

        // Handle LoA file upload
        if ($request->hasFile('loa_file_path')) {
            $loaFile = $request->file('loa_file_path');
            $loaFileName = time() . '_loa_' . $loaFile->getClientOriginalName();
            $loaFilePath = $loaFile->storeAs('publications/loa', $loaFileName, 'public');
            $data['loa_file_path'] = $loaFilePath;
        }

        // Handle HKI fields
        if (in_array('HKI', $tipePublikasi)) {
            $data['hki_publication_date'] = $request->hki_publication_date;
            $data['hki_creator'] = $request->hki_creator;
            
            if ($request->hasFile('hki_certificate')) {
                $hkiFile = $request->file('hki_certificate');
                $hkiFileName = time() . '_hki_' . $hkiFile->getClientOriginalName();
                $hkiFilePath = $hkiFile->storeAs('publications/hki', $hkiFileName, 'public');
                $data['hki_certificate'] = $hkiFilePath;
            }
        }

        // Handle Book fields
        if (in_array('Buku', $tipePublikasi)) {
            $data['book_title'] = $request->book_title;
            $data['book_publisher'] = $request->book_publisher;
            $data['book_year'] = $request->book_year;
            $data['book_edition'] = $request->book_edition;
            $data['book_editor'] = $request->book_editor;
            $data['book_isbn'] = $request->book_isbn;
            
            if ($request->hasFile('book_pdf')) {
                $bookFile = $request->file('book_pdf');
                $bookFileName = time() . '_book_' . $bookFile->getClientOriginalName();
                $bookFilePath = $bookFile->storeAs('publications/books', $bookFileName, 'public');
                $data['book_pdf'] = $bookFilePath;
            }
        }

        Publication::create($data);

        return redirect()->route('publications.index')
            ->with('success', 'Publikasi berhasil diupload!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Auth::user()->hasRole('admin')) {
            // Admin bisa melihat semua publikasi
            $publication = Publication::with(['publicationType', 'reviews', 'student'])
                ->findOrFail($id);
        } else {
            // Mahasiswa hanya bisa melihat publikasi mereka sendiri
            $publication = Publication::where('student_id', Auth::id())
                ->with(['publicationType', 'reviews'])
                ->findOrFail($id);
        }

        return view('publications.show', compact('publication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $publication = Publication::where('student_id', Auth::id())
            ->findOrFail($id);

        return view('publications.edit', compact('publication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $publication = Publication::where('student_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'journal_name' => 'nullable|string|max:255',
            'journal_url' => 'nullable|url|max:500',
            'indexing' => 'nullable|string|max:255',
            'doi' => 'nullable|string|max:255',
            'issn' => 'nullable|string|max:50',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'volume' => 'nullable|string|max:50',
            'issue' => 'nullable|string|max:50',
            'pages' => 'nullable|string|max:50',
            'sumber_artikel' => 'required|in:Skripsi,Magang,Riset',
            'tipe_publikasi' => 'required|array|min:1',
            'tipe_publikasi.*' => 'string|in:Skripsi,HKI,Paten Sederhana,Paten,Artikel,Buku',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            
            // Publication status and LoA validation
            'publication_status' => 'required|in:draft,submitted,accepted,published',
            'loa_date' => 'nullable|date',
            'loa_number' => 'nullable|string|max:255',
            'submission_date_to_publisher' => 'nullable|date',
            'expected_publication_date' => 'nullable|date',
            'publication_notes' => 'nullable|string',
            'publisher_name' => 'nullable|string|max:255',
            'journal_name_expected' => 'nullable|string|max:255',
            'publication_agreement_notes' => 'nullable|string',
            'loa_file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
            
            // HKI validation
            'hki_publication_date' => 'nullable|date|required_if:tipe_publikasi.*,HKI',
            'hki_creator' => 'nullable|string|max:255|required_if:tipe_publikasi.*,HKI',
            'hki_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240|required_if:tipe_publikasi.*,HKI',
            
            // Book validation
            'book_title' => 'nullable|string|max:255|required_if:tipe_publikasi.*,Buku',
            'book_publisher' => 'nullable|string|max:255|required_if:tipe_publikasi.*,Buku',
            'book_year' => 'nullable|integer|min:1900|max:2030|required_if:tipe_publikasi.*,Buku',
            'book_edition' => 'nullable|string|max:255',
            'book_editor' => 'nullable|string|max:255',
            'book_isbn' => 'nullable|string|max:255|required_if:tipe_publikasi.*,Buku',
            'book_pdf' => 'nullable|file|mimes:pdf|max:10240|required_if:tipe_publikasi.*,Buku',
        ]);

        // Validasi tambahan untuk tipe publikasi sesuai sumber artikel
        $sumberArtikel = $request->sumber_artikel;
        $tipePublikasi = $request->tipe_publikasi;

        if ($sumberArtikel === 'Skripsi') {
            if (!in_array('Artikel', $tipePublikasi) || count($tipePublikasi) !== 1) {
                return back()->withErrors(['tipe_publikasi' => 'Untuk Skripsi, tipe publikasi harus Artikel saja.'])->withInput();
            }
        } elseif (in_array($sumberArtikel, ['Magang', 'Riset'])) {
            $validTipe = ['Skripsi', 'HKI', 'Paten Sederhana', 'Paten', 'Artikel', 'Buku'];
            foreach ($tipePublikasi as $tipe) {
                if (!in_array($tipe, $validTipe)) {
                    return back()->withErrors(['tipe_publikasi' => 'Tipe publikasi tidak valid untuk ' . $sumberArtikel])->withInput();
                }
            }
        }

        $data = [
            'title' => $request->title,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'journal_name' => $request->journal_name,
            'journal_url' => $request->journal_url,
            'indexing' => $request->indexing,
            'doi' => $request->doi,
            'issn' => $request->issn,
            'publisher' => $request->publisher,
            'publication_date' => $request->publication_date,
            'volume' => $request->volume,
            'issue' => $request->issue,
            'pages' => $request->pages,
            'sumber_artikel' => $request->sumber_artikel,
            'tipe_publikasi' => $request->tipe_publikasi,
            'publication_status' => $request->publication_status,
            'loa_date' => $request->loa_date,
            'loa_number' => $request->loa_number,
            'submission_date_to_publisher' => $request->submission_date_to_publisher,
            'expected_publication_date' => $request->expected_publication_date,
            'publication_notes' => $request->publication_notes,
            'publisher_name' => $request->publisher_name,
            'journal_name_expected' => $request->journal_name_expected,
            'publication_agreement_notes' => $request->publication_agreement_notes,
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            if ($publication->file_path) {
                Storage::disk('public')->delete($publication->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('publications', $fileName, 'public');
            $data['file_path'] = $filePath;
        }

        // Handle LoA file upload
        if ($request->hasFile('loa_file_path')) {
            // Delete old LoA file
            if ($publication->loa_file_path) {
                Storage::disk('public')->delete($publication->loa_file_path);
            }
            
            $loaFile = $request->file('loa_file_path');
            $loaFileName = time() . '_loa_' . $loaFile->getClientOriginalName();
            $loaFilePath = $loaFile->storeAs('publications/loa', $loaFileName, 'public');
            $data['loa_file_path'] = $loaFilePath;
        }

        // Handle HKI fields
        if (in_array('HKI', $tipePublikasi)) {
            $data['hki_publication_date'] = $request->hki_publication_date;
            $data['hki_creator'] = $request->hki_creator;
            
            if ($request->hasFile('hki_certificate')) {
                // Delete old HKI certificate file
                if ($publication->hki_certificate) {
                    Storage::disk('public')->delete($publication->hki_certificate);
                }
                
                $hkiFile = $request->file('hki_certificate');
                $hkiFileName = time() . '_hki_' . $hkiFile->getClientOriginalName();
                $hkiFilePath = $hkiFile->storeAs('publications/hki', $hkiFileName, 'public');
                $data['hki_certificate'] = $hkiFilePath;
            }
        } else {
            // Clear HKI fields if not selected
            $data['hki_publication_date'] = null;
            $data['hki_creator'] = null;
            if ($publication->hki_certificate) {
                Storage::disk('public')->delete($publication->hki_certificate);
                $data['hki_certificate'] = null;
            }
        }

        // Handle Book fields
        if (in_array('Buku', $tipePublikasi)) {
            $data['book_title'] = $request->book_title;
            $data['book_publisher'] = $request->book_publisher;
            $data['book_year'] = $request->book_year;
            $data['book_edition'] = $request->book_edition;
            $data['book_editor'] = $request->book_editor;
            $data['book_isbn'] = $request->book_isbn;
            
            if ($request->hasFile('book_pdf')) {
                // Delete old book PDF file
                if ($publication->book_pdf) {
                    Storage::disk('public')->delete($publication->book_pdf);
                }
                
                $bookFile = $request->file('book_pdf');
                $bookFileName = time() . '_book_' . $bookFile->getClientOriginalName();
                $bookFilePath = $bookFile->storeAs('publications/books', $bookFileName, 'public');
                $data['book_pdf'] = $bookFilePath;
            }
        } else {
            // Clear Book fields if not selected
            $data['book_title'] = null;
            $data['book_publisher'] = null;
            $data['book_year'] = null;
            $data['book_edition'] = null;
            $data['book_editor'] = null;
            $data['book_isbn'] = null;
            if ($publication->book_pdf) {
                Storage::disk('public')->delete($publication->book_pdf);
                $data['book_pdf'] = null;
            }
        }

        $publication->update($data);

        return redirect()->route('publications.index')
            ->with('success', 'Publikasi berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $publication = Publication::where('student_id', Auth::id())
            ->findOrFail($id);

        if ($publication->file_path) {
            Storage::disk('public')->delete($publication->file_path);
        }

        // Delete HKI certificate file
        if ($publication->hki_certificate) {
            Storage::disk('public')->delete($publication->hki_certificate);
        }

        // Delete Book PDF file
        if ($publication->book_pdf) {
            Storage::disk('public')->delete($publication->book_pdf);
        }

        $publication->delete();

        return redirect()->route('publications.index')
            ->with('success', 'Publikasi berhasil dihapus!');
    }

    /**
     * Download the publication file
     */
    public function download(string $id)
    {
        if (Auth::user()->hasRole('admin')) {
            // Admin bisa download semua file
            $publication = Publication::findOrFail($id);
        } else {
            // Mahasiswa hanya bisa download file mereka sendiri
            $publication = Publication::where('student_id', Auth::id())
                ->findOrFail($id);
        }

        if (!Storage::disk('public')->exists($publication->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($publication->file_path);
    }

    /**
     * Review publication by admin
     */
    public function review(Request $request, string $id)
    {
        // Only admin can review publications
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $publication = Publication::findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $action = $request->action;
        $feedback = $request->feedback;

        if ($action === 'approve') {
            $publication->update([
                'admin_status' => 'approved',
                'admin_feedback' => $feedback,
                'admin_reviewed_at' => now(),
            ]);

            $message = 'Publikasi berhasil disetujui!';
        } else {
            $publication->update([
                'admin_status' => 'rejected',
                'admin_feedback' => $feedback,
                'admin_reviewed_at' => now(),
            ]);

            $message = 'Publikasi berhasil ditolak!';
        }

        return redirect()->route('publications.index')
            ->with('success', $message);
    }
}
