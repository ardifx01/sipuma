<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Publication;
use App\Models\PublicationType;
use Spatie\Permission\Models\Role;

/**
 * @method bool hasRole(string|array $role)
 */
class PublicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            $publications = Publication::with(['student', 'publicationType'])->latest()->get();
        } elseif ($user->hasRole('dosen')) {
            $students = \App\Models\StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
            $publications = Publication::whereIn('student_id', $students)
                ->with(['student', 'publicationType'])
                ->latest()
                ->get();
        } else {
            $publications = Publication::where('student_id', $user->id)
                ->with(['publicationType'])
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $publications,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'publication_status' => 'required|in:draft,submitted,accepted,published',
            'tipe_publikasi' => 'required|array',
            'sumber_artikel' => 'required|in:Skripsi,Magang,Riset',
        ]);

        $data = [
            'student_id' => Auth::id(),
            'title' => $request->title,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'publication_status' => $request->publication_status,
            'tipe_publikasi' => $request->tipe_publikasi,
            'sumber_artikel' => $request->sumber_artikel,
            'submission_date' => now(),
            'admin_status' => 'pending',
            'dosen_status' => 'pending',
        ];

        $publication = Publication::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil dibuat',
            'data' => $publication,
        ], 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            $publication = Publication::with(['student', 'publicationType'])->findOrFail($id);
        } elseif ($user->hasRole('dosen')) {
            $students = \App\Models\StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
            $publication = Publication::whereIn('student_id', $students)
                ->with(['student', 'publicationType'])
                ->findOrFail($id);
        } else {
            $publication = Publication::where('student_id', $user->id)
                ->with(['publicationType'])
                ->findOrFail($id);
        }

        return response()->json([
            'success' => true,
            'data' => $publication,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            $publication = Publication::findOrFail($id);
        } else {
            $publication = Publication::where('student_id', $user->id)->findOrFail($id);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'abstract' => 'sometimes|required|string',
            'keywords' => 'sometimes|required|string',
            'isbn' => 'nullable|string|max:255',
            'doi' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:50',
            'issue' => 'nullable|string|max:50',
            'pages' => 'nullable|string|max:50',
            'publication_status' => 'sometimes|required|in:draft,submitted,accepted,published',
        ]);

        $publication->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil diupdate',
            'data' => $publication,
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            $publication = Publication::findOrFail($id);
        } else {
            $publication = Publication::where('student_id', $user->id)->findOrFail($id);
        }

        $publication->delete();

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil dihapus',
        ]);
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->hasRole('dosen')) {
            $students = \App\Models\StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
            $publication = Publication::whereIn('student_id', $students)->findOrFail($id);
            $publication->update(['dosen_status' => 'approved']);
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil disetujui',
        ]);
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->hasRole('dosen')) {
            $students = \App\Models\StudentProfile::where('supervisor_id', $user->id)->pluck('user_id');
            $publication = Publication::whereIn('student_id', $students)->findOrFail($id);
            $publication->update(['dosen_status' => 'rejected']);
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil ditolak',
        ]);
    }
} 