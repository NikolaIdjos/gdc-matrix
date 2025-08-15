import { Team } from '@/types/Team';
import { League } from '@/types/League';
import { Market } from '@/types/Market';

export interface Event {
    id: number;
    name: string;
    slug: string;
    scheduled_at: string;
    status_id: number;
    status: string;
    competitor_type_id: number;
    competitor_type: string;
    league?: League;
    teams: Team[];
    markets: Market[];
}
