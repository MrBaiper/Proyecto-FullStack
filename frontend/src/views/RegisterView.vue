<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">Crear Cuenta</h2>

      <form @submit.prevent="handleRegister" class="login-form">
        <label class="field-label" for="name">Nombre completo</label>
        <input
          id="name"
          v-model="name"
          type="text"
          placeholder="Tu nombre completo"
          required
        />

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

        <button type="submit" class="login-button">Registrarse</button>
      </form>

      <p v-if="auth.error" class="error">{{ auth.error }}</p>

      <p class="login-footer">
        ¿Ya tienes cuenta?
        <router-link to="/login" class="register-link">Inicia sesión</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/authStore";
import "./RegisterView.css";

const auth = useAuthStore();
const router = useRouter();

const name = ref("");
const email = ref("");
const password = ref("");

const handleRegister = async () => {
  await auth.register({
    name: name.value,
    email: email.value,
    password: password.value,
  });
  if (!auth.error) router.push("/dashboard");
};
</script>
