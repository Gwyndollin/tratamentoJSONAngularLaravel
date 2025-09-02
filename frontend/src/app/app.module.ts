import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

import { HttpClientModule } from '@angular/common/http';
import { ExemploComponent } from './pages/exemplo/exemplo.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { EscolasIndexComponent } from './pages/escolas-index/escolas-index.component';
import { EscolaDetalheComponent } from './pages/escola-detalhe/escola-detalhe.component';
import { CriarEscolaComponent } from './pages/criar-escola/criar-escola.component';

@NgModule({
  declarations: [
    AppComponent,
    ExemploComponent,
    EscolasIndexComponent,
    EscolaDetalheComponent,
    CriarEscolaComponent,
  ],
  imports: [
  BrowserModule,
  HttpClientModule, 
  ReactiveFormsModule,  // <- obrigatório para HttpClient
  FormsModule,
  AppRoutingModule,
],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
