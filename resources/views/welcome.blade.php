<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaraMS - Learning Management System</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #AEDEFC;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .circle1,
        .circle2 {
            position: absolute;
            border-radius: 50%;
            opacity: 0.25;
            background: white;
        }

        .circle1 {
            width: 180px;
            height: 180px;
            top: 60px;
            left: -40px;
        }

        .circle2 {
            width: 250px;
            height: 250px;
            bottom: -60px;
            right: -50px;
        }

        h1 {
            font-size: 50px;
            font-weight: 700;
            color: #1d1d1d;
            margin-bottom: 10px;
        }

        .tagline {
            font-size: 18px;
            font-weight: 300;
            color: #3f3f3f;
            margin-bottom: 6px;
            max-width: 450px;
        }

        .btn-box {
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 14px 40px;
            margin: 10px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 17px;
            font-weight: 600;
            transition: 0.25s ease-in-out;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-login {
            background-color: #8CA9FF;
            color: #fff;
        }

        .btn-login:hover {
            background-color: #3B7AF0;
            transform: translateY(-3px);
        }

        .btn-register {
            background-color: #8CA9FF;
            color: #fff;
        }

        .btn-register:hover {
            background-color: #3B7AF0;
            transform: translateY(-3px);
        }

        footer {
            margin-top: 50px;
            font-size: 13px;
            color: #333;
            opacity: 0.7;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="circle1"></div>
        <div class="circle2"></div>

        <h1>LaraMS</h1>
        <p class="tagline">
            - Learning Management System -
        </p>
        <p class="tagline">
            Mulai perjalananmu dengan akses cepat dan mudah.
        </p>

        <div class="btn-box">
            {{--
            TODO: Integrasi dengan route login dan register
            Route: route('login'), route('register')
            --}}
            <a href="{{ route('login') }}" class="btn btn-login">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-register">Daftar</a>
        </div>

        <footer>
            © {{ date('Y') }} LaraMS — Learning Web Platform
        </footer>

    </div>

</body>

</html>
