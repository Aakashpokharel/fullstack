import axios from "../../axios";

const CategoryService = {
    async addCategory(categoryData){
        try {
            const response = await axios.post('/api/addcategory', categoryData);
            return response.data;
          } catch (error) {
            throw new Error(error.response.data.message || error.message);
          }
    },
     // Add other category-related methods
  async getCategories() {
    try {
      const response = await axios.get('/api/getcategories');
      return response.data.response.categories;
    } catch (error) {
      throw new Error(error.response.data.message || error.message);
    }
  },

   async deleteCategory(id) {
    try {
      const response = await axios.post('/api/deletecategory', { id });
      return response.data;
    } catch (error) {
      throw new Error(error.response.data.message || error.message);
    }
  },
};

export default CategoryService;