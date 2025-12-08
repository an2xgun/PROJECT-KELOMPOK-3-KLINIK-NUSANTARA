<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // load settings from user record (fallback to session)
        $settings = $user->settings ?? session('user_settings', []);
        return view('settings.index', compact('user', 'settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'sidebar_collapsed' => 'nullable|in:on',
            'notifications' => 'nullable|in:on',
            'theme' => 'nullable|in:day,night',
        ]);

        $prefs = [
            'sidebar_collapsed' => isset($data['sidebar_collapsed']),
            'notifications' => isset($data['notifications']),
            'theme' => $data['theme'] ?? 'night',
        ];

        // Save to user's settings JSON column
        try {
            $user = Auth::user();
            $user->settings = $prefs;
            $user->save();
            return redirect()->route('settings.index')->with('success', 'Pengaturan disimpan.');
        } catch (\Throwable $e) {
            // fallback to session if DB column not present
            session(['user_settings' => $prefs]);
            return redirect()->route('settings.index')->with('success', 'Pengaturan disimpan (sementara di session).');
        }
    }
}
