import { Selection } from '@/types/Selection';

export interface Market {
    id: number;
    name: string;
    selections: Selection[];
}
