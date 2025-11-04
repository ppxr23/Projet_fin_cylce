<template>
  <h2>Feedback - Évaluation du collaborateur</h2>

  <div class="mt-4 d-flex gap-3 col-md-5 align-items-center">
    <h5>Collaborateur: </h5>
    <select class="form-select md-1" v-model="selectedCollaborateur">
      <option disabled value="">-- Sélectionner un collaborateur --</option>
      <option value="1">1 - Jean Dupont - Téléopérateur.trice</option>
      <option value="2">2 - Marie Curie - Manager</option>
      <option value="3">3 - Paul Martin - Téléopérateur.trice</option>
    </select>
  </div>

  <h4 class="mt-5">Critères d'évaluation :</h4>

  <div v-for="(critere, index) in criteres" :key="index">
    <h5 class="me-2"><span style="color: #16738A;">❖</span> &nbsp; {{ critere.nom }}</h5>
    <div class="d-flex gap-3 align-items-center mb-1">
      <input 
        type="range" 
        min="0" 
        max="10" 
        v-model="critere.note"
        class="col-md-4"
      >
      <p class="mb-0 ms-3">Note : {{ critere.note }}</p>
    </div>
  </div>

  <h4 class="mt-5">Commentaires :</h4>
  <div class="col-md-5 d-flex flex-column gap-4 mt-3">
    <input type="text" v-model="commentaire" class="form-control" style="height: 100px;">
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

<script setup>
import { reactive, ref } from 'vue';
import Swal from 'sweetalert2';
import api from "../../api";
import { parseJwt } from "../../utils/jwt";

const initialCriteres = [
  { nom: 'Ponctualité :', note: 5 },
  { nom: 'Esprit d’équipe :', note: 5 },
  { nom: 'Qualité du travail :', note: 5 },
  { nom: 'Communication :', note: 5 },
  { nom: 'Initiative :', note: 5 }
]

const criteres = reactive(JSON.parse(JSON.stringify(initialCriteres)))
const commentaire = ref('')
const selectedCollaborateur = ref('')

async function sendForm() {
  if (!selectedCollaborateur.value) {
    Swal.fire({
      title: 'Attention',
      text: 'Veuillez sélectionner un collaborateur.',
      icon: 'warning',
      confirmButtonColor: '#16738A'
    })
    return
  }

  try {
    let connected = null;

    if (!sessionStorage.getItem("token")){
      this.$router.push('/')
    }
    else {
      connected = parseJwt(sessionStorage.getItem("token"));
    }

    const response = await api.post('add_feedback', {
      matricule_concerned: selectedCollaborateur.value,
      matricule_insert: connected.matricule,
      critere_1: criteres[0].note,
      critere_2: criteres[1].note,
      critere_3: criteres[2].note,
      critere_4: criteres[3].note,
      critere_5: criteres[4].note,
      commentary: commentaire.value,
      type_feedback: 1
    })

    Swal.fire({
      title: 'Feedback envoyé !',
      text: 'Merci pour votre évaluation.',
      icon: 'success',
      confirmButtonColor: '#16738A'
    })

    resetForm()
  } catch (error) {
    console.error('Erreur lors de l’ajout du feedback :', error)
  }
}

function resetForm() {
  initialCriteres.forEach((c, i) => criteres[i].note = c.note)
  commentaire.value = ''
  selectedCollaborateur.value = ''
}
</script>
