<template>
  <div class="layout">
    <Sidebar />

    <main class="main-content">
      <div class="content-wrapper">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import Sidebar from "../components/AppSidebar.vue";
import "./MainLayout.css";
</script>
