<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\PublicationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'user_email' => Auth::user()->email ?? 'no email',
            'has_file' => $request->hasFile('file_path'),
            'has_loa_file' => $request->hasFile('loa_file_path'),
            'publication_status' => $request->publication_status,
            'tipe_publikasi' => $request->tipe_publikasi,
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string|required_if:publication_status,published',
            'keywords' => 'nullable|string|required_if:publication_status,published',
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
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240|required_if:publication_status,published',

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
            if (! in_array('Artikel', $tipePublikasi) || count($tipePublikasi) !== 1) {
                return back()->withErrors(['tipe_publikasi' => 'Untuk Skripsi, tipe publikasi harus Artikel saja.'])->withInput();
            }
        } elseif (in_array($sumberArtikel, ['Magang', 'Riset'])) {
            $validTipe = ['HKI', 'Paten Sederhana', 'Paten', 'Artikel', 'Buku']; // Hapus 'Skripsi'
            foreach ($tipePublikasi as $tipe) {
                if (! in_array($tipe, $validTipe)) {
                    return back()->withErrors(['tipe_publikasi' => 'Tipe publikasi tidak valid untuk '.$sumberArtikel])->withInput();
                }
            }

            // Log untuk debugging
            \Log::info('Validasi tipe publikasi Magang/Riset:', [
                'sumber_artikel' => $sumberArtikel,
                'tipe_publikasi' => $tipePublikasi,
                'valid_tipe' => $validTipe,
            ]);
        }

        // Handle main file upload (not required for LoA)
        $filePath = null;
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('publications', $fileName, 'public');

            // Debug file upload
            \Log::info('File upload details:', [
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'stored_path' => $filePath,
                'full_path' => storage_path('app/public/'.$filePath),
                'exists' => \Storage::disk('public')->exists($filePath),
            ]);
        }

        // Get publication type ID based on tipe_publikasi
        $publicationType = PublicationType::where('name', $request->tipe_publikasi[0])->first();
        if (! $publicationType) {
            // Create publication type if it doesn't exist
            $publicationType = PublicationType::create([
                'name' => $request->tipe_publikasi[0],
                'description' => 'Tipe publikasi: '.$request->tipe_publikasi[0],
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

        // Setelah $data = [ ... ];
        $data['admin_status'] = 'pending';
        $data['dosen_status'] = 'pending';
        $data['is_published'] = false;

        // Tambahkan log data yang akan disimpan
        \Log::info('Data yang akan disimpan:', $data);

        // Handle LoA file upload
        if ($request->hasFile('loa_file_path')) {
            $loaFile = $request->file('loa_file_path');
            $loaFileName = time().'_loa_'.$loaFile->getClientOriginalName();
            $loaFilePath = $loaFile->storeAs('publications/loa', $loaFileName, 'public');
            $data['loa_file_path'] = $loaFilePath;

            // Debug LoA file upload
            \Log::info('LoA file upload details:', [
                'original_name' => $loaFile->getClientOriginalName(),
                'file_size' => $loaFile->getSize(),
                'mime_type' => $loaFile->getMimeType(),
                'stored_path' => $loaFilePath,
                'full_path' => storage_path('app/public/'.$loaFilePath),
                'exists' => \Storage::disk('public')->exists($loaFilePath),
            ]);
        }

        // Handle HKI fields
        if (in_array('HKI', $tipePublikasi)) {
            $data['hki_publication_date'] = $request->hki_publication_date;
            $data['hki_creator'] = $request->hki_creator;

            if ($request->hasFile('hki_certificate')) {
                $hkiFile = $request->file('hki_certificate');
                $hkiFileName = time().'_hki_'.$hkiFile->getClientOriginalName();
                $hkiFilePath = $hkiFile->storeAs('publications/hki', $hkiFileName, 'public');
                $data['hki_certificate'] = $hkiFilePath;

                // Debug HKI file upload
                \Log::info('HKI file upload details:', [
                    'original_name' => $hkiFile->getClientOriginalName(),
                    'file_size' => $hkiFile->getSize(),
                    'mime_type' => $hkiFile->getMimeType(),
                    'stored_path' => $hkiFilePath,
                    'full_path' => storage_path('app/public/'.$hkiFilePath),
                    'exists' => \Storage::disk('public')->exists($hkiFilePath),
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
                $bookFileName = time().'_book_'.$bookFile->getClientOriginalName();
                $bookFilePath = $bookFile->storeAs('publications/books', $bookFileName, 'public');
                $data['book_pdf'] = $bookFilePath;

                // Debug Book file upload
                \Log::info('Book file upload details:', [
                    'original_name' => $bookFile->getClientOriginalName(),
                    'file_size' => $bookFile->getSize(),
                    'mime_type' => $bookFile->getMimeType(),
                    'stored_path' => $bookFilePath,
                    'full_path' => storage_path('app/public/'.$bookFilePath),
                    'exists' => \Storage::disk('public')->exists($bookFilePath),
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
                'created_at' => $publication->created_at,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Publikasi berhasil diupload! File telah disimpan dan siap untuk direview.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Publication upload failed: '.$e->getMessage());

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
            if (! in_array('Artikel', $tipePublikasi) || count($tipePublikasi) !== 1) {
                return back()->withErrors(['tipe_publikasi' => 'Untuk Skripsi, tipe publikasi harus Artikel saja.'])->withInput();
            }
        } elseif (in_array($sumberArtikel, ['Magang', 'Riset'])) {
            $validTipe = ['Skripsi', 'HKI', 'Paten Sederhana', 'Paten', 'Artikel', 'Buku'];
            foreach ($tipePublikasi as $tipe) {
                if (! in_array($tipe, $validTipe)) {
                    return back()->withErrors(['tipe_publikasi' => 'Tipe publikasi tidak valid untuk '.$sumberArtikel])->withInput();
                }
            }
        }

        // Get publication type ID based on tipe_publikasi
        $publicationType = PublicationType::where('name', $request->tipe_publikasi[0])->first();
        if (! $publicationType) {
            // Create publication type if it doesn't exist
            $publicationType = PublicationType::create([
                'name' => $request->tipe_publikasi[0],
                'description' => 'Tipe publikasi: '.$request->tipe_publikasi[0],
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
            $fileName = time().'_'.$file->getClientOriginalName();
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
            $loaFileName = time().'_loa_'.$loaFile->getClientOriginalName();
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
                $hkiFileName = time().'_hki_'.$hkiFile->getClientOriginalName();
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
                $bookFileName = time().'_book_'.$bookFile->getClientOriginalName();
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

        // Delete main publication file
        if ($publication->file_path) {
            Storage::disk('public')->delete($publication->file_path);
        }

        // Delete LoA file
        if ($publication->loa_file_path) {
            Storage::disk('public')->delete($publication->loa_file_path);
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

        return redirect()->route('dashboard')
            ->with('success', 'Publikasi berhasil dihapus!');
    }

    /**
     * Show form to update publication status from LoA to Published
     */
    public function showUpdateStatus(string $id)
    {
        try {
            $publication = Publication::where('student_id', Auth::id())
                ->findOrFail($id);

            // Hanya bisa update jika status saat ini adalah 'accepted' (LoA)
            if ($publication->publication_status !== 'accepted') {
                return redirect()->route('publications.show', $publication)
                    ->with('error', 'Hanya publikasi dengan status LoA yang dapat diupdate ke Published.');
            }

            return view('publications.update-status', compact('publication'));
        } catch (\Exception $e) {
            \Log::error('Error in showUpdateStatus: ' . $e->getMessage());
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update publication status from LoA to Published
     */
    public function updateStatus(Request $request, string $id)
    {
        $publication = Publication::where('student_id', Auth::id())
            ->findOrFail($id);

        // Validasi bahwa status saat ini adalah 'accepted'
        if ($publication->publication_status !== 'accepted') {
            return redirect()->route('publications.show', $publication)
                ->with('error', 'Hanya publikasi dengan status LoA yang dapat diupdate ke Published.');
        }

        $request->validate([
            'publication_status' => 'required|in:published',
            'journal_name' => 'required_if:publication_status,published|string|max:255',
            'journal_url' => 'required_if:publication_status,published|url|max:500',
            'doi' => 'nullable|string|max:255',
            'issn' => 'nullable|string|max:50',
            'publisher' => 'required_if:publication_status,published|string|max:255',
            'publication_date' => 'required_if:publication_status,published|date',
            'volume' => 'nullable|string|max:50',
            'issue' => 'nullable|string|max:50',
            'pages' => 'nullable|string|max:50',
            'indexing' => 'nullable|string|max:255',
            'file_path' => 'required_if:publication_status,published|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'journal_name.required_if' => 'Nama jurnal wajib diisi ketika status diubah ke Published.',
            'journal_url.required_if' => 'URL jurnal wajib diisi ketika status diubah ke Published.',
            'publisher.required_if' => 'Nama publisher wajib diisi ketika status diubah ke Published.',
            'publication_date.required_if' => 'Tanggal publikasi wajib diisi ketika status diubah ke Published.',
            'file_path.required_if' => 'File publikasi wajib diupload ketika status diubah ke Published.',
        ]);

        try {
            // Upload file publikasi jika ada
            $filePath = null;
            if ($request->hasFile('file_path')) {
                $filePath = $request->file('file_path')->store('publications', 'public');
            }

            // Update publikasi
            $publication->update([
                'publication_status' => $request->publication_status,
                'journal_name' => $request->journal_name,
                'journal_url' => $request->journal_url,
                'doi' => $request->doi,
                'issn' => $request->issn,
                'publisher' => $request->publisher,
                'publication_date' => $request->publication_date,
                'volume' => $request->volume,
                'issue' => $request->issue,
                'pages' => $request->pages,
                'indexing' => $request->indexing,
                'is_published' => true,
                'file_path' => $filePath ?: $publication->file_path, // Gunakan file baru atau tetap yang lama
            ]);

            // Reset status review untuk review ulang
            $publication->update([
                'admin_status' => 'pending',
                'dosen_status' => 'pending',
                'admin_feedback' => null,
                'dosen_feedback' => null,
            ]);

            return redirect()->route('publications.show', $publication)
                ->with('success', 'Status publikasi berhasil diupdate dari LoA ke Published! Publikasi akan direview ulang oleh dosen dan admin.');

        } catch (\Exception $e) {
            \Log::error('Error updating publication status: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate status publikasi. Silakan coba lagi.');
        }
    }

    /**
     * Download the publication file
     */
    public function download(string $id)
    {
        $publication = Publication::findOrFail($id);

        // Check if user has permission to download
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('dosen') || $publication->student_id === Auth::id()) {
            if ($publication->file_path && Storage::disk('public')->exists($publication->file_path)) {
                return Storage::disk('public')->download($publication->file_path);
            }
        }

        return back()->with('error', 'File tidak ditemukan atau Anda tidak memiliki izin untuk mengunduh file ini.');
    }

    public function downloadLoA(string $id)
    {
        $publication = Publication::findOrFail($id);

        // Check if user has permission to download
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('dosen') || $publication->student_id === Auth::id()) {
            if ($publication->loa_file_path && Storage::disk('public')->exists($publication->loa_file_path)) {
                return Storage::disk('public')->download($publication->loa_file_path);
            }
        }

        return back()->with('error', 'File LoA tidak ditemukan atau Anda tidak memiliki izin untuk mengunduh file ini.');
    }

    // Revision System Methods
    public function revise(string $id)
    {
        $publication = Publication::findOrFail($id);

        // Check if user can revise this publication
        if ($publication->student_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk merevisi publikasi ini.');
        }

        // Check if publication can be revised
        if (! $publication->canBeRevised()) {
            return back()->with('error', 'Publikasi ini tidak dapat direvisi.');
        }

        return view('publications.revise', compact('publication'));
    }

    public function submitRevision(Request $request, string $id)
    {
        $publication = Publication::findOrFail($id);

        // Check if user can revise this publication
        if ($publication->student_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk merevisi publikasi ini.');
        }

        // Check if publication can be revised
        if (! $publication->canBeRevised()) {
            return back()->with('error', 'Publikasi ini tidak dapat direvisi.');
        }

        // Reset publication for revision
        $publication->resetForRevision();

        // Update with new data
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string|required_unless:publication_status,accepted',
            'keywords' => 'nullable|string|required_unless:publication_status,accepted',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'loa_file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'publication_status' => 'required|in:accepted,published',
            'loa_date' => 'nullable|date',
            'loa_number' => 'nullable|string|max:255',
            'submission_date_to_publisher' => 'nullable|date',
            'expected_publication_date' => 'nullable|date',
        ]);

        // Handle file uploads
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time().'_rev'.$publication->revision_number.'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('publications', $fileName, 'public');
            $publication->file_path = $filePath;
        }

        if ($request->hasFile('loa_file_path')) {
            $loaFile = $request->file('loa_file_path');
            $loaFileName = time().'_rev'.$publication->revision_number.'_loa_'.$loaFile->getClientOriginalName();
            $loaFilePath = $loaFile->storeAs('publications/loa', $loaFileName, 'public');
            $publication->loa_file_path = $loaFilePath;
        }

        // Update publication data
        $publication->title = $request->title;
        $publication->abstract = $request->abstract ?? ($request->publication_status === 'accepted' ? 'LoA Document' : '');
        $publication->keywords = $request->keywords ?? ($request->publication_status === 'accepted' ? 'LoA' : '');
        $publication->publication_status = $request->publication_status;
        $publication->loa_date = $request->loa_date;
        $publication->loa_number = $request->loa_number;
        $publication->submission_date_to_publisher = $request->submission_date_to_publisher;
        $publication->expected_publication_date = $request->expected_publication_date;
        $publication->submission_date = now();

        $publication->save();

        return redirect()->route('publications.show', $publication->id)
            ->with('success', 'Publikasi berhasil direvisi. Revisi #'.$publication->revision_number.' telah dikirim untuk review.');
    }

    /**
     * Review publication by admin
     */
    public function review(Request $request, string $id)
    {
        // Only admin can review publications
        if (! Auth::user()->hasRole('admin')) {
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
