export type DiaSem = 'SEG' | 'TER' | 'QUA' | 'QUI' | 'SEX';
export const DIAS: DiaSem[] = ['SEG', 'TER', 'QUA', 'QUI', 'SEX'];

export interface Escola {
    id: number;
    nome_escola: string;
}

export interface Turma {
    id: number;
    nome: string;
    id_externo?: number;
    codigo_externo?: string | null;
    pendencia?: number | null;
}

export interface Professor {
    id: number;
    nome: string;
    id_externo?: number;
    codigo_externo?: string | null;
}

export interface Disciplina {
    id: number;
    nome: string;
    id_externo?: number;
    codigo_externo?: string | null;
}

export interface Aulas {
turmas: number[]|null|undefined;
    id: number;
    dia: string;
    horario_inicio: string;
    horario_termino: string;
    descricao: string;
    aula_fixa: boolean;
    turma: string | null;
    sala: string | null;
    professores: number[];
    disciplinas: number[];
    recursos: string[];
}

export type ProfessorNomeMap  = Record<number, string>;
export type DisciplinaNomeMap = Record<number, string>;