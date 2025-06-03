<template>
  <section class="contenedor">
    <div class="insertarnoti">
      <h2>Introducir Noticia</h2>
      <form @submit.prevent="guardarNoticia">
        <div class="formulario">
          <input type="hidden" v-model="id" />
          <label for="titulo">Título:</label>
          <input type="text" v-model="titulo" id="titulo" required />
        </div>
        <div class="formulario">
          <label for="contenido">Contenido:</label>
          <textarea v-model="contenido" id="contenido" rows="12" required></textarea>
        </div>
        <button type="submit">Guardar Noticia</button>
      </form>
    </div>
  </section>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

export default {
  setup() {
    const id = ref('');
    const titulo = ref('');
    const contenido = ref('');
    const route = useRoute();
    const router = useRouter();

    function devolverDatos(datos) {
      id.value = datos.idnoticias || datos.id || datos.idNoti || '';
      titulo.value = datos.titulo || '';
      contenido.value = datos.texto || '';
    }

    function obtenerNoticia() {
      const noticiaId = route.query.id;
      if (!noticiaId) return; 
      axios.get(`/api/noticias/${noticiaId}`)
        .then(response => {
          devolverDatos(response.data);
        })
        .catch(error => {
          console.error("Error al obtener noticia:", error);
        });
    }

    function guardarNoticia() {
      const noticiaData = {
        titulo: titulo.value,
        texto: contenido.value
      };

      if (id.value) {
        axios.put(`/api/noticias/${id.value}`, noticiaData)
          .then(() => {
            alert("Noticia actualizada con éxito");
            router.push('/noticias');
          })
          .catch(error => {
            console.error("Error al actualizar noticia:", error);
          });
      } else {
        axios.post('/api/noticias', noticiaData)
          .then(() => {
            alert("Noticia guardada con éxito");
            router.push('/noticias');
          })
          .catch(error => {
            console.error("Error al guardar noticia:", error);
          });
      }
    }

    onMounted(() => {
      if (route.query.id) {
        obtenerNoticia();
      } else {
        devolverDatos({ id: '', titulo: '', texto: '' });
      }
    });

    watch(
      () => route.query.id,
      (newId) => {
        if (newId) {
          obtenerNoticia();
        } else {
          devolverDatos({ id: '', titulo: '', texto: '' });
        }
      }
    );

    return {
      id,
      titulo,
      contenido,
      guardarNoticia
    };
  }
}
</script>

<style scoped src="/resources/css/insertarnoticias.css"></style>
