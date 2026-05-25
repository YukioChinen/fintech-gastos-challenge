<template>
  <div>
    <h1>Registrar</h1>
    <form @submit.prevent="submit">
      <div><input v-model="name" placeholder="Nome" required /></div>
      <div><input v-model="email" placeholder="Email" required /></div>
      <div><input v-model="password" type="password" placeholder="Senha" required /></div>
      <div><input v-model="password_confirmation" type="password" placeholder="Confirmar senha" required /></div>
      <div style="margin-top:8px">
        <button type="submit">Registrar</button>
      </div>
      <div v-if="errors && Object.keys(errors).length" style="color:red;margin-top:8px">
        <div v-for="(msgs, key) in errors" :key="key">{{ key }}: {{ Array.isArray(msgs) ? msgs.join(', ') : msgs }}</div>
      </div>
    </form>

    <p>Já tem conta? <router-link to="/login">Entrar</router-link></p>
  </div>
</template>

<script>
import api from '../services/api'

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      errors: {},
    }
  },
  methods: {
    async submit() {
      this.errors = {}
      try {
        const res = await api.post('/auth/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })

        const token = res.data.token
        localStorage.setItem('api_token', token)
        localStorage.setItem('user', JSON.stringify(res.data.user))
        this.$router.push('/')
      } catch (e) {
        this.errors = e.response?.data?.errors || { general: e.response?.data?.message }
      }
    }
  }
}
</script>
