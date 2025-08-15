<template>
  <div>
    <!-- Filtres -->
    <div class="row mb-3">
      <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Rechercher par nom" v-model="filters.nom" @input="applyFilters">
      </div>
      <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Rechercher par email" v-model="filters.email" @input="applyFilters">
      </div>
      <div class="col-md-3">
        <select class="form-select" v-model="filters.role" @change="applyFilters">
          <option value="">Tous les rôles</option>
          <option value="Admin">Admin</option>
          <option value="Utilisateur">Utilisateur</option>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select" v-model="filters.actif" @change="applyFilters">
          <option value="">Tous les statuts</option>
          <option value="true">Actif</option>
          <option value="false">Inactif</option>
        </select>
      </div>
    </div>

    <!-- Tableau -->
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-light">
          <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Statut</th>
            <th>Dernière connexion</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, index) in filteredUsers" :key="index">
            <td>{{ user.nom }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.role }}</td>
            <td>
              <span v-if="user.actif" class="badge bg-success">Actif</span>
              <span v-else class="badge bg-secondary">Inactif</span>
            </td>
            <td>{{ user.derniereConnexion }}</td>
            <td>
              <button class="btn btn-sm btn-primary me-1">Modifier</button>
              <button class="btn btn-sm btn-danger">Supprimer</button>
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
        { nom: 'Jean Dupont', email: 'jean@example.com', role: 'Admin', actif: true, derniereConnexion: '2025-08-14 14:30' },
        { nom: 'Marie Curie', email: 'marie@example.com', role: 'Utilisateur', actif: false, derniereConnexion: '2025-08-13 09:12' },
        { nom: 'Paul Martin', email: 'paul@example.com', role: 'Utilisateur', actif: true, derniereConnexion: '2025-08-10 16:45' }
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
