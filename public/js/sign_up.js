new Vue({
    el: '#app',
    data: {
        form: {
            username: '',
            password: '',
            email: '',
            phone_number: ''
        },
        message: ''
    },
    methods: {
        register() {
            axios.post('/api/register', this.form)
                .then(response => {
                    this.message = "Registration successful!";
                    // optionally redirect
                    // window.location.href = "/login";
                })
                .catch(error => {
                    if (error.response && error.response.data) {
                        this.message = error.response.data.message || 'Registration failed';
                    } else {
                        this.message = 'Registration failed';
                    }
                });
        }
    }
});