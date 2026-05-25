<template>
  <div>
    <h1>Login</h1>
    <form @submit.prevent="submit">
      <div><input v-model="email" placeholder="Email" /></div>
      <div><input v-model="password" type="password" placeholder="Senha" /></div>
      <button type="submit">Entrar</button>
    </form>
  </div>
</template>

<script>
import api from '../services/api'
export default {
  data() { return { email: '', password: '' } },
  methods: {
    async submit() {
      const res = await api.post('/auth/login', { email: this.email, password: this.password })
      localStorage.setItem('api_token', res.data.token)
      localStorage.setItem('user', JSON.stringify(res.data.user))
      this.$router.push('/')
    }
  }
}
</script>
