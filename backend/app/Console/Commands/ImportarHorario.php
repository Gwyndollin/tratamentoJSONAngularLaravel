<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Escola;
use App\Models\Turma;
use App\Models\Disciplina;
use App\Models\Professor;
use App\Models\Aula;

// Esse arquivo, resumidamente, lê um JSON que já esteja armazenado nos documento da aplicação. Ele vai ler todas as infos e jogar para os lugares corretos.

class ImportarHorario extends Command
{
    protected $signature = 'importar:horario {arquivo=horario.json}';
    protected $description = 'Importa dados de horário (escola, turmas, disciplinas, professores e aulas) de um JSON. Bloqueia escolas duplicadas.';

    private function canon(string $s): string
    {
        $s = preg_replace('/\s+/', ' ', $s);
        return Str::upper(trim($s));
    }

    public function handle()
    {
        // procura o arquivo na pasta do $path
        $arquivo = $this->argument('arquivo');
        $path = storage_path("app/{$arquivo}");

        // se o arquivo não existir no path, ele vai dar erro
        if (!file_exists($path)) {
            $this->error("Arquivo não encontrado: {$arquivo}");
            return self::FAILURE;
        }
        // ele armazena em $data o conteúdo todo do arquivo JSON
        $data = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("JSON inválido: " . json_last_error_msg());
            return self::FAILURE;
        }

        // aqui ele pega o nome do arquivo e transforma como nome da escola
        $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
        $nomeEscola = Str::upper(str_replace('_', ' ', $nomeArquivo));

        $this->info("Importando dados da escola: {$nomeEscola}");

        // nome canon é para evitar duplicatadas
        $nomeCanon = $this->canon($nomeEscola);

        // ele está verificando se existe uma escola com o mesmo nome
        $existe = Escola::where('nome_canon', $nomeCanon)->exists();
        if ($existe) {
            $this->error("Escola já cadastrada: {$nomeEscola}");
            return self::FAILURE;
        }

        // essa chamada basicamente abre uma "maleta" e vai guardando nela. Quando dá commit, ele entrega todo o conteúdo pro banco
        // porém se dá rollback, ele cancela tudo. É para garantir que nenhum dado vai ser armazenado se algo der errado lá no fim
        DB::beginTransaction();

        // ele cria a escola no banco
        $escola = Escola::create([
            'nome_escola' => $nomeEscola,
            'nome_canon' => $nomeCanon,
        ]);
        $this->info('Escola criada');

        // ele procura todas as turmas dentro de $data, então para cada turma ele cria uma turma única. os null é para garantir que algo vai ser armazenado
        // o ?? [] é caso um elemento seja nulo, ele vai guardar algo, no caso um array vazio. Se tivesse nulo e não tivesse ?? [], daria erro
        foreach ($data['turmas'] ?? [] as $t) {
            Turma::create([
                'id_externo'     => $t['id'] ?? null,
                'nome'           => $t['nome'] ?? null,
                'codigo_externo' => $t['codigo_externo'] ?? null,
                'pendencia'      => $t['pendencias'] ?? 0,
                'escola_id'      => $escola->id,
            ]);
        }
        $this->info('Turmas importadas');

        // aqui é para as disciplinas. Importante: ele sempre está verificando se $d é um array, se ele não é um array, ele retorna $d como um array
        // com isso, ele consegue armazenar corretamente na variável
        foreach ($data['disciplinas'] ?? [] as $d) {
            $disc = is_array($d) ? $d : (array)$d;

            Disciplina::create([
                'id_externo'     => $disc['id'] ?? null,
                'nome'           => $disc['nome'] ?? null,
                'codigo_externo' => $disc['codigo_externo'] ?? null,
                'escola_id'      => $escola->id,
            ]);
        }
        $this->info('Disciplinas importadas');

        // aqui é os professores.
        foreach ($data['professores'] ?? [] as $p) {
            $prof = is_array($p) ? $p : (array)$p;

            Professor::create([
                'id_externo'     => $prof['id'] ?? null,
                'nome'           => $prof['nome'] ?? null,
                'codigo_externo' => $prof['codigo_externo'] ?? null,
                'escola_id'      => $escola->id,
            ]);
        }
        $this->info('Professores importados');

        // aqui é para as aulas
        foreach ($data['aulas'] ?? [] as $a) {
            Aula::create([
                'escola_id'      => $escola->id,
                'dia'            => $a['dia'] ?? null,
                'horario_inicio' => $a['horario_inicio'] ?? null,
                'horario_termino'=> $a['horario_termino'] ?? null,
                'descricao'      => $a['descricao'] ?? null,
                'aula_fixa'      => (bool)($a['aula_fixa'] ?? false),
                'turma'          => $a['turma'] ?? null,
                'sala'           => $a['sala'] ?? null,
                'local'          => $a['local'] ?? null,
                'professores'    => $a['professores'] ?? [],
                'disciplinas'    => $a['disciplinas'] ?? [],
                'recursos'       => $a['recursos'] ?? [],
            ]);
        }
        $this->info('Aulas importadas');

        DB::commit();
        $this->info('Dados importados com sucesso');

        return self::SUCCESS;
    }
}
