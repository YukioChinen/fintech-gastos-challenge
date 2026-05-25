<template>
  <div>
    <h1>Despesas</h1>

    <form @submit.prevent="createExpense" style="margin-bottom:12px">
      <div><input v-model="description" placeholder="Descrição" required /></div>
      <div><input v-model.number="amount" type="number" step="0.01" placeholder="Valor" required /></div>
      <div><input v-model="date" type="date" required /></div>
      <div>
        <select v-model.number="category_id" required>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
      </div>
      <div style="margin-top:8px">
        <button type="submit">{{ editId ? 'Salvar' : 'Criar despesa' }}</button>
        <button type="button" v-if="editId" @click="() => { editId = null; description=''; amount=null }">Cancelar</button>
      </div>
      <div v-if="errors && Object.keys(errors).length" style="color:red;margin-top:6px">
        <div v-for="(msgs, key) in errors" :key="key">{{ key }}: {{ Array.isArray(msgs) ? msgs.join(', ') : msgs }}</div>
      </div>
    </form>

    <ul>
      <li v-for="exp in expenses" :key="exp.id" style="margin-bottom:8px">
        {{ exp.date }} — {{ exp.description }} — {{ exp.amount }} ({{ exp.category?.name }})
        <button @click="startEdit(exp)" style="margin-left:8px">Editar</button>
        <button @click="deleteExpense(exp.id)" style="margin-left:8px">Excluir</button>
      </li>
    </ul>
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
        this.errors = e.response?.data?.errors || { general: e.response?.data?.message }
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
        this.errors = e.response?.data?.errors || { general: e.response?.data?.message }
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
