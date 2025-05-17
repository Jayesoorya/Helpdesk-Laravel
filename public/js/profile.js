 new Vue({
            el: '#app',
            data: {
                user: {},
                current_password: '',
                new_password: '',
                new_password_confirmation: '',
                message: '',
                error: ''
            },
            mounted() {
                this.fetchUser();
            },
            methods: {
                fetchUser() {
                    const token = localStorage.getItem('token');
                    axios.get('/api/user', {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        this.user = response.data.user;
                        console.log('User:', this.user);

                    })
                    .catch(() => {
                        this.error = 'Failed to load user';
                    });
                },
                changePassword() {
                    const token = localStorage.getItem('token');
                    axios.post('/api/change-password', {
                        current_password: this.current_password,
                        new_password: this.new_password,
                        new_password_confirmation: this.new_password_confirmation
                    }, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    })
                    .then(response => {
                        this.message = response.data.message;
                        this.error = '';
                        this.current_password = '';
                        this.new_password = '';
                        this.new_password_confirmation = '';
                    })
                    .catch(error => {
                        this.error = error.response?.data?.message || 'Error updating password';
                        this.message = '';
                    });
                }
            }
        });