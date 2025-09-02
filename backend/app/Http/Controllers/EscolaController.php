<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Escola;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EscolaController extends Controller
{

    // request é para pegar as informações que foram passadas pelo post
    public function index(Request $req)
    {
        // ele está armazendo em $escolas todas as Escolas do banco de dado, ordenandno por ID. precisa do Get() para efetivar esse resgate
        $escolas = Escola::select('id', 'nome_escola', 'nome_canon')
            ->orderBy('id')
            ->get();
        
        // essa verificação de debug ele tá esperando um valor true pra saber se o que está chegando do post está "certo". Se chega coisa ele continua o processo
        // o map basicamente está pegando cada elemento e separando em saída, com o implode esses elementos estão sendo quebrados por linha. Cada elemento é separado
        // no return    
        if ($req->boolean('debug')) {
            $saida = $escolas->map(function ($t) {
                return "{$t->id} - {$t->nome_escola} - {$t->nome_canon}";
            })->implode("\n");

            // ele vai retornar (com o response) um texto "cru" e informar que foi um sucesso (200)
            return response($saida, 200)
                ->header('Content-Type', 'text/plain; charset=utf-8');
        }

        // aqui ele vai retornar o json em questão. O JSON_UNESCAPED_UNICODE retorna como está escrito, sem ficar quebrando acento.
        return response()->json($escolas, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function store(Request $request)
    {
        // validate é uma verificação para saber se o arquivo que veio (já q eu envio o arquivo na aplicação com nome e documento json). Se o nome e o file seguir as regras
        // ele aceita. Validete é do próprio laravel.
        $request->validate([
            'nome' => 'required|string|min:2',
            'file' => 'required|file|max:20480|mimetypes:application/json,text/plain,application/octet-stream',
        ]);

        // primeira pega o file, depois armazena as informação, depois torna as informações em um Json.
        // por fim ele vê se não deu erro.
        $uploaded = $request->file('file');
        $contents = file_get_contents($uploaded->getRealPath());
        $data = json_decode($contents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'JSON inválido: ' . json_last_error_msg()], 422);
        }

        // salva arquivo (opcional, só para ter histórico)
        $safeName = \Illuminate\Support\Str::slug($request->input('nome')) . '-' . time() . '.json';
        $path = $uploaded->storeAs('escolas', $safeName);

        // aqui começa a importação dos dados
        try {
            DB::beginTransaction();

            // escolas
            $escola = \App\Models\Escola::create([
                'nome_escola' => $request->input('nome'),
                'nome_canon'  => \Illuminate\Support\Str::upper($request->input('nome')),
                'json_path'   => $path,
            ]);

            // turmas
            $turmasCount = 0;
            foreach (($data['turmas'] ?? []) as $t) {
                \App\Models\Turma::updateOrCreate(
                    ['escola_id' => $escola->id, 'id_externo' => $t['id'] ?? null],
                    [
                        'nome'           => $t['nome'] ?? null,
                        'codigo_externo' => $t['codigo_externo'] ?? null,
                        'pendencia'      => $t['pendencias'] ?? 0,
                    ]
                );
                $turmasCount++;
            }

            // disciplinas
            $disciplinasCount = 0;
            foreach (($data['disciplinas'] ?? []) as $d) {
                $disc = is_array($d) ? $d : (array) $d;
                \App\Models\Disciplina::updateOrCreate(
                    ['escola_id' => $escola->id, 'id_externo' => $disc['id'] ?? null],
                    [
                        'nome'           => $disc['nome'] ?? null,
                        'codigo_externo' => $disc['codigo_externo'] ?? null,
                    ]
                );
                $disciplinasCount++;
            }

            // professores
            $professoresCount = 0;
            foreach (($data['professores'] ?? []) as $p) {
                $prof = is_array($p) ? $p : (array) $p;
                \App\Models\Professor::updateOrCreate(
                    ['escola_id' => $escola->id, 'id_externo' => $prof['id'] ?? null],
                    [
                        'nome'           => $prof['nome'] ?? null,
                        'codigo_externo' => $prof['codigo_externo'] ?? null,
                    ]
                );
                $professoresCount++;
            }

            // aulas
            $aulasCount = 0;
            foreach (($data['aulas'] ?? []) as $a) {
                \App\Models\Aula::create([
                    'escola_id'       => $escola->id,
                    'dia'             => $a['dia'] ?? null,
                    'horario_inicio'  => $a['horario_inicio'] ?? null,
                    'horario_termino' => $a['horario_termino'] ?? null,
                    'descricao'       => $a['descricao'] ?? null,
                    'aula_fixa'       => (bool)($a['aula_fixa'] ?? false),
                    'turma'           => $a['turma'] ?? null,         // id_externo da turma
                    'sala'            => $a['sala'] ?? null,
                    'local'           => $a['local'] ?? null,
                    'professores'     => $a['professores'] ?? [],     // ids_externos
                    'disciplinas'     => $a['disciplinas'] ?? [],     // ids_externos
                    'recursos'        => $a['recursos'] ?? [],
                ]);
                $aulasCount++;
            }

            DB::commit();

            return response()->json([
                'message'            => 'Escola e dados importados com sucesso',
                'escola'             => $escola,
                'counts'             => [
                    'turmas'       => $turmasCount,
                    'disciplinas'  => $disciplinasCount,
                    'professores'  => $professoresCount,
                    'aulas'        => $aulasCount,
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Falha ao importar escola', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Erro ao importar dados da escola'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
   
    public function update(Request $request, string $id)
    {
        //
    }

        // resumidamente: ele vai procurar baseado no ID e vai excluir, como tem cascade vai tudo junto.
    public function destroy($id)
    {
        $escola = Escola::find($id);
        if (!$escola) {
            return response()->json(['message' => 'Escola não encontrada'], 404);
        }

        try {
            DB::beginTransaction();

            if (!empty($escola->json_path)) {
                Storage::delete($escola->json_path);
            }

            $escola->delete();

            DB::commit();
            return response()->json(['message' => 'Escola excluída com sucesso']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erro ao excluir escola', ['id' => $id, 'e' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao excluir a escola'], 500);
        }
    }
}
