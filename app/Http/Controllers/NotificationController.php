<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        $notification = Notifikasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (! $notification) {
            return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
        }

        $notification->update(['is_read' => true]);

        return redirect()->back();
    }

    public function markAllRead()
    {
        Notifikasi::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back();
    }
}
