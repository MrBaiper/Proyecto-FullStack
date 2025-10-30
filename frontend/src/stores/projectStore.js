import { defineStore } from "pinia";
import api from "../api/axios";

export const useProjectStore = defineStore("projects", {
  state: () => ({
    projects: [],
    project: null,
    loading: false,
    error: null,
    message: null,
  }),

  actions: {
    // Obtener todos los proyectos
    async fetchProjects() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get("/auth/projects");
        this.projects = res.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || "Error al cargar proyectos";
      } finally {
        this.loading = false;
      }
    },

    // Crear nuevo proyecto
    async createProject(data) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post("/auth/projects", data);
        this.message = res.data.message;
        await this.fetchProjects();
      } catch (error) {
        this.error = error.response?.data?.errors || error.response?.data?.message;
      } finally {
        this.loading = false;
      }
    },

    // Actualizar proyecto existente
    async updateProject(id, data) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.put(`/auth/projects/${id}`, data);
        this.message = res.data.message;
        await this.fetchProjects();
      } catch (error) {
        this.error = error.response?.data?.errors || error.response?.data?.message;
      } finally {
        this.loading = false;
      }
    },

    // Eliminar proyecto
    async deleteProject(id) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.delete(`/auth/projects/${id}`);
        this.message = res.data.message;
        await this.fetchProjects();
      } catch (error) {
        this.error = error.response?.data?.message || "Error al eliminar proyecto";
      } finally {
        this.loading = false;
      }
    },
  },
});
