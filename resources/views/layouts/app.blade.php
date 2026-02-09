<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="/students">Student Management</a>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

</body>
</html>
