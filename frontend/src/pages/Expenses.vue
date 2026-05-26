<template>
  <div>
    <h1>Despesas</h1>

    <div class="card" style="max-width:720px;margin-bottom:12px">
      <form @submit.prevent="createExpense">
        <input v-model="description" placeholder="Descrição" required />
        <div class="form-row">
          <input v-model.number="amount" type="number" step="0.01" placeholder="Valor" required />
          <input v-model="date" type="date" required />
          <select v-model.number="category_id" required>
            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
        </div>
        <div style="margin-top:8px;display:flex;gap:12px;align-items:center">
          <button type="submit">{{ editId ? 'Salvar' : 'Criar despesa' }}</button>
          <button type="button" v-if="editId" @click="() => { editId = null; description=''; amount=null }" class="secondary">Cancelar</button>
        </div>
        <div v-if="errors && Object.keys(errors).length" style="color:red;margin-top:6px">
          <div v-for="(msgs, key) in errors" :key="key" style="margin-bottom:6px">
            <div>{{ fieldLabel(key) }}:</div>
            <div v-if="Array.isArray(msgs)">
              <div v-for="(msg, index) in msgs" :key="index" style="margin-left:12px">- {{ msg }}</div>
            </div>
            <div v-else style="margin-left:12px">- {{ msgs }}</div>
          </div>
        </div>
      </form>
    </div>

    <div v-if="expenses.length" class="card" style="max-width:720px">
      <ul>
        <li v-for="exp in expenses" :key="exp.id" style="margin-bottom:8px;display:flex;justify-content:space-between;align-items:center">
          <div>
            <div>{{ formatDate(exp.date) }} <span class="muted small">— {{ exp.category?.name }}</span></div>
            <div class="muted small">{{ exp.description }}</div>
          </div>
          <div style="display:flex;gap:16px;align-items:center">
            <div>R$ {{ Number(exp.amount).toFixed(2) }}</div>
            <div style="display:flex;gap:16px;align-items:center">
              <button @click="startEdit(exp)" class="secondary">Editar</button>
              <button @click="deleteExpense(exp.id)" class="secondary">Excluir</button>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div v-else class="card" style="max-width:720px">
      <div class="muted">Nenhuma despesa registrada.</div>
    </div>
  </div>
</template>

<script>
import api from '../services/api'

export default {
  data() {
    return {
      expenses: [],
      categories: [],
      description: '',
      amount: null,
      date: new Date().toISOString().slice(0, 10),
      category_id: null,
      errors: {},
      editId: null,
    }
  },
  created() {
    this.loadCategories()
    this.loadExpenses()
  },
  methods: {
    fieldLabel(key) {
      const labels = {
        description: 'Descrição',
        amount: 'Valor',
        date: 'Data',
        category_id: 'Categoria',
        general: 'Geral',
      }

      return labels[key] || key
    },

    formatDate(value) {
      if (!value) return ''
      const datePart = String(value).slice(0, 10)
      const [year, month, day] = datePart.split('-')
      if (!year || !month || !day) return String(value)
      return `${day}/${month}/${year}`
    },
    async loadCategories() {
      try {
        const res = await api.get('/categories')
        this.categories = res.data.data || res.data
        if (!this.category_id && this.categories.length) this.category_id = this.categories[0].id
      } catch (e) { console.error(e) }
    },
    async loadExpenses() {
      try {
        const res = await api.get('/expenses')
        this.expenses = res.data.data || res.data
      } catch (e) { console.error(e) }
    },
    async createExpense() {
      this.errors = {}
      try {
        if (this.editId) return this.updateExpense()
        await api.post('/expenses', {
          description: this.description,
          amount: this.amount,
          date: this.date,
          category_id: this.category_id,
        })
        this.description = ''
        this.amount = null
        this.loadExpenses()
      } catch (e) {
        const status = e.response?.status
        const serverMessage = e.response?.data?.message
        if (status === 500) {
          this.errors = { general: serverMessage || 'Erro no servidor. O valor informado pode ser maior que o permitido.' }
        } else {
          this.errors = e.response?.data?.errors || { general: serverMessage || 'Erro ao salvar despesa.' }
        }
      }
    },
    startEdit(exp) {
      this.editId = exp.id
      this.description = exp.description
      this.amount = exp.amount
      this.date = exp.date
      this.category_id = exp.category_id
      this.errors = {}
    },
    async updateExpense() {
      try {
        await api.put(`/expenses/${this.editId}`, {
          description: this.description,
          amount: this.amount,
          date: this.date,
          category_id: this.category_id,
        })
        this.editId = null
        this.description = ''
        this.amount = null
        this.loadExpenses()
      } catch (e) {
          const status = e.response?.status
          const serverMessage = e.response?.data?.message
          if (status === 500) {
            this.errors = { general: serverMessage || 'Erro no servidor. O valor informado pode ser maior que o permitido.' }
          } else {
            this.errors = e.response?.data?.errors || { general: serverMessage || 'Erro ao atualizar despesa.' }
          }
      }
    },
    async deleteExpense(id) {
      if (!confirm('Confirma exclusão?')) return
      try {
        await api.delete(`/expenses/${id}`)
        this.loadExpenses()
      } catch (e) { console.error(e) }
    }
  }
}
</script>
