import api from './api'

export default {
  list() { return api.get('/expenses') },
  create(payload) { return api.post('/expenses', payload) },
  delete(id) { return api.delete(`/expenses/${id}`) }
  ,update(id, payload) { return api.put(`/expenses/${id}`, payload) }
}
