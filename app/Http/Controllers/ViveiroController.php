<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateViveiro;
use App\Models\Biometria;
use App\Models\Viveiro;
use Illuminate\Http\Request;

class ViveiroController extends Controller
{
    public readonly Viveiro $viveiro;
    public function __construct()
    {
        $this->viveiro = new Viveiro();
    }
   public function index()
{
    $viveiros = Viveiro::all();

    if ($viveiros->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Nenhum viveiro encontrado.'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $viveiros
    ], 200);
}




    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $created = $this->viveiro->create([
        'name' => $request->input('name'),
        'width' => $request->input('width'),
        'length' => $request->input('length'),
        'area' => $request->input('width') * $request->input('length')
    ]);

    if ($created) {
        return response()->json([
            'success' => true,
            'message' => 'Viveiro cadastrado com sucesso!',
            'data' => $created
        ], 201); // 201 Created
    }

    return response()->json([
        'success' => false,
        'message' => 'Erro ao cadastrar o viveiro.'
    ], 500); // 500 Internal Server Error
}


    /**
     * Display the specified resource.
     */
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:viveiros,id',
        'name' => 'required|string|max:255',
        'width' => 'required|numeric|gt:0|lte:999',
        'length' => 'required|numeric|gt:0|lte:999',
    ]);

    $id = $request->input('id');

    $viveiro = $this->viveiro->findOrFail($id);

    $viveiro->update([
        'name' => $request->input('name'),
        'width' => $request->input('width'),
        'length' => $request->input('length'),
        'area' => $request->input('width') * $request->input('length'),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Viveiro atualizado com sucesso.',
    ]);
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
{
    $id = $request->query('id');

    if (!$id || !is_numeric($id)) {
        return response()->json(['error' => 'ID inválido ou ausente'], 400);
    }

    $deleted = $this->viveiro->where('id', $id)->delete();

    if ($deleted) {
        return response()->json(['success' => 'Viveiro deletado com sucesso']);
    }

    return response()->json(['error' => 'Viveiro não encontrado'], 404);
}

}
