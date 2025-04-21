<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

    }

    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        padding: 10rem;
        background: #f0f2f5;
    }

    /* Minimalist Circle Form */
    .circle-form {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        position: relative;
    }

    .circle-form::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: #4CAF50;
        border-radius: 50%;
        z-index: -1;
        opacity: 0.1;
    }

    .circle-form::after {
        content: '';
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 150px;
        height: 150px;
        background: #4CAF50;
        border-radius: 50%;
        z-index: -1;
        opacity: 0.1;
    }

    .circle-form form {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .circle-form input {
        width: 100%;
        padding: 1rem 1.5rem;
        margin: 0.7rem 0;
        border: none;
        background: #f8f9fa;
        border-radius: 25px;
        outline: none;
        transition: background 0.3s;
    }

    .circle-form input:focus {
        background: #eef1f4;
    }

    .circle-form button {
        width: 100%;
        padding: 1rem;
        margin-top: 1.5rem;
        border: none;
        border-radius: 25px;
        background: #4CAF50;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .circle-form button:hover {
        transform: scale(1.02);
    }
</style>

<body>

    <!-- Minimalist Circle Form -->
    <div class="circle-form">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('login') }}" method="post">
            {{ csrf_field() }}
            {{-- @if (count($errors))
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                        {{ $error }}
                    </div>
                @endforeach
            @endif --}}
            <h2>Welcome</h2>
            <input type="text" placeholder="Email" name="email" required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit">Sign In</button>
        </form>
    </div>
</body>

</html>
