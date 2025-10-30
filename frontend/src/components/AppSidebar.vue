<template>
  <aside class="sidebar">
    <div class="sidebar-header">
      <h2 class="logo">Dashboard</h2>
    </div>

    <nav class="nav">
      <router-link
        to="/dashboard"
        class="nav-item"
        :class="{ active: isActive('/dashboard') }"
      >
        <i class="fa-solid fa-house"></i>
        <span>Inicio</span>
      </router-link>

      <router-link
        to="/projects"
        class="nav-item"
        :class="{ active: isActive('/projects') }"
      >
        <i class="fa-solid fa-folder"></i>
        <span>Proyectos</span>
      </router-link>

      <router-link
        to="/tasks"
        class="nav-item"
        :class="{ active: isActive('/tasks') }"
      >
        <i class="fa-solid fa-folder"></i>
        <span>Tareas</span>
      </router-link>
    </nav>

    <button @click="logout" class="logout-btn">
      <i class="fa-regular fa-share-from-square"></i>
      <span>Salir</span>
    </button>
  </aside>
</template>


<script setup>
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/authStore";
import "./AppSidebar.css";

const router = useRouter();
const auth = useAuthStore();

const logout = async () => {
  await auth.logout();
  router.push("/login");
};

const isActive = (path) => router.currentRoute.value.path.startsWith(path);
</script>
