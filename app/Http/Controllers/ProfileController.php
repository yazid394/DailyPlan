<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     */
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the authenticated user's sleep preferences.
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_sleep_start' => ['required', 'date_format:H:i'],
            'default_sleep_end' => ['required', 'date_format:H:i'],
        ]);

        $request->user()->preference()->updateOrCreate([], $validated);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Preferensi tidur berhasil disimpan.');
    }
}
