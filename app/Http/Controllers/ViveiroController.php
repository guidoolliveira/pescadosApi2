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
   $areaValue = ($request->input('width') * $request->input('length')) / 10000;
$area = rtrim(rtrim(number_format($areaValue, 2, '.', ''), '0'), '.') . " ha";

    $created = $this->viveiro->create([
        'name' => $request->input('name'),
        'width' => $request->input('width'),
        'length' => $request->input('length'),
        'area' => $area
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
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'width' => 'required|numeric|gt:0|lte:999',
        'length' => 'required|numeric|gt:0|lte:999',
    ]);

    $viveiro = $this->viveiro->find($id);

    if (!$viveiro) {
        return response()->json([
            'success' => false,
            'message' => 'Viveiro não encontrado.'
        ], 404);
    }
    $areaValue = ($request->input('width') * $request->input('length')) / 10000;
$area = rtrim(rtrim(number_format($areaValue, 2, '.', ''), '0'), '.') . " ha";

    $viveiro->update([
        'name' => $request->input('name'),
        'width' => $request->input('width'),
        'length' => $request->input('length'),
        'area' => $area,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Viveiro atualizado com sucesso.',
        'data' => $viveiro
    ]);
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    if (!is_numeric($id)) {
        return response()->json([
            'success' => false,
            'message' => 'ID inválido.'
        ], 400);
    }

    $viveiro = $this->viveiro->find($id);

    if (!$viveiro) {
        return response()->json([
            'success' => false,
            'message' => 'Viveiro não encontrado.'
        ], 404);
    }

    $viveiro->delete();

    return response()->json([
        'success' => true,
        'message' => 'Viveiro deletado com sucesso.'
    ]);
}


}
