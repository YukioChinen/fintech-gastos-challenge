import { createRouter, createWebHistory } from 'vue-router'
import Login from '../pages/Login.vue'
import Dashboard from '../pages/Dashboard.vue'
import Categories from '../pages/Categories.vue'
import Expenses from '../pages/Expenses.vue'

const routes = [
  { path: '/', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/login', component: Login },
  { path: '/categories', component: Categories, meta: { requiresAuth: true } },
  { path: '/expenses', component: Expenses, meta: { requiresAuth: true } },
]

const router = createRouter({ history: createWebHistory(), routes })

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('api_token')
  if (to.meta.requiresAuth && !token) return next('/login')
  next()
})

export default router
