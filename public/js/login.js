 new Vue({
        el: '#app',
        data: {
            email: '',
            password: '',
            showPassword: false,
            errorMessage: ''
        },
        methods: {
            login() {

                this.email = this.email.toLowerCase(); // convert before validation

                //frontend validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

                if (!emailRegex.test(this.email)) {
                    this.errorMessage = 'Invalid email format.';
                    return;
                }

                if (!passwordRegex.test(this.password)) {
                    this.errorMessage = 'Password must contain at least 1 uppercase, 1 lowercase, 1 number, 1 symbol and be at least 6 characters long.';
                    return;
                }

                this.errorMessage = ''; 

                //login request
                axios.post('/api/login', {
                    email: this.email,
                    password: this.password
                },
                {
                    headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest",
                    //"X-API-KEY": "api123"
                    }
                })
                .then(response => {
                    const token = response.data.token;
                    localStorage.setItem('token', token);
                   window.location.href = '/home'; // redirect after login
                   

                })
                .catch(error => {
                    this.errorMessage = error.response?.data?.error || 'Email or Password is Incorrect';
                });
            },
            togglePassword() {
            this.showPassword = !this.showPassword;
            }
        }
    });