import Dashboard from "./pages/Dashboard.vue";
import Landing from "./pages/Landing.vue";
import {createRouter, createWebHistory} from "vue-router/dist/vue-router";

const routes = [
    {
        name: 'home',
        path: '/',
        component: Landing
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    }
];

export const router = createRouter({
    history: createWebHistory('/'),
    routes
});
