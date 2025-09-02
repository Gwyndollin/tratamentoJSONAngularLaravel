import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CriarEscolaComponent } from './criar-escola.component';

describe('CriarEscolaComponent', () => {
  let component: CriarEscolaComponent;
  let fixture: ComponentFixture<CriarEscolaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CriarEscolaComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CriarEscolaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
