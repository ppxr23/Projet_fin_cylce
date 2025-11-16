<template>
  <h2>Feedback - Évaluation du collaborateur</h2>

  <div class="mt-4 d-flex gap-3 col-md-5 align-items-center">
    <h5 style="width: 190px;">
      Collaborateur :
    </h5>
    <select
      v-model="selectedCollaborateur"
      class="form-select md-1"
    >
      <option
        disabled
        value=""
      >
        -- Sélectionner un collaborateur --
      </option>
      <option 
        v-for="user in sous_vigie" 
        :key="user.matricule" 
        :value="user.matricule"
      >
        {{ user.matricule }} - {{ user.name }} {{ user.firstname }} - {{ user.roles[0] }}
      </option>
    </select>
  </div>

  <h4 class="mt-5">
    Critères d'évaluation :
  </h4>

  <div
    v-for="(critere, index) in criteres"
    :key="index"
  >
    <h5 class="me-2">
      <span style="color: #16738A;">❖</span> &nbsp; {{ critere.nom }}
    </h5>
    <div class="d-flex gap-3 align-items-center mb-1">
      <input 
        v-model="critere.note" 
        type="range" 
        min="0" 
        max="10"
        class="col-md-4"
      >
      <p class="mb-0 ms-3">
        Note : {{ critere.note }}
      </p>
    </div>
  </div>

  <h4 class="mt-5">
    Commentaires :
  </h4>
  <div class="col-md-5 d-flex flex-column gap-4 mt-3">
    <input
      v-model="commentaire"
      type="text"
      class="form-control"
      style="height: 100px;"
    >
    <div class="d-flex w-100 justify-content-between">
      <button 
        type="button" 
        class="btn btn-secondary btn-lg" 
        style="width: 45%;" 
        @click="resetForm"
      >
        Annuler
      </button>
      
      <button 
        type="button" 
        class="btn btn-primary btn-lg" 
        style="width: 45%;" 
        @click="sendForm"
      >
        Envoyer Feedback
      </button>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import api from '../../api'
import { parseJwt } from '../../utils/jwt'

export default {
  name: "FeedbackRh",
  data() {
    return {
      sous_vigie: [],
      selectedCollaborateur: '',
      commentaire: '',
      criteres: [
        { nom: 'Ponctualité :', note: 5 },
        { nom: 'Esprit d’équipe :', note: 5 },
        { nom: 'Qualité du travail :', note: 5 },
        { nom: 'Communication :', note: 5 },
        { nom: 'Initiative :', note: 5 }
      ]
    }
  },

  async mounted() {
    try {
      let connecte = null

      if (!sessionStorage.getItem('token')) {
        this.$router.push('/')
        return
      } else {
        connecte = parseJwt(sessionStorage.getItem('token'))
      }

      const res = await api.post('list_user_rh', {
        matricule: connecte.matricule,
        roles: 'RH',
        all: false
      });

      this.sous_vigie = res.data
    } catch (error) {
      console.error(error)
    }
  },

  methods: {
    async sendForm() {
      if (!this.selectedCollaborateur) {
        Swal.fire({
          title: 'Attention',
          text: 'Veuillez sélectionner un collaborateur.',
          icon: 'warning',
          confirmButtonColor: '#16738A'
        })
        return
      }

      try {
        let connected = null

        if (!sessionStorage.getItem('token')) {
          this.$router.push('/')
          return
        } else {
          connected = parseJwt(sessionStorage.getItem('token'))
        }

        await api.post('add_feedback', {
          matricule_concerned: this.selectedCollaborateur,
          matricule_insert: connected.matricule,
          critere_1: this.criteres[0].note,
          critere_2: this.criteres[1].note,
          critere_3: this.criteres[2].note,
          critere_4: this.criteres[3].note,
          critere_5: this.criteres[4].note,
          commentary: this.commentaire,
          type_feedback: 1
        })

        Swal.fire({
          title: 'Feedback envoyé !',
          text: 'Merci pour votre évaluation.',
          icon: 'success',
          confirmButtonColor: '#16738A'
        })

        this.resetForm()
      } catch (error) {
        console.error('Erreur lors de l’ajout du feedback :', error)
      }
    },

    resetForm() {
      this.criteres = this.criteres.map(c => ({ ...c, note: 5 }))
      this.commentaire = ''
      this.selectedCollaborateur = ''
    }
  }
}
</script>
