<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    // fillable é para garantir que somente os campos nomeados vão ser preenchidos. É medida de segurança para atribuição em massa
    protected $fillable = [
        'escola_id',
        'dia',
        'horario_inicio',
        'horario_termino',
        'descricao',
        'aula_fixa',
        'turma',
        'sala',
        'local',
        'professores',
        'disciplinas',
        'recursos'
    ];

    // aqui ele irá converter as variáveis para um único tipo, para não dar erro na hora de ler o JSON
    protected $casts = [
        'aula_fixa'   => 'boolean',
        'professores' => 'array',
        'disciplinas' => 'array',
        'recursos'    => 'array',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
}
