import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ExemploComponent } from './pages/exemplo/exemplo.component';
import { EscolasIndexComponent } from './pages/escolas-index/escolas-index.component';
import { EscolaDetalheComponent } from './pages/escola-detalhe/escola-detalhe.component';
import { CriarEscolaComponent } from './pages/criar-escola/criar-escola.component';


const routes: Routes = [
  { path: 'escolas', component: EscolasIndexComponent },
  { path: 'escolas/:id', component: EscolaDetalheComponent },
  { path: 'criarEscola', component: CriarEscolaComponent },
  { path: '', redirectTo: 'escolas', pathMatch: 'full' },
  { path: '**', redirectTo: 'escolas' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule { }
