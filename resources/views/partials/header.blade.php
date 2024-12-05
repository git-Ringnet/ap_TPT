<!-- resources/views/partials/header.blade.php -->
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
                        <a class="dropdown-item text-13-black" href="#">Nhóm
                            đối tượng</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a class="text-white justify-content-center align-items-center mx-3 px-1 font-weight-600 navbar-head @if (!empty($activeGroup) && $activeGroup == 'manageProfess') active-navbar @endif"
                        href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        NGHIỆP VỤ
                    </a>
                    <div class="dropdown-menu" style="">
                        <a class="dropdown-item text-13-black" href="#">Phiếu bán hàng
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
            <div class="settings-noitifi align-items-baseline justify-content-end d-flex" style="width: 168px">
                <div class="setting mr-3">
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
        <div class="px-3 py-2 border-bottom border-top bg-grey  d-block ">
            <div class=" d-flex ">
                <a href="#" class="height-36">
                    <button type="button"
                        class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2  active ">
                        Nhóm đối tượng
                    </button>
                </a>
                <a href="#" class="height-36">
                    <button type="button"
                        class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 ">
                        Khách hàng
                    </button>
                </a>
                <a href="#" class="height-36">
                    <button type="button"
                        class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 ">
                        Nhà cung cấp
                    </button>
                </a>
                <a href="#" class="height-36">
                    <button type="button"
                        class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 ">
                        Hàng hóa
                    </button>
                </a>
                <a href="#" class="height-36">
                    <button type="button"
                        class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 ">
                        Nhân viên
                    </button>
                </a>
                <a href="#" class="height-36">
                    <button type="button"
                        class="h-100 border text-dark justify-content-center align-items-center text-13-black rounded bg-white ml-2 ">
                        Kho
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
