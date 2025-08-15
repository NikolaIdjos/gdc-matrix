import { Market } from '@/types/Market';

interface Bet {
    eventId: number;
    name: string;
    markets: Market[];
}
