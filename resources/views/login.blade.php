<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>

<div class="container" id="app">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
           <div class="card">
               <div class="card-body">
                   <h1 class="text-center mb-4">HELPDESK</h1>

                   <div v-if="errorMessage" class="alert alert-danger">
                       @{{ errorMessage }}
                   </div>

                   <h4 class="mb-3">Login</h4>
                   <div class="form-group">
                       <label>Email</label>
                       <input class="form-control" v-model="email" type="email" placeholder="Enter Email" required>
                   </div>

                   <div class="form-group">
                       <label>Password</label>
                       <div class="input-group">
                            <input :type="showPassword ? 'text' : 'password'" class="form-control" v-model="password" placeholder="Password">
                            <div class="input-group-append">
                                <span class="input-group-text" @click="togglePassword">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </span>
                            </div>
                        </div>
                   </div>

                   <button class="btn btn-info btn-block" @click="login">Login</button>

                   <p class="mt-3 text-center">
                       Don't have an account?
                       <a href="{{ route('register') }}">Sign up here</a>
                   </p>
               </div>
           </div>
        </div>
    </div>
</div>

<!-- Vue + Axios CDN -->
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- Vue Login Logic -->
<script src="/js/login.js"></script>


</body>
</html>
