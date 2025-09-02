<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Professor;
use App\Models\Escola;

class ProfessorController extends Controller
{
    // aqui só é importante se eu quiser salvar direto no código, tudo isso é feito direto no EscolaController se for direto do site
    public function index(Escola $escola, Request $req)
    {
        $professores = Professor::where('escola_id', $escola->id)
            ->select('id', 'id_externo', 'nome', 'codigo_externo')
            ->orderBy('id')
            ->get();

        if ($req->boolean('debug')) {
            $saida = $professores->map(
                fn($p) =>
                "{$p->id} - {$p->nome} - {$p->codigo_externo}"
            )->implode("\n");

            return response($saida, 200)->header('Content-Type', 'text/plain; charset=utf-8');
        }

        return response()->json($professores, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
