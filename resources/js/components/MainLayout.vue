<template>
    <div class="d-flex flex-column min-vh-100">
      <HeaderComponent />
      <div class="d-flex flex-grow-1">
        <SidebarComponent />
        <main class="flex-grow-1 p-3">
          <router-view></router-view>
        </main>
      </div>
      <FooterComponent />
    </div>
  </template>
  
  <script>
  import HeaderComponent from './HeaderComponent.vue';
  import FooterComponent from './FooterComponent.vue';
  import SidebarComponent from './SidebarComponent.vue';
  
  export default {
    components: {
      HeaderComponent,
      FooterComponent,
      SidebarComponent
    }
  };
  </script>
  
  <style>
  /* Add your styles here */
  </style>
  