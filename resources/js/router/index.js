import { createRouter, createWebHistory } from 'vue-router'

// Importar componentes desde la carpeta correcta
import Planner from '../paginas/Planner.vue'
import Noticias from '../paginas/Noticias.vue'
import Perfil from '../paginas/Perfil.vue'
import Login from '../paginas/Login.vue'
import Registro from '../paginas/Registro.vue'
import InsertarNoticias from '../paginas/InsertarNoticias.vue'

const routes = [
  { path: '/', name: 'planner', component: Planner },
  { path: '/noticias', name: 'noticias', component: Noticias },
  { path: '/perfil', name: 'perfil', component: Perfil },
  { path: '/login', name: 'login', component: Login },
  { path: '/registro', name: 'registro', component: Registro },
  { path: '/insertarnoticias', name: 'insertarnoticias', component: InsertarNoticias },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
