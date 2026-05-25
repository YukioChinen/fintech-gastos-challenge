<template>
  <div>
    <h1>Dashboard</h1>
    <p>Bem-vindo, {{ user?.name }}</p>

    <div class="cards">
      <div class="card">
        <h3>Total gasto neste mês</h3>
        <div class="value">R$ {{ formatCurrency(total) }}</div>
        <div class="muted small">Mês atual</div>
      </div>

      <div class="card">
        <h3>Últimas 5 despesas</h3>
        <div v-if="loading" class="loading">Carregando...</div>
        <ul v-else class="recent-list">
          <li v-for="e in recent" :key="e.id">
            <div>
              <div>{{ formatDate(e.date) }} <span class="muted small">— {{ e.category?.name || 'Sem categoria' }}</span></div>
              <div class="muted small">{{ e.description }}</div>
            </div>
            <div style="min-width:120px;text-align:right">R$ {{ formatCurrency(e.amount) }}</div>
          </li>
          <li v-if="recent.length===0" class="muted">Nenhuma despesa encontrada neste mês.</li>
        </ul>
      </div>

      <div class="card">
        <h3>Resumo por categoria (mês atual)</h3>
        <ul class="by-cat">
          <li v-for="c in byCategory" :key="c.category_id">
            <div>{{ c.category_name || 'Sem categoria' }}</div>
            <div>R$ {{ formatCurrency(c.total) }}</div>
          </li>
          <li v-if="byCategory.length===0" class="muted">Nenhuma categoria encontrada.</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../services/api'

export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user') || 'null'),
      total: 0,
      recent: [],
      byCategory: [],
      loading: false,
    }
  },
  methods: {
    formatCurrency(v){
      const n = Number(v || 0)
      return n.toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2})
    },
    formatDate(d){
      if (!d) return ''
      const datePart = String(d).slice(0, 10)
      const [year, month, day] = datePart.split('-')
      if (!year || !month || !day) return String(d)
      return `${day}/${month}/${year}`
    }
  },
  async created() {
    try {
      this.loading = true
      const res = await api.get('/auth/dashboard')
      const data = res.data
      this.total = data.total || 0
      this.recent = data.recent || []
      this.byCategory = data.by_category || []
      this.loading = false
    } catch (e) {
      this.loading = false
      console.error('Failed to load dashboard', e)
    }
  }
}
</script>
