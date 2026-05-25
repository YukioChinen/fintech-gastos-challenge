<template>
  <div>
    <nav style="padding:10px;background:#f5f5f5">
      <a href="/">Dashboard</a> |
      <router-link to="/categories">Categorias</router-link> |
      <router-link to="/expenses">Despesas</router-link> |
      <a href="#" @click.prevent="logout">Sair</a>
    </nav>
    <main style="padding:16px">
      <router-view />
    </main>
  </div>
</template>

<script>
export default {
  methods: {
    logout() {
      localStorage.removeItem('api_token')
      localStorage.removeItem('user')
      this.$router.push('/login')
    }
  }
}
</script>
