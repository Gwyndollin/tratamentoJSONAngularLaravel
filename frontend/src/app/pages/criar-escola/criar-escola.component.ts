import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-criar-escola',
  templateUrl: './criar-escola.component.html',
  styleUrls: ['./criar-escola.component.scss']
})

export class CriarEscolaComponent {
  form!: FormGroup;
  selectedFile?: File;
  message = '';
  validationErrors: { [key: string]: string[] } | null = null;

  constructor(private fb: FormBuilder, private http: HttpClient) {
    this.form = this.fb.group({
      nome: ['', Validators.required],
    });
  }

  onFileChange(event: any) {
    const file = event.target.files[0];
    if (file) this.selectedFile = file;
  }

  onSubmit() {
    if (!this.selectedFile) return;

    const formData = new FormData();
    formData.append('nome', this.form?.value.nome);
    formData.append('file', this.selectedFile);

    this.http.post(`${environment.apiBase}/escolas`, formData).subscribe({
      next: (res) => (this.message = 'Escola adicionada com sucesso!'),
      error: (err) => {
        console.error('Falha no upload', err);
        // Mostra a mensagem genérica
        this.message = err?.error?.message || 'Erro ao adicionar a escola';
        // Mostra mensagens de validação (422)
        this.validationErrors = err?.error?.errors || null;
      },
    });
  }

  ngOnInit(): void {
  }

}
