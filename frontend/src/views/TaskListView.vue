<template>
  <MainLayout>
    <div class="projects">
      <header class="projects-header">
        <h1 class="projects-title">Gestión de Tareas</h1>
        <button class="btn-toggle" @click="openModal">Nueva Tarea</button>
      </header>

      <!-- Modal Popup -->
      <div v-if="showForm" class="modal-overlay" @click.self="cancelEdit">
        <div class="modal">
          <h2 class="modal-title">{{ editMode ? "Editar Tarea" : "Crear Tarea" }}</h2>

          <form @submit.prevent="submitForm" class="form">
            <div class="form-group">
              <label>Título</label>
              <input v-model="form.title" type="text" required maxlength="150" placeholder="Título de la tarea" />
            </div>

            <div class="form-group">
              <label>Estado</label>
              <select v-model="form.status" required>
                <option value="">Selecciona estado</option>
                <option value="pendiente">Pendiente</option>
                <option value="en_proceso">En proceso</option>
                <option value="completada">Completada</option>
              </select>
            </div>

            <div class="form-group">
              <label>Proyecto</label>
              <select v-model="form.project_id" required>
                <option value="">Selecciona un proyecto</option>
                <option v-for="p in projects" :key="p.id" :value="p.id">
                  {{ p.name }}
                </option>
              </select>
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

      <section class="table-section">
        <h2 class="section-title">Lista de Tareas</h2>

        <div v-if="store.loading" class="loading">Cargando...</div>
        <div v-else-if="!store.tasks.length" class="no-data">No hay tareas registradas.</div>

        <table v-else class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Título</th>
              <th>Estado</th>
              <th>Proyecto</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="t in store.tasks" :key="t.id">
              <td>{{ t.id }}</td>
              <td>{{ t.title }}</td>
              <td><span :class="['status', t.status]">{{ formatStatus(t.status) }}</span></td>
              <td>{{ t.project?.name || "Sin proyecto" }}</td>
              <td class="table-actions">
                <button class="btn-small" @click="openEditModal(t)">Editar</button>
                <button class="btn-small danger" @click="confirmDelete(t.id)">Eliminar</button>
              </td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- Modal Confirmación Eliminar -->
      <div v-if="deleteId" class="modal-overlay" @click.self="deleteId = null">
        <div class="modal small">
          <h3>¿Eliminar esta tarea?</h3>
          <div class="form-actions">
            <button class="btn-primary danger" @click="deleteTask()">Eliminar</button>
            <button class="btn-secondary" @click="deleteId = null">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>


<script setup>
import { ref, onMounted } from "vue";
import { useTaskStore } from "../stores/taskStore";
import { useProjectStore } from "../stores/projectStore";
import MainLayout from "../layouts/MainLayout.vue";
import "./TaskListView.css";

const store = useTaskStore();
const projectStore = useProjectStore();

const form = ref({ title: "", status: "", project_id: "" });
const showForm = ref(false);
const editMode = ref(false);
let editId = null;

onMounted(async () => {
  await projectStore.fetchProjects();
  await store.fetchTasks();
});

const projects = projectStore.projects;

const submitForm = async () => {
  if (editMode.value) {
    await store.updateTask(editId, form.value);
  } else {
    await store.createTask(form.value);
  }
  resetForm();
};

/* const editTask = (task) => {
  editMode.value = true;
  showForm.value = true;
  editId = task.id;
  form.value = {
    title: task.title,
    status: task.status,
    project_id: Number(task.project_id), // 👈 fuerza número
  };
}; */


const cancelEdit = () => resetForm();

const resetForm = () => {
  form.value = { title: "", status: "", project_id: "" };
  editMode.value = false;
  showForm.value = false;
  editId = null;
};

const formatStatus = (status) => {
  switch (status) {
    case "pendiente":
      return "Pendiente";
    case "en_proceso":
      return "En proceso";
    case "completada":
      return "Completada";
    default:
      return status;
  }
};

// referencia para confirmar eliminación
const deleteId = ref(null);

// abrir modal para crear (reset + mostrar)
const openModal = () => {
  form.value = { title: "", status: "", project_id: "" };
  editMode.value = false;
  editId = null;
  showForm.value = true;
};

// abrir modal para editar
const openEditModal = (task) => {
  editMode.value = true;
  showForm.value = true;
  editId = task.id;
  form.value = {
    title: task.title,
    status: task.status,
    project_id: Number(task.project_id ?? task.project?.id ?? ""),
  };
};

// al hacer clic en "Eliminar" del listado
const confirmDelete = (id) => {
  deleteId.value = id;
};

// ejecutar la eliminación confirmada
const deleteTask = async () => {
  if (!deleteId.value) return;
  await store.deleteTask(deleteId.value);
  deleteId.value = null;
};
</script>
