import { defineStore } from "pinia";
import api from "../api/axios";
import router from "../router";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: localStorage.getItem("token") || null,
    error: null,
    loading: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    //REGISTRO
    async register(data) {
      this.error = null;
      this.loading = true;

      try {
        const res = await api.post("/auth/register", data);

        // Laravel-JWT típicamente devuelve: { access_token, token_type, expires_in }
        this.token = res.data.access_token || res.data.token;
        localStorage.setItem("token", this.token);

        // Opcional: cargar perfil después de registro
        await this.fetchUser();
        console.log("Usuario registrado correctamente");
        return true;
      } catch (error) {
        this.error = error.response?.data?.message || "Error al registrar usuario";
        console.error("Error en register:", this.error);
        return false;
      } finally {
        this.loading = false;
      }
    },

    //LOGIN
    async login(credentials) {
      this.error = null;
      this.loading = true;

      try {
        const res = await api.post("/auth/login", credentials);

        this.token = res.data.access_token || res.data.token;
        localStorage.setItem("token", this.token);

        await this.fetchUser();
        console.log("Login exitoso");

        //redireccionar al dashboard
        router.push("/dashboard");

        return true;
      } catch (error) {
        this.error = error.response?.data?.message || "Error al iniciar sesión";
        console.error("Error en login:", this.error);
        return false;
      } finally {
        this.loading = false;
      }
    },

    //OBTENER PERFIL AUTENTICADO
    async fetchUser() {
      if (!this.token) return;

      try {
        const res = await api.get("/auth/profile");
        this.user = res.data.user || res.data;
        console.log("Perfil obtenido:", this.user);
      } catch (error) {
        console.error("Error al obtener usuario:", error.response?.data || error.message);
        this.logout(); // si hay error, limpiamos sesión
      }
    },

    //REFRESCAR TOKEN
    async refreshToken() {
      try {
        const res = await api.post("/auth/refresh");
        this.token = res.data.access_token || res.data.token;
        localStorage.setItem("token", this.token);
        console.log("Token renovado correctamente");
        return true;
      } catch (error) {
        console.warn("Error al refrescar token:", error.response?.data || error.message);
        this.logout();
        return false;
      }
    },

    //LOGOUT
    async logout() {
      try {
        await api.get("/auth/logout");
      } catch (error) {
        console.warn("Error al cerrar sesión (ignorable si el token ya expiró)", error.response?.data || error.message);
      }

      this.user = null;
      this.token = null;
      localStorage.removeItem("token");

      console.log("Sesión cerrada correctamente");
    },

    //RUTA DE ACCESO NO AUTORIZADO
    async unauthorized() {
      try {
        const res = await api.get("/");
        console.log("Acceso restringido:", res.data);
        return res.data;
      } catch (error) {
        console.error("Error al acceder a ruta no autorizada:", error);
      }
    },
  },
});
