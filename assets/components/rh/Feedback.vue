<template>
  <h2>Feedback - Évaluation du collaborateur</h2>

  <div class="mt-4 d-flex gap-3 col-md-5 align-items-center">
    <h5>Collaborateur: </h5>
    <select class="form-select md-1">
      <option value="1">1 - Jean Dupont - Téléopérateur.trice</option>
      <option value="2">2 - Marie Curie - Manager</option>
      <option value="3">3 - Paul Martin - Téléopérateur.trice</option>
    </select>
  </div>

  <h4 class="mt-5">Critères d'évaluation :</h4>

  <div class="mt-3 ms-3" v-for="(critere, index) in criteres" :key="index">
    <h5 class="me-2" style="width: 170px;">{{ critere.nom }}</h5>
    <div class="d-flex gap-3 align-items-center mb-4">
      <Vue3StarRatings
        v-model="critere.note"
        class="stars"
        :starSize="26"
        starColor="#ff9800"
        inactiveColor="#000000"
        :numberOfStars="5"
        :disableClick="false"
      />
      <p class="mb-0 ms-2">Note : {{ critere.note }}</p>
    </div>
  </div>

  <h4 class="mt-5">Commentaires :</h4>
  <div class="col-md-5 d-flex flex-column gap-4 mt-3">
    <input type="text" class="form-control"style="height: 100px;">
    <div class="d-flex w-100 justify-content-between">
        <button type="button" class="btn btn-secondary btn-lg" style="width: 45%;">Annuler</button>
        <button type="button" class="btn btn-primary btn-lg" style="width: 45%;">Envoyer Feedback</button>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import Vue3StarRatings from 'vue3-star-ratings'

const criteres = reactive([
  { nom: 'Ponctualité :', note: 5 },
  { nom: 'Esprit d’équipe :', note: 5 },
  { nom: 'Qualité du travail :', note: 5 },
  { nom: 'Communication :', note: 5 },
  { nom: 'Initiative :', note: 5 }
])
</script>

<style scoped>
/* cibler les spans générés par vue3-star-ratings pour ajouter un espace */
::v-deep(.stars span) {
  margin-right: 6px; /* espace entre les étoiles */
}

::v-deep(.stars span:last-child) {
  margin-right: 0;
}
</style>
