<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>

    <title>Sistema Electoral</title>

    <style>
        body {
            font-family: Arial;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 5px 0;
        }

        .sidebar a:hover {
            background: #34495e;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }
    </style>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body>



    <div class="content">

        @yield('contenido')

    </div>

</body>

</html>