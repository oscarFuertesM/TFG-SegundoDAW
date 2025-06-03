<template>
  <div class="noticias-wrapper">
    <h1 class="titunot">Noticias Pokémon</h1>

    <section class="noticias" id="notisid">
      <div v-if="cargando" class="loader">Cargando noticias...</div>
      <div v-else>
        <div
          v-for="noticia in noticias"
          :key="noticia.idnoticias"
          class="publicacion"
        >
          <h2 class="titulo">{{ noticia.titulo }}</h2>
          <p class="texto">{{ noticia.texto }}</p>

          <div v-if="tienePermiso">
            <button class="botonEditar">
              <router-link :to="`/insertarnoticias?id=${noticia.idnoticias}`">Editar</router-link>
            </button>
            <button class="botonBorrar" @click="borrarNoticia(noticia.idnoticias)">Borrar</button>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { obtenerPermisos, cerrarSesion } from '/resources/helpers/authHelper';

const noticias = ref([]);
const tienePermiso = ref(false);
const cargando = ref(true);

const obtenerNoticias = async () => {
  cargando.value = true;
  try {
    const response = await axios.get('/api/noticias');
    noticias.value = response.data;
  } catch (error) {
    console.error("Error al obtener noticias:", error);
  } finally {
    cargando.value = false;
  }
};

const borrarNoticia = async (id) => {
  if (confirm("Se eliminará la noticia de manera permanente, ¿estás seguro?")) {
    try {
      await axios.delete(`/api/noticias/${id}`);
      await obtenerNoticias(); 
    } catch (error) {
      console.error("Error al borrar la noticia:", error);
    }
  }
};

const aplicarVisibilidadBotones = async () => {
  try {
    const { permiso } = await obtenerPermisos();
    tienePermiso.value = permiso >= 7;
  } catch (error) {
    console.error("Error al obtener permisos:", error);
    tienePermiso.value = false;
  }
};

onMounted(() => {
  obtenerNoticias();
  aplicarVisibilidadBotones();
});
</script>

<style scoped src="/resources/css/noticias.css"></style>
