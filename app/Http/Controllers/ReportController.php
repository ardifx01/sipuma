<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Laporan Statistik Publikasi untuk LAM Infokom
     */
    public function lamInfokomReport(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Statistik publikasi per tahun
        $publicationsByYear = Publication::selectRaw('
            YEAR(created_at) as year,
            COUNT(*) as total,
            SUM(CASE WHEN publication_status = "published" THEN 1 ELSE 0 END) as published,
            SUM(CASE WHEN publication_status = "accepted" THEN 1 ELSE 0 END) as accepted,
            SUM(CASE WHEN publication_status = "submitted" THEN 1 ELSE 0 END) as submitted
        ')
        ->groupBy('year')
        ->orderBy('year', 'desc')
        ->get();

        // Statistik per jenis publikasi
        $publicationsByType = Publication::join('publication_types', 'publications.publication_type_id', '=', 'publication_types.id')
            ->selectRaw('
                publication_types.name as type,
                COUNT(*) as total,
                SUM(CASE WHEN publications.publication_status = "published" THEN 1 ELSE 0 END) as published
            ')
            ->groupBy('publication_types.id', 'publication_types.name')
            ->get();

        // Statistik per program studi
        $publicationsByStudyProgram = Publication::join('users', 'publications.student_id', '=', 'users.id')
            ->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
            ->selectRaw('
                student_profiles.major as study_program,
                COUNT(*) as total,
                SUM(CASE WHEN publications.publication_status = "published" THEN 1 ELSE 0 END) as published
            ')
            ->groupBy('student_profiles.major')
            ->get();

        // Daftar publikasi yang sudah published
        $publishedPublications = Publication::with(['student', 'student.studentProfile', 'publicationType'])
            ->where('publication_status', 'published')
            ->whereYear('created_at', $year)
            ->orderBy('publication_date', 'desc')
            ->get();

        // Statistik per dosen pembimbing
        $publicationsBySupervisor = Publication::join('users as students', 'publications.student_id', '=', 'students.id')
            ->join('student_profiles', 'students.id', '=', 'student_profiles.user_id')
            ->join('users as supervisors', 'student_profiles.supervisor_id', '=', 'supervisors.id')
            ->selectRaw('
                supervisors.name as supervisor_name,
                COUNT(*) as total,
                SUM(CASE WHEN publications.publication_status = "published" THEN 1 ELSE 0 END) as published
            ')
            ->groupBy('supervisors.id', 'supervisors.name')
            ->get();

        return view('reports.lam-infokom', compact(
            'publicationsByYear',
            'publicationsByType', 
            'publicationsByStudyProgram',
            'publishedPublications',
            'publicationsBySupervisor',
            'year'
        ));
    }

    /**
     * Export laporan ke PDF
     */
    public function exportLamInfokomPDF(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Ambil data yang sama seperti di atas
        $publishedPublications = Publication::with(['student', 'student.studentProfile', 'publicationType'])
            ->where('publication_status', 'published')
            ->whereYear('created_at', $year)
            ->orderBy('publication_date', 'desc')
            ->get();

        $publicationsByType = Publication::join('publication_types', 'publications.publication_type_id', '=', 'publication_types.id')
            ->selectRaw('
                publication_types.name as type,
                COUNT(*) as total,
                SUM(CASE WHEN publications.publication_status = "published" THEN 1 ELSE 0 END) as published
            ')
            ->groupBy('publication_types.id', 'publication_types.name')
            ->get();

        // Generate PDF menggunakan library seperti DomPDF
        // $pdf = PDF::loadView('reports.pdf.lam-infokom', compact('publishedPublications', 'publicationsByType', 'year'));
        // return $pdf->download("laporan-publikasi-lam-infokom-{$year}.pdf");
        
        return view('reports.pdf.lam-infokom', compact('publishedPublications', 'publicationsByType', 'year'));
    }

    /**
     * Generate sertifikat publikasi untuk mahasiswa
     */
    public function generateCertificate($publicationId)
    {
        $publication = Publication::with(['student', 'student.studentProfile', 'publicationType'])
            ->findOrFail($publicationId);

        if ($publication->publication_status !== 'published') {
            return back()->with('error', 'Sertifikat hanya dapat dibuat untuk publikasi yang sudah terbit');
        }

        return view('reports.certificate', compact('publication'));
    }

    /**
     * Dashboard statistik untuk admin
     */
    public function dashboardStats()
    {
        $currentYear = date('Y');
        
        $stats = [
            'total_publications' => Publication::count(),
            'published_this_year' => Publication::where('publication_status', 'published')
                ->whereYear('created_at', $currentYear)->count(),
            'pending_reviews' => Publication::where('admin_status', 'pending')->count(),
            'accepted_publications' => Publication::where('publication_status', 'accepted')->count(),
        ];

        return response()->json($stats);
    }
}
