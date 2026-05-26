<template>
  <div>
    <h1>Trocar senha</h1>

    <form @submit.prevent="submit" style="display:flex;flex-direction:column;gap:12px;max-width:420px">
      <div>
        <input v-model="current_password" type="password" placeholder="Senha atual" required />
      </div>
      <div>
        <input v-model="password" type="password" placeholder="Nova senha" required />
      </div>
      <div>
        <input v-model="password_confirmation" type="password" placeholder="Confirmar nova senha" required />
      </div>
      <button type="submit" style="align-self:flex-start">Salvar nova senha</button>
    </form>

    <p v-if="message" style="color: green; margin-top: 8px">{{ message }}</p>
    <div v-if="errors && Object.keys(errors).length" style="color: red; margin-top: 8px">
      <div v-for="(msgs, key) in errors" :key="key" style="margin-bottom:6px">
        <div>{{ fieldLabel(key) }}:</div>
        <div v-if="Array.isArray(msgs)">
          <div v-for="(msg, index) in msgs" :key="index" style="margin-left:12px">- {{ msg }}</div>
        </div>
        <div v-else style="margin-left:12px">- {{ msgs }}</div>
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
    fieldLabel(key) {
      const labels = {
        current_password: 'Senha atual',
        password: 'Nova senha',
        password_confirmation: 'Confirmar nova senha',
        geral: 'Geral',
      }

      return labels[key] || key
    },

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
