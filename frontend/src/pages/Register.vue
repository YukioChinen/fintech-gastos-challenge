<template>
  <div class="card" style="max-width:520px">
    <h1>Registrar</h1>
    <form @submit.prevent="submit">
      <input v-model="name" placeholder="Nome" required />
      <input v-model="email" placeholder="Email" required />
      <input v-model="password" type="password" placeholder="Senha" required />
      <input v-model="password_confirmation" type="password" placeholder="Confirmar senha" required />
      <div style="margin-top:8px">
        <button type="submit">Registrar</button>
      </div>
      <div v-if="errors && Object.keys(errors).length" style="color:red;margin-top:8px">
        <div v-for="(msgs, key) in errors" :key="key" style="margin-bottom:6px">
          <div>{{ fieldLabel(key) }}:</div>
          <div v-if="Array.isArray(msgs)">
            <div v-for="(msg, index) in msgs" :key="index" style="margin-left:12px">- {{ msg }}</div>
          </div>
          <div v-else style="margin-left:12px">- {{ msgs }}</div>
        </div>
      </div>
    </form>

    <p class="muted small">Já tem conta? <router-link to="/login">Entrar</router-link></p>
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
    fieldLabel(key) {
      const labels = {
        name: 'Nome',
        email: 'Email',
        password: 'Senha',
        password_confirmation: 'Confirmar senha',
        general: 'Geral',
      }

      return labels[key] || key
    },

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
          // notify app that auth changed so header updates
          window.dispatchEvent(new CustomEvent('auth:changed'))
          this.$router.push('/')
      } catch (e) {
        this.errors = e.response?.data?.errors || { general: e.response?.data?.message }
      }
    }
  }
}
</script>
