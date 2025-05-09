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
use Jenssegers\Agent\Agent;

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
            'font' => 'nullable|string|max:255',
            'avatar' => 'nullable|string|max:2048',
            'avatar_file' => 'nullable|image|max:1024',
        ]);

        $profile = Auth::user()->profile;
        
        // Remove avatar_file do array validado antes de atualizar o perfil
        if (isset($validated['avatar_file'])) {
            unset($validated['avatar_file']);
        }

        // Se um arquivo foi enviado, faça o upload
        if ($request->hasFile('avatar_file')) {
            // Remova o avatar antigo se não for uma URL externa
            $oldAvatar = $profile->avatar;
            if ($oldAvatar && !filter_var($oldAvatar, FILTER_VALIDATE_URL)) {
                // Limpa o caminho para encontrar o arquivo
                $oldPath = str_replace('/storage/', '', $oldAvatar);
                if (!empty($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Salve o novo avatar e armazene apenas o path relativo
            $path = $request->file('avatar_file')->store('avatars', 'public');
            $validated['avatar'] = '/storage/' . $path;
        } 
        // Se o avatar estiver vazio no envio, mas existir um avatar anterior, mantenha o anterior
        elseif (empty($validated['avatar']) && $profile->avatar) {
            $validated['avatar'] = $profile->avatar;
        }

        $profile->update($validated);

        return Redirect::route('linktree.profile.edit');
    }

    /**
     * Display a user's public profile.
     */
    public function show(string $slug): Response
    {
        $profile = Profile::where('slug', $slug)->with('user')->firstOrFail();
        $links = $profile->links()->where('is_active', true)->orderBy('order')->get();

        return Inertia::render('Profile/Show', [
            'profile' => $profile,
            'links' => $links,
        ]);
    }
    
    /**
     * Record a visit to a link and redirect to the destination URL.
     */
    public function visitLink(Request $request, string $slug, int $linkId): RedirectResponse
    {
        $profile = Profile::where('slug', $slug)->firstOrFail();
        $link = $profile->links()->where('id', $linkId)->where('is_active', true)->firstOrFail();
        
        // Detectar dispositivo
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        
        $deviceType = 'desktop';
        if ($agent->isTablet()) {
            $deviceType = 'tablet';
        } elseif ($agent->isMobile()) {
            $deviceType = 'mobile';
        }
        
        // Registrar a visita
        $profile->visits()->create([
            'link_id' => $link->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'device_type' => $deviceType,
            // Nota: para país e cidade, você precisaria de um serviço de geolocalização de IP
            // como MaxMind GeoIP ou IP-API
        ]);
        
        return Redirect::away($link->url);
    }
} 