<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Estoque;
use App\Models\Product;
use App\Models\Viveiro;
use App\Models\UsoDiario;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

class SiteController extends Controller
{
    public function index()
    {
        $currentPageViveiros = Request::get('page_viveiros', 1);
        $currentPageDiasCultivo = Request::get('page_dias_cultivo', 1);
        $currentPageEstoqueBaixo = Request::get('page_estoque_baixo', 1);

        $viveiros = Viveiro::with('latestBiometria')->paginate(3, ['*'], 'page_viveiros');

        $products = Product::with('estoque')->get();

        $consumoPorProduto = UsoDiario::whereDate('data', today())
            ->selectRaw('produto_id, SUM(quantidade_utilizada) as total')
            ->groupBy('produto_id')
            ->with('produto')
            ->paginate(5, ['*'], 'page_consumo');

        $viveirosFull = Viveiro::with('cultivos')->get()->map(function ($viveiro) {
            $cultivoAtivo = $viveiro->cultivo_ativo;

            if ($cultivoAtivo) {
                $diasCultivo = Carbon::parse($cultivoAtivo->data_inicio)->diffInDays(now());
                $viveiro->dias_de_cultivo = round($diasCultivo);
                $viveiro->cultivo_status = 'Ativo';
            } else {
                $viveiro->dias_de_cultivo = null;
                $viveiro->cultivo_status = 'Inativo';
            }

            return $viveiro;
        });

        $viveirosComDiasDeCultivo = new LengthAwarePaginator(
            $viveirosFull->forPage($currentPageDiasCultivo, 5),
            $viveirosFull->count(),
            5,
            $currentPageDiasCultivo,
            ['pageName' => 'page_dias_cultivo']
        );

        $produtoBaixaQuantidade = $products->map(function ($product) {
            $totalQuantity = $product->estoque->sum('quantity');
            return [
                'product' => $product,
                'total_quantity' => $totalQuantity,
            ];
        })->sortBy('total_quantity')->values()->take(5);        

        $produtosVencendo = Estoque::with('product')
            ->whereDate('validity', '<=', now()->addDays(30))
            ->where('quantity', '>', 0)
            ->paginate(5, ['*'], 'page_vencendo');

        return view('dashboard', [
            'viveiros' => $viveiros,
            'products' => $products,
            'produtoBaixaQuantidade' => $produtoBaixaQuantidade,
            'consumoPorProduto' => $consumoPorProduto,
            'produtosVencendo' => $produtosVencendo,
            'viveirosComDiasDeCultivo' => $viveirosComDiasDeCultivo,
        ]);
    }
}
