import axios from 'axios';

const instance = axios.create({
  baseURL: 'http://127.0.0.1:8000',
});

// Add a request interceptor to include the api_token
instance.interceptors.request.use(
  config => {
    const api_token = localStorage.getItem('api_token');
    const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
    console.log('Retrieved token:', api_token); // Debug log for retrieved token
    console.log('Retrieved user_id:', user_id); // Debug log for retrieved user_id
    if (api_token) {
      config.headers.Authorization = `Bearer ${api_token}`;
      console.log('Attaching token:', api_token); // Debug log for token
    }

    if (user_id) {
      config.headers['User-ID'] = user_id; // Assuming user_id is sent via a custom header X-User-ID
      console.log('Attaching user_id:', user_id); // Debug log for user_id
    }


    return config;
  },
  error => {
    console.error('Request error:', error); // Debug log for request errors
    return Promise.reject(error);
  }
);

export default instance;
