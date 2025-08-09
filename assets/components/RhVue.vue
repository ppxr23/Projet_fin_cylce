<template>
  <nav id="menu-verticale" class="bg-light p-3">
    <div id="menu" class="nav flex-column">
      <a class="nav-link active" href="#">Tableau de bord</a>
      <a class="nav-link" href="#">Feedbacks</a>
      <a class="nav-link" href="#">Rapports</a>
      <a class="nav-link" href="#">Gestion d'utilisateurs</a>
    </div>
  </nav>
  <nav id="menu-content">
    <dashboard />
  </nav>
</template>

<script>
    import Dashboard from './rh/Dashboard.vue';
    export default {
      components: {
        Dashboard
      }
    }
</script>
