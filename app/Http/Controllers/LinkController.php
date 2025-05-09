<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = Auth::user();
        // Garante que o perfil existe
        if (!$user->profile) {
            $user->profile()->create([
                'title' => $user->name . ' Links',
                'slug' => Str::slug($user->name . '-' . $user->id),
            ]);
            $user->refresh(); // Atualiza o modelo do usuário para pegar o novo perfil
        }
        $links = $user->profile->links()->orderBy('order')->get();
        return Inertia::render('Links/Index', [
            'links' => $links,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Links/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $maxOrder = Auth::user()->profile->links()->max('order') ?? 0;
        
        Auth::user()->profile->links()->create([
            ...$validated,
            'order' => $maxOrder + 1,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return Redirect::route('links.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Link $link): Response
    {
        $this->authorize('update', $link);

        return Inertia::render('Links/Edit', [
            'link' => $link,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Link $link): RedirectResponse
    {
        $this->authorize('update', $link);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $link->update($validated);

        return Redirect::route('links.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        $link->delete();

        return Redirect::route('links.index');
    }

    /**
     * Update the order of links.
     */
    public function updateOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ordered_ids' => 'required|array',
            'ordered_ids.*' => 'integer|exists:links,id',
        ]);

        foreach ($validated['ordered_ids'] as $index => $id) {
            $link = Link::find($id);
            
            if ($link && Auth::user()->profile->id === $link->profile_id) {
                $link->update(['order' => $index]);
            }
        }

        return Redirect::route('links.index');
    }
} 