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
        // Debug: Log request data
        \Log::info('Publication store method called', [
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'no email'
        ]);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string|required_unless:publication_status,accepted',
            'keywords' => 'nullable|string|required_unless:publication_status,accepted',
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
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240|required_unless:publication_status,accepted', // Max 10MB, not required for LoA
            
            // Publication status and LoA validation
            'publication_status' => 'required|in:accepted,published',
            'loa_date' => 'nullable|date',
            'loa_number' => 'nullable|string|max:255',
            'submission_date_to_publisher' => 'nullable|date',
            'expected_publication_date' => 'nullable|date',
            'publication_notes' => 'nullable|string',
            'publisher_name' => 'nullable|string|max:255',
            'journal_name_expected' => 'nullable|string|max:255',
            'publication_agreement_notes' => 'nullable|string',
            'loa_file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120|required_if:publication_status,accepted', // Max 5MB, required for accepted status
            
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

        // Handle main file upload (not required for LoA)
        $filePath = null;
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('publications', $fileName, 'public');
            
            // Debug file upload
            \Log::info('File upload details:', [
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'stored_path' => $filePath,
                'full_path' => storage_path('app/public/' . $filePath),
                'exists' => \Storage::disk('public')->exists($filePath)
            ]);
        }

        // Get publication type ID based on tipe_publikasi
        $publicationType = PublicationType::where('name', $request->tipe_publikasi[0])->first();
        if (!$publicationType) {
            // Create publication type if it doesn't exist
            $publicationType = PublicationType::create([
                'name' => $request->tipe_publikasi[0],
                'description' => 'Tipe publikasi: ' . $request->tipe_publikasi[0]
            ]);
        }

        $data = [
            'student_id' => Auth::id(),
            'title' => $request->title,
            'abstract' => $request->abstract ?? ($request->publication_status === 'accepted' ? 'LoA Document' : ''),
            'keywords' => $request->keywords ?? ($request->publication_status === 'accepted' ? 'LoA' : ''),
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
            'publication_type_id' => $publicationType->id,
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
            
            // Debug LoA file upload
            \Log::info('LoA file upload details:', [
                'original_name' => $loaFile->getClientOriginalName(),
                'file_size' => $loaFile->getSize(),
                'mime_type' => $loaFile->getMimeType(),
                'stored_path' => $loaFilePath,
                'full_path' => storage_path('app/public/' . $loaFilePath),
                'exists' => \Storage::disk('public')->exists($loaFilePath)
            ]);
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
                
                // Debug HKI file upload
                \Log::info('HKI file upload details:', [
                    'original_name' => $hkiFile->getClientOriginalName(),
                    'file_size' => $hkiFile->getSize(),
                    'mime_type' => $hkiFile->getMimeType(),
                    'stored_path' => $hkiFilePath,
                    'full_path' => storage_path('app/public/' . $hkiFilePath),
                    'exists' => \Storage::disk('public')->exists($hkiFilePath)
                ]);
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
                
                // Debug Book file upload
                \Log::info('Book file upload details:', [
                    'original_name' => $bookFile->getClientOriginalName(),
                    'file_size' => $bookFile->getSize(),
                    'mime_type' => $bookFile->getMimeType(),
                    'stored_path' => $bookFilePath,
                    'full_path' => storage_path('app/public/' . $bookFilePath),
                    'exists' => \Storage::disk('public')->exists($bookFilePath)
                ]);
            }
        }

        try {
            $publication = Publication::create($data);
            
            // Debug database insertion
            \Log::info('Publication created successfully:', [
                'publication_id' => $publication->id,
                'title' => $publication->title,
                'file_path' => $publication->file_path,
                'loa_file_path' => $publication->loa_file_path,
                'hki_certificate' => $publication->hki_certificate,
                'book_pdf' => $publication->book_pdf,
                'student_id' => $publication->student_id,
                'created_at' => $publication->created_at
            ]);
            
            return redirect()->route('dashboard')
                ->with('success', 'Publikasi berhasil diupload! File telah disimpan dan siap untuk direview.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Publication upload failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal mengupload publikasi. Silakan coba lagi atau hubungi administrator.')
                ->withInput();
        }
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
            'abstract' => 'nullable|string|required_unless:publication_status,accepted',
            'keywords' => 'nullable|string|required_unless:publication_status,accepted',
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
            'publication_status' => 'required|in:accepted,published',
            'loa_date' => 'nullable|date',
            'loa_number' => 'nullable|string|max:255',
            'submission_date_to_publisher' => 'nullable|date',
            'expected_publication_date' => 'nullable|date',
            'publication_notes' => 'nullable|string',
            'publisher_name' => 'nullable|string|max:255',
            'journal_name_expected' => 'nullable|string|max:255',
            'publication_agreement_notes' => 'nullable|string',
            'loa_file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120|required_if:publication_status,accepted', // Max 5MB, required for accepted status
            
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

        // Get publication type ID based on tipe_publikasi
        $publicationType = PublicationType::where('name', $request->tipe_publikasi[0])->first();
        if (!$publicationType) {
            // Create publication type if it doesn't exist
            $publicationType = PublicationType::create([
                'name' => $request->tipe_publikasi[0],
                'description' => 'Tipe publikasi: ' . $request->tipe_publikasi[0]
            ]);
        }

        $data = [
            'title' => $request->title,
            'abstract' => $request->abstract ?? ($request->publication_status === 'accepted' ? 'LoA Document' : ''),
            'keywords' => $request->keywords ?? ($request->publication_status === 'accepted' ? 'LoA' : ''),
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
            'publication_type_id' => $publicationType->id,
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
