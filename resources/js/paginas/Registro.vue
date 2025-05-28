<template>
  <section class="container">
    <div class="login">
      <form @submit.prevent="registrarUsuario">
        <h1>Registro</h1>

        <div class="form-group">
          <label for="usuario">Usuario:</label>
          <input type="text" id="usuario" v-model="usuario" required />
        </div>

        <div class="form-group">
          <label for="mail">Email:</label>
          <input type="email" id="mail" v-model="mail" required />
        </div>

        <div class="form-group">
          <label for="pass">Contraseña:</label>
          <input type="password" id="pass" v-model="pass" required />
        </div>

        <button type="submit">Enviar</button>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const usuario = ref('')
const mail = ref('')
const pass = ref('')
const router = useRouter()

const registrarUsuario = async () => {
  
  if (usuario.value.length < 3) {
    alert('El nombre de usuario debe tener al menos 3 caracteres.')
    return
  }
  if (!mail.value.includes('@')) {
    alert('Introduce un email válido.')
    return
  }
  if (pass.value.length < 6) {
    alert('La contraseña debe tener al menos 6 caracteres.')
    return
  }

  try {
    const datos = {
      mailUsuario: mail.value,
      passUsuario: pass.value,
      nombreUsuario: usuario.value,
      permisos: 1
    }

    await axios.post('http://localhost:8000/api/register', datos, {
      headers: {
        'Content-Type': 'application/json'
      }
    })

    alert('Registro completado correctamente')
    router.push('/login') 
  } catch (error) {
    console.error('Error al registrar usuario:', error)
    alert('Error al registrar. Inténtalo de nuevo.')
  }
}
</script>

<style scoped src="/resources/css/registro.css"></style>
