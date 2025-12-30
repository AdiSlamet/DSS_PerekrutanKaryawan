// class AuthService {
//     constructor() {
//         this.baseUrl = 'http://localhost:8000/api';
//         this.token = localStorage.getItem('auth_token');
//         this.user = JSON.parse(localStorage.getItem('auth_user') || 'null');
//     }

//     async login(email, password) {
//         try {
//             const response = await fetch(`${this.baseUrl}/login`, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'Accept': 'application/json'
//                 },
//                 body: JSON.stringify({ email, password })
//             });

//             const data = await response.json();

//             if (!response.ok) {
//                 throw new Error(data.message || 'Login failed');
//             }

//             // Save token and user data
//             localStorage.setItem('auth_token', data.data.token);
//             localStorage.setItem('auth_user', JSON.stringify(data.data.user));
            
//             this.token = data.data.token;
//             this.user = data.data.user;

//             return { success: true, data: data.data };
//         } catch (error) {
//             console.error('Login error:', error);
//             return { success: false, message: error.message };
//         }
//     }

//     async logout() {
//         try {
//             if (this.token) {
//                 await fetch(`${this.baseUrl}/logout`, {
//                     method: 'POST',
//                     headers: {
//                         'Authorization': `Bearer ${this.token}`,
//                         'Accept': 'application/json'
//                     }
//                 });
//             }
//         } catch (error) {
//             console.error('Logout error:', error);
//         } finally {
//             // Clear local storage
//             localStorage.removeItem('auth_token');
//             localStorage.removeItem('auth_user');
//             this.token = null;
//             this.user = null;
            
//             // Redirect to login page
//             window.location.href = '/';
//         }
//     }

//     getAuthHeaders() {
//         return {
//             'Authorization': `Bearer ${this.token}`,
//             'Content-Type': 'application/json',
//             'Accept': 'application/json'
//         };
//     }

//     isAuthenticated() {
//         return !!this.token;
//     }

//     getUser() {
//         return this.user;
//     }

//     getToken() {
//         return this.token;
//     }
// }

// // Global auth instance
// window.authService = new AuthService();