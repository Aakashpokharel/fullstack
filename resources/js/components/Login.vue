<template>
  <section class="vh-100" style="background-color: #508bfc;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card shadow-2-strong" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">
              <h3 class="mb-5">Sign in</h3>
              <div v-if="message" :class="`alert alert-${messageType}`" role="alert">
                {{ message }}
              </div>
              <form @submit.prevent="login">
                <div class="form-outline mb-4">
                  <label class="form-label" for="typeEmailX-2">Email</label>
                  <input type="email" id="typeEmailX-2" class="form-control form-control-lg" v-model="email" />
                  <span class="text-danger" v-if="errors.email">{{ errors.email }}</span>
                </div>
                <div class="form-outline mb-4">
                  <label class="form-label" for="typePasswordX-2">Password</label>
                  <input type="password" id="typePasswordX-2" class="form-control form-control-lg" v-model="password" />
                  <span class="text-danger" v-if="errors.password">{{ errors.password }}</span>
                </div>
                <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
                <hr class="my-4">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from '../axios';

export default {
  data() {
    return {
      email: '',
      password: '',
      errors: {},
      message: '',
      messageType: ''
    };
  },
  methods: {
    async login() {
      this.errors = {};  // Clear previous errors
      this.message = ''; // Clear previous messages

      // Basic front-end validation
      if (!this.email) {
        this.errors.email = 'Email is required';
      }
      if (!this.password) {
        this.errors.password = 'Password is required';
      }

      if (Object.keys(this.errors).length === 0) {
        try {
          const response = await axios.post('/api/login', {
            email: this.email,
            password: this.password
          });

          console.log('Login response:', response.data);
          console.log('User-ID:', response.data.response.user_id)

          if (response.data.type === 'success') {
            if (response.data.response.first_time_login === null) {
              // Redirect to OTP verification if first_time_login is null
              localStorage.setItem('api_token', response.data.response.api_token);
              localStorage.setItem('user_id', response.data.response.user_id);
              this.$router.push('/verifyotp');
            } else {
              // Store the api_token in localStorage
              localStorage.setItem('api_token', response.data.response.api_token);
              localStorage.setItem('user_id', response.data.response.user_id);
              // Redirect to the dashboard
              this.$router.push('/dashboard');
            }
          } else {
            this.showMessage('Login failed. Please check your credentials and try again.', 'danger');
          }
        } catch (error) {
          console.error('Login error:', error.response || error.message || error);
          this.showMessage('Login failed. Please check your credentials and try again.', 'danger');
        }
      }
    },
    showMessage(message, type) {
      this.message = message;
      this.messageType = type;
      setTimeout(() => {
        this.message = '';
      }, 3000);
    }
  }
};
</script>

<style scoped>
.text-danger {
  color: red;
}
</style>
