<template>
  <div>
    <h1>Categorias</h1>

    <form @submit.prevent="createCategory" style="margin-bottom:12px">
      <input v-model="name" placeholder="Nome da categoria" required />
      <button type="submit">Criar</button>
      <div v-if="error" style="color:red;margin-top:6px">{{ error }}</div>
    </form>

    <ul>
      <li v-for="cat in categories" :key="cat.id" style="margin-bottom:8px">
        <template v-if="editId === cat.id">
          <input v-model="editName" />
          <button @click="updateCategory">Salvar</button>
          <button @click="cancelEdit">Cancelar</button>
          <div v-if="error" style="color:red;margin-top:6px">{{ error }}</div>
        </template>
        <template v-else>
          {{ cat.name }}
          <button @click="startEdit(cat)" style="margin-left:8px">Editar</button>
          <button @click="deleteCategory(cat.id)" style="margin-left:8px">Excluir</button>
        </template>
      </li>
    </ul>
  </div>
</template>

<script>
import api from '../services/api'

export default {
  data() {
    return { categories: [], name: '', error: null, editId: null, editName: '' }
  },
  created() {
    this.loadCategories()
  },
  methods: {
    async loadCategories() {
      try {
        const res = await api.get('/categories')
        this.categories = res.data.data || res.data
      } catch (e) {
        console.error(e)
      }
    },
    async createCategory() {
      this.error = null
      try {
        await api.post('/categories', { name: this.name })
        this.name = ''
        this.loadCategories()
      } catch (e) {
        this.error = e.response?.data?.errors?.name?.[0] || e.response?.data?.message || 'Erro'
      }
    },
    startEdit(cat) {
      this.editId = cat.id
      this.editName = cat.name
      this.error = null
    },
    cancelEdit() {
      this.editId = null
      this.editName = ''
      this.error = null
    },
    async updateCategory() {
      this.error = null
      try {
        await api.put(`/categories/${this.editId}`, { name: this.editName })
        this.cancelEdit()
        this.loadCategories()
      } catch (e) {
        this.error = e.response?.data?.errors?.name?.[0] || e.response?.data?.message || 'Erro'
      }
    },
    async deleteCategory(id) {
      if (!confirm('Confirma exclusão?')) return
      try {
        await api.delete(`/categories/${id}`)
        this.loadCategories()
      } catch (e) {
        console.error(e)
      }
    }
  }
}
</script>
