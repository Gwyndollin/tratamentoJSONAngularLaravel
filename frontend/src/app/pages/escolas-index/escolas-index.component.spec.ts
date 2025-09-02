import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EscolasIndexComponent } from './escolas-index.component';

describe('EscolasIndexComponent', () => {
  let component: EscolasIndexComponent;
  let fixture: ComponentFixture<EscolasIndexComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EscolasIndexComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EscolasIndexComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
