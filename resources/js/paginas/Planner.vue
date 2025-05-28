<template>
  <div class="botonera">
    <button class="botones" @click="rellenarEquipoRandom">Equipo aleatorio</button>
    <button v-if="isUserLoggedIn" class="botones" @click="guardarEquipo">Guardar equipo</button>
  </div>

  <section class="planificador">

    <section class="team" id="equipo">
      <div class="poke" v-for="(slot, index) in 6" :key="index">
        <div class="pokediv">
          <select class="pokemon" name="Pokemon" v-model="selectedPokemons[index]" @change="updateMoves(index)">
            <option value=""></option>
            <option v-for="pokemon in pokemonData" :key="pokemon.nombre" :value="pokemon.nombre">
              {{ pokemon.nombre }}
            </option>
          </select>
        </div>
        <div class="movsdiv">
          <select class="movs" name="movs" v-for="i in 4" :key="i" v-model="selectedMoves[index][i-1]">
            <option value=""></option>
            <option v-for="move in filteredMoves(index)" :key="move.nombre" :value="move.nombre">
              {{ move.nombre }}
            </option>
          </select>
        </div>
      </div>
    </section>

    



    <section class="debilidades">
      <div class="tipos" v-for="(tipo, index) in tipos" :key="index">
        <img class="tiposimg" :src="`/image/tipos/${tipo}.png`" :alt="`Tipo ${tipo}`" />
        <span class="contador" :class="{
            positivo: tipoContador[tipo] > 0,
            negativo: tipoContador[tipo] < 0
          }">
          {{ tipoContador[tipo] || 0 }}
        </span>
      </div>
    </section>
  </section>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const isUserLoggedIn = ref(false);

// Lista de tipos de Pokémon
const tipos = [
  'agua', 'bicho', 'dragon', 'electrico', 'fantasma', 'fuego',
  'hielo', 'lucha', 'normal', 'planta', 'psiquico', 'roca',
  'siniestro', 'tierra', 'veneno', 'volador'
];

const selectedPokemons = ref(Array(6).fill(''));
const selectedMoves = ref(Array(6).fill().map(() => Array(4).fill('')));
const pokemonData = ref([]);
const movimientosData = ref([]);

// Debilidades de los Pokémon según su tipo
const debilidadesPokemon = {
  "Normal": ["Lucha"],
  "Fuego": ["Agua", "Tierra", "Roca"],
  "Agua": ["Planta", "Eléctrico"],
  "Planta": ["Fuego", "Hielo", "Veneno", "Volador", "Bicho"],
  "Eléctrico": ["Tierra"],
  "Hielo": ["Fuego", "Lucha", "Roca"],
  "Lucha": ["Volador", "Psíquico"],
  "Tierra": ["Agua", "Planta", "Hielo"],
  "Volador": ["Eléctrico", "Hielo", "Roca"],
  "Veneno": ["Tierra", "Psíquico"],
  "Psíquico": ["Bicho", "Fantasma", "Siniestro"],
  "Bicho": ["Fuego", "Volador", "Roca"],
  "Roca": ["Agua", "Planta", "Lucha", "Tierra"],
  "Fantasma": ["Fantasma", "Siniestro"],
  "Dragón": ["Hielo", "Dragón"],
  "Siniestro": ["Lucha", "Bicho"]
};

// Efectividad de los movimientos según su tipo
const efectividadMovimientos = {
  "Normal": [],
  "Fuego": ["Bicho", "Planta", "Hielo"],
  "Agua": ["Tierra", "Roca", "Fuego"],
  "Eléctrico": ["Agua", "Volador"],
  "Planta": ["Tierra", "Roca", "Agua"],
  "Hielo": ["Planta", "Tierra", "Volador", "Dragón"],
  "Lucha": ["Normal", "Roca", "Hielo", "Siniestro"],
  "Veneno": ["Planta"],
  "Tierra": ["Fuego", "Eléctrico", "Veneno", "Roca"],
  "Volador": ["Planta", "Lucha", "Bicho"],
  "Psíquico": ["Lucha", "Veneno"],
  "Bicho": ["Planta", "Psíquico", "Siniestro"],
  "Roca": ["Fuego", "Hielo", "Volador", "Bicho"],
  "Fantasma": ["Fantasma", "Psíquico"],
  "Dragón": ["Dragón"],
  "Siniestro": ["Fantasma", "Psíquico"]
};

// Obtiene los Pokémon de la API
async function obtenerPokemon() {
  try {
    const response = await axios.get('http://localhost:8000/api/listar');
    pokemonData.value = response.data;
  } catch (error) {
    console.error('Error al obtener Pokémon:', error);
  }
}

// Obtiene los movimientos de la API
async function obtenerMovimientos() {
  try {
    const response = await axios.get('http://localhost:8000/api/movimientos');
    movimientosData.value = response.data;
  } catch (error) {
    console.error('Error al obtener movimientos:', error);
  }
}

function updateMoves(index) {
  selectedMoves.value[index] = ["", "", "", ""];
}

// Filtra los movimientos disponibles según el Pokémon seleccionado
function filteredMoves(index) {
  const selectedPokemon = selectedPokemons.value[index];
  const pokemon = pokemonData.value.find(p => p.nombre === selectedPokemon);
  if (!pokemon) return [];
  return movimientosData.value.filter(
    move => move.tipo === pokemon.tipo1 || move.tipo === pokemon.tipo2
  );
}


const tipoContador = ref({});

// Calcula el contador de tipos basado en los Pokémon y movimientos seleccionados
function calcularTipoContador() {
  const contador = {};
  tipos.forEach(tipo => { contador[tipo] = 0; });

  selectedPokemons.value.forEach((nombre, pokeIndex) => {
    const pokemon = pokemonData.value.find(p => p.nombre === nombre);
    if (pokemon) {
      // Debilidades restan
      (debilidadesPokemon[pokemon.tipo1] || []).forEach(tipo => contador[tipo.toLowerCase()]--);
      if (pokemon.tipo2 && pokemon.tipo2 !== pokemon.tipo1) {
        (debilidadesPokemon[pokemon.tipo2] || []).forEach(tipo => contador[tipo.toLowerCase()]--);
      }
      // Fortalezas suman
      (selectedMoves.value[pokeIndex] || []).forEach(nombreMov => {
        const mov = movimientosData.value.find(m => m.nombre === nombreMov);
        if (mov && efectividadMovimientos[mov.tipo]) {
          efectividadMovimientos[mov.tipo].forEach(tipo => contador[tipo.toLowerCase()]++);
        }
      });
    }
  });

  tipoContador.value = contador;
}

watch([selectedPokemons, selectedMoves], calcularTipoContador, { deep: true });

onMounted(() => {
  obtenerPokemon();
  obtenerMovimientos();
  isUserLoggedIn.value = !!localStorage.getItem('userId');
});

//función para guardar el equipo
async function guardarEquipo() {
  const token = localStorage.getItem('token');
  const nombre = prompt('Ponle un nombre a tu equipo:');
  if (!nombre) return;

  const datosEquipo = {
    nombre,
    equipo: selectedPokemons.value.map((nombrePokemon, i) => ({
      nombre: nombrePokemon,
      movimientos: (selectedMoves.value[i] || []).slice(0, 4).map(mov => mov || "")
    }))
  };

  try {
    await axios.post(
      'http://localhost:8000/api/equipos',
      {
        datosEquipo: JSON.stringify(datosEquipo)
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      }
    );
    alert('¡Equipo guardado correctamente!');
  } catch (error) {
    alert('Error al guardar el equipo');
    console.error(error);
  }
}

//Función para rellenar el equipo con Pokémon y movimientos aleatorios
function rellenarEquipoRandom() {
  for (let i = 0; i < 6; i++) {
    const randomPokemon = pokemonData.value[Math.floor(Math.random() * pokemonData.value.length)];
    selectedPokemons.value[i] = randomPokemon ? randomPokemon.nombre : "";

    const moves = movimientosData.value.filter(
      move => move.tipo === randomPokemon.tipo1 || move.tipo === randomPokemon.tipo2
    );

    const shuffled = moves
      .map(value => ({ value, sort: Math.random() }))
      .sort((a, b) => a.sort - b.sort)
      .map(({ value }) => value.nombre);

    selectedMoves.value[i] = [
      shuffled[0] || "",
      shuffled[1] || "",
      shuffled[2] || "",
      shuffled[3] || ""
    ];
  }
}
</script>

<style src="/resources/css/planner.css"></style>

