import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Escola, Turma, Aulas, Professor, Disciplina } from '../models/api.models';
import { environment } from '../../environments/environment';

@Injectable({ providedIn: 'root' })
export class ApiService {

    private base = 'http://localhost:8000/api';

    constructor(private http: HttpClient) { }

    getEscolas(): Observable<Escola[]> {
        return this.http.get<Escola[]>(`${this.base}/escolas`)
    }

    getTurmas(escolaId: number): Observable<Turma[]> {
        return this.http.get<Turma[]>(`${this.base}/escolas/${escolaId}/turmas`)
    }

    getProfessor(escolaId: number): Observable<Professor[]> {
        return this.http.get<Professor[]>(`${this.base}/escolas/${escolaId}/professores`);
    }

    getDisciplina(escolaId: number): Observable<Disciplina[]> {
        return this.http.get<Disciplina[]>(`${this.base}/escolas/${escolaId}/disciplinas`)
    }

    getAulas(escolaId: number, opts?: { dia?: string; turma?: string }): Observable<Aulas[]> {
        let params = new HttpParams();
        if (opts?.dia) params = params.set('dia', opts.dia);
        if (opts?.turma) params = params.set('turma', opts.turma);

        return this.http.get<Aulas[]>(`${this.base}/escolas/${escolaId}/aulas`, { params });
    }

    deleteEscola(id: number) {
        return this.http.delete<{ message: string }>(`${environment.apiBase}/escolas/${id}`);
    }

}

