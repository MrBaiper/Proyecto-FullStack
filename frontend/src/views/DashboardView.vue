<template>
  <MainLayout>
    <section class="dashboard">
      <div class="dashboard-card">
        <h1 class="dashboard-title">Bienvenido al Panel</h1>
        <p class="dashboard-subtitle">
          Selecciona un módulo desde la barra lateral para gestionar proyectos o tareas.
        </p>
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import MainLayout from "../layouts/MainLayout.vue";
import "./DashboardView.css";
</script>
