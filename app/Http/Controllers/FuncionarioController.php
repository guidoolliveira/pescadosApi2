<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Http\Requests\StoreUpdateFuncionario;

class FuncionarioController extends Controller
{
    public readonly Funcionario $funcionario;
    public function __construct()
    {
        $this->funcionario = new Funcionario();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $funcionarios = Funcionario::all();
        return view("funcionarios.index", ['funcionarios' => $funcionarios]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("funcionarios.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateFuncionario $request){
        $created = $this->funcionario->create([
            'name' => $request->input('nome'),
            'salary' => $request->input('salario'),
            'function' => $request->input('funcao'),
            'phone' => $request->input('telefone')
        ]);
        if($created){
            return redirect()->route("funcionarios.index")->with('success', 'Funcionário Cadastrado com Sucesso' );
        }
        return redirect()->back()->with('message', 'Erro ao cadastrar' );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Funcionario $funcionario)
    {
        return view("funcionarios.edit", ["funcionario" => $funcionario]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateFuncionario $request, string $id)
    {
        $updated = $this->funcionario->where('id', $id)->update([
            'name' => $request->input('nome'),
            'salary' => $request->input('salario'),
            'function' => $request->input('funcao'),
            'phone' => $request->input('telefone')
        ], $request->except('_token', '_method'));
        if($updated){
            return redirect()->route("funcionarios.index")->with('success', 'Funcionário Editado com Sucesso' );
        }
        return redirect()->back()->with('message', 'Erro ao editar' );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->funcionario->where('id', $id)->delete();
        return redirect()->route("funcionarios.index")->with('success', 'Funcionário deletado com Sucesso' );
    }
}
