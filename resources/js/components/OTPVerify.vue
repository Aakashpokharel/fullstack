<template>
  <div>
    <h3>Verify OTP</h3>
    <form @submit.prevent="verifyOTP">
      <div v-if="message" :class="`alert alert-${messageType}`" role="alert">
        {{ message }}
      </div>
      <div class="form-group">
        <label for="otp">Enter OTP:</label>
        <input type="text" class="form-control" id="otp" v-model="otp">
        <span class="text-danger" v-if="errors.otp">{{ errors.otp }}</span>
      </div>
      <button type="submit" class="btn btn-primary">Verify OTP</button>
    </form>
  </div>
</template>

<script>
import axios from '../axios';

export default {
  data() {
    return {
      otp: '',
      message: '',
      messageType: '',
      errors: {}
    };
  },
  methods: {
    async verifyOTP() {
      this.errors = {};
      if (!this.otp) {
        this.errors.otp = 'OTP is required.';
        return; // Exit early if OTP is empty
      }

      try {
        const response = await axios.post('/api/verifyotp', { otp_code: this.otp });

        console.log('Login response:', response.data); // Debug log for response data

        if (response.data.type === 'success') {
          // const token = response.data.response.api_token.replace('Bearer ', '');
          const user_id = response.data.response.user_id; // Correctly retrieving user_id
          // localStorage.setItem('api_token', token);
          localStorage.setItem('user_id', user_id);
          // console.log('Token saved:', token); // Debug log for token storage
          console.log('Data: ',response.data.response.redirect_url);
          this.$router.push('/resetpassword');
        } else {
          this.showMessage(response.data.message, 'danger');
        }
      } catch (error) {
        // console.error('OTP verification error:', error);
        if (error.response && error.response.data) {
          this.showMessage(error.response.data.message, 'danger');
        } else if (error.request) {
          // Network error (no response received)
          this.showMessage('Network error. Please check your internet connection.', 'danger');
        } else {
          // Other unknown errors
          this.showMessage('OTP verification failed. Please try again later.', 'danger');
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
.alert {
  margin-top: 10px;
}
.alert-danger {
  color: #721c24;
  background-color: #f8d7da;
  border-color: #f5c6cb;
}
.alert-success {
  color: #155724;
  background-color: #d4edda;
  border-color: #c3e6cb;
}
</style>
