<!-- resources/views/partials/header.blade.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $title ?? 'Trang chủ')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('dist/img/icon/favicon.ico') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <script src="https://kit.fontawesome.com/774b78ff1e.js" crossorigin="anonymous"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Việt css -->
    <link rel="stylesheet" href="{{ asset('dist/css/css.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script src="{{ asset('dist/js/scripts.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.css">
    <script src="https://cdn.jsdelivr.net/npm/n2words@1.21.0/dist/n2words.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <!-- Thêm các CSS chung cho toàn bộ trang -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Thêm jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <!-- Thêm Alpinejs -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <!-- /.navbar -->
        <div class="header-fixed border-bottom-0">
            <!-- Main Sidebar Container -->
            <div class="d-flex align-items-center justify-content-between w-100 height-47" id="head-nav">
                <div class="logo-tpt align-baseline">
                    <img src="{{ asset('images/loto-tpp.png') }}" alt="" width="148px" height="54px">
                </div>
                <div class="d-flex content__heading--right flex-grow-1 justify-content-center">
                    <div class="dropdown">
                        <a class="text-white justify-content-center align-items-center mx-3 px-1 font-weight-600 navbar-head @if (!empty($activeGroup) && $activeGroup == 'systemFirst') active-navbar @endif"
                            href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                            THIẾT LẬP
                        </a>
                        <div class="dropdown-menu" style="">
                            <a class="dropdown-item text-13-black" href="{{ route('groups.index') }}">Nhóm
                                đối tượng</a>
                            <a class="dropdown-item text-13-black" href="{{ route('customers.index') }}">Khách hàng</a>
                            <a class="dropdown-item text-13-black" href="{{ route('providers.index') }}">Nhà cung
                                cấp</a>
                            <a class="dropdown-item text-13-black" href="{{ route('products.index') }}">Hàng hoá</a>
                            <a class="dropdown-item text-13-black" href="{{ route('users.index') }}">Nhân viên</a>
                            <a class="dropdown-item text-13-black" href="{{ route('warehouses.index') }}">Kho</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="text-white justify-content-center align-items-center mx-3 px-1 font-weight-600 navbar-head @if (!empty($activeGroup) && $activeGroup == 'manageProfess') active-navbar @endif"
                            href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                            NGHIỆP VỤ
                        </a>
                        <div class="dropdown-menu" style="">
                            <a class="dropdown-item text-13-black" href="{{ route('imports.index') }}">Phiếu nhập hàng
                            </a>
                            <a class="dropdown-item text-13-black" href="{{ route('exports.index') }}">Phiếu xuất hàng
                            </a>
                            <a class="dropdown-item text-13-black" href="{{ route('inventoryLookup.index') }}">
                                Tra cứu tồn kho
                            </a>
                            <a class="dropdown-item text-13-black" href="#">Tra cứu bảo hành
                            </a>
                            <a class="dropdown-item text-13-black" href="{{ route('receivings.index') }}">Phiếu tiếp
                                nhận
                            </a>
                            <a class="dropdown-item text-13-black" href="{{ route('quotations.index') }}">Phiếu báo giá
                            </a>
                            <a class="dropdown-item text-13-black" href="{{ route('returnforms.index') }}">Phiếu trả
                                hàng
                            </a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="text-white justify-content-center align-items-center mx-3 px-1 font-weight-600 navbar-head @if (!empty($activeGroup) && $activeGroup == 'statistic') active-navbar @endif"
                            href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                            BÁO CÁO
                        </a>
                        <div class="dropdown-menu" style="">
                            <a class="dropdown-item text-13-black" href="#">Công nợ khách hàng
                            </a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <div class="list-noti-container">
                        <a class="nav-link position-relative mr-3 notification-icon" id="notification-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.4487 20.584C15.8913 20.584 16.25 20.934 16.25 21.3657V21.746C16.25 21.7459 16.25 21.746 16.25 21.746C16.2501 22.5868 15.9079 23.3935 15.2986 23.9882C14.6892 24.5829 13.8627 24.9171 13.0007 24.9173C12.1388 24.9175 11.312 24.5836 10.7024 23.9891C10.0928 23.3947 9.75019 22.5883 9.75 21.7474V21.3667C9.75 20.935 10.1087 20.585 10.5513 20.585C10.9938 20.585 11.3526 20.935 11.3526 21.3667V21.747C11.3526 21.747 11.3526 21.7471 11.3526 21.747C11.3527 22.1732 11.5264 22.5821 11.8353 22.8834C12.1444 23.1847 12.5634 23.354 13.0004 23.3539C13.4373 23.3538 13.8563 23.1844 14.1652 22.8829C14.4741 22.5814 14.6475 22.1726 14.6474 21.7463V21.3657C14.6474 20.934 15.0062 20.584 15.4487 20.584Z"
                                    fill="white" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M13.3309 1.08398C12.9779 1.08398 12.6283 1.14736 12.3021 1.27049C11.976 1.39363 11.6796 1.5741 11.43 1.80162C11.1804 2.02914 10.9824 2.29925 10.8473 2.59651C10.7122 2.89378 10.6426 3.21239 10.6426 3.53415V4.40495C10.6426 4.48385 10.6567 4.55976 10.6828 4.63069C10.333 4.71136 9.98902 4.81798 9.65408 4.95008C8.80508 5.28494 8.02748 5.77823 7.36807 6.40631C6.70845 7.03458 6.17972 7.78585 5.81713 8.6199C5.45448 9.45407 5.26647 10.3523 5.26661 11.2614L5.26647 13.9464L5.26646 14.225C5.26646 14.225 5.26581 14.2277 5.26454 14.2306C5.26212 14.2362 5.25559 14.248 5.24044 14.2624L4.52767 14.9413C3.72242 15.7081 3.25025 16.77 3.25 17.899C3.25 18.9974 3.70899 20.0301 4.49138 20.7753C5.27043 21.5173 6.30699 21.9181 7.36846 21.9181H19.2935C19.8211 21.9181 20.3456 21.8192 20.838 21.6249C21.3304 21.4307 21.784 21.1435 22.1706 20.7753C22.5574 20.4069 22.8697 19.9642 23.0847 19.4698C23.2998 18.9751 23.412 18.4412 23.412 17.8995C23.4117 16.7704 22.9398 15.7084 22.1345 14.9415L21.4215 14.2624C21.4064 14.248 21.3999 14.2362 21.3974 14.2306C21.3968 14.2292 21.3964 14.2279 21.3961 14.2269C21.3957 14.2258 21.3955 14.225 21.3955 14.225L21.3954 11.2614C21.3955 10.3523 21.2075 9.45407 20.8448 8.6199C20.4822 7.78585 19.9535 7.03458 19.2939 6.40631C18.6345 5.77823 17.8569 5.28494 17.0079 4.95008C16.6729 4.81796 16.3289 4.71133 15.9791 4.63066C16.0051 4.55974 16.0192 4.48384 16.0192 4.40495V3.53415C16.0192 3.21239 15.9496 2.89378 15.8145 2.59651C15.6794 2.29925 15.4814 2.02914 15.2318 1.80162C14.9822 1.5741 14.6858 1.39363 14.3597 1.27049C14.0335 1.14736 13.6839 1.08398 13.3309 1.08398ZM14.4593 4.44531C14.4585 4.43195 14.458 4.41849 14.458 4.40495V3.53415C14.458 3.39925 14.4289 3.26566 14.3722 3.14102C14.3156 3.01638 14.2326 2.90313 14.1279 2.80774C14.0232 2.71234 13.899 2.63667 13.7622 2.58505C13.6255 2.53342 13.4789 2.50685 13.3309 2.50685C13.1829 2.50685 13.0363 2.53342 12.8996 2.58505C12.7628 2.63667 12.6386 2.71234 12.5339 2.80774C12.4292 2.90313 12.3462 3.01638 12.2896 3.14102C12.2329 3.26566 12.2038 3.39925 12.2038 3.53415V4.40495C12.2038 4.4185 12.2034 4.43196 12.2025 4.44532C12.2426 4.44467 12.2826 4.44434 12.3227 4.44435H14.3393C14.3793 4.44434 14.4193 4.44466 14.4593 4.44531ZM7.2829 11.231C7.28284 11.241 7.28281 11.251 7.28281 11.2609V14.2242C7.28268 14.7862 7.04822 15.325 6.631 15.7223L5.91801 16.4014C5.50078 16.7987 5.26632 17.3376 5.2662 17.8995C5.2662 18.4305 5.48768 18.9399 5.88194 19.3154C6.27619 19.6909 6.8109 19.9019 7.36846 19.9019H19.2935C19.5696 19.9019 19.843 19.8501 20.098 19.7494C20.3531 19.6488 20.5848 19.5013 20.78 19.3154C20.9752 19.1294 21.1301 18.9087 21.2357 18.6658C21.3414 18.4228 21.3958 18.1624 21.3958 17.8995C21.3956 17.3376 21.1612 16.7987 20.744 16.4014L20.031 15.7223C19.6137 15.325 19.3793 14.7862 19.3792 14.2242V11.2609C19.3793 10.6305 19.249 10.0062 18.9958 9.42374C18.7426 8.84127 18.3714 8.31202 17.9033 7.86624C17.4353 7.42045 16.8797 7.06686 16.2681 6.82567C15.6566 6.58448 15.0012 6.46041 14.3393 6.46054H12.3227C11.7398 6.46042 11.162 6.55661 10.6148 6.74427C10.5405 6.76973 10.4669 6.79686 10.3938 6.82567C9.7823 7.06686 9.22665 7.42045 8.75863 7.86624C8.2906 8.31202 7.91938 8.84127 7.66615 9.42374C7.41693 9.997 7.28682 10.6108 7.2829 11.231Z"
                                    fill="white" />
                            </svg>
                            <span class="badge badge-danger navbar-badge">3</span>
                        </a>
                        <div class="list-noti">
                            <ul class="nav nav-tabs p-2">
                                <li>
                                    <a class="text-secondary active mx-2 m-0 text-13" data-toggle="tab"
                                        href="#info">
                                        Tra cứu tồn kho
                                    </a>
                                </li>
                                <li>
                                    <a class="text-secondary m-0 mx-2 text-13" data-toggle="tab" href="#history">
                                        Phiếu tiếp nhận
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content overflow-auto" style="height: 280px;">
                                <div class="tab-pane fade show active" id="info">
                                    <a href="#"class="dropdown-item border-bottom rounded bg-white"><span>tới hạn
                                            bảo trì định kỳ</span></a>
                                </div>
                                <div class="tab-pane fade" id="history"></div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-noitifi pr-4">
                        <div class="setting">
                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M13 24.375C16.0168 24.375 18.9101 23.1766 21.0433 21.0433C23.1766 18.9101 24.375 16.0168 24.375 13C24.375 9.98316 23.1766 7.08989 21.0433 4.95666C18.9101 2.82343 16.0168 1.625 13 1.625C9.98316 1.625 7.08989 2.82343 4.95666 4.95666C2.82343 7.08989 1.625 9.98316 1.625 13C1.625 16.0168 2.82343 18.9101 4.95666 21.0433C7.08989 23.1766 9.98316 24.375 13 24.375ZM8.32162 17.6784L6.68038 19.3196C5.4304 18.0697 4.57915 16.4772 4.23426 14.7435C3.88937 13.0098 4.06634 11.2127 4.74279 9.57958C5.41923 7.94644 6.56478 6.55057 8.03455 5.56848C9.50432 4.5864 11.2323 4.06222 13 4.06222C14.7677 4.06222 16.4957 4.5864 17.9655 5.56848C19.4352 6.55057 20.5808 7.94644 21.2572 9.57958C21.9337 11.2127 22.1106 13.0098 21.7657 14.7435C21.4209 16.4772 20.5696 18.0697 19.3196 19.3196L17.6784 17.6784C17.2256 17.2255 16.688 16.8662 16.0964 16.6211C15.5047 16.376 14.8705 16.2499 14.2301 16.25H11.7699C11.1295 16.2499 10.4953 16.376 9.90364 16.6211C9.31198 16.8662 8.7744 17.2255 8.32162 17.6784Z"
                                    fill="white" />
                                <path
                                    d="M13 6.5C12.138 6.5 11.3114 6.84241 10.7019 7.4519C10.0924 8.0614 9.75 8.88805 9.75 9.75V10.5625C9.75 11.4245 10.0924 12.2511 10.7019 12.8606C11.3114 13.4701 12.138 13.8125 13 13.8125C13.862 13.8125 14.6886 13.4701 15.2981 12.8606C15.9076 12.2511 16.25 11.4245 16.25 10.5625V9.75C16.25 8.88805 15.9076 8.0614 15.2981 7.4519C14.6886 6.84241 13.862 6.5 13 6.5Z"
                                    fill="white" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="px-3 py-2 border-bottom border-top bg-grey @if (
                    (!empty($activeGroup) && $activeGroup == 'systemFirst') ||
                        (!empty($activeGroup) && $activeGroup == 'manageProfess') ||
                        (!empty($activeGroup) && $activeGroup == 'statistic')) d-block @else d-none @endif">
                <div class="@if (!empty($activeGroup) && $activeGroup == 'systemFirst') d-flex @else d-none @endif">
                    <a href="{{ route('groups.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2  @if (!empty($activeName) && $activeName == 'groups') active @endif ">
                            Nhóm đối tượng
                        </button>
                    </a>
                    <a href="{{ route('customers.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'customers') active @endif ">
                            Khách hàng
                        </button>
                    </a>
                    <a href="{{ route('providers.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'providers') active @endif">
                            Nhà cung cấp
                        </button>
                    </a>
                    <a href="{{ route('products.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'products') active @endif">
                            Hàng hóa
                        </button>
                    </a>
                    <a href="{{ route('users.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'users') active @endif">
                            Nhân viên
                        </button>
                    </a>
                    <a href="{{ route('warehouses.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'warehouses') active @endif">
                            Kho
                        </button>
                    </a>
                </div>
                <div class="@if (!empty($activeGroup) && $activeGroup == 'manageProfess') d-flex @else d-none @endif">
                    <a href="{{ route('imports.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2  @if (!empty($activeName) && $activeName == 'imports') active @endif ">
                            Phiếu nhập hàng
                        </button>
                    </a>
                    <a href="{{ route('exports.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'exports') active @endif">
                            Phiếu xuất hàng
                        </button>
                    </a>
                    <a href="{{ route('inventoryLookup.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'inventoryLookup') active @endif">
                            Tra cứu tồn kho
                        </button>
                    </a>
                    <a href="#" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2">
                            Tra cứu bảo hành
                        </button>
                    </a>
                    <a href="{{ route('receivings.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'receivings') active @endif">
                            Phiếu tiếp nhận
                        </button>
                    </a>
                    <a href="{{ route('quotations.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'quotations') active @endif">
                            Phiếu báo giá
                        </button>
                    </a>
                    <a href="{{ route('returnforms.index') }}" class="height-36">
                        <button type="button"
                            class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 @if (!empty($activeName) && $activeName == 'returnforms') active @endif">
                            Phiếu trả hàng
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="alert notification d-flex justify-content-center align-items-center m-0 w-100"
        style="position: absolute;top: 0;">
        <div class="success">
            @if (Session::has('msg'))
                <div id="notification" class="alert alert-success alert-dismissible fade show" role="alert"
                    style="z-index: 999999;">
                    <div class="icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 4.38462C7.79374 4.38462 4.38462 7.79374 4.38462 12C4.38462 16.2063 7.79374 19.6154 12 19.6154C16.2063 19.6154 19.6154 16.2063 19.6154 12C19.6154 7.79374 16.2063 4.38462 12 4.38462ZM3 12C3 7.02903 7.02903 3 12 3C16.971 3 21 7.02903 21 12C21 16.971 16.971 21 12 21C7.02903 21 3 16.971 3 12Z"
                                fill="#ffff" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M16.1818 9.66432C16.4522 9.93468 16.4522 10.373 16.1818 10.6434L11.5664 15.2588C11.2961 15.5291 10.8577 15.5291 10.5874 15.2588L7.81813 12.4895C7.54777 12.2192 7.54777 11.7808 7.81813 11.5105C8.08849 11.2401 8.52684 11.2401 8.7972 11.5105L11.0769 13.7902L15.2027 9.66432C15.4731 9.39396 15.9115 9.39396 16.1818 9.66432Z"
                                fill="#ffff" />
                        </svg>
                    </div>
                    <div class="message pl-3">
                        {{ Session::get('msg') }}
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span class="d-flex" aria-hidden="true"><svg width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 18L6 6" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18 6L6 18" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>
                </div>
            @endif
        </div>
        <div class="warning">
            @if (Session::has('warning'))
                <div id="notification" class="alert alert-warning alert-dismissible fade show m-0" role="alert"
                    style="z-index: 999999;">
                    <div class="icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 4.38462C7.79374 4.38462 4.38462 7.79374 4.38462 12C4.38462 16.2063 7.79374 19.6154 12 19.6154C16.2063 19.6154 19.6154 16.2063 19.6154 12C19.6154 7.79374 16.2063 4.38462 12 4.38462ZM12 21C7.02903 21 3 16.971 3 12C3 7.02903 7.02903 3 12 3C16.971 3 21 7.02903 21 12C21 16.971 16.971 21 12 21Z"
                                fill="#ffff" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 7.15384C12.3824 7.15384 12.6923 7.4638 12.6923 7.84615V12.4615C12.6923 12.8439 12.3824 13.1538 12 13.1538C11.6177 13.1538 11.3077 12.8439 11.3077 12.4615V7.84615C11.3077 7.4638 11.6177 7.15384 12 7.15384Z"
                                fill="#ffff" />
                            <circle cx="12" cy="15.6923" r="0.923077" fill="#ffff" />
                        </svg>
                    </div>
                    <div class="message pl-3">
                        {{ Session::get('warning') }}
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span class="d-flex" aria-hidden="true"><svg width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 18L6 6" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18 6L6 18" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>
                </div>
            @endif
        </div>
    </div>
    <script>
        // Mở hoặc đóng danh sách thông báo khi nhấn vào icon
        const notificationIcon = document.getElementById('notification-icon');
        const listNoti = document.querySelector('.list-noti');

        notificationIcon.addEventListener('click', function(event) {
            event.stopPropagation(); // Ngừng sự kiện lan truyền để không bị đóng khi nhấn vào icon
            listNoti.classList.toggle('show');
        });

        // Đóng danh sách khi nhấn vào ngoài
        document.addEventListener('click', function(event) {
            if (!notificationIcon.contains(event.target) && !listNoti.contains(event.target)) {
                listNoti.classList.remove('show');
            }
        });
    </script>
</body>
