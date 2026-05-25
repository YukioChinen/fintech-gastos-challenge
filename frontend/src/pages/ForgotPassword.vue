<template>
  <div>
    <h1>Esqueci minha senha</h1>

    <form @submit.prevent="submit">
      <div><input v-model="email" type="email" placeholder="Email" required /></div>
      <button type="submit">Enviar instrucoes</button>
    </form>

    <p v-if="message" style="color: green; margin-top: 8px">{{ message }}</p>
    <p v-if="error" style="color: red; margin-top: 8px">{{ error }}</p>

    <div v-if="resetToken" style="margin-top: 12px">
      <p>Token de redefinicao (ambiente local):</p>
      <pre style="background:#f5f5f5;padding:8px">{{ resetToken }}</pre>
      <router-link :to="`/reset-password?email=${encodeURIComponent(email)}&token=${encodeURIComponent(resetToken)}`">
        Ir para redefinir senha
      </router-link>
    </div>

    <p style="margin-top: 12px"><router-link to="/login">Voltar ao login</router-link></p>
  </div>
</template>

<script>
import api from '../services/api'

export default {
  data() {
    return {
      email: '',
      message: '',
      error: '',
      resetToken: '',
    }
  },
  methods: {
    async submit() {
      this.message = ''
      this.error = ''
      this.resetToken = ''

      try {
        const res = await api.post('/auth/forgot-password', { email: this.email })
        this.message = res.data.message || 'Verifique seu email para redefinir a senha.'
        this.resetToken = res.data.reset_token || ''
      } catch (e) {
        this.error = e.response?.data?.message || 'Nao foi possivel enviar as instrucoes.'
      }
    },
  },
}
</script>
