<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Turma;
use App\Models\Escola;

class TurmaController extends Controller
{
    // aqui só é importante se eu quiser salvar direto no código, tudo isso é feito direto no EscolaController se for direto do site
    public function index(Escola $escola, Request $req)
    {
        $turmas = Turma::where('escola_id', $escola->id)
            ->select('id','id_externo', 'nome', 'codigo_externo', 'pendencia')
            ->orderBy('id')
            ->get();

        if ($req->boolean('debug')) {
            $saida = $turmas->map(
                fn($t) =>
                "{$t->id} - {$t->nome} - {$t->codigo_externo} - pendência: {$t->pendencia}"
            )->implode("\n");

            return response($saida, 200)->header('Content-Type', 'text/plain; charset=utf-8');
        }

        return response()->json($turmas, 200, [], JSON_UNESCAPED_UNICODE);
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
