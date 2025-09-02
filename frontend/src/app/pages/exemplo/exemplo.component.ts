/**import { Component, OnInit } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { Escola, Turma, Aulas, Disciplina, Professor } from '../../models/api.models';

@Component({
  selector: 'app-exemplo',
  templateUrl: './exemplo.component.html',
  styleUrls: ['./exemplo.component.scss']
})
export class ExemploComponent implements OnInit {

  escolas: Escola[] = [];
  turmas: Turma[] = [];
  professores: Professor[] = [];
  disciplinas: Disciplina[] = [];
  aulas: Aulas[] = [];

  constructor(private api: ApiService) { }

  ngOnInit(): void {
    this.api.getEscolas().subscribe({
      next: (data) => (this.escolas = data),
      error: (err) => console.error('Erro ao carregar escolas', err),
    });
  }

  carregarTurmas(escolaId: number) {
    this.api.getTurmas(escolaId).subscribe({
      next: (data) => (this.turmas = data),
      error: (err) => console.error('Erro ao carregar turmas', err),
  });
}

carregarProfessores(escolaId: number){
  this.api.getProfessor(escolaId).subscribe({
    next: (data) => (this.professores = data),
    error: (err) => console.error('Erro ao carregar os Professores', err),
  });
}

carregarDisciplinas(escolaId: number){
  this.api.getDisciplina(escolaId).subscribe({
    next: (data) => (this.disciplinas = data),
    error: (err) => console.error('Erro ao carregar as Disciplinas', err),
  });
}

carregarAulas(escolaId: number, dia ?: string, turma ?: string){
  this.api.getAulas(escolaId, { dia, turma }).subscribe({
    next: (data) => (this.aulas = data),
    error: (err) => console.error('Erro ao carregar as Aulas', err),
  });
}

}
*/
import { Component, OnInit } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { Escola, Turma, Aulas, Professor, Disciplina } from '../../models/api.models';

@Component({
  selector: 'app-exemplo',
  templateUrl: './exemplo.component.html',
  styleUrls: ['./exemplo.component.scss'],
})
export class ExemploComponent implements OnInit {
  escolas: Escola[] = [];
  turmas: Turma[] = [];
  aulas: Aulas[] = [];
  professores: Professor[] = [];
  disciplinas: Disciplina[] = [];

  selectedEscolaId?: number;
  error?: string;
  loading = false;

  constructor(private api: ApiService) { }

  ngOnInit(): void {
    this.carregarEscolas();
  }

  private carregarEscolas() {
    this.loading = true; this.error = undefined;
    this.api.getEscolas().subscribe({
      next: (data) => (this.escolas = data),
      error: (err) => {
        console.error('Erro escolas (detalhe):', err);
        this.error = `Falha ao carregar escolas (HTTP ${err.status || '???'})`;
      },
    });
  }

  onTrocarEscola(event: Event) {
    const select = event.target as HTMLSelectElement;
    this.selectedEscolaId = Number(select.value) || undefined;
    this.turmas = [];
    this.aulas = [];
    this.professores = [];
    this.disciplinas = [];
    if (this.selectedEscolaId) {
      this.carregarTurmas();
      this.carregarAulas();
      this.carregarProfessores();
      this.carregarDisciplinas();
    }
  }

  carregarTurmas() {
    if (!this.selectedEscolaId) return;
    this.api.getTurmas(this.selectedEscolaId).subscribe({
      next: (data) => (this.turmas = data),
      error: (err) => {
        console.error('Erro ao carregar turmas', err);
        this.error = 'Falha ao carregar turmas';
      },
    });
  }

  carregarProfessores() {
    if (!this.selectedEscolaId) return;
    this.api.getProfessor(this.selectedEscolaId).subscribe({
      next: (data) => (this.professores = data),
      error: (err) => {
        console.error('Erro ao carregar Professores', err);
        this.error = 'Falha ao carregar Professores';
      },
    });
  }

  carregarDisciplinas() {
    if (!this.selectedEscolaId) return;
    this.api.getDisciplina(this.selectedEscolaId).subscribe({
      next: (data) => (this.disciplinas = data),
      error: (err) => {
        console.error('Erro ao carregar Disciplinas', err);
        this.error = 'Falha ao carregar Disciplinas';
      },
    });
  }

  carregarAulas() {
    if (!this.selectedEscolaId) return;
    this.api.getAulas(this.selectedEscolaId).subscribe({
      next: (data) => (this.aulas = data),
      error: (err) => {
        console.error('Erro ao carregar aulas', err);
        this.error = 'Falha ao carregar aulas';
      },
    });
  }
}

