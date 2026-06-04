<!DOCTYPE html>
<html>

<head>
    <title>Student Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Container */
        .container {
            max-width: 1200px;
        }

        /* Card style for pages */
        .content-box {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        /* Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 500;
        }

        /* Inputs */
        .form-control,
        .form-select {
            border-radius: 10px;
        }

        /* Table */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table-dark {
            background: #212529 !important;
        }

        /* Hover effect */
        .table tbody tr:hover {
            background: #f1f5ff;
            transition: 0.2s;
        }

        /* Footer spacing */
        footer {
            margin-top: 40px;
            text-align: center;
            color: #888;
            font-size: 14px;
        }
    </style>

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/students">
                🎓 Student Management System
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">

        <div class="content-box">
            @yield('content')
        </div>

    </div>


</body>

</html>