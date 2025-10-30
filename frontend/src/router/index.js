import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import DashboardView from '../views/DashboardView.vue'
import ProjectListView from '../views/ProjectListView.vue'
import TaskListView from '../views/TaskListView.vue'

//Definicion de rutas
const routes = [
  {path: '/', redirect: '/login' },
  {path: '/login', component: LoginView },
  {path: '/register', component: RegisterView },
  {
    path: '/dashboard',
    component: DashboardView,
    meta: {requiresAuth: true}
  },
  {
    path: '/projects', 
    component: ProjectListView,
    meta: {requiresAuth: true}
  },
  {
    path: '/tasks',
    component: TaskListView,
    meta: {requiresAuth: true}
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

//Protege rutas que requieren login
router.beforeEach((to) => {
  const auth = useAuthStore();

  //Si la ruta requiere autenticacion y no hay token, redirigir a login
  if (to.meta.requiresAuth && !auth.token) {
    return '/login'
  }

  //Si intenta ir a login y ya esta autenticado, redirigir a dashboard
  if ((to.path === '/login' || to.path === '/register') && auth.token) {
    return '/dashboard'
  }

  return true;
});

export default router
