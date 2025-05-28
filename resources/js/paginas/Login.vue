<template>
  <section class="container">
    <div class="login">
      <form @submit.prevent="iniciarSesion">
        <h1>Iniciar Sesión</h1>

        <div class="form-group">
          <label for="mail">Correo electrónico:</label>
          <input v-model="email" type="text" id="mail" name="mail" />
        </div>

        <div class="form-group">
          <label for="pass">Contraseña:</label>
          <input v-model="password" type="password" id="pass" name="pass" />
        </div>

        <button type="submit">Entrar</button>

        <div class="signin">
          <span>¿No tienes cuenta?</span>
          <span class="registro"><router-link to="/registro"> Regístrate </router-link></span>
        </div>

        <div v-if="error" class="error-message">{{ error }}</div>
      </form>
      
    </div>
  </section>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { obtenerPermisos, cerrarSesion } from '/resources/helpers/authHelper';

export default {
  setup() {
    const router = useRouter();

    const email = ref('');
    const password = ref('');
    const error = ref('');
    const isLogged = ref(false);
    const isAdmin = ref(false);

    // Ajustar menú según permisos
    const ajustarMenuUsuario = async () => {
      try {
        const { permiso, idUsuario } = await obtenerPermisos();
        isLogged.value = idUsuario > 0;
        isAdmin.value = permiso >= 7;
      } catch (error) {
        isLogged.value = false;
        isAdmin.value = false;
        console.error('Error al ajustar el menú del usuario');
      }
    };

    // Cerrar sesión
    const cerrarSesionUsuario = async () => {
      if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        try {
          await cerrarSesion();
          localStorage.clear();
          sessionStorage.clear();
          isLogged.value = false;
          isAdmin.value = false;
          router.push({ name: 'login' });
        } catch (error) {
          console.error('Error al cerrar sesión');
        }
      }
    };

    onMounted(() => {
      ajustarMenuUsuario();
    });

    const iniciarSesion = async () => {
      error.value = '';
      try {
        const response = await fetch('http://localhost:8000/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            mailUsuario: email.value,
            passUsuario: password.value
          })
        });

        const data = await response.json();

        if (response.ok && data.token) {
          localStorage.setItem('token', data.token);
          localStorage.setItem('userId', data.user.idUsuario);
          await ajustarMenuUsuario();
          router.push('/'); // Redirige a la página principal
          setTimeout(() => window.location.reload(), 100); // Recarga tras redirigir
        } else {
          error.value = data.message || 'Credenciales incorrectas';
        }
      } catch (e) {
        error.value = 'Error al conectar con el servidor';
      }
    };

    return {
      email,
      password,
      error,
      iniciarSesion,
      isLogged,
      isAdmin,
      cerrarSesionUsuario,
      router
    };
  }
};
</script>

<style scoped src="/resources/css/login.css"></style>

