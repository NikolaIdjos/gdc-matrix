import { Market } from '@/types/Market';

export interface Selection {
    id: number;
    market_id: number;
    name: string;
    odds: number;
    market: Market;
}
