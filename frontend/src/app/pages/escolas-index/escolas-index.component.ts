import { Component, OnInit } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { Escola } from '../../models/api.models';
import { Router } from '@angular/router';

@Component({
  selector: 'app-escolas-index',
  templateUrl: './escolas-index.component.html',
  styleUrls: ['./escolas-index.component.scss']
})
export class EscolasIndexComponent implements OnInit {
  escolas: Escola[] = [];
  loading = false;
  error?: string;
  message = '';

  constructor(private api: ApiService) { }

  ngOnInit(): void {
    this.loading = true;
    this.api.getEscolas().subscribe({
      next: (data) => { this.escolas = data; this.loading = false; },
      error: (err) => { console.error(err); this.error = 'Falha ao carregar as Escolas', this.loading = false; },
    });
  }

  carregar() {
    this.loading = true;
    // seu getEscolas já existente
    this.api.getEscolas().subscribe({
      next: (data) => { this.escolas = data; this.loading = false; },
      error: () => { this.error = 'Falha ao carregar escolas'; this.loading = false; }
    });
  }

  remover(id: number) {
    const ok = confirm('Tem certeza que deseja excluir este horário?');
    if (!ok) return;

    // otimista (remove da UI antes; se falhar, volta)
    const backup = [...this.escolas];
    this.escolas = this.escolas.filter(e => e.id !== id);

    this.api.deleteEscola(id).subscribe({
      next: (res) => { this.message = res.message || 'Excluído'; },
      error: () => {
        this.escolas = backup;
        alert('Erro ao excluir. Tente novamente.');
      }
    });
  }

}
