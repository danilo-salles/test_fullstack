import { createRouter, createWebHashHistory } from 'vue-router';
import HomePage from '../views/HomePage.vue';
import DesenvolvedorPage from '../views/DesenvolvedorPage.vue';
import NiveisPage from '../views/NiveisPage.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage
  },
  {
    path: '/desenvolvedor',
    name: 'Desenvolvedor',
    component: DesenvolvedorPage
  },
  {
    path: '/niveis',
    name: 'Niveis',
    component: NiveisPage
  }
];

const router = createRouter({
    history: createWebHashHistory(), 
    routes,
  });

export default router;