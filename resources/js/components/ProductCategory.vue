<template>
    <div>
        <h2>Add Product Category</h2>
        <div v-if="message" :class="`alert alert-${messageType}`" role="alert">
            {{ message }}
        </div>
        <form @submit.prevent="submitForm">
            <div class="mb-3">
                <label for="categoryName" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="categoryName" v-model="categoryName" />
                <span class="text-danger" v-if="errors.categoryName">{{ errors.categoryName }}</span>
            </div>

            <div class="mb-3">
                <label for="categoryOrder" class="form-label">Order</label>
                <input type="number" class="form-control" id="categoryOrder" v-model="categoryOrder" />
                <span class="text-danger" v-if="errors.categoryOrder">{{ errors.categoryOrder }}</span>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Category</button>
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
                            <button class="btn btn-primary btn-sm me-2" @click="editCategory(category)">Edit</button>
                            <button class="btn btn-danger btn-sm" @click="deleteCategory(category.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from '../axios';

export default {
    name: 'ProductCategory',
    data() {
        return {
            categoryName: '',
            categoryOrder: 0,
            categories: [],
            errors: {},
            message: '',
            messageType: ''
        };
    },
    methods: {
        async submitForm() {
            this.errors = {};  // Clear previous errors
            this.message = ''; // Clear previous messages

            // Basic front-end validation
            if (!this.categoryName) {
                this.errors.categoryName = 'Category name is required';
            }
            if (!this.categoryOrder || this.categoryOrder <= 0) {
                this.errors.categoryOrder = 'Order must be a positive number';
            }

            if (Object.keys(this.errors).length === 0) {
                const newCategory = {
                    name: this.categoryName,
                    order: this.categoryOrder
                };

                try {
                    const response = await axios.post('/api/addcategory', newCategory);
                    console.log('API Response:', response.data);

                    if (response.data.type === 'success') {
                        this.categoryName = '';
                        this.categoryOrder = 0; // Reset categoryOrder to 0
                        this.showMessage('Category added successfully!', 'success');
                        this.fetchCategories(); // Refresh the category list
                    } else {
                        this.showMessage('Failed to add category. Please try again.', 'danger');
                    }
                } catch (error) {
                    console.error('API error:', error.response || error.message || error);
                    this.showMessage('Failed to add category. Please try again.', 'danger');
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
        async fetchCategories() {
            try {
                const response = await axios.get('/api/getcategories');
                this.categories = response.data.response.categories;
                console.log(this.categories);
            } catch (error) {
                console.error('Error fetching categories:', error.response || error.message || error);
            }
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
