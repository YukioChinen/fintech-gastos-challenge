<template>
  <div>
    <header class="app-nav">
      <div class="links">
        <router-link to="/">Dashboard</router-link>
        <router-link to="/categories">Categorias</router-link>
        <router-link to="/expenses">Despesas</router-link>
      </div>
      <div style="display:flex;align-items:center;gap:12px">
        <span class="muted small">Olá, {{ user?.name }}</span>
        <a href="#" class="btn" @click.prevent="logout">Sair</a>
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
  methods: {
    logout() {
      localStorage.removeItem('api_token')
      localStorage.removeItem('user')
      this.$router.push('/login')
    }
  }
}
</script>
