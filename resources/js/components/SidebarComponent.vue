<template>
    <aside class="bg-light border-end p-3" style="width: 250px;">
      <ul class="nav flex-column">
        <li class="nav-item">
          <router-link class="nav-link" to="/dashboard">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link " to="/addcategory">Product Category</router-link>
        </li>
      </ul>
    </aside>
  </template>
  
  <script>
  export default {
    name: 'SidebarComponent'
  };
  </script>
  
  <style>
  /* Add your styles here */
  </style>
  