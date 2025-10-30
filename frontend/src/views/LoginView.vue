<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">Iniciar Sesión</h2>

      <form @submit.prevent="handleLogin" class="login-form">
        <label class="field-label" for="email">Correo electrónico</label>
        <input
          id="email"
          v-model="email"
          type="email"
          placeholder="Tu correo"
          required
        />

        <label class="field-label" for="password">Contraseña</label>
        <input
          id="password"
          v-model="password"
          type="password"
          placeholder="••••••••"
          required
        />

        <button type="submit" class="login-button">Ingresar</button>
      </form>

      <p v-if="auth.error" class="error">{{ auth.error }}</p>

      <p class="login-footer">
        ¿No tienes cuenta?
        <router-link to="/register" class="register-link">Regístrate</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/authStore";
import "./LoginView.css";

const auth = useAuthStore();
const router = useRouter();
const email = ref("");
const password = ref("");

const handleLogin = async () => {
  await auth.login({ email: email.value, password: password.value });
  if (!auth.error) router.push("/dashboard");
};
</script>
