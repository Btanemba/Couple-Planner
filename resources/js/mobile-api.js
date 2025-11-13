// Mobile API Client for Couple Planner
class CoupleAPI {
    constructor() {
        this.baseURL = 'https://couple-planner.vercel.app/api';
        this.token = localStorage.getItem('auth_token');
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;

        const config = {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        };

        if (this.token) {
            config.headers['Authorization'] = `Bearer ${this.token}`;
        }

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Request failed');
            }

            return data;
        } catch (error) {
            console.error('API Request failed:', error);
            throw error;
        }
    }

    async register(userData) {
        const response = await this.request('/register', {
            method: 'POST',
            body: JSON.stringify(userData)
        });

        if (response.success && response.token) {
            this.setToken(response.token);
        }

        return response;
    }

    async login(credentials) {
        const response = await this.request('/login', {
            method: 'POST',
            body: JSON.stringify(credentials)
        });

        if (response.success && response.token) {
            this.setToken(response.token);
        }

        return response;
    }

    setToken(token) {
        this.token = token;
        localStorage.setItem('auth_token', token);
    }

    removeToken() {
        this.token = null;
        localStorage.removeItem('auth_token');
    }

    isAuthenticated() {
        return !!this.token;
    }
}

// Initialize API client
window.coupleAPI = new CoupleAPI();

// Handle registration form
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('form[action*="register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const userData = {
                name: formData.get('name'),
                email: formData.get('email'),
                password: formData.get('password')
            };

            try {
                const response = await window.coupleAPI.register(userData);

                if (response.success) {
                    alert('Registration successful!');
                    window.location.href = '/dashboard';
                } else {
                    alert('Registration failed: ' + response.message);
                }
            } catch (error) {
                alert('Registration error: ' + error.message);
            }
        });
    }

    // Handle login form
    const loginForm = document.querySelector('form[action*="login"]');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const credentials = {
                email: formData.get('email'),
                password: formData.get('password')
            };

            try {
                const response = await window.coupleAPI.login(credentials);

                if (response.success) {
                    alert('Login successful!');
                    window.location.href = '/dashboard';
                } else {
                    alert('Login failed: ' + response.message);
                }
            } catch (error) {
                alert('Login error: ' + error.message);
            }
        });
    }
});
