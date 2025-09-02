<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Aula;
use App\Models\Escola;

use Illuminate\Support\Facades\DB;

class AulaController extends Controller
{
    
    // aqui só é importante se eu quiser salvar direto no código, tudo isso é feito direto no EscolaController se for direto do site
    public function index(Escola $escola, Request $req)
    {
        $req->validate([
            'dia'   => 'nullable|in:SEG,TER,QUA,QUI,SEX,SAB,DOM',
            'turma' => 'nullable|string|max:100',
        ]);

        $q = Aula::where('escola_id', $escola->id)
            ->select('id','dia','horario_inicio','horario_termino','descricao',
                     'aula_fixa','turma','sala','local','professores','disciplinas','recursos');

        if ($req->filled('dia'))   $q->where('dia', $req->dia);
        if ($req->filled('turma')) $q->where('turma', $req->turma);

        $aulas = $q->orderBy('dia')->orderBy('horario_inicio')->get();

        if ($req->boolean('debug')) {
            $saida = $aulas->map(function ($t) {
                $professores = implode(', ', $t->professores ?? []);
                $disciplinas = implode(', ', $t->disciplinas ?? []);
                $recursos    = implode(', ', $t->recursos ?? []);
                $fixa        = $t->aula_fixa ? 'fixa' : 'pontual';

                return "{$t->id} - {$t->dia} - Início: {$t->horario_inicio} - Término: {$t->horario_termino} - Tipo: {$t->descricao} - {$fixa} - Turma: {$t->turma} - Sala: {$t->sala} - Professor(es): {$professores} - Disciplina(s): {$disciplinas} - Recurso(s): {$recursos} - Local: {$t->local}";
            })->implode("\n");

            return response($saida, 200)->header('Content-Type','text/plain; charset=utf-8');
        }

        return response()->json($aulas, 200, [], JSON_UNESCAPED_UNICODE);
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
