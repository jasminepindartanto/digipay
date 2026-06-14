<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPay Login</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            overflow:hidden;

            background:
                radial-gradient(circle at top left,#ff6b6b,transparent 35%),
                radial-gradient(circle at bottom left,#ff9f43,transparent 30%),
                radial-gradient(circle at center,#c445ff,transparent 40%),
                radial-gradient(circle at right,#2e86de,transparent 40%),
                #0f172a;
        }

        .login-wrapper{
            width:100%;
            display:flex;
            flex-direction:column;
            align-items:center;
        }

        .glass-card{

            width:450px;
            padding:80px 40px 40px;

            border-radius:30px;

            background:rgba(255,255,255,.08);

            backdrop-filter:blur(25px);

            border:1px solid rgba(255,255,255,.15);

            box-shadow:
                0 20px 50px rgba(0,0,0,.25);

            position:relative;
        }

        .avatar{

            width:120px;
            height:120px;

            border-radius:50%;

            background:#0f2d6b;

            border:4px solid white;

            position:absolute;

            top:-60px;
            left:50%;

            transform:translateX(-50%);

            display:flex;
            align-items:center;
            justify-content:center;

            color:white;
            font-size:55px;
        }

        .input-group{

            background:#24344d;

            border-radius:18px;

            padding:18px 20px;

            margin-bottom:20px;

            display:flex;
            align-items:center;
            gap:15px;
        }

        .input-group i{
            color:#cbd5e1;
            font-size:18px;
        }

        .input-group input{

            border:none;
            outline:none;

            background:transparent;

            width:100%;

            color:white;

            font-size:16px;
        }

        .input-group input::placeholder{
            color:#cbd5e1;
        }

        .options{

            display:flex;
            justify-content:space-between;
            align-items:center;

            margin-top:10px;

            color:white;
            font-size:14px;
        }

        .options a{
            color:#d6d6ff;
            text-decoration:none;
        }

        .login-btn{

            width:450px;

            margin-top:25px;

            border:none;

            border-radius:20px;

            padding:18px;

            background:#173ea5;

            color:white;

            font-size:20px;
            font-weight:700;

            cursor:pointer;

            box-shadow:
                0 10px 25px rgba(0,0,0,.25);

            transition:.3s;
        }

        .login-btn:hover{

            transform:translateY(-2px);

            background:#1f4bc8;
        }

        .error{

            color:#ffb4b4;
            margin-bottom:15px;
            text-align:center;
        }

    </style>

</head>
<body>

    @yield('content')

</body>
</html>