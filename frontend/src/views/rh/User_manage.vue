<template>
    <div class="d-flex w-100 justify-content-between mb-3">
        <h2> Gestion des utilisateurs</h2>
        
        <div class="d-flex gap-3">
            <a class="btn d-flex gap-2" style="padding: 10px 40px 10px 40px; background-color: #16738A; color: #fff; cursor: pointer;" @click="showModal = true" >
                <b>Ajouter un utilisateur</b>
                <font-awesome-icon :icon="['fas', 'plus-circle']" style="font-size: 20px; color: #fff;" aria-hidden="true" />
            </a>
        </div>
    </div>

    <User_tables />

    <div v-if="showModal" class="modal-backdrop">
      <div class="modal-content">
        <h3 class="mb-4 d-flex justify-content-center">Ajouter un utilisateur</h3>
        <form @submit.prevent="addUser">

          <div class="mb-4">
            <input type="text" v-model="newUser.name" placeholder="Nom" class="form-control" style="height: 50px;" required>
          </div>

          <div class="mb-4">
            <input type="email" v-model="newUser.email" placeholder="Email" class="form-control" style="height: 50px;" required>
          </div>

          <div class="mb-4">
            <select v-model="newUser.role" class="form-select" style="height: 50px;" required>
              <option value="">Sélectionner un rôle</option>
              <option value="MANAGER">Manager</option>
              <option value="RH">RH</option>
              <option value="COLLABORATEUR">Collaborateur</option>
            </select>
          </div>

          <div class="mb-4">
            <select v-model="newUser.status" class="form-select" style="height: 50px;" required>
              <option value="">Sélectionner le statut</option>
              <option :value="true">Actif</option>
              <option :value="false">Inactif</option>
            </select>
          </div>

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-danger" @click="showModal = false" style="width: 200px; padding: 10px; font-weight: bold;">Annuler</button>
            <button type="submit" class="btn btn-success" style="width: 200px; padding: 10px; font-weight: bold;">Enregistrer</button>
          </div>

        </form>

      </div>

    </div>
    
</template>

<script>
    import User_tables from './User_tables.vue';
    
    export default {
      components: {
        User_tables,
      },
        data() {
            return {
            showModal: false,
            newUser: {
                name: '',
                email: '',
                role: '',
                status: true
            }
            };
        },
        methods: {
            addUser() {
            console.log("Nouvel utilisateur :", this.newUser);

            this.showModal = false;
            this.newUser = { name: '', email: '', role: '', status: true };
            }
        }
    }
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #fff;
  padding: 30px;
  border-radius: 10px;
  width: 500px;
}
</style>
