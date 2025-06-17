<?php

namespace App\Http\Controllers;

use App\Models\Biometria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BiometriaController extends Controller
{
    public readonly Biometria $biometria;

    public function __construct()
    {
        $this->biometria = new Biometria();
    }

    public function index()
    {
        $biometrias = Biometria::with('viveiro')->get();

        if ($biometrias->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma biometria encontrada.'
            ], 404);
        }

        // Formatando datas no padrão brasileiro
        $formatted = $biometrias->map(function ($item) {
            $item->date = \Carbon\Carbon::parse($item->date)->format('d/m/Y');
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $formatted
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|gt:0',
            'quantity' => 'required|integer|gt:0',
            'date' => 'required|date',
            'viveiro_id' => 'required|integer|exists:viveiros,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $fileName = now()->format('Ymd_His') . '.' . $request->image->extension();
            $imagePath = $request->file('image')->storeAs('images', $fileName, 'public');
        }

        $shrimp_weight = round($request->input('weight') / $request->input('quantity'), 2);

        $created = $this->biometria->create([
            'weight' => $request->input('weight'),
            'quantity' => $request->input('quantity'),
            'date' => $request->input('date'),
            'image' => $imagePath,
            'viveiro_id' => $request->input('viveiro_id'),
            'shrimp_weight' => $shrimp_weight
        ]);

        if ($created) {
            return response()->json([
                'success' => true,
                'message' => 'Biometria criada com sucesso!',
                'data' => $created
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao criar biometria.'
        ], 500);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'weight' => 'required|numeric|gt:0',
            'quantity' => 'required|integer|gt:0',
            'date' => 'required|date',
            'viveiro_id' => 'required|integer|exists:viveiros,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $biometria = $this->biometria->find($id);

        if (!$biometria) {
            return response()->json(['error' => 'Biometria não encontrada'], 404);
        }

        $imagePath = $biometria->image;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $shrimp_weight = round($request->input('weight') / $request->input('quantity'), 2);

        $biometria->update([
            'weight' => $request->input('weight'),
            'quantity' => $request->input('quantity'),
            'date' => $request->input('date'),
            'image' => $imagePath,
            'viveiro_id' => $request->input('viveiro_id'),
            'shrimp_weight' => $shrimp_weight
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Biometria atualizada com sucesso.',
            'data' => $biometria
        ]);
    }

    public function destroy($id)
    {
        $biometria = $this->biometria->find($id);

        if (!$biometria) {
            return response()->json(['error' => 'Biometria não encontrada'], 404);
        }

        if ($biometria->image) {
            Storage::disk('public')->delete($biometria->image);
        }

        $biometria->delete();

        return response()->json(['success' => 'Biometria deletada com sucesso']);
    }
}
