<template>
    <div class="d-flex w-100 justify-content-between mb-3">
        <h2> Gestion des utilisateurs</h2>
        
        <div class="d-flex gap-3">
            <a href="" class="btn d-flex gap-2" style="padding: 10px 40px 10px 40px; background-color: #16738A; color: #fff;">
                <b>Ajouter un utilisateur</b>
                <font-awesome-icon :icon="['fas', 'plus-circle']" style="font-size: 20px; color: #fff;" aria-hidden="true" />
            </a>
        </div>
    </div>

    <User_tables />
    
</template>

<script>
    import User_tables from './User_tables.vue';
    export default {
      components: {
        User_tables,
      }
    }
</script>