<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::orderByDesc('created_at')->get();

        // Convertir file_path en URL
        $publications->transform(function ($item) {
            if ($item->file_path) {
                $item->file_path = Storage::url($item->file_path);
            }
            return $item;
        });

        return response()->json($publications, 200);
    }

    // ✅ Création d'une nouvelle publication
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:image,video,document,youtube',
            'title_ar' => 'required|string',
            'date' => 'required|string',
            'title_fr' => 'required|string',
            'title_en' => 'required|string',
            'description_ar' => 'nullable|string',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'file_path' => 'nullable|file|max:51200',
            'video_url' => 'nullable|url',
        ]);

        // Cas vidéo YouTube
        if ($data['type'] === 'video' && $request->filled('video_url')) {
            $data['file_path'] = null;
        } elseif ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('publications', 'public');
            $data['video_url'] = null;
        } else {
            $data['file_path'] = null;
            $data['video_url'] = null;
        }

        $publication = Publication::create($data);

        // Ajout de l'URL complète du fichier
        if ($publication->file_path) {
            $publication->file_path = Storage::url($publication->file_path);
        }

        return response()->json($publication, 201);
    }

    // ✅ Détail d'une publication
    public function show($id)
    {
        $publication = Publication::findOrFail($id);

        if ($publication->file_path) {
            $publication->file_path = Storage::url($publication->file_path);
        }

        return response()->json($publication);
    }

    public function update(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);

        $data = $request->validate([
            'type' => 'required|in:image,video,document,youtube',
            'date' => 'required|string',
            'title_ar' => 'required|string',
            'title_fr' => 'required|string',
            'title_en' => 'required|string',
            'description_ar' => 'nullable|string',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'file_path' => 'nullable|file|max:51200',
            'video_url' => 'nullable|url',
        ]);

        // ✅ Gérer le cas YouTube (pas de fichier)
        if ($data['type'] === 'youtube') {
            if ($publication->file_path && Storage::disk('public')->exists($publication->file_path)) {
                Storage::disk('public')->delete($publication->file_path);
            }
            $data['file_path'] = null;
        }

        // ✅ Gérer le fichier local (image, video, document)
        if ($request->hasFile('file_path')) {
            if ($publication->file_path && Storage::disk('public')->exists($publication->file_path)) {
                Storage::disk('public')->delete($publication->file_path);
            }

            $path = $request->file('file_path')->store('publications', 'public');
            $data['file_path'] = $path;
            $data['video_url'] = null; // si on envoie un fichier, on supprime le lien YouTube
        }

        $publication->update($data);

        // Rendre le chemin du fichier visible depuis le front
        if ($publication->file_path) {
            $publication->file_path = Storage::url($publication->file_path);
        }

        return response()->json($publication);
    }


    // ✅ Suppression
    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);

        if ($publication->file_path) {
            Storage::disk('public')->delete($publication->file_path);
        }

        $publication->delete();

        return response()->json(['message' => 'Publication supprimée avec succès']);
    }
}
