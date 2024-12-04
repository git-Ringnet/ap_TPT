<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ')</title>
    <!-- Thêm các CSS chung cho toàn bộ trang -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <!-- Bao gồm phần header -->
    @include('partials.header')

    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <!-- Nội dung trang con sẽ được chèn vào đây -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bao gồm phần footer -->
    @include('partials.footer')

    <!-- Thêm JS chung cho toàn bộ trang -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
