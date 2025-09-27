<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipe;
use Illuminate\Support\Facades\Storage;

class EquipeController extends Controller
{
    // Liste de tous les membres
    public function index()
    {
        return response()->json(Equipe::all(), 200);
    }


    // Ajouter un nouveau membre
    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar' => 'required|string',
            'name_fr' => 'required|string',
            'date' => 'nullable|date',
            'email' => 'nullable|email|unique:equipes,email',
            'image' => 'nullable|image|max:2048',
            'bio_ar' => 'nullable|string',
            'bio_fr' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'linkedin' => 'nullable|url',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('equipes', 'public');
        }
        $equipe = Equipe::create($data);
        return response()->json($equipe, 201);
    }

    // Afficher un membre
    public function show($id)
    {
        $equipe = Equipe::findOrFail($id);
        return response()->json($equipe);
    }

    // Mettre à jour un membre
  public function update(Request $request, $id)
{
    $equipe = Equipe::findOrFail($id);

    // Base rules
    $rules = [
        'name_ar' => 'sometimes|string',
        'name_fr' => 'sometimes|string',
        'date' => 'nullable|date',
        'email' => 'nullable|email|unique:equipes,email,' . $id,
        'bio_ar' => 'nullable|string',
        'bio_fr' => 'nullable|string',
        'bio_en' => 'nullable|string',
        'linkedin' => 'nullable|url',
        'facebook' => 'nullable|url',
        'instagram' => 'nullable|url',
    ];

    // Only validate image if it's a real uploaded file
    if ($request->hasFile('image')) {
        $rules['image'] = 'image|max:2048';
    }

    $data = $request->validate($rules);

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($equipe->image) {
            Storage::disk('public')->delete($equipe->image);
        }

        // Store new image
        $data['image'] = $request->file('image')->store('equipes', 'public');
    }

    $equipe->update($data);

    return response()->json($equipe);
}
    // Supprimer un membre
   public function destroy($id)
{
    $equipe = Equipe::findOrFail($id);

    // ✅ إذا عندك علاقات مثل rendezvous، نحيدهم كاملين قبل
    if ($equipe->relationLoaded('rendezvous') || method_exists($equipe, 'rendezvous')) {
        $equipe->rendezvous()->delete();
    }

    // ✅ نحيد الصورة إلا كانت كاينة
    if ($equipe->image && Storage::disk('public')->exists($equipe->image)) {
        Storage::disk('public')->delete($equipe->image);
    }

    // ✅ نحيد العضو نهائياً من قاعدة البيانات
    $equipe->delete();

    return response()->json(['message' => '✅ العضو تحذف بنجاح.']);
}

}
