// resources/helpers/authHelper.js
import axios from 'axios';

// Obtiene los datos del usuario autenticado y sus permisos
export function obtenerPermisos() {
  const token = localStorage.getItem('token');
  return axios.get('http://localhost:8000/api/me', {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
    .then(response => ({
      permiso: response.data.permisos,
      idUsuario: response.data.idUsuario
    }))
    .catch(error => {
      console.error('Error al obtener permisos del usuario:', error);
      return { permiso: 0, idUsuario: 0 };
    });
}

export function cerrarSesion() {
  if (!window.confirm('¿Estás seguro que quieres cerrar la sesión?')) {
    return Promise.resolve(false);
  }
  const token = localStorage.getItem('token');
  return axios.post('http://localhost:8000/api/logout', {}, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
    .then(() => {
      localStorage.removeItem('token');
      localStorage.removeItem('userId');
      localStorage.removeItem('permisos');
      localStorage.removeItem('usuario');
      return true;
    })
    .catch(error => {
      localStorage.removeItem('token');
      localStorage.removeItem('userId');
      localStorage.removeItem('permisos');
      localStorage.removeItem('usuario');
      console.error('Error al cerrar sesión:', error);
      throw error;
    });
}

