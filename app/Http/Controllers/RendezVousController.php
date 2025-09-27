<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return RendezVous::with(['equipe:id,name_fr'])->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'telephone' => 'required|string',
            'date' => 'required|date',
            'heure' => 'required',
            'sujet' => 'required|string',
            'email' => 'required|email',
            'equipe_id'=>'required',
        ]);

        // Vérifier si le créneau existe déjà
        $existe = RendezVous::where('date', $request->date)
            ->where('heure', $request->heure)
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Ce créneau horaire est déjà réservé'
            ], 409);
        }

        // Création du rendez-vous
        $rdv = RendezVous::create($data);

        return response()->json($rdv, 201);
    }


    public function destroy($id)
    {
        $rdv = RendezVous::findOrFail($id);
        $rdv->delete();

        return response()->json(null, 204);
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'avocat' => 'required|string',
            'telephone' => 'required|string',
            'date' => 'required|date',
            'heure' => 'required',
            'sujet' => 'required|string',
            'email' => 'nullable|email',
        ]);

        // Vérifier si le créneau existe déjà pour un autre rendez-vous
        $existe = RendezVous::where('date', $request->date)
            ->where('heure', $request->heure)
            ->where('id', '!=', $id) // exclure le rendez-vous en cours de modification
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Ce créneau horaire est déjà réservé'
            ], 409);
        }

        // Mise à jour du rendez-vous
        $rdv = RendezVous::findOrFail($id);
        $rdv->update($data);

        return response()->json($rdv, 200);
    }
}
