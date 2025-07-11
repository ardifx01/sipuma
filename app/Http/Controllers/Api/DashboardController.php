<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Publication;
use App\Models\User;
use App\Models\StudentProfile;
use Spatie\Permission\Models\Role;

/**
 * @method bool hasRole(string|array $role)
 */
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            $data = [
                'totalPublications' => Publication::count(),
                'pendingReviews' => Publication::where('admin_status', 'pending')->count(),
                'approvedPublications' => Publication::where('admin_status', 'approved')->count(),
                'mahasiswaCount' => User::role('mahasiswa')->count(),
                'dosenCount' => User::role('dosen')->count(),
                'adminCount' => User::role('admin')->count(),
            ];
        } elseif ($user->hasRole('mahasiswa')) {
            $publications = Publication::where('student_id', $user->id)->get();
            $data = [
                'totalPublications' => $publications->count(),
                'pendingPublications' => $publications->where('admin_status', 'pending')->count(),
                'approvedPublications' => $publications->where('admin_status', 'approved')->count(),
                'rejectedPublications' => $publications->where('admin_status', 'rejected')->count(),
            ];
        } elseif ($user->hasRole('dosen')) {
            $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
            $allPublications = Publication::whereIn('student_id', $students);
            
            $data = [
                'totalStudents' => $students->count(),
                'totalPublications' => $allPublications->count(),
                'pendingReviews' => Publication::whereIn('student_id', $students)
                    ->where('admin_status', 'approved')
                    ->where('dosen_status', 'pending')
                    ->count(),
                'reviewedPublications' => Publication::whereIn('student_id', $students)
                    ->whereIn('dosen_status', ['approved', 'rejected'])
                    ->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function adminReview()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $publications = Publication::where('admin_status', 'pending')
            ->with(['student', 'publicationType', 'student.studentProfile'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $publications,
        ]);
    }

    public function adminApprove(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $publication = Publication::findOrFail($id);
        $publication->update([
            'admin_status' => 'approved',
            'admin_feedback' => $request->feedback ?? 'Disetujui',
            'admin_reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil disetujui',
        ]);
    }

    public function adminReject(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $publication = Publication::findOrFail($id);
        $publication->update([
            'admin_status' => 'rejected',
            'admin_feedback' => $request->feedback ?? 'Ditolak',
            'admin_reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil ditolak',
        ]);
    }

    public function dosenReview()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('dosen')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
        $publications = Publication::whereIn('student_id', $students)
            ->where('admin_status', 'approved')
            ->where('dosen_status', 'pending')
            ->with(['student', 'publicationType', 'student.studentProfile'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $publications,
        ]);
    }

    public function dosenApprove(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('dosen')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
        $publication = Publication::whereIn('student_id', $students)->findOrFail($id);
        
        $publication->update([
            'dosen_status' => 'approved',
            'dosen_feedback' => $request->feedback ?? 'Disetujui',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil disetujui',
        ]);
    }

    public function dosenReject(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('dosen')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
        $publication = Publication::whereIn('student_id', $students)->findOrFail($id);
        
        $publication->update([
            'dosen_status' => 'rejected',
            'dosen_feedback' => $request->feedback ?? 'Ditolak',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil ditolak',
        ]);
    }
} 