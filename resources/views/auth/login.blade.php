<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - EduPay</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f4f6f9;
    font-family:'Segoe UI',sans-serif;
}

.login-wrapper{
    width:100%;
    max-width:420px;
}

.login-card{
    background:rgba(255,255,255,0.9);
    backdrop-filter:blur(10px);
    border-radius:25px;
    padding:40px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
}

.avatar{
    width:90px;
    height:90px;
    margin:auto;
    margin-top:-80px;
    margin-bottom:25px;
    border-radius:50%;
    background:#1e3a8a;
    display:flex;
    justify-content:center;
    align-items:center;
    border:4px solid white;
}

.avatar svg{
    color:white;
}

.form-control{
    height:55px;
    border-radius:15px;
    background:#e9eef5;
    border:none;
    padding-left:45px;
}

.input-group{
    position:relative;
}

.input-icon{
    position:absolute;
    left:15px;
    top:50%;
    transform:translateY(-50%);
    z-index:10;
    color:#6c757d;
}

.remember-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:15px;
    margin-bottom:20px;
}

.login-btn{
    width:100%;
    height:55px;
    border:none;
    border-radius:15px;
    background:#1e3a8a;
    color:white;
    font-weight:bold;
    letter-spacing:1px;
    transition:.3s;
}

.login-btn:hover{
    background:#163172;
}

.title{
    text-align:center;
    margin-bottom:25px;
    font-weight:700;
    color:#1e293b;
}

</style>

</head>
<body>

<div class="login-wrapper">

    <div class="login-card">

        <div class="avatar">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="40"
                 height="40"
                 fill="currentColor"
                 class="bi bi-person"
                 viewBox="0 0 16 16">

                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>

                <path d="M14 14s-1-4-6-4-6 4-6 4h12z"/>

            </svg>

        </div>

        <h3 class="title">
            Login
        </h3>

        <form method="POST" action="{{ route('login') }}">

            @csrf

            <div class="mb-3 input-group">

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Email"
                    required
                >

            </div>

            <div class="mb-3 input-group">

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Password"
                    required
                >

            </div>

            <div class="remember-row">

                <div>

                    <input
                        type="checkbox"
                        name="remember"
                    >

                    Remember me

                </div>

                <a href="#">
                    Forgot Password?
                </a>

            </div>

            @error('email')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <button type="submit" class="login-btn">

                LOGIN

            </button>

        </form>

    </div>

</div>

</body>
</html>