<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function edit(): Response
    {
        $user = Auth::user();
        $profile = $user->profile ?? Profile::create([
            'user_id' => $user->id,
            'title' => $user->name . "'s Links",
            'slug' => Str::slug($user->name . '-' . Str::random(6)),
        ]);

        return Inertia::render('Profile/Edit', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'slug' => 'required|string|max:255|unique:profiles,slug,' . Auth::user()->profile->id,
            'theme' => 'nullable|string|max:255',
            'avatar' => 'nullable|string|max:2048',
            'avatar_file' => 'nullable|image|max:1024',
        ]);

        // Remove avatar_file do array validado antes de atualizar o perfil
        if (isset($validated['avatar_file'])) {
            unset($validated['avatar_file']);
        }

        // Se um arquivo foi enviado, faça o upload
        if ($request->hasFile('avatar_file')) {
            // Remova o avatar antigo se não for uma URL externa
            $oldAvatar = Auth::user()->profile->avatar;
            if ($oldAvatar && Str::startsWith($oldAvatar, 'avatars/')) {
                Storage::disk('public')->delete($oldAvatar);
            }

            // Salve o novo avatar
            $path = $request->file('avatar_file')->store('avatars', 'public');
            $validated['avatar'] = Storage::url($path);
        }

        Auth::user()->profile->update($validated);

        return Redirect::route('linktree.profile.edit');
    }

    /**
     * Display a user's public profile.
     */
    public function show(string $slug): Response
    {
        $profile = Profile::where('slug', $slug)->firstOrFail();
        $links = $profile->links()->where('is_active', true)->orderBy('order')->get();

        return Inertia::render('Profile/Show', [
            'profile' => $profile,
            'links' => $links,
        ]);
    }
} 