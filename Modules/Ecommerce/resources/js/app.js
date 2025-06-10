import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import LayoutMain from "./layout/LayoutMain.vue";
import '@fortawesome/fontawesome-free/css/all.min.css'
// import '../css/sass/style.scss';
import '../css/sass/css/style.css';
import '../css/sass/css/css1.css';
import 'primeicons/primeicons.css';
import {ConfirmationService} from "primevue";
import StyleClass from 'primevue/styleclass';
import ToastService from 'primevue/toastservice'
import $ from 'jquery'
import 'slick-carousel'
import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'
window.$ = $
window.jQuery = $

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`]
        if (!page.default.layout && page.default.layout !== null) {
            page.default.layout = LayoutMain;
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
