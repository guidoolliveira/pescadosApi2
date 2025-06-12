<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateUsoDiario;
use App\Models\Product;
use App\Models\Viveiro;
use App\Models\UsoDiario;
use Illuminate\Http\Request;

class UsoDiarioController extends Controller
{
    public function index(Request $request)
    {
        $viveiros = Viveiro::all();
        $usoDiario = UsoDiario::all();

        return view('uso_diario.index', [
            'usoDiario' => $usoDiario,
            'viveiros' => $viveiros,
        ]);
    }

    public function create()
    {
        $viveiros = Viveiro::all();
        $produtos = Product::all();
        
        return view('uso_diario.create', compact('viveiros', 'produtos'));
    }

    public function store(StoreUpdateUsoDiario $request)
{
    $viveiro = Viveiro::find($request->viveiro_id);
    if (!$viveiro) {
        return redirect()->back()->with('error', 'Viveiro não encontrado.');
    }

    $cultivoAtivo = $viveiro->cultivo_ativo;

    if (!$cultivoAtivo) {
        return redirect()->back()->withInput()->with('message', 'Erro ao cadastrar: Não há cultivo ativo no viveiro selecionado.');
    }

    $produto = Product::findOrFail($request->produto_id);
    $quantidade_utilizada = $request->quantidade_utilizada;

    $peso_unidade = $produto->weight; 
    
    $quantidadeNecessaria = ceil($quantidade_utilizada / $peso_unidade); 

    $estoque = $produto->estoque()
        ->orderBy('validity')
        ->orderBy('created_at')
        ->get();

    $restante = $quantidadeNecessaria;

    foreach ($estoque as $e) {
        if ($restante <= 0) break;
        if ($e->quantity == 0) continue;

        $usado = min($restante, $e->quantity);
        $anterior = $e->quantity;

        $e->quantity -= $usado;
        $e->save();

        $e->historico()->create([
            'quantidade_anterior' => $anterior,
            'quantidade_utilizada' => $usado,
        ]);

        $restante -= $usado;
    }

    if ($restante > 0) {
        return redirect()->back()->with('message', "Estoque insuficiente. Faltaram {$restante} unidades para completar a baixa.");
    }

    // Registra o uso diário
    UsoDiario::create([
        'cultivo_id' => $cultivoAtivo->id,
        'viveiro_id' => $viveiro->id,
        'produto_id' => $request->produto_id,
        'data' => $request->data,
        'observacoes' => $request->observacoes,
        'quantidade_utilizada' => $quantidade_utilizada,
    ]);

    return redirect()->route('uso_diario.index')->with('success', 'Uso diário registrado e baixa no estoque realizada com sucesso.');
}



    

    public function show($id)
    {
        $usoDiario = UsoDiario::findOrFail($id); 
        return view('uso_diario.show', compact('usoDiario'));
    }

    public function edit(UsoDiario $usoDiario)
    {
        $viveiros = Viveiro::all();
        $produtos = Product::all();
        return view('uso_diario.edit', [
            'usoDiario' => $usoDiario,
            'viveiros' => $viveiros,
            'produtos' => $produtos,
        ]);
    }

    public function update(StoreUpdateUsoDiario $request, UsoDiario $usoDiario)
    {
        $viveiro = Viveiro::find($request->viveiro_id);
        if (!$viveiro) {
            return redirect()->back()->with('error', 'Viveiro não encontrado.');
        }
    
        $cultivoAtivo = $viveiro->cultivo_ativo;
    
        if (!$cultivoAtivo) {
            return redirect()->back()->withInput()->with('message', 'Erro ao atualizar: Não há cultivo ativo no viveiro selecionado.');
        }
    
        $produto = Product::findOrFail($request->produto_id);
        $novoValorKg = $request->quantidade_utilizada;
        $valorAntigoKg = $usoDiario->quantidade_utilizada;
    
        $peso_unidade = $produto->weight;
    
        $novaQtdUnidades   = floor($novoValorKg / $peso_unidade);
        $antigaQtdUnidades = floor($valorAntigoKg / $peso_unidade);
        $diferenca         = $novaQtdUnidades - $antigaQtdUnidades;
    
        if (abs($diferenca) >= 1) {
            $estoque = $produto->estoque()
                ->orderBy('validity')
                ->orderBy('created_at')
                ->get();
    
            if ($diferenca < 0) {
                $restaurar = abs($diferenca);
                foreach ($estoque as $e) {
                    if ($restaurar <= 0) break;
    
                    $anterior = $e->quantity;
                    $e->quantity += $restaurar;
                    $e->save();
    
                    $e->historico()->create([
                        'quantidade_anterior' => $anterior,
                        'quantidade_utilizada' => -$restaurar,
                    ]);
    
                    $restaurar = 0;
                }
            }
    
            if ($diferenca > 0) {
                $restante = $diferenca;
                foreach ($estoque as $e) {
                    if ($restante <= 0) break;
                    if ($e->quantity == 0) continue;
    
                    $usado = min($restante, $e->quantity);
                    $anterior = $e->quantity;
    
                    $e->quantity -= $usado;
                    $e->save();
    
                    $e->historico()->create([
                        'quantidade_anterior' => $anterior,
                        'quantidade_utilizada' => $usado,
                    ]);
    
                    $restante -= $usado;
                }
    
                if ($restante > 0) {
                    return redirect()->back()->withInput()->with('message', "Estoque insuficiente. Faltaram {$restante} unidades para completar a baixa.");
                }
            }
        }
    
        $usoDiario->update([
            'viveiro_id' => $request->viveiro_id,
            'produto_id' => $request->produto_id,
            'data' => $request->data,
            'quantidade_utilizada' => $request->quantidade_utilizada,
            'observacoes' => $request->observacoes,
            'cultivo_id' => $cultivoAtivo->id,
        ]);
    
        return redirect()->route('uso_diario.index')->with('success', 'Uso diário atualizado com sucesso.');
    }
    
    
public function destroy(UsoDiario $usoDiario)
{
    $usoDiario->delete();
    return redirect()->route('uso_diario.index')->with('success', 'Uso diário deletado com sucesso.');
}

}

