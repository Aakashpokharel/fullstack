<template>
  <div>
    <h2 v-if="editMode">Edit Product Category</h2>
    <h2 v-else>Add Product Category</h2>
    <div v-if="message" :class="`alert alert-${messageType}`" role="alert">
      {{ message }}
    </div>

    <form @submit.prevent="submitForm">
      <div class="mb-3">
        <label for="categoryName" class="form-label">Category Name</label>
        <input type="text" class="form-control" id="categoryName" placeholder="eg: Laptop/Mobile"
          v-model="categoryName" />
        <span class="text-danger" v-if="errors.categoryName">{{ errors.categoryName }}</span>
      </div>

      <div class="mb-3">
        <label for="categoryOrder" class="form-label">Order</label>
        <input type="text" class="form-control" id="categoryOrder" v-model="categoryOrder" />
        <span class="text-danger" v-if="errors.categoryOrder">{{ errors.categoryOrder }}</span>
      </div>

      <button type="submit" class="btn btn-primary">{{ editMode ? 'Update Category' : 'Add Category' }}</button>
      <button v-if="editMode" type="button" class="btn btn-secondary" @click="cancelEdit">Cancel</button>
    </form>
    <hr>
    <div class="container mt-4">
      <h3 class="mb-4">Category List</h3>
      <table class="table table-striped table-bordered">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Order</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in categories" :key="category.id">
            <td>{{ category.name }}</td>
            <td>{{ category.order }}</td>
            <td>
              <button class="btn text-primary btn-sm me-2" @click="editCategory(category)"><i
                  class="fas fa-edit"></i></button>

              <button class="btn text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                @click="confirmDelete(category.id)">
                <i class="fas fa-trash-alt"></i>
              </button>

            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
// import axios from '../axios';
import Swal from 'sweetalert2';
import CategoryService from './Service/CategoryService';

export default {
  name: 'ProductCategory',
  data() {
    return {
      categoryName: '',
      categoryOrder: 1,
      categories: [],
      editMode: false,
      editCategoryId: null,
      errors: {},
      message: '',
      messageType: ''
    };
  },
  methods: {
    async submitForm() {
      this.errors = {};
      this.message = '';

      if (!this.categoryName) {
        this.errors.categoryName = 'Category name is required';
      }
      if (!this.categoryOrder || this.categoryOrder <= 0) {
        this.errors.categoryOrder = 'Order must be a positive number';
      }

      if (Object.keys(this.errors).length === 0) {
        const categoryData = {
          id: this.editCategoryId,
          name: this.categoryName,
          order: this.categoryOrder
        };

        try {
          const response = await CategoryService.addCategory(categoryData);
          if (response.type === 'success') {
            this.categoryName = '';
            this.categoryOrder = 1;
            this.editMode = false;
            this.editCategoryId = null;
            this.showMessage(response.message, 'success');
            this.fetchCategories();
          } else {
            this.showMessage(response.message, 'danger');
          }
        } catch (error) {
          console.error('API error:', error.response || error.message || error);
          this.showMessage('Failed to save category. Please try again.', 'danger');
        }
      }
    },

    showMessage(message, type) {
      this.message = message;
      this.messageType = type;
      setTimeout(() => {
        this.message = '';
      }, 3000);
    },

    // Fetch Categories
    async fetchCategories() {
      try {
        const categories = await CategoryService.getCategories();
        this.categories = categories;
      } catch (error) {
        console.error('Error fetching categories:', error.response || error.message || error);
      }
    },

    // Edit Category
    editCategory(category) {
      this.editMode = true;
      this.editCategoryId = category.id;
      this.categoryName = category.name;
      this.categoryOrder = category.order;
    },
  
    // Confirm Delete
    confirmDelete(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this category!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          // console.log(this.deleteCategoryId);
          this.deleteCategory(id);
        }
      });
    },

    // Delete Category
    async deleteCategory(id) {
      try {
        const response = await CategoryService.deleteCategory(id);
        if (response.type === 'success') {
          this.showMessage(response.message, 'success');
          this.fetchCategories();
        } else {
          this.showMessage(response.message, 'danger');
        }
      } catch (error) {
        console.error('API error:', error.response || error.message || error);
        this.showMessage('Failed to delete category. Please try again.', 'danger');
      }
    },

    // Cancel Edit
    cancelEdit() {
      this.editMode = false;
      this.editCategoryId = null;
      this.categoryName = '';
      this.categoryOrder = 1;
    }
  },

  mounted() {
    this.fetchCategories();
  }
};
</script>

<style scoped>
.text-danger {
  color: red;
}

.container {
  max-width: 800px;
}

.table {
  margin-top: 20px;
}

.btn {
  margin-right: 5px;
}
</style>