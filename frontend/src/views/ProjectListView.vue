<template>
  <MainLayout>
    <div class="projects">
      <header class="projects-header">
        <h1 class="projects-title">Gestión de Proyectos</h1>
        <button class="btn-toggle" @click="openModal">
          Nuevo Proyecto
        </button>
      </header>

      <!-- Tabla de proyectos -->
      <section class="table-section">
        <h2 class="section-title">Lista de Proyectos</h2>

        <div v-if="store.loading" class="loading">Cargando...</div>
        <div v-else-if="!store.projects.length" class="no-data">
          No hay proyectos registrados.
        </div>

        <table v-else class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in store.projects" :key="p.id">
              <td>{{ p.id }}</td>
              <td>{{ p.name }}</td>
              <td>{{ p.description }}</td>
              <td class="table-actions">
                <button class="btn-small" @click="openEditModal(p)">Editar</button>
                <button class="btn-small danger" @click="confirmDelete(p.id)">Eliminar</button>
              </td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- Modal Crear/Editar -->
      <div v-if="showForm" class="modal-overlay">
        <div class="modal">
          <h2 class="form-title">
            {{ editMode ? "Editar Proyecto" : "Crear Proyecto" }}
          </h2>

          <form @submit.prevent="submitForm" class="form">
            <div class="form-group">
              <label>Nombre</label>
              <input
                v-model="form.name"
                type="text"
                required
                maxlength="100"
                placeholder="Nombre del proyecto"
              />
            </div>

            <div class="form-group">
              <label>Descripción</label>
              <textarea
                v-model="form.description"
                rows="3"
                placeholder="Breve descripción"
              ></textarea>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-primary" :disabled="store.loading">
                {{ editMode ? "Actualizar" : "Crear" }}
              </button>
              <button type="button" class="btn-secondary" @click="cancelEdit">
                Cancelar
              </button>
            </div>
          </form>

          <div v-if="store.error" class="alert error">
            <pre>{{ store.error }}</pre>
          </div>
          <div v-if="store.message" class="alert success">
            {{ store.message }}
          </div>
        </div>
      </div>

      <!-- Modal Confirmar eliminación -->
      <div v-if="deleteId" class="modal-overlay">
        <div class="modal">
          <h3>¿Seguro que deseas eliminar este proyecto?</h3>
          <div class="form-actions">
            <button class="btn-small danger" @click="deleteProject">Eliminar</button>
            <button class="btn-small" @click="deleteId = null">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import MainLayout from "../layouts/MainLayout.vue";
import { useProjectStore } from "../stores/projectStore";
import "./ProjectListView.css";

const store = useProjectStore();

const form = ref({ name: "", description: "" });
const showForm = ref(false);
const editMode = ref(false);
const deleteId = ref(null);
let editId = null;

onMounted(() => {
  store.fetchProjects();
});

// Abrir modal de crear
const openModal = () => {
  form.value = { name: "", description: "" };
  editMode.value = false;
  editId = null;
  showForm.value = true;
};

// Abrir modal de editar
const openEditModal = (project) => {
  form.value = { name: project.name, description: project.description };
  editMode.value = true;
  editId = project.id;
  showForm.value = true;
};

// Enviar formulario (crear o actualizar)
const submitForm = async () => {
  if (editMode.value) {
    await store.updateProject(editId, form.value);
  } else {
    await store.createProject(form.value);
  }
  resetForm();
};

// Cancelar edición o creación
const cancelEdit = () => resetForm();

// Resetear y cerrar modal
const resetForm = () => {
  form.value = { name: "", description: "" };
  editMode.value = false;
  showForm.value = false;
  editId = null;
};

// Confirmar eliminación
const confirmDelete = (id) => {
  deleteId.value = id;
};

// Ejecutar eliminación
const deleteProject = async () => {
  if (!deleteId.value) return;
  await store.deleteProject(deleteId.value);
  deleteId.value = null;
};
</script>
