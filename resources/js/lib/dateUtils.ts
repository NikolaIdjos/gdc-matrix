import dayjs from 'dayjs';
import { App } from 'vue';

// Convert datetime to utc
export function toUTCDateOnly(dateStr: string): string {
    if (!dateStr) return '';
    const [year, month, day] = dateStr.split('-').map(Number);
    const utcDate = new Date(Date.UTC(year, month - 1, day));
    return utcDate.toISOString().split('T')[0];
}

// Format date
export default {
    install(app: App) {
        app.config.globalProperties.$formatDate = (dateString: string) => {
            return dayjs(dateString).format('DD.MM.YYYY HH:mm');
        };
    },
};
