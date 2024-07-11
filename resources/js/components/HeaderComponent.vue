<template>
  <header class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
    <div class="container">
      <h1>My Application</h1>
    </div>
    <div>
      <button class="btn btn-light" @click="logout">Logout</button>
    </div>
  </header>
</template>

<script>
export default {
  name: 'HeaderComponent',
  methods: {
    logout() {
      // Perform logout actions here, such as clearing session/local storage, redirecting to login page, etc.
      console.log('Logging out...');

      localStorage.removeItem('api_token');

      this.$router.push('/login');
    }
  }
};
</script>

<style>
/* Add your styles here */
</style>