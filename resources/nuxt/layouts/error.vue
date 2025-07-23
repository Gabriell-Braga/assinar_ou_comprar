<template><div>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header text-uppercase font-weight-bold">Error: {{ error.statusCode }}</div>
      <div class="card-body">
        {{ error.message }}
        <pre class="bg-dark p-2 mt-3" style="color:lime;">Details: {{ error }}</pre>
      </div>
      <div class="card-footer">
        <a href="" class="btn btn-primary">Recarregar</a>
      </div>
    </div>
  </div>
</div></template>

<script>
export default {
  // layout: 'empty',
  layout: 'admin',
  props: {
    error: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      pageNotFound: '404 Not Found',
      otherError: 'An error occurred'
    }
  },
  head() {
    const title =
      this.error.statusCode === 404 ? this.pageNotFound : this.otherError
    return {
      title
    }
  }
}
</script>

<style scoped>
h1 {
  font-size: 20px;
}
</style>
