<template>
    <div class="d-flex w-100 justify-content-between">
        <h2> Gestion des utilisateurs</h2>
        <div class="d-flex gap-3">
            <button class="btn btn-primary btn-lg" style="padding: 10px 40px 10px 40px;"><b>Ajouter un utilisateur</b></button>
            <div style="background-color: #6C757D1e; padding: 10px; height: max-content; width: max-content; border-radius: 50%;">
                <font-awesome-icon :icon="['fas', 'user']" style="font-size: 30px; color: #6C757D;" aria-hidden="true" />
            </div>
        </div>
    </div>

    <div class="mt-3 p-3" style="width: 100%; height: 100%; border-radius: 10px; box-shadow: #6C757D 1px 2px 2px 2px;">
        <user_tables />
    </div>
    
</template>

<script>
    import user_tables from './user_tables.vue';
    export default {
      components: {
        user_tables,
      }
    }
</script>