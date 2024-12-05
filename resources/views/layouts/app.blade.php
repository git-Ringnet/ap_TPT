<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Thêm jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <!-- Thêm Alpinejs -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.6/dist/cdn.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
