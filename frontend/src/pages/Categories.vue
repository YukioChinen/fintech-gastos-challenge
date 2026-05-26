<template>
  <div>
    <h1>Categorias</h1>

    <div class="card" style="max-width:640px;margin-bottom:12px">
      <form @submit.prevent="createCategory">
        <input v-model="name" placeholder="Nome da categoria" required />
        <div style="display:flex;gap:8px">
          <button type="submit">Criar</button>
        </div>
        <div v-if="error" style="color:red;margin-top:6px">{{ error }}</div>
      </form>
    </div>

    <div v-if="categories.length" class="card" style="max-width:640px">
      <ul>
        <li v-for="cat in categories" :key="cat.id" style="margin-bottom:8px;display:flex;justify-content:space-between;align-items:center">
          <div style="flex:1">
            <template v-if="editId === cat.id">
              <input v-model="editName" />
              <div v-if="error" style="color:red;margin-top:6px">{{ error }}</div>
            </template>
            <template v-else>
              {{ cat.name }}
            </template>
          </div>
          <div style="margin-left:8px; display:flex; gap:16px; align-items:center">
            <button v-if="editId !== cat.id" @click="startEdit(cat)" class="secondary">Editar</button>
            <button v-if="editId === cat.id" @click="updateCategory">Salvar</button>
            <button @click="deleteCategory(cat.id)" class="secondary">Excluir</button>
            <button v-if="editId === cat.id" @click="cancelEdit" class="secondary">Cancelar</button>
          </div>
        </li>
      </ul>
    </div>
    <div v-else class="card" style="max-width:640px">
      <div class="muted">Nenhuma categoria cadastrada.</div>
    </div>
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
