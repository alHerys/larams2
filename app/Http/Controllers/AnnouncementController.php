<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement = new Announcement;
        $announcement->user_id = Auth::id();
        $announcement->title = $validated['title'];
        $announcement->content = $validated['content'];
        $announcement->save();

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function destroy($id)
    {
        $announcement = Announcement::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $announcement->delete();

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}
