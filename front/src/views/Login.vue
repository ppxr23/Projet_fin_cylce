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
          <div class="mb-2">
            <input
              v-model="password"
              type="password"
              class="form-control"
              id="mdp"
              placeholder="Mot de passe"
            />
          </div>
          <div class="mb-4 d-flex justify-content-end">
            <a href="" id="forget">Mot de passe oublié?</a>
          </div>

          <button type="submit" class="btn btn-primary" id="login">
            Se connecter
          </button>
        </form>

        <!-- message d'erreur -->
        <p v-if="error" class="text-danger mt-3">{{ error }}</p>
      </div>
    </nav>
  </nav>
</template>

<script>
import api from "../api";
import { parseJwt } from "../utils/jwt";

export default {
  data() {
    return {
      email: "",
      password: "",
      error: null
    };
  },
  methods: {
    async login() {
      try {
        const res = await api.post("login_check", {
          email: this.email,
          password: this.password
        });

        // stocker le token
        localStorage.setItem("token", res.data.token);
        const token = localStorage.getItem("token");
        const user = parseJwt(token);
        console.log(user.roles);

        if (user.roles == 'RH'){
            this.$router.push("/rh");
        }
        else if (user.roles == 'COLLABORATEUR'){
            this.$router.push("/collab");
        }
        else if (user.roles == 'MANAGER'){
            this.$router.push("/manager");
        }
        else {
            this.error = "Rôle non défini"
        }
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
  background-color: #16738A;
  color: #fff;
  width: 100%;
}

#forget {
  color: #16738A;
}
</style>
