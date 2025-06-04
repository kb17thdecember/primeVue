import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import Layout from "./layout/Layout.vue";
import '../js/assets/styles.scss';
import 'primeicons/primeicons.css';
import {ConfirmationService} from "primevue";
import StyleClass from 'primevue/styleclass';
import ToastService from 'primevue/toastservice'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`]
        if (!page.default.layout && page.default.layout !== null) {
            page.default.layout = Layout;
        }

        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue,{
                theme: {
                    preset: Aura,
                    options: {
                        darkModeSelector: '.app-dark'
                    }
                }
            })
            .use(ToastService)
            .use(ConfirmationService)
            .directive('styleclass', StyleClass)
            .mount(el)
    },
})
