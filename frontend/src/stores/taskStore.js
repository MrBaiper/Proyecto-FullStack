import { defineStore } from "pinia";
import api from "../api/axios";

export const useTaskStore = defineStore("tasks", {
  state: () => ({
    tasks: [],
    loading: false,
    error: null,
    message: null,
  }),

  actions: {
    // Obtener todas las tareas
    async fetchTasks() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get("/auth/tasks");
        this.tasks = res.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || "Error al cargar tareas";
      } finally {
        this.loading = false;
      }
    },

    // Crear nueva tarea
    async createTask(data) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post("/auth/tasks", data);
        this.message = res.data.message;
        await this.fetchTasks();
      } catch (error) {
        this.error = error.response?.data?.errors || error.response?.data?.message;
      } finally {
        this.loading = false;
      }
    },

    // Actualizar tarea
    async updateTask(id, data) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.put(`/auth/tasks/${id}`, data);
        this.message = res.data.message;
        await this.fetchTasks();
      } catch (error) {
        this.error = error.response?.data?.errors || error.response?.data?.message;
      } finally {
        this.loading = false;
      }
    },

    // Eliminar tarea
    async deleteTask(id) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.delete(`/auth/tasks/${id}`);
        this.message = res.data.message;
        await this.fetchTasks();
      } catch (error) {
        this.error = error.response?.data?.message || "Error al eliminar tarea";
      } finally {
        this.loading = false;
      }
    },
  },
});
