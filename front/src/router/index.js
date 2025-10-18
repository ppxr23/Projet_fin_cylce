import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Rh from '../views/RhVue.vue'
import Collab from '../views/CollabVue.vue'
import Manager from '../views/ManagerVue.vue'

const routes = [
  { path: '/', component: Login },
  { path: '/rh', component: Rh },
  { path: '/manager', component: Manager },
  { path: '/collab', component: Collab },
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
