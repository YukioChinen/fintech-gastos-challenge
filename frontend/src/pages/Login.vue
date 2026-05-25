<template>
  <div class="card" style="max-width:420px">
    <h1>Login</h1>
    <form @submit.prevent="submit">
      <input v-model="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Senha" />
      <div style="display:flex;gap:8px">
        <button type="submit">Entrar</button>
        <router-link to="/register" class="btn" style="background:#ddd;color:#0f172a;padding:8px 10px;border-radius:6px;text-decoration:none">Registrar</router-link>
      </div>
      <div v-if="error" style="color:red;margin-top:8px">{{ error }}</div>
    </form>

    <p class="muted small" style="margin-top:8px"><router-link to="/forgot-password">Esqueci minha senha</router-link></p>
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
 
