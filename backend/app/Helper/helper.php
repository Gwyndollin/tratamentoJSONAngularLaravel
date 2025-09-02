<?php
use Illuminate\Support\Str;

// essa função (eu não lembro se eu chamo ela em outro lugar além ou eu só refiz no Importar he) retorna o nome da escola em maisculo sem espaços extras
if (!function_exists('canon')) {
    function canon(string $s): string {
        $s = preg_replace('/\s+/', ' ', $s);
        return Str::upper(trim($s));
    }
}