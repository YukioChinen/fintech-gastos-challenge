<template>
  <div>
    <h1>Trocar senha</h1>

    <form @submit.prevent="submit">
      <div>
        <input v-model="current_password" type="password" placeholder="Senha atual" required />
      </div>
      <div>
        <input v-model="password" type="password" placeholder="Nova senha" required />
      </div>
      <div>
        <input v-model="password_confirmation" type="password" placeholder="Confirmar nova senha" required />
      </div>
      <button type="submit">Salvar nova senha</button>
    </form>

    <p v-if="message" style="color: green; margin-top: 8px">{{ message }}</p>
    <div v-if="errors && Object.keys(errors).length" style="color: red; margin-top: 8px">
      <div v-for="(msgs, key) in errors" :key="key">
        {{ key }}: {{ Array.isArray(msgs) ? msgs.join(', ') : msgs }}
      </div>
    </div>
  </div>
</template>

<script>
import api from '../services/api'

export default {
  data() {
    return {
      current_password: '',
      password: '',
      password_confirmation: '',
      message: '',
      errors: {},
    }
  },
  methods: {
    async submit() {
      this.message = ''
      this.errors = {}

      try {
        const res = await api.post('/auth/change-password', {
          current_password: this.current_password,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })

        this.message = res.data.message || 'Senha alterada com sucesso.'
        this.current_password = ''
        this.password = ''
        this.password_confirmation = ''
      } catch (e) {
        this.errors = e.response?.data?.errors || { geral: e.response?.data?.message || 'Erro ao trocar senha.' }
      }
    },
  },
}
</script>
