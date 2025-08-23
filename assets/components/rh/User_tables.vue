<template>
  <div>
    <!-- Filtres -->
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
            <td>{{ user.nom }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.role }}</td>
            <td>
              <span v-if="user.actif" class="badge bg-success" style="width: 100px; padding: 10px;">Actif</span>
              <span v-else class="badge bg-secondary" style="width: 100px; padding: 10px;">Inactif</span>
            </td>
            <td>{{ user.derniereConnexion }}</td>
            <td class="d-flex gap-3">
              <a href="">
                <font-awesome-icon :icon="['fas', 'fa-pencil']" style="font-size: 25px; color: #6C757D;" aria-hidden="true" />
              </a>
              <a href="">
                <font-awesome-icon :icon="['fas', 'trash']" style="font-size: 25px; color: #6C757D;" aria-hidden="true" />
              </a>
              <a href="">
                <font-awesome-icon :icon="['fas', 'eye']" style="font-size: 25px; color: #6C757D;" aria-hidden="true" />
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
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
      users: [
        { id:1, nom: 'Jean Dupont', email: 'jean@example.com', role: 'Admin', actif: true, derniereConnexion: '2025-08-14 14:30' },
        { id:2, nom: 'Marie Curie', email: 'marie@example.com', role: 'Utilisateur', actif: false, derniereConnexion: '2025-08-13 09:12' },
        { id:3, nom: 'Paul Martin', email: 'paul@example.com', role: 'Utilisateur', actif: true, derniereConnexion: '2025-08-10 16:45' }
      ]
    };
  },
  computed: {
    filteredUsers() {
      return this.users.filter(user => {
        const matchNom = user.nom.toLowerCase().includes(this.filters.nom.toLowerCase());
        const matchEmail = user.email.toLowerCase().includes(this.filters.email.toLowerCase());
        const matchRole = this.filters.role ? user.role === this.filters.role : true;
        const matchActif = this.filters.actif !== '' ? String(user.actif) === this.filters.actif : true;
        return matchNom && matchEmail && matchRole && matchActif;
      });
    }
  },
  methods: {
    applyFilters() {
      // Computed `filteredUsers` s'actualise automatiquement
    }
  }
};
</script>

<style>
.table-responsive {
  overflow-x: auto;
}
</style>
