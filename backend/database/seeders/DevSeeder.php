<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $escola = \App\Models\Escola::create(['nome_escola' => 'Colégio Exemplo']);

        $turma = \App\Models\Turma::create([
            'nome' => '6A',
            'escola_id' => $escola->id,
            'pendencia' => 0
        ]);

        $disciplina = \App\Models\Disciplina::create([
            'nome' => 'Matemática',
            'escola_id' => $escola->id
        ]);

        $professor = \App\Models\Professor::create([
            'nome' => 'Ana Silva',
            'escola_id' => $escola->id
        ]);

        \App\Models\Aula::create([
            'escola_id'      => $escola->id,
            'dia'            => 'SEG',
            'horario_inicio' => '07:30',
            'horario_termino' => '08:20',
            'descricao'      => 'Aula de Matemática',
            'aula_fixa'      => true,
            'turma'          => $turma->nome,
            'professores'    => [$professor->id],
            'disciplinas'    => [$disciplina->id],
            'recursos'       => [],
        ]);
    }
}
