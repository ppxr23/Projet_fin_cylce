<template>
  <RhVue v-if="page === 'rh'" />
  <ManagerVue v-else-if="page === 'manager'" />
  <CollabVue v-else-if="page === 'collab'" />
  <p v-else>Page non trouvée (page={{ page }})</p>
</template>

<script>
import RhVue from './RhVue.vue';
import ManagerVue from './ManagerVue.vue';
import CollabVue from './CollabVue.vue';

export default {
  name: 'App',
  components: { RhVue, ManagerVue, CollabVue },
  data() {
    return {
      page: document.getElementById('app')?.dataset.page || null
    };
  },
  mounted() {
    console.log("Page détectée :", this.page);
  }
}
</script>
  