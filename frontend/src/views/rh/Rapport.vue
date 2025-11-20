<template>
  <h2 class="mb-3">
    Génération de rapport
  </h2>

  <div class="mt-4 d-flex flex-column col-md-5">
    <h5>Nom du rapport </h5>
    <input
      id=""
      v-model="nomRapport"
      type="text"
      name=""
      class="form-control form-control-lg"
    >
  </div>

  <div class="mt-4 d-flex flex-column col-md-5">
    <h5>Type de rapport </h5>
    <select
      v-model="typeRapport"
      class="form-select form-select-lg md-1"
    >
      <option value="1">
        Résumé
      </option>
      <option value="2">
        KPI
      </option>
    </select>
  </div>

  <h5 class="mt-4">
    Période
  </h5>
  <div class="mt-2 d-flex col-md-5 gap-5">
    <div class="form-check">
      <input
        id="radio1"
        v-model="periode"
        class="form-check-input form-check-input-lg"
        type="radio"
        name="exampleRadios"
        value="1"
        checked
      >
      <label
        class="form-check-label form-check-label-lg"
        for="radio1"
      >
        Journalière
      </label>
    </div>
        
    <div class="form-check">
      <input
        id="radio2"
        v-model="periode"
        class="form-check-input form-check-input-lg"
        type="radio"
        name="exampleRadios"
        value="2"
      >
      <label
        class="form-check-label form-check-label-lg"
        for="radio2"
      >
        Hebdomadaire
      </label>
    </div>
        
    <div class="form-check">
      <input
        id="radio3"
        v-model="periode"
        class="form-check-input form-check-input-lg"
        type="radio"
        name="exampleRadios"
        value="3"
      >
      <label
        class="form-check-label form-check-label-lg"
        for="radio3"
      >
        Mensuelle
      </label>
    </div>

    <div class="form-check">
      <input
        id="radio4"
        v-model="periode"
        class="form-check-input form-check-input-lg"
        type="radio"
        name="exampleRadios"
        value="4"
      >
      <label
        class="form-check-label form-check-label-lg"
        for="radio4"
      >
        Annuelle
      </label>
    </div>
  </div>

  <h4 class="mt-5">
    Description :
  </h4>
  <div class="col-md-5 d-flex flex-column gap-4 mt-3">
    <input
      v-model="description"
      type="text"
      class="form-control"
      style="height: 100px;"
    >
    <div class="d-flex w-100 justify-content-between">
      <button
        type="button"
        class="btn btn-secondary btn-lg"
        style="width: 45%;"
      >
        Annuler
      </button>
      <button
        type="button"
        class="btn btn-primary btn-lg"
        style="width: 45%;"
        @click="download"
      >
        Créer
      </button>
    </div>
  </div>
</template>
<script>
import api from '../../api'
import { parseJwt } from '../../utils/jwt'

export default {
    name: "RapportRh",
    data() {
        return {
            nomRapport: '',
            typeRapport: '1',
            periode: '1',
            description: ''
        }
    },

    methods: {
        async download() {
            try {
                let connecte = null;

                if (!sessionStorage.getItem('token')) {
                    this.$router.push('/')
                    return
                } else {
                    connecte = parseJwt(sessionStorage.getItem('token'))
                }

                const params = {
                    nomRapport: this.nomRapport,
                    typeRapport: this.typeRapport,
                    periode: this.periode,
                    description: this.description
                };

                const res = await api.post('down', {
                  matricule: connecte.matricule,
                  roles: 'RH',
                  params: params
                },{
                  responseType: 'blob',
                });

                const name_file = this.nomRapport + ".xlsx";
                const url = window.URL.createObjectURL(new Blob([res.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', name_file);
                document.body.appendChild(link);
                link.click();
                link.remove();

            } catch (err) {
                console.log(err);
            }
        }
    }
}
</script>
