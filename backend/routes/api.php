<?php

use App\Http\Controllers\AulaController;
use App\Http\Controllers\DisciplinaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\TurmaController;

Route::get('/escolas', [EscolaController::class, 'index']);
Route::get('/escolas/{escola}/turmas', [TurmaController::class, 'index']);
Route::get('/escolas/{escola}/professores', [ProfessorController::class, 'index']);
Route::get('/escolas/{escola}/disciplinas', [DisciplinaController::class, 'index']);
Route::get('/escolas/{escola}/aulas', [AulaController::class, 'index']);
Route::post('/escolas', [EscolaController::class, 'store']);
Route::delete('/escolas/{id}', [EscolaController::class, 'destroy']);
