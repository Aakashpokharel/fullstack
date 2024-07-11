// resources/js/router.js

import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import OTPVerify from './components/OTPVerify.vue';
import ResetPassword from './components/ResetPassword.vue';
import MainLayout from './components/MainLayout.vue';
import ProductCategory from './components/ProductCategory.vue';



const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/verifyotp', component: OTPVerify }, // Add route for OTP verification
  { path: '/resetpassword', component: ResetPassword },
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
      { path: 'addcategory', component: ProductCategory}
    ]
  },
  // { path: '/addcategory', component: ProductCategory},

];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guard to check for authentication
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('api_token');
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login'); // Redirect to login if authentication is required but not authenticated
  } else {
    next();
  }
});

export default router;
