import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Rh from '../views/RhVue.vue'
import Collab from '../views/CollabVue.vue'
import Manager from '../views/ManagerVue.vue'
import { parseJwt } from "../utils/jwt"

const routes = [
  { path: '/', component: Login },
  { path: '/rh', component: Rh, meta: { requiresAuth: true } },
  { path: '/manager', component: Manager, meta: { requiresAuth: true } },
  { path: '/collab', component: Collab, meta: { requiresAuth: true } },
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const token = sessionStorage.getItem("token");
  const user = parseJwt(token);

  // Si la route est protégée et pas de token → login
  if (to.meta.requiresAuth && !token) return next('/')

  // Si utilisateur connecté sur login → redirige selon rôle
  if (token && to.path === '/') {
    if (user.roles === 'RH' && to.path != '/rh') return next('/rh')
    if (user.roles === 'MANAGER' && to.path != '/manager') return next('/manager')
    if (user.roles === 'COLLABORATEUR' && to.path != '/collab') return next('/collab')
  }

  // Vérifie le rôle pour routes protégées
  if (to.meta.requiresAuth) {
    if (!user.roles) {
      alert(user.roles)
      if (user.roles === 'RH') return next('/rh')
      if (user.roles === 'MANAGER') return next('/manager')
      if (user.roles === 'COLLABORATEUR') return next('/collab')
      return next('/')
    }
  }

  next()
})

export default router
