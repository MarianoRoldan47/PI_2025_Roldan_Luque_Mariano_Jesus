<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('is_approved', false)->get();
        return view('admin.users.pending', compact('pendingUsers'));
    }

    public function approve(User $user)
    {
        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Usuario aprobado correctamente.');
    }
}
