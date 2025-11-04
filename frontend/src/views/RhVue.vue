<template>
  <nav id="menu-verticale" class="bg-light p-3 d-flex flex-column justify-content-between ">
    <div id="menu" class="nav flex-column">
      <p class="mb-3 ms-3" style="font-size: 14px; opacity: 0.7;">NAVIGATION</p>
      <a class="nav-link" 
         :class="{ active: menu_ele === 1 }" 
         @click="menu_ele = 1">
         <font-awesome-icon :icon="['fas', 'bar-chart']" style="font-size: 18px;" aria-hidden="true" />
         &nbsp; Tableau de bord</a>

      <a class="nav-link" 
         :class="{ active: menu_ele === 2 }" 
         @click="menu_ele = 2">
         <font-awesome-icon :icon="['fas', 'comments']" style="font-size: 18px;" aria-hidden="true" />
         &nbsp; Feedbacks</a>

      <a class="nav-link" 
         :class="{ active: menu_ele === 3 }" 
         @click="menu_ele = 3">
         <font-awesome-icon :icon="['fas', 'file-text']" style="font-size: 18px;" aria-hidden="true" />
         &nbsp; Rapports</a>

      <a class="nav-link" 
         :class="{ active: menu_ele === 4 }" 
         @click="menu_ele = 4">
         <font-awesome-icon :icon="['fas', 'users-gear']" style="font-size: 18px;" aria-hidden="true" />
         &nbsp; Gestion d'utilisateurs</a>
    </div>

    <div id="menu" class="nav flex-column">
      <p class="mb-3 ms-3" style="font-size: 14px; opacity: 0.7;">COMPTE</p>
      
      <a style="color: black; text-decoration: none; cursor: pointer;" class="ps-3 pt-2  pb-2">
        <font-awesome-icon :icon="['fas', 'cog']" style="font-size: 16px; color: black" aria-hidden="true" />
        &nbsp; Paramètres</a>
      <a style="color: red; text-decoration: none; cursor: pointer;" class="ps-3 pt-2 pb  -2" @click="delogin" >
        <font-awesome-icon :icon="['fas', 'sign-out']" style="font-size: 16px; color: red" aria-hidden="true" />
        &nbsp; Déconnexion</a>

      <hr>
      <div class="d-flex gap-3 pt-3">
        <div style="background-color: #00000028; padding: 10px; height: max-content; width: max-content; border-radius: 50%;">
           <font-awesome-icon :icon="['fas', 'user']" style="font-size: 20px; color: #6C757D;" aria-hidden="true" />
        </div>
        <div class="d-flex flex-column gap-1">
          <h6 class="m-0">{{ connected.name }} {{ connected.firstname }}</h6>
          <p class="m-0">{{ connected.username }}</p>
        </div>
      </div>
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
import api from "../api";
import { parseJwt } from "../utils/jwt";

export default {
  components: {
    Dashboard,
    Feedback,
    User_manage,
    Rapport
  },
  data() {
    return {
      menu_ele: 1,
      connected: {} 
    }
  },
  mounted(){
    if (!sessionStorage.getItem("token")){
      this.$router.push('/')
    }
    else {
      this.connected = parseJwt(sessionStorage.getItem("token"));
    }
  },
  methods: {
    async delogin() {
      try {
        const res = await api.post("/deconnexion");

        sessionStorage.removeItem("token");
        this.$router.push('/');
      } catch (err) {
        if (err.response) {
          if (err.response.status === 401 && err.response.data.detail === "Votre compte est désactivé.") {
            this.error = "Votre compte est désactivé";
          } else {
            this.error = "Identifiants invalides";
          }
        } else {
          console.error("Erreur déconnexion :", err.response?.data || err);
        }
      }
    }
  }
}
</script>
