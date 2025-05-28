<template>
  <div class="head">
    <ul class="header">
      <li>
        <router-link to="/"><img id="logo" src="/public/image/logo.png" alt="Logo Poke Planner" /></router-link>
      </li>
      <li class="txt"><router-link to="/">Planificador</router-link></li>
      <li class="txt"><router-link to="/noticias">Noticias</router-link></li>
      <li class="txt"><router-link to="/perfil">Perfil</router-link></li>
      
      <li v-if="!usuarioLogueado" class="txt" id="login">
        <router-link to="/login">Iniciar sesión</router-link>
      </li>
      <li v-if="permisos >= 7" class="txt" id="admin">
        <router-link to="/insertarnoticias">Insertar noticia</router-link>
      </li>
      <li v-if="usuarioLogueado" class="txt" id="logout">
        <a href="#" @click.prevent="cerrarSesion">Cerrar Sesión</a>
      </li>
      <li><img id="pokeball" src="/public/image/pokeball.png" alt="Pokeball" /></li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { obtenerPermisos, cerrarSesion as cerrarSesionHelper } from '/resources/helpers/authHelper'
import { useRouter } from 'vue-router'

const usuarioLogueado = ref(false)
const permisos = ref(0)
const router = useRouter()

const cargarPermisos = async () => {
  try {
    const datos = await obtenerPermisos()
    usuarioLogueado.value = (datos.idUsuario ?? datos.id ?? 0) > 0
    permisos.value = datos.permisos ?? datos.permiso ?? 0
  } catch {
    usuarioLogueado.value = false
    permisos.value = 0
  }
}

const cerrarSesion = async () => {
  await cerrarSesionHelper()
  usuarioLogueado.value = false
  permisos.value = 0
  router.push('/login')
}

onMounted(() => {
  cargarPermisos()
  window.addEventListener('storage', cargarPermisos)
})
</script>

<style scoped src="/resources/css/navbar.css"></style>
