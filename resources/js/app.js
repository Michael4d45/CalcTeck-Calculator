import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import CalculatorView from './views/CalculatorView.vue';
import './bootstrap';

const routes = [
    {
        path: '/',
        name: 'calculator',
        component: CalculatorView,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp(App);
app.use(router);
app.mount('#app');
