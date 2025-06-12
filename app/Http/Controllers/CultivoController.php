<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cultivo;
use App\Models\Viveiro;
use App\Http\Requests\StoreUpdateCultivo;

class CultivoController extends Controller
{
    public readonly Cultivo $cultivo;

    public function __construct()
    {
        $this->cultivo = new Cultivo();
    }

    public function index()
    {
        $viveiros = Viveiro::all();
        $cultivos = Cultivo::with('viveiro')->get();
        return view("cultivos.index", compact('cultivos', 'viveiros'));
    }

    public function create()
    {
        $viveiros = Viveiro::all();
        return view("cultivos.create", compact('viveiros'));
    }
    public function show($id)
{
    $cultivo = Cultivo::with(['viveiro.latestBiometria', 'usoDiarios', 'biometrias'])->findOrFail($id);

    $dataInicio = strtotime(date('Y-m-d', strtotime($cultivo->data_inicio)));
    $hoje = strtotime(date('Y-m-d'));
    $diasCultivo = floor(($hoje - $dataInicio) / (60 * 60 * 24));

    $consumoPorProduto = $cultivo->usoDiarios()
        ->with('produto')
        ->get()
        ->groupBy('produto_id')
        ->map(function ($grupo) {
            $ultimoConsumo = $grupo->sortByDesc('data')->first();
            return [
                'produto' => $grupo->first()->produto->name ?? 'Desconhecido',
                'quantidade_total' => $grupo->sum('quantidade_utilizada'),
                'ultimo_consumo' => $ultimoConsumo ? $ultimoConsumo->quantidade_utilizada : 0,
                'data_ultimo_consumo' => $ultimoConsumo ? $ultimoConsumo->data : null,
            ];
        });

    return view('cultivos.show', compact('cultivo', 'diasCultivo', 'consumoPorProduto'));
}


public function store(StoreUpdateCultivo $request)
{
    $viveiroId = $request->viveiro_id;

    $existeCultivoAtivo = $this->cultivo
        ->where('viveiro_id', $viveiroId)
        ->where('status', true) 
        ->exists();

    if ($existeCultivoAtivo) {
        return redirect()->back()->with('message', 'Já existe um cultivo ativo neste viveiro.');
    }

    // Cria o cultivo normalmente
    $created = $this->cultivo->create($request->validated());

    if ($created) {
        return redirect()->route("cultivos.index")->with('success', 'Cultivo iniciado com sucesso');
    }

    return redirect()->back()->with('message', 'Erro ao iniciar cultivo');
}


    public function edit(Cultivo $cultivo)
    {
        $viveiros = Viveiro::all();
        return view("cultivos.edit", compact('cultivo', 'viveiros'));
    }

    public function update(StoreUpdateCultivo $request, string $id)
    {

        $updated = $this->cultivo->where('id', $id)->update($request->validated());
    
        if ($updated) {
            return redirect()->route("cultivos.index")->with('success', 'Cultivo atualizado com sucesso');
        }
    
        return redirect()->back()->with('message', 'Erro ao atualizar cultivo');
    }
    

    public function finalizar(Cultivo $cultivo)
{
    if ($cultivo->data_fim) {
        return redirect()->back()->with('message', 'Este cultivo já foi finalizado.');
    }

    $cultivo->update([
        'data_fim' => Carbon::now()->toDateString(), 
        'status' => 0, 
    ]);

    return redirect()->route('cultivos.index')->with('success', 'Cultivo finalizado com sucesso.');
}

    public function destroy(string $id)
    {
        $this->cultivo->where('id', $id)->delete();
        return redirect()->route("cultivos.index")->with('success', 'Cultivo deletado com sucesso');
    }

    public function relatorio(string $id)
    {
        $cultivo = Cultivo::with('viveiro')->findOrFail($id);

        $consumos = $cultivo->usosDiarios()
            ->selectRaw('produto_id, SUM(quantidade_utilizada) as total')
            ->with('produto')
            ->groupBy('produto_id')
            ->get();

        return view('cultivos.relatorio', compact('cultivo', 'consumos'));
    }
}
