import '../css/app.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import dateUtils from '@/lib/dateUtils';
import AppLayout from '@/layouts/AppLayout.vue';
import { IMaskDirective } from 'vue-imask';
import axios from 'axios';
import Toast, { useToast, POSITION } from 'vue-toastification';
import 'vue-toastification/dist/index.css';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue')
        );

        if (!page.default.layout) {
            page.default.layout = AppLayout;
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(dateUtils)
            .use(Toast, { position: POSITION.TOP_RIGHT })
            .directive('imask', IMaskDirective);

        const toast = useToast();

        axios.interceptors.response.use(
            (response) => {
                return response;
            },
            (error) => {
                if (error.response?.status === 500) {
                    toast.error(error.message);
                }
                if (error.response?.status === 422) {
                    const errors = error.response.data.errors;
                    const messages = Object.values(errors).flat().join('\n');
                    toast.error(messages);
                } else {
                    toast.error(error.messages);
                }
                return Promise.reject(error);
            }
        );

        app.mount(el);
    },
});
