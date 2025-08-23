<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Review;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

/**
 * @method bool hasRole(string|array $role)
 */
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard($request);
        } elseif ($user->hasRole('mahasiswa')) {
            return $this->mahasiswaDashboard();
        } elseif ($user->hasRole('dosen')) {
            return $this->dosenDashboard();
        }

        // Fallback untuk user tanpa role
        return redirect()->route('welcome');
    }

    private function adminDashboard(Request $request)
    {
        // Query untuk publikasi yang perlu direview (pending) dan yang sudah approved
        $recentQuery = Publication::with(['student', 'publicationType', 'student.studentProfile'])
            ->whereIn('admin_status', ['pending', 'approved']);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $recentQuery->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('studentProfile', function ($q2) use ($search) {
                        $q2->where('nim', 'like', "%$search%");
                    });
            });
        }
        $data = [
            'totalPublications' => Publication::count(),
            'pendingReviews' => Publication::where('admin_status', 'pending')->count(),
            'approvedPublications' => Publication::where('admin_status', 'approved')->count(),
            'mahasiswaCount' => User::role('mahasiswa')->count(),
            'dosenCount' => User::role('dosen')->count(),
            'adminCount' => User::role('admin')->count(),
            'recentPublications' => $recentQuery->orderBy('admin_status', 'asc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString(),
            'pendingPublications' => Publication::where('admin_status', 'pending')
                ->with(['student', 'publicationType', 'student.studentProfile'])
                ->latest()
                ->get(),
        ];

        return view('dashboard.admin', $data);
    }

    private function mahasiswaDashboard()
    {
        $user = Auth::user();
        $studentProfile = $user->studentProfile;

        $publications = Publication::where('student_id', $user->id)
            ->with(['publicationType', 'student', 'student.studentProfile'])
            ->latest()
            ->get();

        $data = [
            'totalPublications' => $publications->count(),
            'pendingPublications' => $publications->where('admin_status', 'pending')->count(),
            'approvedPublications' => $publications->where('admin_status', 'approved')->count(),
            'rejectedPublications' => $publications->where('admin_status', 'rejected')->count(),
            'publications' => $publications,
        ];

        return view('dashboard.mahasiswa', $data);
    }

    private function dosenDashboard()
    {
        $user = Auth::user();

        // Ambil mahasiswa bimbingan
        $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');

        // Semua publikasi bimbingan
        $allPublications = Publication::whereIn('student_id', $students)
            ->with(['student', 'student.studentProfile', 'publicationType']);

        // Total semua publikasi bimbingan
        $totalPublications = $allPublications->count();

        // Menunggu review dosen (admin sudah approve, dosen belum review) atau admin masih pending
        $pendingReviews = Publication::whereIn('student_id', $students)
            ->where(function ($query) {
                $query->where('admin_status', 'approved')
                    ->where('dosen_status', 'pending')
                    ->orWhere(function ($q) {
                        $q->where('admin_status', 'pending')
                            ->where('dosen_status', 'pending');
                    });
            })
            ->with(['student', 'student.studentProfile', 'publicationType'])
            ->count();

        // Sudah direview dosen (approved/rejected)
        $reviewedPublications = Publication::whereIn('student_id', $students)
            ->whereIn('dosen_status', ['approved', 'rejected'])
            ->with(['student', 'student.studentProfile', 'publicationType'])
            ->count();

        // Diagnosis: publikasi dengan admin_status = pending
        $pendingAdmin = Publication::whereIn('student_id', $students)
            ->where('admin_status', 'pending')
            ->with(['student', 'student.studentProfile', 'publicationType'])
            ->count();

        // Diagnosis: publikasi dengan admin_status = rejected
        $rejectedAdmin = Publication::whereIn('student_id', $students)
            ->where('admin_status', 'rejected')
            ->with(['student', 'student.studentProfile', 'publicationType'])
            ->count();

        // Diagnosis: publikasi dengan dosen_status selain pending/approved/rejected
        $otherDosenStatus = Publication::whereIn('student_id', $students)
            ->whereNotIn('dosen_status', ['pending', 'approved', 'rejected'])
            ->with(['student', 'student.studentProfile', 'publicationType'])
            ->count();

        // Data lain (untuk tampilan tabel/list)
        $pendingPublications = Publication::whereIn('student_id', $students)
            ->where(function ($query) {
                $query->where('admin_status', 'approved')
                    ->where('dosen_status', 'pending')
                    ->orWhere('admin_status', 'pending');
            })
            ->with(['student', 'student.studentProfile', 'publicationType'])
            ->latest()
            ->get();

        $recentReviews = Review::where('reviewer_id', $user->id)
            ->with(['publication.student', 'publication.student.studentProfile'])
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'totalStudents' => $students->count(),
            'totalPublications' => $totalPublications,
            'pendingReviews' => $pendingReviews,
            'reviewedPublications' => $reviewedPublications,
            'pendingAdmin' => $pendingAdmin,
            'rejectedAdmin' => $rejectedAdmin,
            'otherDosenStatus' => $otherDosenStatus,
            'pendingPublications' => $pendingPublications,
            'students' => StudentProfile::where('supervisor_id', $user->id)->with(['user', 'publications'])->get(),
            'recentReviews' => $recentReviews,
        ];

        return view('dashboard.dosen', $data);
    }

    public function reviewPublications(Request $request)
    {
        $query = Publication::where('admin_status', 'pending')
            ->with(['student', 'publicationType', 'student.studentProfile']);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('studentProfile', function ($q2) use ($search) {
                        $q2->where('nim', 'like', "%$search%");
                    });
            });
        }
        $publications = $query->latest()->paginate(20)->withQueryString();

        return view('dashboard.admin-review', compact('publications'));
    }

    public function reviewDetail($id)
    {
        $publication = Publication::with([
            'student',
            'publicationType',
            'student.studentProfile',
            'reviews',
        ])->findOrFail($id);

        return view('dashboard.admin-review-detail', compact('publication'));
    }

    public function approvePublication(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);
        if ($publication->dosen_status !== 'approved' || $publication->admin_status !== 'pending') {
            return redirect()->route('dashboard.admin-review')->with('error', 'Publikasi hanya bisa direview admin jika sudah disetujui dosen.');
        }
        $publication->update([
            'admin_status' => 'approved',
            'admin_feedback' => $request->feedback,
            'admin_reviewed_at' => now(),
        ]);

        return redirect()->route('dashboard.admin-review')
            ->with('success', 'Publikasi berhasil disetujui!');
    }

    public function rejectPublication(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);
        if ($publication->dosen_status !== 'approved' || $publication->admin_status !== 'pending') {
            return redirect()->route('dashboard.admin-review')->with('error', 'Publikasi hanya bisa direview admin jika sudah disetujui dosen.');
        }
        $publication->update([
            'admin_status' => 'rejected',
            'admin_feedback' => $request->feedback,
            'rejection_reason' => $request->feedback, // Simpan alasan penolakan
            'admin_reviewed_at' => now(),
        ]);

        return redirect()->route('dashboard.admin-review')
            ->with('success', 'Publikasi berhasil ditolak!');
    }

    // Dosen Review Methods
    public function dosenReviewPublications()
    {
        $user = Auth::user();

        // Ambil mahasiswa yang dibimbing oleh dosen ini
        $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');

        $publications = Publication::whereIn('student_id', $students)
            ->where(function ($query) {
                $query->where('admin_status', 'approved')
                    ->where('dosen_status', 'pending')
                    ->orWhere('admin_status', 'pending')
                    ->where('dosen_status', 'pending');
            })
            ->with(['student', 'publicationType', 'student.studentProfile'])
            ->latest()
            ->paginate(20)->withQueryString();

        return view('dashboard.dosen-review', compact('publications'));
    }

    public function dosenReviewDetail($id)
    {
        $user = Auth::user();
        // Pastikan dosen hanya bisa melihat publikasi mahasiswa bimbingannya
        $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
        $publication = Publication::whereIn('student_id', $students)
            ->with([
                'student',
                'publicationType',
                'student.studentProfile',
                'reviews',
            ])->findOrFail($id);

        return view('dashboard.dosen-review-detail', compact('publication'));
    }

    public function dosenApprovePublication(Request $request, $id)
    {
        $user = Auth::user();

        // Pastikan dosen hanya bisa review publikasi mahasiswa bimbingannya
        $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');

        $publication = Publication::whereIn('student_id', $students)
            ->where('dosen_status', 'pending')
            ->findOrFail($id);

        $publication->update([
            'dosen_status' => 'approved',
            'dosen_feedback' => $request->feedback,
        ]);

        return redirect()->route('dashboard.dosen-review')
            ->with('success', 'Publikasi berhasil disetujui!');
    }

    public function dosenRejectPublication(Request $request, $id)
    {
        $user = Auth::user();

        // Pastikan dosen hanya bisa review publikasi mahasiswa bimbingannya
        $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');

        $publication = Publication::whereIn('student_id', $students)
            ->where('dosen_status', 'pending')
            ->findOrFail($id);

        $publication->update([
            'dosen_status' => 'rejected',
            'dosen_feedback' => $request->feedback,
            'rejection_reason' => $request->feedback, // Simpan alasan penolakan
        ]);

        return redirect()->route('dashboard.dosen-review')
            ->with('success', 'Publikasi berhasil ditolak!');
    }

    // Student-Dosen Management
    public function manageStudents(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('dosen')) {
            // Dosen melihat mahasiswa bimbingannya
            $query = StudentProfile::where('supervisor_id', $user->id)
                ->with(['user', 'publications']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })->orWhere('nim', 'like', "%$search%");
            }

            $students = $query->paginate(15)->withQueryString();

            // If AJAX request, return only the table content
            if ($request->has('ajax')) {
                return view('dashboard.manage-students', compact('students'))->render();
            }

            return view('dashboard.manage-students', compact('students'));
        } elseif ($user->hasRole('admin')) {
            // Admin melihat semua mahasiswa dan bisa assign dosen
            $query = StudentProfile::with(['user', 'supervisor']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })->orWhere('nim', 'like', "%$search%");
            }

            $students = $query->paginate(20)->withQueryString();
            $dosen = User::role('dosen')->get();

            // If AJAX request, return only the table content
            if ($request->has('ajax')) {
                return view('dashboard.manage-students', compact('students', 'dosen'))->render();
            }

            return view('dashboard.manage-students', compact('students', 'dosen'));
        }

        return redirect()->route('dashboard');
    }

    public function assignSupervisor(Request $request, $studentId)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
        ]);

        $studentProfile = StudentProfile::where('user_id', $studentId)->firstOrFail();
        $studentProfile->update(['supervisor_id' => $request->supervisor_id]);

        return redirect()->route('dashboard.manage-students')
            ->with('success', 'Dosen pembimbing berhasil diassign!');
    }

    public function removeSupervisor($studentId)
    {
        $studentProfile = StudentProfile::where('user_id', $studentId)->firstOrFail();
        $studentProfile->update(['supervisor_id' => null]);

        return redirect()->route('dashboard.manage-students')
            ->with('success', 'Dosen pembimbing berhasil dihapus!');
    }

    public function approvedPublications(Request $request)
    {
        $query = Publication::with(['student', 'publicationType', 'student.studentProfile'])
            ->where('admin_status', 'approved');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('studentProfile', function ($q2) use ($search) {
                        $q2->where('nim', 'like', "%$search%");
                    });
            });
        }

        $publications = $query->orderByDesc('admin_reviewed_at')->paginate(20)->withQueryString();

        return view('dashboard.admin-approved-publications', compact('publications'));
    }

    public function ajaxSearchPublications(Request $request)
    {
        $type = $request->input('type', 'all');
        $search = $request->input('search', '');
        $query = Publication::with(['student', 'publicationType', 'student.studentProfile']);
        if ($type === 'approved') {
            $query->where('admin_status', 'approved');
        } elseif ($type === 'pending') {
            $query->where('admin_status', 'pending');
        }
        if ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('studentProfile', function ($q2) use ($search) {
                        $q2->where('nim', 'like', "%$search%");
                    });
            });
        }
        $publications = $query->orderByDesc('admin_reviewed_at')->limit(20)->get();
        $result = $publications->map(function ($pub) {
            return [
                'id' => $pub->id,
                'title' => $pub->title,
                'student_name' => $pub->student->name ?? '-',
                'nim' => $pub->student->studentProfile->nim ?? '-',
                'type' => $pub->publicationType->name ?? '-',
                'admin_status' => $pub->admin_status,
                'admin_reviewed_at' => $pub->admin_reviewed_at ? $pub->admin_reviewed_at->format('d/m/Y H:i') : '-',
                'detail_url' => route('dashboard.admin-review-detail', $pub->id),
                'download_url' => $pub->file_path ? route('publications.download', $pub->id) : null,
            ];
        });

        return response()->json(['data' => $result]);
    }

    public function publicationsByStudent(Request $request, $userId)
    {
        $user = User::with('studentProfile')->findOrFail($userId);
        $query = Publication::with(['publicationType'])
            ->where('student_id', $user->id);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%$search%")
                ->orWhere('keywords', 'like', "%$search%")
                ->orWhere('abstract', 'like', "%$search%");
        }
        if ($request->input('sort') === 'type') {
            $query->orderBy('publication_type_id')->orderByDesc('created_at');
        } else {
            $query->orderByDesc('created_at');
        }
        $publications = $query->paginate(20)->withQueryString();

        return view('dashboard.student-publications', compact('user', 'publications'));
    }

    public function dosenAllPublications(Request $request)
    {
        $user = Auth::user();
        $students = StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
        $query = Publication::with(['student', 'publicationType'])
            ->whereIn('student_id', $students);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhereHas('student', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            });
        }
        $publications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('dashboard.dosen-all-publications', compact('publications'));
    }

    public function adminAllPublications(Request $request)
    {
        $query = Publication::with(['student', 'publicationType', 'student.studentProfile']);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhereHas('student', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            });
        }
        $publications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('dashboard.admin-all-publications', compact('publications'));
    }
}
