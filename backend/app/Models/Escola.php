<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    protected $fillable = ['nome_escola', 'nome_canon', 'json_path'];

    public function turmas(){
        return $this->hasMany(Turma::class);
    }

    public function disciplinas(){
        return $this->hasMany(Disciplina::class);
    }

    public function professores(){
        return $this->hasMany(Professor::class);
    }

    public function Aulas(){
        return $this->hasMany(Aula::class);
    }

    
}
