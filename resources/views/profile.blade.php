<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="container mt-5" id="app">
        <div class="card">
            <div class="card-header">My Profile</div>
            <div class="card-body">
            <p><strong>Username:</strong> @{{ user.username }}</p>
            <p><strong>Email:</strong> @{{ user.email }}</p>
            <p><strong>Phone:</strong> @{{ user.phone_number }}</p>
                <h5 class="mt-4">Change Password</h5>
                <input type="password" class="form-control" v-model="current_password" placeholder="Current Password"><br>
                <input type="password" class="form-control" v-model="new_password" placeholder="New Password"><br>
                <input type="password" class="form-control" v-model="new_password_confirmation" placeholder="Confirm New Password"><br>
                <button class="btn btn-success" @click="changePassword">Change Password</button>
                <a class="btn btn-secondary" href="/home">Back</a>
                <p class="text-success mt-2" v-if="message">@{{ message }}</p>
                <p class="text-danger mt-2" v-if="error">@{{ error }}</p>
            </div>
        </div>
    </div>

    <!-- Profile Vue script -->
    <script>
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
    </script>
</body>
</html>
