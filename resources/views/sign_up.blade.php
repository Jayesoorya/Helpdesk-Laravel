<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container" id="app">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Sign Up</h2>
                    <form @submit.prevent="register">
                        <label>Username:</label>
                        <input type="text" class="form-control" v-model="form.username" required>

                        <label>Password:</label>
                        <input type="password" class="form-control" v-model="form.password" required>

                        <label>Email:</label>
                        <input type="email" class="form-control" v-model="form.email" required>

                        <label>Phone:</label>
                        <input type="text" class="form-control" v-model="form.phone_number" required>

                        <button class="btn btn-info mt-4" type="submit">Register</button>
                    </form>
                    <br>
                    <a class="btn btn-secondary" href="{{route('login')}}">Back to Login</a>
                    <p v-if="message">@{{ message }}</p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <script src="resources/js/app/sign_up.js"></script> -->

<!-- Vue + Axios CDN -->
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
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

</script>
