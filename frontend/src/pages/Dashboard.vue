<template>
  <div>
    <h1>Dashboard</h1>
    <p>Bem-vindo, {{ user?.name }}</p>
  </div>
</template>

<script>
export default {
  data() { return { user: JSON.parse(localStorage.getItem('user')||'null') } }
}
</script>
