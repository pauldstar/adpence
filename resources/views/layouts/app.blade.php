<html lang="en">
<head>
    <title>CashClickz - @yield('title')</title>
</head>
<body>
@section('sidebar')
    <p>This is the master sidebar.</p>
@endsection

<div class="container">
    @yield('content')
</div>
</body>
</html>
