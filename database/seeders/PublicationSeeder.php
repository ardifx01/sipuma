<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publication;
use App\Models\User;
use App\Models\PublicationType;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user mahasiswa yang sudah ada
        $students = User::role('mahasiswa')->get();
        $publicationTypes = PublicationType::all();
        
        if ($students->isEmpty()) {
            $this->command->info('Tidak ada user mahasiswa. Membuat user mahasiswa terlebih dahulu...');
            return;
        }
        
        if ($publicationTypes->isEmpty()) {
            $this->command->info('Tidak ada tipe publikasi. Membuat tipe publikasi terlebih dahulu...');
            return;
        }
        
        // Buat 25 publikasi dummy
        for ($i = 1; $i <= 25; $i++) {
            $student = $students->random();
            $publicationType = $publicationTypes->random();
            
            // Tipe publikasi sebagai array
            $tipePublikasi = ['Jurnal Nasional', 'Jurnal Internasional', 'Konferensi'];
            $selectedTipe = $tipePublikasi[rand(0, 2)];
            
            Publication::create([
                'student_id' => $student->id,
                'title' => 'Publikasi Dummy ' . $i . ' - ' . $publicationType->name,
                'abstract' => 'Ini adalah abstrak untuk publikasi dummy nomor ' . $i . '. Publikasi ini dibuat untuk testing pagination di dashboard admin.',
                'keywords' => 'dummy, testing, pagination, admin',
                'journal_name' => 'Journal of Dummy Research',
                'journal_url' => 'https://example.com/journal',
                'indexing' => 'SCOPUS',
                'doi' => '10.1000/dummy.' . $i,
                'issn' => '1234-5678',
                'publisher' => 'Dummy Publisher',
                'publication_date' => now()->subDays(rand(1, 365)),
                'volume' => rand(1, 10),
                'issue' => rand(1, 12),
                'pages' => rand(1, 20),
                'publication_type_id' => $publicationType->id,
                'file_path' => 'dummy/file' . $i . '.pdf',
                'is_published' => true,
                'admin_status' => ['pending', 'approved', 'rejected'][rand(0, 2)],
                'dosen_status' => 'pending',
                'admin_feedback' => null,
                'dosen_feedback' => null,
                'submission_date' => now()->subDays(rand(1, 30)),
                'sumber_artikel' => 'Jurnal Nasional',
                'tipe_publikasi' => json_encode([$selectedTipe]), // Simpan sebagai array JSON
                'hki_publication_date' => null,
                'hki_creator' => null,
                'hki_certificate' => null,
                'book_title' => null,
                'book_publisher' => null,
                'book_year' => null,
                'book_edition' => null,
                'book_editor' => null,
                'book_isbn' => null,
                'book_pdf' => null,
                'admin_reviewed_at' => null,
            ]);
        }
        
        $this->command->info('Berhasil membuat 25 publikasi dummy!');
    }
} 