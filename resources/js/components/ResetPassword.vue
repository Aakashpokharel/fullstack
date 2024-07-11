<template>
    <div>
      <h3>Reset Password</h3>
      <form @submit.prevent="resetPassword">
        <div v-if="message" :class="`alert alert-${messageType}`" role="alert">
          {{ message }}
        </div>

        <div class="form-group">
          <label for="new_password">New Password:</label>
          <input type="password" class="form-control" id="new_password" v-model="new_password">
          <span class="text-danger" v-if="errors.new_password">{{ errors.new_password }}</span>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password:</label>
          <input type="password" class="form-control" id="confirm_password" v-model="confirm_password">
          <span class="text-danger" v-if="errors.confirm_password">{{ errors.confirm_password }}</span>
        </div>

        <button type="submit" class="btn btn-primary">Reset Password</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from '../axios';
  
  export default {
    data() {
      return {
        new_password: '',
        confirm_password: '',
        message: '',
        messageType: '',
        errors: {}
      };
    },

    methods: {
      async resetPassword() {
        this.errors = {};
  
        // Basic client-side validation
        if (!this.new_password) {
          this.errors.new_password = 'New password is required.';
        } else if (this.new_password.length < 8) {
          this.errors.new_password = 'Password must be at least 8 characters long.';
        }
  
        if (!this.confirm_password) {
          this.errors.confirm_password = 'Please confirm your password.';
        } else if (this.new_password !== this.confirm_password) {
          this.errors.confirm_password = 'Passwords do not match.';
        }
  
        if (Object.keys(this.errors).length > 0) {
          return;
        }
  
        try {
          const response = await axios.post('/api/resetpassword', {
            new_password: this.new_password,
            confirm_password: this.confirm_password
          });
  
          console.log('Reset password response:', response.data);
  
          if (response.data.status === 'success') {
            this.showMessage(response.data.message, 'success');
            setTimeout(() => {
              this.$router.push('/login');
            }, 3000);
          } else {
            this.showMessage(response.data.message, 'danger');
          }
          
        } catch (error) {
          if (error.response && error.response.data) {
            this.showMessage(error.response.data.message, 'danger');
          } else if (error.request) {
            this.showMessage('Network error. Please check your internet connection.', 'danger');
          } else {
            this.showMessage('Password reset failed. Please try again later.', 'danger');
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
  