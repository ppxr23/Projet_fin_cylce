<template>
    <h2>Tableau de bord Collaborateur</h2>

    <div class="d-flex gap-3">
        <div class="p-3 m-2 d-flex gap-3 element-dash" style="border: 2px solid #00000028; border-radius: 10px; width: 25%;" >
            <div style="background-color: #16738a1e; padding: 10px; height: max-content; width: max-content; border-radius: 10%;">
                <font-awesome-icon :icon="['fas', 'user']" style="font-size: 40px; color: #16738A" aria-hidden="true" />
            </div>
            <div>
                <h2 class="m-0">230</h2>
                <p class="m-0"><b>Membre de l'équipe</b></p>
            </div>
        </div>

        <div class="p-3 m-2 d-flex gap-3 element-dash" style="border: 2px solid #00000028; border-radius: 10px; width: 25%;">
            <div style="background-color: #ff40401e; padding: 10px; height: max-content; width: max-content; border-radius: 10%;">
                <font-awesome-icon :icon="['fas', 'clock']" style="font-size: 40px; color: #ff4040;" aria-hidden="true" />
            </div>
            <div>
                <h2 class="m-0">5</h2>
                <p class="m-0"><b>En retard aujourd'hui</b></p>
            </div>
        </div>

        <div class="p-3 m-2 d-flex gap-3 element-dash" style="border: 2px solid #00000028; border-radius: 10px; width: 30%;">
            <div style="background-color: #FFA5001e; padding: 10px; height: max-content; width: max-content; border-radius: 10%;">
                <font-awesome-icon :icon="['fas', 'gavel']" style="font-size: 40px; color: #FFA500;" aria-hidden="true" />
            </div>
            <div>
                <h2 class="m-0">0</h2>
                <p class="m-0"><b>Sanction enregistrée aujourd'hui</b></p>
            </div>
        </div>

        <div class="p-3 m-2 d-flex gap-3 element-dash" style="border: 2px solid #00000028; border-radius: 10px; width: 20%;">
            <div style="background-color: #6C757D1e; padding: 10px; height: max-content; width: max-content; border-radius: 10%;">
                <font-awesome-icon :icon="['fas', 'user-slash']" style="font-size: 40px; color: #6C757D;" aria-hidden="true" />
            </div>
            <div>
                <h2 class="m-0">0</h2>
                <p class="m-0"><b>Absence</b></p>
            </div>
        </div>
    </div>

    <div class="d-flex mt-3" style="gap: 5%;">
        <div style="width: 45%; min-width: 400px;" class="mt-3 d-flex flex-column gap-5">
            <div class="chart-container">
                <h3 class="mb-2"><center>Performance de l'équipe <font-awesome-icon :icon="['fas', 'line-chart']" style="font-size: 30px; color: #0096C7;" aria-hidden="true" /></center></h3>
                <ChartBars />
            </div>
            <div class="chart-container">
                <h3 class="mb-2"><center>Récap du mois <font-awesome-icon :icon="['fas', 'chart-pie']" style="font-size: 30px; color: #16738A;" aria-hidden="true" /></center></h3>
                <center><ChartCircu style="width: 400px; height: 400px;"/></center>
            </div>
        </div>

        <div class="mt-3 d-flex flex-column gap-3" style="width: 45%;">
            <div>
                <h3 class="mb-2"><center>Alertes anomalie <font-awesome-icon :icon="['fas', 'exclamation-triangle']" style="font-size: 30px; color: red;" aria-hidden="true" /></center></h3>
                <div class="m-2 d-flex flex-column p-2" style="border: 2px solid #00000028; border-radius: 10px; width: 100%; height: max-content; overflow-y: scroll;">
                    <div class="d-flex gap-2 align-items-center w-100 m-0  anomalie" style="border-bottom: 2px solid #00000028;">
                        <span class="danger"><center>Elevés</center></span>
                        <div>
                            <h5 class="m-0">Jean Dupont</h5>
                            <p class="m-0">Absences fréquente</p>
                        </div>
                    </div>
    
                    <div class="d-flex gap-2 align-items-center w-100 m-0 anomalie" style="border-bottom: 2px solid #00000028;">
                        <span class="warning"><center>Moyennes</center></span>
                        <div>
                            <h5 class="m-0">Jean Dupont</h5>
                            <p class="m-0">Absences fréquente</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="mb-2"><center> Feedback <font-awesome-icon :icon="['fas', 'comment-dots']" style="font-size: 30px; color: #6f42c1;" aria-hidden="true" /></center></h3>
                <div class="m-2 d-flex flex-column p-2" style="border: 2px solid #00000028; border-radius: 10px; width: 100%; height: max-content; overflow-y: scroll;">
                    <div class="d-flex gap-2 align-items-center w-100 m-0  anomalie" style="border-bottom: 2px solid #00000028;">
                        <span class="feedback_name"><center>Jean Dupont</center></span>
                        <div>
                            <h5 class="m-0"><u>Note:</u> 5</h5>
                            <p class="m-0"><u>Commentaire:</u> Soit sérieux</p>
                        </div>
                    </div>
    
                    <div class="d-flex gap-2 align-items-center w-100 m-0 anomalie" style="border-bottom: 2px solid #00000028;">
                        <span class="feedback_name"><center>Jean Dupont</center></span>
                        <div>
                            <h5 class="m-0"><u>Note:</u> 5</h5>
                            <p class="m-0"><u>Commentaire:</u> Soit sérieux</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ChartBars from '../ChartBars.vue'
    import ChartCircu from '../ChartCircu.vue';

    export default {
        components: { ChartBars, ChartCircu }
    }
</script>

