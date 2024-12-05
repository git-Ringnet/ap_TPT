<!-- resources/views/partials/header.blade.php -->
<div class="wrapper">
    <!-- /.navbar -->
    <div class="header-fixed border-bottom-0">
        <!-- Main Sidebar Container -->
        <div class="d-flex align-items-center justify-content-between w-100 height-47">
            <div class="align-baseline opacity-0">
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M15 28.125C18.481 28.125 21.8194 26.7422 24.2808 24.2808C26.7422 21.8194 28.125 18.481 28.125 15C28.125 11.519 26.7422 8.18064 24.2808 5.71922C21.8194 3.25781 18.481 1.875 15 1.875C11.519 1.875 8.18064 3.25781 5.71922 5.71922C3.25781 8.18064 1.875 11.519 1.875 15C1.875 18.481 3.25781 21.8194 5.71922 24.2808C8.18064 26.7422 11.519 28.125 15 28.125ZM9.60187 20.3981L7.70813 22.2919C6.26585 20.8497 5.28363 19.0122 4.88569 17.0117C4.48774 15.0113 4.69193 12.9377 5.47245 11.0534C6.25296 9.16897 7.57474 7.55835 9.27063 6.42517C10.9665 5.292 12.9604 4.68717 15 4.68717C17.0396 4.68717 19.0335 5.292 20.7294 6.42517C22.4253 7.55835 23.747 9.16897 24.5276 11.0534C25.3081 12.9377 25.5123 15.0113 25.1143 17.0117C24.7164 19.0122 23.7341 20.8497 22.2919 22.2919L20.3981 20.3981C19.8757 19.8755 19.2554 19.461 18.5727 19.1782C17.89 18.8954 17.1583 18.7499 16.4194 18.75H13.5806C12.8417 18.7499 12.11 18.8954 11.4273 19.1782C10.7446 19.461 10.1243 19.8755 9.60187 20.3981Z"
                        fill="#151516" />
                    <path
                        d="M15 7.5C14.0054 7.5 13.0516 7.89509 12.3483 8.59835C11.6451 9.30161 11.25 10.2554 11.25 11.25V12.1875C11.25 13.1821 11.6451 14.1359 12.3483 14.8392C13.0516 15.5424 14.0054 15.9375 15 15.9375C15.9946 15.9375 16.9484 15.5424 17.6516 14.8392C18.3549 14.1359 18.75 13.1821 18.75 12.1875V11.25C18.75 10.2554 18.3549 9.30161 17.6516 8.59835C16.9484 7.89509 15.9946 7.5 15 7.5Z"
                        fill="#151516" />
                </svg>
            </div>
            <div class="d-flex content__heading--right">
                <div class="dropdown">
                    <a class="text-dark justify-content-center align-items-center mx-3 px-1 bg-white font-weight-600 navbar-head @if (!empty($activeGroup) && $activeGroup == 'systemFirst') active-navbar @endif"
                        href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        THIẾT LẬP BAN ĐẦU
                    </a>
                    <div class="dropdown-menu" style="">
                        <a class="dropdown-item text-13-black" href="#">Nhóm
                            đối tượng</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a class="text-dark justify-content-center align-items-center mx-3 px-1 bg-white font-weight-600 navbar-head @if (!empty($activeGroup) && $activeGroup == 'manageProfess') active-navbar @endif"
                        href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        QUẢN LÝ NGHIỆP VỤ
                    </a>
                    <div class="dropdown-menu" style="">
                        <a class="dropdown-item text-13-black" href="#">Phiếu bán hàng
                        </a>
                    </div>
                </div>
                <div class="dropdown">
                    <a class="text-dark justify-content-center align-items-center mx-3 px-1 bg-white font-weight-600 navbar-head @if (!empty($activeGroup) && $activeGroup == 'statistic') active-navbar @endif"
                        href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        BÁO CÁO THỐNG KÊ
                    </a>
                    <div class="dropdown-menu" style="">
                        <a class="dropdown-item text-13-black" href="#">Công nợ khách hàng
                        </a>
                    </div>
                </div>
            </div>
            <div class="align-baseline setting">
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M15 28.125C18.481 28.125 21.8194 26.7422 24.2808 24.2808C26.7422 21.8194 28.125 18.481 28.125 15C28.125 11.519 26.7422 8.18064 24.2808 5.71922C21.8194 3.25781 18.481 1.875 15 1.875C11.519 1.875 8.18064 3.25781 5.71922 5.71922C3.25781 8.18064 1.875 11.519 1.875 15C1.875 18.481 3.25781 21.8194 5.71922 24.2808C8.18064 26.7422 11.519 28.125 15 28.125ZM9.60187 20.3981L7.70813 22.2919C6.26585 20.8497 5.28363 19.0122 4.88569 17.0117C4.48774 15.0113 4.69193 12.9377 5.47245 11.0534C6.25296 9.16897 7.57474 7.55835 9.27063 6.42517C10.9665 5.292 12.9604 4.68717 15 4.68717C17.0396 4.68717 19.0335 5.292 20.7294 6.42517C22.4253 7.55835 23.747 9.16897 24.5276 11.0534C25.3081 12.9377 25.5123 15.0113 25.1143 17.0117C24.7164 19.0122 23.7341 20.8497 22.2919 22.2919L20.3981 20.3981C19.8757 19.8755 19.2554 19.461 18.5727 19.1782C17.89 18.8954 17.1583 18.7499 16.4194 18.75H13.5806C12.8417 18.7499 12.11 18.8954 11.4273 19.1782C10.7446 19.461 10.1243 19.8755 9.60187 20.3981Z"
                        fill="#151516" />
                    <path
                        d="M15 7.5C14.0054 7.5 13.0516 7.89509 12.3483 8.59835C11.6451 9.30161 11.25 10.2554 11.25 11.25V12.1875C11.25 13.1821 11.6451 14.1359 12.3483 14.8392C13.0516 15.5424 14.0054 15.9375 15 15.9375C15.9946 15.9375 16.9484 15.5424 17.6516 14.8392C18.3549 14.1359 18.75 13.1821 18.75 12.1875V11.25C18.75 10.2554 18.3549 9.30161 17.6516 8.59835C16.9484 7.89509 15.9946 7.5 15 7.5Z"
                        fill="#151516" />
                </svg>
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
