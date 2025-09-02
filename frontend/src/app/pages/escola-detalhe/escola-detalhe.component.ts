import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { forkJoin } from 'rxjs';
import { ApiService } from '../../services/api.service';
import { Turma, Professor, Disciplina, Aulas, Escola } from '../../models/api.models';

@Component({
  selector: 'app-escola-detalhe',
  templateUrl: './escola-detalhe.component.html',
  styleUrls: ['./escola-detalhe.component.scss']
})
export class EscolaDetalheComponent implements OnInit {
  escolaId!: number;

  escolas: Escola[] = [];
  turmas: Turma[] = [];
  professores: Professor[] = [];
  disciplinas: Disciplina[] = [];
  aulas: Aulas[] = [];

  loading = false;
  error?: string;

  // “tabelas” simples: índice = id_externo (ou id), valor = nome
  profByIdArr: (string | undefined)[] = [];
  discByIdArr: (string | undefined)[] = [];
  turmaByIdArr: (string | undefined)[] = [];

  constructor(private route: ActivatedRoute, private api: ApiService) { }

  ngOnInit(): void {
    this.escolaId = Number(this.route.snapshot.paramMap.get('id'));
    if (!this.escolaId) {
      this.error = 'ID da escola inválido';
      return;
    }

    this.loading = true;

    this.api.getEscolas().subscribe({
      next: (data) => { this.escolas = data; this.loading = false; },
      error: (err) => { console.error(err); this.error = 'Falha ao carregar as Escolas', this.loading = false; },
    });

    forkJoin({
      turmas: this.api.getTurmas(this.escolaId),
      professores: this.api.getProfessor(this.escolaId),
      disciplinas: this.api.getDisciplina(this.escolaId),
      aulas: this.api.getAulas(this.escolaId),
    }).subscribe({
      next: (res) => {
        this.turmas = res.turmas ?? [];
        this.professores = res.professores ?? [];
        this.disciplinas = res.disciplinas ?? [];
        this.aulas = res.aulas ?? [];

        // monta os “dicionários” por id_externo (ou id se não vier)
        this.profByIdArr = [];
        for (const p of this.professores) {
          const key = Number((p as any).id_externo ?? p.id);
          if (!Number.isNaN(key)) this.profByIdArr[key] = p.nome;
        }

        this.discByIdArr = [];
        for (const d of this.disciplinas) {
          const key = Number((d as any).id_externo ?? d.id);
          if (!Number.isNaN(key)) this.discByIdArr[key] = d.nome;
        }

        this.turmaByIdArr = [];
        for (const t of this.turmas) {
          const key = Number((t as any).id_externo ?? t.id);
          if (!Number.isNaN(key)) this.turmaByIdArr[key] = t.nome;
        }

        this.loading = false;
      },
      error: () => {
        this.error = 'Falha ao carregar dados da escola';
        this.loading = false;
      },
    });
  }

  getAulasDaTurma(turmaId: number) {
    const ordemDias = ['SEG', 'TER', 'QUA', 'QUI', 'SEX'];
    return this.aulas
      .filter(a => Number(a.turma) === Number(turmaId))
      .sort((a, b) => {
        const d = ordemDias.indexOf(a.dia) - ordemDias.indexOf(b.dia);
        return d !== 0 ? d : (a.horario_inicio ?? '').localeCompare(b.horario_inicio ?? '');
      });
  }

  getTurmaNome(id: any): string {
    if (id == null) return '';
    const num = Number(id);
    if (Number.isNaN(num)) return '';
    return this.turmaByIdArr[num] ?? `#${id}`;
  }

  getAulasDoProfessor(profId: number) {
    const ordemDias = ['SEG', 'TER', 'QUA', 'QUI', 'SEX'];
    return this.aulas
      .filter(a => Array.isArray(a.professores) && a.professores.map(Number).includes(Number(profId)))
      .sort((a, b) => {
        const d = ordemDias.indexOf(a.dia) - ordemDias.indexOf(b.dia);
        return d !== 0 ? d : (a.horario_inicio ?? '').localeCompare(b.horario_inicio ?? '');
      });
  }

  // time slots únicos (HH:MM) com base nas aulas da turma
  getTimeSlots(turmaId: number): Array<{ inicio: string; fim: string }> {
    const set = new Set<string>();
    for (const a of this.aulas) {
      if (Number(a.turma) !== Number(turmaId)) continue;
      const inicio = a.horario_inicio?.slice(0, 5);
      const fim = a.horario_termino?.slice(0, 5);
      if (inicio && fim) set.add(`${inicio}-${fim}`);
    }


    return Array.from(set)
      .map(k => { const [inicio, fim] = k.split('-'); return { inicio, fim }; })
      .sort((x, y) => x.inicio.localeCompare(y.inicio));
  }

  getTimeSlotsProfessor(profId: number): Array<{ inicio: string; fim: string }> {
    const set = new Set<string>();
    for (const a of this.aulas) {
      if (!Array.isArray(a.professores)) continue;
      if (!a.professores.map(Number).includes(Number(profId))) continue;

      const inicio = a.horario_inicio?.slice(0, 5);
      const fim = a.horario_termino?.slice(0, 5);
      if (inicio && fim) set.add(`${inicio}-${fim}`);
    }
    return Array.from(set)
      .map(k => { const [inicio, fim] = k.split('-'); return { inicio, fim }; })
      .sort((x, y) => x.inicio.localeCompare(y.inicio));
  }

  // nomes pelos IDs externos
  getProfessor(ids: number[] | null | undefined): string {
    if (!ids?.length) return '';
    return ids.map(id => this.profByIdArr[Number(id)] ?? `#${id}`).join(', ');
  }

  getDisciplina(ids: number[] | null | undefined): string {
    if (!ids?.length) return '';
    return ids.map(id => this.discByIdArr[Number(id)] ?? `#${id}`).join(', ');
  }

  getTurmas(ids: number[] | null | undefined): string {
    if (!ids?.length) return '';
    return ids.map(id => this.turmaByIdArr[Number(id)] ?? `#${id}`).join(', ');
  }
}
