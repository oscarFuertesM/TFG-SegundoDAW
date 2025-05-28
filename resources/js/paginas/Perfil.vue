<template>
  <section class="equipos">
    <div v-if="!usuarioLogueado" class="aviso-login">
      <p>
        Para guardar tus equipos necesitas
        <router-link to="/login">iniciar sesión</router-link>
        o
        <router-link to="/registro">registrarte</router-link>.
      </p>
    </div>
    <div id="equipos" class="equipo-container">
      <div
        v-for="equipo in equipos"
        :key="equipo.idEquipo"
        class="equipo"
      >
        <h3 class="nombre-equipo">{{ equipo.nombre }}</h3>
        <div
          v-for="pokemon in equipo.pokemons"
          :key="pokemon.nombre"
          class="poke"
        >
          <div class="pokediv">
            <p class="pokemon">{{ pokemon.nombre }}</p>
          </div>
          <div class="movsdiv">
            <p
              v-for="(mov, idx) in pokemon.movimientos"
              :key="idx"
              class="movimiento"
            >
              {{ mov }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { obtenerPermisos } from '/resources/helpers/authHelper';

const equipos = ref([]);
const cargando = ref(true);
const usuarioLogueado = ref(false); 

const cargarEquipos = async () => {
  const token = localStorage.getItem('token');
  try {
    const response = await axios.get('http://localhost:8000/api/mis-equipos', {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    });

    const datos = response.data;

    equipos.value = datos.map(equipo => {
      const datosEquipo = JSON.parse(equipo.datosEquipo);
      const lista = datosEquipo.equipo || datosEquipo.pokemons || [];
      return {
        idEquipo: equipo.idEquipo,
        nombre: datosEquipo.nombre || equipo.nombre || 'Equipo sin nombre',
        pokemons: lista.map(pokemon => {
          if (typeof pokemon === 'string') {
            return { nombre: pokemon, movimientos: [] };
          }
          return {
            nombre: pokemon.nombre,
            movimientos: pokemon.movimientos || []
          };
        })
      };
    });
  } catch (error) {
    console.error('Error al obtener los equipos:', error);
    // Redirigir si se quiere
    // router.push('/login');
  }
};

const validarPermisos = async () => {
  try {
    const datos = await obtenerPermisos();
    usuarioLogueado.value = (datos.idUsuario ?? datos.id ?? 0) > 0; 
  } catch (error) {
    console.error('Error al validar sesión:', error);
    usuarioLogueado.value = false; 
  }
};

onMounted(() => {
  validarPermisos();
  cargarEquipos();
});
</script>

<style scoped src="/resources/css/perfil.css"></style>
