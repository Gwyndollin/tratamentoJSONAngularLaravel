<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    // fillable é para garantir que somente os campos nomeados vão ser preenchidos. É medida de segurança para atribuição em massa
    // esse $table é pro laravel n pensar que o plural de professor é diferente de profesosores
    protected $table = 'professores';
    protected $fillable = ['id_externo','nome','escola_id'];
    
    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }
}
