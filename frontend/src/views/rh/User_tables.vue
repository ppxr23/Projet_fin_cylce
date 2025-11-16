<template>
  <div>
    <div class="row mb-4">
      <div class="col-md-2">
        <input
          v-model="filters.nom"
          type="text"
          class="form-control"
          placeholder=" 🔍︎   Rechercher par nom"
          @input="applyFilters"
        >
      </div>
      <div class="col-md-1">
        <select
          v-model="filters.role"
          class="form-select"
          @change="applyFilters"
        >
          <option value="">
            Rôles
          </option>
          <option value="Admin">
            Admin
          </option>
          <option value="RH">
            RH
          </option>
          <option value="COLLABORATEUR">
            Collaborateur
          </option>
        </select>
      </div>
      <div class="col-md-1">
        <select
          v-model="filters.actif"
          class="form-select"
          @change="applyFilters"
        >
          <option value="">
            Statut
          </option>
          <option value="true">
            Actif
          </option>
          <option value="false">
            Inactif
          </option>
        </select>
      </div>
    </div>

    <!-- Tableau -->
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Vigie</th>
            <th>Statut</th>
            <th>Date de création</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(user, index) in filteredUsers.filter(u => u.email !== connected.username)"
            :key="index"
          >
            <td>{{ user.matricule }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.firstname }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.role }}</td>
            <td>{{ user.vigie?.name }}</td>
            <td>
              <span
                v-if="user.statut"
                class="badge bg-success"
                style="width: 100px; padding: 10px;"
              >Actif</span>
              <span
                v-else
                class="badge bg-secondary"
                style="width: 100px; padding: 10px;"
              >Inactif</span>
            </td>
            <td>{{ formatDate(user.dateCreation) }}</td>
            <td class="d-flex gap-3">
              <a @click.prevent="$emit('edit-user', user)">
                <font-awesome-icon
                  :icon="['fas', 'pencil']"
                  style="font-size: 25px; color: #6C757D;"
                />
              </a>
              <a @click.prevent="deleteUser(user.id)">
                <font-awesome-icon
                  :icon="['fas', 'trash']"
                  style="font-size: 25px; color: red;"
                />
              </a>
              <a>
                <font-awesome-icon
                  :icon="['fas', 'history']"
                  style="font-size: 25px; color: orange;"
                />
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
import Swal from 'sweetalert2';
import { parseJwt } from "../../utils/jwt";

export default {
  name: 'UserTable',
  emits: ["edit-user"],
  data() {
    return {
      filters: { nom: '', email: '', role: '', actif: '' },
      users: [],
      connected: {}
    };
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

  async mounted() {
    this.fetchUsers();
    
    if (!sessionStorage.getItem("token")){
      this.$router.push('/')
    }
    else {
      this.connected = parseJwt(sessionStorage.getItem("token"));
    }
  },

  methods: {
    formatDate(dateStr) {
      const date = new Date(dateStr)
      return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
      })
    },

    async fetchUsers() {
      try {
        if (!sessionStorage.getItem("token")){
          this.$router.push('/')
        }
        else {
          this.connected = parseJwt(sessionStorage.getItem("token"));
        }

        const res = await api.post("list_user_rh",{
          matricule: this.connected.matricule,
          roles: 'RH',
          all: true
        });
        this.users = res.data;
      } catch (err) {
        console.log(err);
      }
    },

    applyFilters() {},

    async deleteUser(userId) {
      const result = await Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous ne pourrez pas revenir en arrière !",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Oui, supprimer',
        confirmButtonColor: '#d33'
      });

      if (result.isConfirmed) {
        try {
          await api.delete(`delete_user/${userId}`);
          this.users = this.users.filter(u => u.id !== userId);
          Swal.fire({
            title: 'Supprimé !',
            text: 'Utilisateur supprimé avec succès.',
            icon: 'success',
            confirmButtonText: 'Fermer',
            confirmButtonColor: '#16738A'
          });
        } catch (err) {
          Swal.fire('Erreur', 'Impossible de supprimer l’utilisateur.', 'error');
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
