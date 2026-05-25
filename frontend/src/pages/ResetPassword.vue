<template>
  <div>
    <h1>Redefinir senha</h1>

    <form @submit.prevent="submit">
      <div><input v-model="email" type="email" placeholder="Email" required /></div>
      <div><input v-model="token" placeholder="Token" required /></div>
      <div><input v-model="password" type="password" placeholder="Nova senha" required /></div>
      <div><input v-model="password_confirmation" type="password" placeholder="Confirmar nova senha" required /></div>
      <button type="submit">Redefinir senha</button>
    </form>

    <p v-if="message" style="color: green; margin-top: 8px">{{ message }}</p>
    <p v-if="error" style="color: red; margin-top: 8px">{{ error }}</p>

    <p style="margin-top: 12px"><router-link to="/login">Voltar ao login</router-link></p>
  </div>
</template>

<script>
import api from '../services/api'

export default {
  data() {
    return {
      email: this.$route.query.email || '',
      token: this.$route.query.token || '',
      password: '',
      password_confirmation: '',
      message: '',
      error: '',
    }
  },
  methods: {
    async submit() {
      this.message = ''
      this.error = ''

      try {
        const res = await api.post('/auth/reset-password', {
          email: this.email,
          token: this.token,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })
        this.message = res.data.message || 'Senha redefinida com sucesso.'
      } catch (e) {
        this.error = e.response?.data?.message || 'Nao foi possivel redefinir a senha.'
      }
    },
  },
}
</script>
