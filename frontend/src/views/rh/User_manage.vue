<template>
  <div>
    <div class="d-flex w-100 justify-content-between mb-3">
      <h2>Gestion des utilisateurs</h2>

      <div class="d-flex gap-3">
        <a
          class="btn d-flex gap-2"
          style="padding: 10px 40px; background-color: #16738A; color: #fff; cursor: pointer;"
          @click="openAddModal"
        >
          <b>Ajouter un utilisateur</b>
          <font-awesome-icon
            :icon="['fas', 'plus-circle']"
            style="font-size: 20px; color: #fff;"
          />
        </a>
      </div>
    </div>

    <!-- Tableau des utilisateurs -->
    <User_tables
      ref="userTable"
      @edit-user="openEditModal"
    />

    <!-- Modale d’ajout/modification -->
    <div
      v-if="showModal"
      class="modal-backdrop"
    >
      <div class="modal-content">
        <h3 class="mb-4 d-flex justify-content-center">
          {{ isEditing ? 'Modifier un utilisateur' : 'Ajouter un utilisateur' }}
        </h3>

        <form @submit.prevent="addUser">
          <div class="mb-4">
            <input
              v-model="newUser.name"
              type="text"
              placeholder="Nom"
              class="form-control"
              style="height: 50px;"
              required
            >
          </div>

          <div class="mb-4">
            <input
              v-model="newUser.firstname"
              type="text"
              placeholder="Prénom"
              class="form-control"
              style="height: 50px;"
              required
            >
          </div>

          <div class="mb-4">
            <input
              v-model="newUser.email"
              type="email"
              placeholder="Email"
              class="form-control"
              style="height: 50px;"
              required
            >
          </div>

          <div class="mb-4">
            <input
              v-model="newUser.matricule"
              type="number"
              placeholder="Matricule"
              class="form-control"
              style="height: 50px;"
              required
            >
          </div>

          <div class="mb-4">
            <select
              v-model="newUser.roles"
              class="form-select"
              style="height: 50px;"
              required
            >
              <option value="">
                Sélectionner un rôle
              </option>
              <option value="MANAGER">
                Manager
              </option>
              <option value="RH">
                RH
              </option>
              <option value="COLLABORATEUR">
                Collaborateur
              </option>
            </select>
          </div>

          <div class="mb-4">
            <select
              v-model="newUser.statut"
              class="form-select"
              style="height: 50px;"
              required
            >
              <option value="">
                Sélectionner le statut
              </option>
              <option :value="true">
                Actif
              </option>
              <option :value="false">
                Inactif
              </option>
            </select>
          </div>

          <div class="d-flex justify-content-between">
            <button
              type="button"
              class="btn btn-danger"
              style="width: 250px; padding: 10px; font-weight: bold;"
              @click="showModal = false"
            >
              Annuler
            </button>
            <button
              type="submit"
              class="btn btn-success"
              style="width: 250px; padding: 10px; font-weight: bold;"
            >
              {{ isEditing ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import User_tables from './User_tables.vue';
import api from "../../api";
import Swal from 'sweetalert2';

export default {
  name: "UserManage",
  components: { User_tables },

  data() {
    return {
      showModal: false,
      isEditing: false,
      newUser: {
        id: null,
        name: '',
        firstname: '',
        email: '',
        matricule: 0,
        roles: '',
        statut: true
      }
    };
  },

  methods: {
    openAddModal() {
      this.isEditing = false;
      this.newUser = { id: null, name: '', firstname: '', email: '', matricule: 0, roles: '', statut: true };
      this.showModal = true;
    },

    openEditModal(user) {
      this.isEditing = true;
      this.newUser = { id: user.id, name: user.name, firstname: user.firstname, email: user.email, roles: user.roles[0], matricule: user.matricule, statut: user.statut };
      this.showModal = true;
    },

    async addUser() {
      try {
       if (this.isEditing) {
          const res = await api.put(`update_user/${this.newUser.id}`, this.newUser);
          if (res.data.message === "Utilisateur mis à jour avec succès") {
            Swal.fire({
              title: 'Modifié !',
              text: 'Utilisateur mis à jour avec succès.',
              icon: 'success',
              confirmButtonText: 'Fermer',
              confirmButtonColor: '#16738A'
            });
          }
        } else {
          const res = await api.post("add_user", this.newUser);
          if (res.data.message === "Utilisateur ajouté avec succès") {
            Swal.fire({
              title: 'Ajouté !',
              text: 'Utilisateur ajouté avec succès.',
              icon: 'success',
              confirmButtonText: 'Fermer',
              confirmButtonColor: '#16738A'
            });
          }
        }


        this.$refs.userTable.fetchUsers();
      } catch (err) {
        console.log(err);
      }

      this.showModal = false;
      this.newUser = { id: null, name: '', firstname: '', email: '', matricule: 0, roles: '', statut: true };
    }
  }
};
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #fff;
  padding: 30px;
  border-radius: 10px;
  width: 600px;
}
</style>
