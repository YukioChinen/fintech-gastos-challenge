import api from './api'

export default {
  list() { return api.get('/categories') },
  create(payload) { return api.post('/categories', payload) },
  delete(id) { return api.delete(`/categories/${id}`) }
  ,update(id, payload) { return api.put(`/categories/${id}`, payload) }
}
