<template>
  <nav id="app-login">
    <nav id="nav-login">
      <div class="container p-5">
        <h3 class="mb-4" style="text-align: center;">CONNEXION</h3>

        <form @submit.prevent="login">
          <div class="mb-4">
            <input
              v-model="email"
              type="email"
              class="form-control"
              id="email"
              placeholder="Adresse e-mail"
            />
          </div>

          <div class="mb-2 input-group password-group">
            <input
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              class="form-control"
              id="mdp"
              placeholder="Mot de passe"
            />
            <span class="input-group-text eye-icon" @click="togglePassword">
              <font-awesome-icon
                :icon="showPassword ? ['fas', 'eye-slash'] : ['fas', 'eye']"
                class="animate-eye"
                style="font-size: 20px; color: #16738A;"
              />
            </span>
          </div>

          <div class="mb-4 d-flex justify-content-end">
            <a href="" id="forget">Mot de passe oublié?</a>
          </div>

          <button type="submit" class="btn btn-primary" id="login">
            Se connecter
          </button>
        </form>

        <p v-if="error" class="text-danger mt-3">{{ error }}</p>
      </div>
    </nav>
  </nav>
</template>

<script>
import api from "../api";
import { parseJwt } from "../utils/jwt";

if (sessionStorage.getItem("token")){
  const connected = parseJwt(sessionStorage.getItem("token"));
  console.log(connected)
}

export default {
  data() {
    return {
      email: "",
      password: "",
      showPassword: false,
      error: null
    };
  },
  mounted(){
    if (sessionStorage.getItem("token")){
      const connected = parseJwt(sessionStorage.getItem("token"));

      console.log(connected)
      if (connected.roles[0] === 'RH') this.$router.push('/rh');
      else if (connected.roles[0] === 'MANAGER') this.$router.push('/manager');
      else if (connected.roles[0] === 'COLLABORATEUR') this.$router.push('/collab');
      else this.error = "Rôle non défini";
    }
  },
  methods: {
    togglePassword() {
      this.showPassword = !this.showPassword;
    },
    async login() {
      try {
        const res = await api.post("login_check", {
          email: this.email,
          password: this.password
        });

        sessionStorage.setItem("token", res.data.token);
        localStorage.setItem("token", res.data.token);
        const token = sessionStorage.getItem("token");
        const user = parseJwt(token);

        if (user.roles[0] === 'RH') this.$router.push('/rh');
        else if (user.roles[0] === 'MANAGER') this.$router.push('/manager');
        else if (user.roles[0] === 'COLLABORATEUR') this.$router.push('/collab');
        else this.error = "Rôle non défini";
      } catch (err) {
        this.error = "Identifiants invalides";
      }
    }
  }
};
</script>

<style>
#app-login {
  background-color: #f1f1f1;
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

#nav-login {
  background: #ffffff;
  width: 500px;
  height: max-content;
  border-radius: 15px;
}

#login {
  background-color: #16738a;
  color: #fff;
  width: 100%;
}

#forget {
  color: #16738a;
}

</style>
