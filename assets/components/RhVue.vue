<template>
  <nav id="menu-verticale" class="bg-light p-3">
    <div id="menu" class="nav flex-column">
      <a class="nav-link" 
         :class="{ active: menu_ele === 1 }" 
         @click="menu_ele = 1">Tableau de bord</a>

      <a class="nav-link" 
         :class="{ active: menu_ele === 2 }" 
         @click="menu_ele = 2">Feedbacks</a>

      <a class="nav-link" 
         :class="{ active: menu_ele === 3 }" 
         @click="menu_ele = 3">Rapports</a>

      <a class="nav-link" 
         :class="{ active: menu_ele === 4 }" 
         @click="menu_ele = 4">Gestion d'utilisateurs</a>
    </div>
  </nav>

  <nav id="menu-content">
    <Dashboard v-if="menu_ele === 1" />
    <Feedback v-if="menu_ele === 2" />
    <Rapport v-if="menu_ele ===3" />
    <User_manage v-else-if="menu_ele === 4" />
  </nav>
</template>

<script>
import Dashboard from './rh/Dashboard.vue';
import Feedback from './rh/Feedback.vue';
import Rapport from './rh/Rapport.vue';
import User_manage from './rh/User_manage.vue';

export default {
  components: {
    Dashboard,
    Feedback,
    User_manage,
    Rapport
  },
  data() {
    return {
      menu_ele: 3
    }
  }
}
</script>
