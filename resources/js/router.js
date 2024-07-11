// resources/js/router.js

import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import OTPVerify from './components/OTPVerify.vue';
import ResetPassword from './components/ResetPassword.vue';
import MainLayout from './components/MainLayout.vue';
import ProductCategory from './components/ProductCategory.vue';
import UserManagement from './components/User Mannagement/UserManagement.vue';



const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/verifyotp', component: OTPVerify, meta: { requiresAuth: true }, }, // Add route for OTP verification
  { path: '/resetpassword', component: ResetPassword, meta: { requiresAuth: true }, },
  {
    path: '/',
    component: MainLayout,
    meta: { requiresAuth: true },
    children: [
      { path: 'dashboard', component: Dashboard, },
      { path: 'addcategory', component: ProductCategory },
      { path: 'user-management', component: UserManagement },
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
