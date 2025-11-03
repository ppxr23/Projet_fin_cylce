<template>
  <div>
    <div class="row mb-4">
      <div class="col-md-2">
        <input type="text" class="form-control" placeholder=" 🔍︎   Rechercher par nom" v-model="filters.nom" @input="applyFilters">
      </div>
      <div class="col-md-1">
        <select class="form-select" v-model="filters.role" @change="applyFilters">
          <option value="">Rôles</option>
          <option value="Admin">Admin</option>
          <option value="rh">RH</option>
          <option value="employe">employé</option>
        </select>
      </div>
      <div class="col-md-1">
        <select class="form-select" v-model="filters.actif" @change="applyFilters">
          <option value="">Statut</option>
          <option value="true">Actif</option>
          <option value="false">Inactif</option>
        </select>
      </div>
    </div>

    <!-- Tableau -->
    <div class="table-responsive">
      <table class="table table-striped">
        <thead class="">
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Statut</th>
            <th>Date de création</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, index) in filteredUsers" :key="index">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}  {{ user.firstname }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.roles[0] }}</td>
            <td>
              <span v-if="user.status" class="badge bg-success" style="width: 100px; padding: 10px;">Actif</span>
              <span v-else class="badge bg-secondary" style="width: 100px; padding: 10px;">Inactif</span>
            </td>
            <td>{{ user.last_connexion }}</td>
            <td class="d-flex gap-3">
              <a href="">
                <font-awesome-icon :icon="['fas', 'fa-pencil']" style="font-size: 25px; color: #6C757D;" aria-hidden="true" />
              </a>
              <a href="">
                <font-awesome-icon :icon="['fas', 'trash']" style="font-size: 25px; color: red;" aria-hidden="true" @click.prevent="deleteUser(user.id)" />
              </a>
              <a href="">
                <font-awesome-icon :icon="['fas', 'eye']" style="font-size: 25px; color: blue ;" aria-hidden="true" />
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import api from "../../api";
import { useToast } from "vue-toastification";

export default {
  name: 'UserTable',

  data() {
    return {
      filters: {
        nom: '',
        email: '',
        role: '',
        actif: ''
      },
      users: []
    };
  },

  async mounted() {
    const toast = useToast();

    try {
      const res = await api.get("list_users"); // pas besoin de POST
      this.users = res.data; // stocke les utilisateurs récupérés
      console.log("Utilisateurs :", this.users);
    } 
    catch (err) {
      if (err.response.data) {
        if (err.response.status === 401 && err.response.data.message === "Expired JWT Token") {
          toast.error("Session expiré");
          sessionStorage.removeItem("token");
          this.$router.push('/');
        }
      } else {
        toast.error("Erreur de connexion au serveur");
      }
    }
  },

  computed: {
    filteredUsers() {
      return this.users.filter(user => {
        const matchNom = user.name?.toLowerCase().includes(this.filters.nom.toLowerCase());
        const matchEmail = user.email?.toLowerCase().includes(this.filters.email.toLowerCase());
        const matchRole = this.filters.role ? user.roles.includes(this.filters.role) : true;
        const matchActif = this.filters.actif !== '' ? String(user.status) === this.filters.actif : true;
        return matchNom && matchEmail && matchRole && matchActif;
      });
    }
  },

  methods: {
    applyFilters() {

    },
    async deleteUser(userId) {
      const toast = useToast();
      if (!confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) return;

      try {

        await api.delete(`delete_user/${userId}`);

        this.users = this.users.filter(user => user.id !== userId);
        toast.success("Utilisateur supprimé avec succès !");
      } catch (err) {
        if (err.response) {
          if (err.response.status === 401 && err.response.data.message === "Expired JWT Token") {
            toast.error("Session expiré");
          } else {
            toast.error = "Identifiants invalides";
          }
        } else {
          toast.error("Erreur de connexion au serveur");
        }
      }
    }
  }
};
</script>

<style>
.table-responsive {
  overflow-x: auto;
}
</style>
