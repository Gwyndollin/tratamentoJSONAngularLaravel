<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    // fillable é para garantir que somente os campos nomeados vão ser preenchidos. É medida de segurança para atribuição em massa
    protected $fillable = ['id_externo','nome','codigo_externo','pendencia','escola_id'];
    protected $casts  = ['id_externo' => 'integer'];
    
    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }
}
