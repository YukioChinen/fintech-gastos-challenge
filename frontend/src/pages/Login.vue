<template>
  <div>
    <h1>Login</h1>
    <form @submit.prevent="submit">
      <div><input v-model="email" placeholder="Email" /></div>
      <div><input v-model="password" type="password" placeholder="Senha" /></div>
      <button type="submit">Entrar</button>
      <div v-if="error" style="color:red;margin-top:8px">{{ error }}</div>
    </form>

    <p>Não tem conta? <router-link to="/register">Registrar</router-link></p>
    <p><router-link to="/forgot-password">Esqueci minha senha</router-link></p>
  </div>
</template>

<script>
import api from '../services/api'
export default {
  data() { return { email: '', password: '', error: '' } },
  methods: {
    async submit() {
      this.error = ''
      try {
        const res = await api.post('/auth/login', { email: this.email, password: this.password })
        localStorage.setItem('api_token', res.data.token)
        localStorage.setItem('user', JSON.stringify(res.data.user))
        this.$router.push('/')
      } catch (e) {
        const status = e.response?.status
        if (status === 401) {
          this.error = 'Usuario ou senha incorretos.'
        } else if (status === 422) {
          this.error = 'Preencha email e senha corretamente.'
        } else {
          this.error = 'Nao foi possivel entrar agora. Tente novamente.'
        }
      }
    }
  }
}
</script>
 
