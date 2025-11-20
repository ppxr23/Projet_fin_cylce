<template>
  <h2>Tableau de bord RH</h2>

  <div class="d-flex gap-3">
    <div
      class="p-3 m-2 d-flex gap-3 element-dash"
      style="border: 2px solid #00000028; border-radius: 10px; width: 25%;"
    >
      <div style="background-color: #16738a1e; padding: 10px; height: max-content; width: max-content; border-radius: 10%;">
        <font-awesome-icon
          :icon="['fas', 'user']"
          style="font-size: 40px; color: #16738A"
          aria-hidden="true"
        />
      </div>
      <div>
        <h2 class="m-0">
          {{ user_actif }}
        </h2>
        <p class="m-0">
          <b>Employés actifs</b>
        </p>
      </div>
    </div>

    <div
      class="p-3 m-2 d-flex gap-3 element-dash"
      style="border: 2px solid #00000028; border-radius: 10px; width: 25%;"
    >
      <div style="background-color: #ff40401e; padding: 10px; height: max-content; width: max-content; border-radius: 10%;">
        <font-awesome-icon
          :icon="['fas', 'clock']"
          style="font-size: 40px; color: #ff4040;"
          aria-hidden="true"
        />
      </div>
      <div>
        <h2 class="m-0">
          {{ retard_rh }}
        </h2>
        <p class="m-0">
          <b>En retard aujourd'hui</b>
        </p>
      </div>
    </div>

    <div
      class="p-3 m-2 d-flex gap-3 element-dash"
      style="border: 2px solid #00000028; border-radius: 10px; width: 30%;"
    >
      <div style="background-color: #FFA5001e; padding: 10px; height: max-content; width: max-content; border-radius: 10%;">
        <font-awesome-icon
          :icon="['fas', 'gavel']"
          style="font-size: 40px; color: #FFA500;"
          aria-hidden="true"
        />
      </div>
      <div>
        <h2 class="m-0">
          {{ sanction_rh }}
        </h2>
        <p class="m-0">
          <b>Sanction enregistrée aujourd'hui</b>
        </p>
      </div>
    </div>

    <div
      class="p-3 m-2 d-flex gap-3 element-dash"
      style="border: 2px solid #00000028; border-radius: 10px; width: 20%;"
    >
      <div style="background-color: #6C757D1e; padding: 10px; height: max-content; width: max-content; border-radius: 10%;">
        <font-awesome-icon
          :icon="['fas', 'user-slash']"
          style="font-size: 40px; color: #6C757D;"
          aria-hidden="true"
        />
      </div>
      <div>
        <h2 class="m-0">
          {{ abs_rh }}
        </h2>
        <p class="m-0">
          <b>Absence aujourd'hui</b>
        </p>
      </div>
    </div>
  </div>

  <div
    class="d-flex mt-3"
    style="gap: 5%;"
  >
    <div
      style="width: 45%; min-width: 400px;"
      class="mt-3 d-flex flex-column gap-5"
    >
      <div class="chart-container">
        <h3 class="mb-2 centered">
          Performance par équipe <font-awesome-icon
            :icon="['fas', 'line-chart']"
            style="font-size: 30px; color: #0096C7;"
            aria-hidden="true"
          />
        </h3>
        <ChartBars />
      </div>
      <div class="chart-container">
        <h3 class="mb-2 centered">
          Récap du mois <font-awesome-icon
            :icon="['fas', 'chart-pie']"
            style="font-size: 30px; color: #16738A;"
            aria-hidden="true"
          />
        </h3>
        <div class="centered">
          <ChartCircu style="width: 400px; height: 400px;" />
        </div>
      </div>
    </div>

    <div
      class="mt-3 d-flex flex-column gap-3"
      style="width: 45%;"
    >
      <div>
        <h3 class="mb-2 centered">
          Alertes anomalie <font-awesome-icon
            :icon="['fas', 'exclamation-triangle']"
            style="font-size: 30px; color: red;"
            aria-hidden="true"
          />
        </h3>
        
        <div
          class="m-2 d-flex flex-column p-2"
          style="border: 2px solid #00000028; border-radius: 10px; width: 100%; height: 400px; overflow-y: scroll;"
          
          >
          <div
            class="d-flex gap-2 align-items-center w-100 m-0  anomalie"
            style="border-bottom: 2px solid #00000028;"
            v-for="(anomali, index) in anomalie_rh.filter(a => a.matricule !== connected.matricule)"
            :key="index"
          >
            <span v-if="anomali.degree == 2" class="danger centered" style="width: 100px;">Elevés</span>
            <span v-if="anomali.degree == 1" class="warning centered" style="width: 100px;">Moyennes</span>
            <div>
              <h5 class="m-0">
                {{ anomali.name }} {{ anomali.firstname }}
              </h5>
              <p class="m-0" v-if="anomali.type_anomalie == 3">
                Absences fréquente
              </p>
              <p class="m-0" v-if="anomali.type_anomalie == 2">
                Retards fréquente
              </p>
              <p class="m-0" v-if="anomali.type_anomalie == 1">
                Sanctions fréquente
              </p>
            </div>
          </div>

        </div>

      </div>

      <div class="mt-4">
        <h3 class="mb-2 centered">
          Feedback <font-awesome-icon
            :icon="['fas', 'comment-dots']"
            style="font-size: 30px; color: #6f42c1;"
            aria-hidden="true"
          />
        </h3>
        <div
          class="m-2 d-flex flex-column p-2"
          style="border: 2px solid #00000028; border-radius: 10px; width: 100%; height: 400px; overflow-y: scroll;"
        >
          <div
            class="d-flex gap-2 align-items-center w-100 m-0  anomalie"
            style="border-bottom: 2px solid #00000028;"
            v-for="(feedback, index) in feedback_rh"
            :key="index"
          >
            <span v-if="feedback.note >= 8" class="success centered" style="width: 200px; height: 60px;">{{ feedback.name }} {{ feedback.firstname }}</span>
            <span v-if="feedback.note >= 5 && feedback.note < 8" class="warning centered" style="width: 200px; height: 60px;">{{ feedback.name }} {{ feedback.firstname }}</span>
            <span v-if="feedback.note < 5" class="danger centered" style="width: 200px; height: 60px;">{{ feedback.name }} {{ feedback.firstname }}</span>
            
            <div>
              <h5 class="m-0">
                <u>Note:</u> {{ feedback.note }}
              </h5>
              <p class="m-0">
                <u>Commentaire:</u> {{ feedback.commentaire }}
              </p>
            </div>
          </div>
    
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import ChartBars from './ChartBars.vue'
    import ChartCircu from './ChartCircu.vue';
    import api from "../../api";
    import { parseJwt } from '../../utils/jwt';

    export default {
        name: "DashboardRh",
        components: { ChartBars, ChartCircu },

        data() {
            return {
                user_actif: 0,
                abs_rh: 0,
                sanction_rh: 0,
                retard_rh: 0,
                anomalie_rh: [],
                feedback_rh: [],
                connected: 0,
            };
        },

        async mounted() {
            try {
                if (!sessionStorage.getItem('token')) {
                this.$router.push('/')
                return
                } else {
                  this.connected = parseJwt(sessionStorage.getItem('token'))
                }

                const [user, abs, sanction, retard, anomalie, feedback] = await Promise.all([
                    api.post('count_user_rh',{
                        matricule: this.connected.matricule,
                        roles: 'RH',
                        all: false
                    }),
                    api.post('count_absence_rh',{
                        matricule: this.connected.matricule,
                        roles: 'RH',
                        all: false
                    }),
                    api.post('count_sanction_rh',{
                        matricule: this.connected.matricule,
                        roles: 'RH',
                        all: false
                    }),
                    api.post('count_retard_rh',{
                        matricule: this.connected.matricule,
                        roles: 'RH',
                        all: false
                    }),
                    api.post('all_anomalie',{
                        matricule: this.connected.matricule,
                        roles: 'RH'
                    }),
                    api.post('all_feedback',{
                        matricule: this.connected.matricule,
                        roles: 'RH'
                    })
                ]);

                this.user_actif = user.data
                this.abs_rh = abs.data;
                this.sanction_rh = sanction.data;
                this.retard_rh = retard.data;
                this.anomalie_rh = anomalie.data;
                this.feedback_rh = feedback.data;
            } catch (error) {
                console.error(error);
            }
        }
    }

</script>
