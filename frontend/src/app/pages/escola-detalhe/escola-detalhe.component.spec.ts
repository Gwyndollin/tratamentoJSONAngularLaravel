import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EscolaDetalheComponent } from './escola-detalhe.component';

describe('EscolaDetalheComponent', () => {
  let component: EscolaDetalheComponent;
  let fixture: ComponentFixture<EscolaDetalheComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EscolaDetalheComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EscolaDetalheComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
