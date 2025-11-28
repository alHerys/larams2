{{-- filepath: resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'LaraMS' }} - Learning Management System</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{--
    TODO: Integrasi dengan Vite
    Uncomment baris di bawah jika sudah setup Vite:
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    --}}

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #AEDEFC;
            font-family: 'Poppins', Arial, sans-serif;
            min-height: 100vh;
        }

        .auth-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .auth-card {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .auth-card h2 {
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: 600;
            color: #1d1d1d;
        }

        .form-group {
            width: 100%;
            margin-bottom: 15px;
        }

        .input-box {
            width: 100%;
            padding: 14px 16px;
            border: none;
            background: #e5e5e5;
            font-size: 14px;
            border-radius: 5px;
            transition: background 0.2s;
        }

        .input-box:focus {
            outline: none;
            background: #d9d9d9;
        }

        .input-box::placeholder {
            color: #888;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: #8CA9FF;
            color: #1d1d1d;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            transition: background 0.2s, transform 0.1s;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background: #7a9af0;
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .auth-link {
            display: block;
            margin-top: 20px;
            color: #1d1d1d;
            font-size: 14px;
            text-decoration: underline;
            transition: opacity 0.2s;
        }

        .auth-link:hover {
            opacity: 0.7;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 13px;
            margin-bottom: 15px;
            text-align: left;
        }

        .error-message ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-text {
            color: #dc2626;
            font-size: 12px;
            text-align: left;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>
</body>

</html>