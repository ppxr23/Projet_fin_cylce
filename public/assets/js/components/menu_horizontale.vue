<div id="menu-horizontal">
  <nav>
    <ul style="display: flex; gap: 20px; list-style: none;">
      <li v-for="item in menu" :key="item.name">
        <a :href="item.link">{{ item.name }}</a>
      </li>
    </ul>
  </nav>
</div>

<script>
const { createApp } = Vue;

createApp({
  data() {
    return {
      menu: [
        { name: 'Accueil', link: '/' },
        { name: 'À propos', link: '/about' },
        { name: 'Contact', link: '/contact' }
      ]
    };
  }
}).mount('#menu-horizontal');
</script>
