<template>
  <div>
    <header class="app-nav">
      <div class="links">
        <router-link to="/">Dashboard</router-link>
        <router-link to="/categories">Categorias</router-link>
        <router-link to="/expenses">Despesas</router-link>
        <router-link v-if="user" to="/change-password">Senha</router-link>
      </div>
      <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
        <span v-if="user" class="muted small">Olá, {{ user.name }}</span>
        <!-- hide logout button on the login page -->
        <a v-if="user && $route.path !== '/login'" href="#" class="btn" @click.prevent="logout">Sair</a>
      </div>
    </header>
    <main class="container">
      <router-view />
    </main>
  </div>
</template>

<script>
export default {
  data() { return { user: JSON.parse(localStorage.getItem('user') || 'null') } },
  created() {
    this._authHandler = () => {
      this.user = JSON.parse(localStorage.getItem('user') || 'null')
    }
    window.addEventListener('auth:changed', this._authHandler)
  },
  beforeUnmount() {
    window.removeEventListener('auth:changed', this._authHandler)
  },
  methods: {
    logout() {
      localStorage.removeItem('api_token')
      localStorage.removeItem('user')
      this.user = null
      window.dispatchEvent(new CustomEvent('auth:changed'))
      this.$router.push('/login')
    }
  }
}
</script>
