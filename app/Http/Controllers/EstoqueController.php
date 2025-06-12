<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateEstoque;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Estoque;

class EstoqueController extends Controller
{
    
    public function create()
    {
        $products = Product::all(); 
        return view('estoque.create', compact('products')); 
    }


    /**
     * Store a newly created stock entry in storage.
     */
    public function store(StoreUpdateEstoque  $request)
{
    $estoque = new Estoque();
    $estoque->product_id = $request->input('product_id');
    $estoque->quantity = $request->input('quantidade');
    $estoque->lot = $request->input('lote');
    $estoque->validity = $request->input('validade');
    
    if ($estoque->save()) {
        return redirect()->route('products.index')->with('success', 'Entrada adicionada com sucesso!');
    }

    return redirect()->back()->withInput()->with('error', 'Erro ao adicionar o estoque.');
}



    /**
     * Show the form for editing the specified stock entry.
     */
    public function edit(Estoque $estoque)
{
    $estoque->load('product');
    $products = Product::all();

    return view("estoque.edit", [
        'estoque' => $estoque,
        'products' => $products
    ]);
}



    /**
     * Update the specified stock entry in storage.
     */
    public function update(StoreUpdateEstoque $request, $estoqueId)
    
    {
        $estoque = Estoque::findOrFail($estoqueId);
        $product = Product::findOrFail($estoque->product_id);
        $estoque->quantity = $request->input('quantidade');
        $estoque->lot = $request->input('lote');
        $estoque->validity = $request->input('validade');
        
        if ($estoque->save()) {
            return redirect()->route('estoque.index', ['estoque' => $product->id ])->with('success', 'Entrada atualizado com sucesso!');
        }
    
        return redirect()->back()->withInput()->with('error', 'Erro ao atualizar o estoque!');
    }
    

    /**
     * Remove the specified stock entry from storage.
     */
    public function destroy( $estoqueId)
    {
        $estoque = Estoque::findOrFail($estoqueId);
        $estoque->delete();
        
        return redirect()->route('products.index')->with('success', 'Entrada excluído com sucesso!');
    }

    /**
     * Show the list of stock entries for a product.
     */
    public function index(Request $request)
    {
        $productId = $request->query('estoque'); 
    
        $product = Product::findOrFail($productId);
        $estoques = $product->estoque()->orderBy('validity')->get();
    
        return view('estoque.index', ['product' => $product, 'estoques' => $estoques]);
    }
    
}
