@include('partials.header', ['activeGroup' => 'manageProfess', 'activeName' => 'quotations'])
@section('title', $title)
<div class="content-wrapper m-0 min-height--none p-0">
    <div class="content-header-fixed p-0 border-0">
        <div class="content__header--inner">
            <div class="d-flex align-items-center ml-3">
                <form action="" method="get" id="search-filter" class="p-0 m-0">
                    <div class="position-relative ml-1">
                        <input type="text" placeholder="Tìm kiếm" id="search" name="keywords"
                            style="outline: none;" class="pr-4 w-100 input-search text-13"
                            value="{{ request()->keywords }}" />
                        <span id="search-icon" class="search-icon">
                            <i class="fas fa-search btn-submit"></i>
                        </span>
                        <input class="btn-submit" type="submit" id="hidden-submit" name="hidden-submit"
                            style="display: none;" />
                    </div>
                </form>
                <div class="dropdown mx-2 filter-all">
                    <button class="btn-filter_search" data-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path
                                d="M12.9548 3H10.0457C9.74445 3 9.50024 3.24421 9.50024 3.54545V6.45455C9.50024 6.75579 9.74445 7 10.0457 7H12.9548C13.256 7 13.5002 6.75579 13.5002 6.45455V3.54545C13.5002 3.24421 13.256 3 12.9548 3Z"
                                fill="#6D7075" />
                            <path
                                d="M6.45455 3H3.54545C3.24421 3 3 3.24421 3 3.54545V6.45455C3 6.75579 3.24421 7 3.54545 7H6.45455C6.75579 7 7 6.75579 7 6.45455V3.54545C7 3.24421 6.75579 3 6.45455 3Z"
                                fill="#6D7075" />
                            <path
                                d="M6.45455 9.50024H3.54545C3.24421 9.50024 3 9.74445 3 10.0457V12.9548C3 13.256 3.24421 13.5002 3.54545 13.5002H6.45455C6.75579 13.5002 7 13.256 7 12.9548V10.0457C7 9.74445 6.75579 9.50024 6.45455 9.50024Z"
                                fill="#6D7075" />
                            <path
                                d="M12.9548 9.50024H10.0457C9.74445 9.50024 9.50024 9.74445 9.50024 10.0457V12.9548C9.50024 13.256 9.74445 13.5002 10.0457 13.5002H12.9548C13.256 13.5002 13.5002 13.256 13.5002 12.9548V10.0457C13.5002 9.74445 13.256 9.50024 12.9548 9.50024Z"
                                fill="#6D7075" />
                        </svg>
                        <span class="text-btnIner">Bộ lọc</span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.42342 6.92342C5.65466 6.69219 6.02956 6.69219 6.26079 6.92342L9 9.66264L11.7392 6.92342C11.9704 6.69219 12.3453 6.69219 12.5766 6.92342C12.8078 7.15466 12.8078 7.52956 12.5766 7.76079L9.41868 10.9187C9.18745 11.1499 8.81255 11.1499 8.58132 10.9187L5.42342 7.76079C5.19219 7.52956 5.19219 7.15466 5.42342 6.92342Z"
                                fill="#6B6F76" />
                        </svg>
                    </button>
                    <div class="dropdown-menu" id="dropdown-menu" aria-labelledby="dropdownMenuButton" style="z-index:">
                        <div class="search-container px-2">
                            <input type="text" placeholder="Tìm kiếm" id="myInput" class="text-13"
                                onkeyup="filterFunction()" style="outline: none;">
                            <span class="search-icon mr-2">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <div class="scrollbar">
                            <button class="dropdown-item btndropdown text-13-black" id="btn-ma" data-button="ma"
                                type="button">
                                Mã
                            </button>
                            <button class="dropdown-item btndropdown text-13-black" id="btn-ten" data-button="ten"
                                type="button">
                                Tên
                            </button>
                            <button class="dropdown-item btndropdown text-13-black" id="btn-diachi" data-button="diachi"
                                type="button">
                                Địa chỉ
                            </button>
                            <button class="dropdown-item btndropdown text-13-black" id="btn-phone" data-button="phone"
                                type="button">
                                Điện thoại
                            </button>
                            <button class="dropdown-item btndropdown text-13-black" id="btn-email" data-button="email"
                                type="button">
                                Email
                            </button>
                            <button class="dropdown-item btndropdown text-13-black" id="btn-debt" data-button="debt"
                                type="button">
                                Công nợ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex content__heading--right">
                <div class="row m-0">
                    <a href="{{ route('quotations.create') }}" class="activity mr-3" data-name1="KH" data-des="Tạo mới">
                        <button type="button" class="custom-btn mx-1 d-flex align-items-center h-100">
                            <svg class="mr-1" width="12" height="12" viewBox="0 0 18 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9 0C9.58186 -2.96028e-08 10.0536 0.471694 10.0536 1.05356L10.0536 16.9464C10.0536 17.5283 9.58186 18 9 18C8.41814 18 7.94644 17.5283 7.94644 16.9464V1.05356C7.94644 0.471694 8.41814 -2.96028e-08 9 0Z"
                                    fill="white" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M18 9C18 9.58187 17.5283 10.0536 16.9464 10.0536H1.05356C0.471694 10.0536 -2.07219e-07 9.58187 0 9C-7.69672e-07 8.41814 0.471695 7.94644 1.05356 7.94644H16.9464C17.5283 7.94644 18 8.41814 18 9Z"
                                    fill="white" />
                            </svg>
                            <p class="m-0 ml-1">Tạo mới</p>
                        </button>
                    </a>

                </div>
            </div>
        </div>
    </div>
    <div class="content margin-top-127">
        <section class="content">
            <div class="container-fluided">
                <div class="row result-filter-guest margin-left30 my-1">
                </div>
                <div class="col-12 p-0 m-0">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="outer2 table-responsive text-nowrap">
                            <table id="example2" class="table table-hover bg-white rounded">
                                <thead class="border-custom">
                                    <tr>
                                        <th class="height-30 py-0 border-right pl-4" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit" data-sort-by="key"
                                                    data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Mã phiếu</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-key"></div>
                                            </span>
                                        </th>
                                        <th class="height-30 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit"
                                                    data-sort-by="guest_name_display" data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Khách hàng</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_name_display"></div>
                                            </span>
                                        </th>
                                        <th class="height-30 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit"
                                                    data-sort-by="guest_name_display" data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Ngày lập phiếu</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_name_display"></div>
                                            </span>
                                        </th>
                                        <th class="height-30 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit"
                                                    data-sort-by="guest_name_display" data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Ngày đóng phiếu</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_name_display"></div>
                                            </span>
                                        </th>
                                        <th class="height-30 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit"
                                                    data-sort-by="guest_code" data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Loại phiếu</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_code"></div>
                                            </span>
                                        </th>
                                        <th class="height-30 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit"
                                                    data-sort-by="guest_code" data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Hàng tiếp nhận</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_code"></div>
                                            </span>
                                        </th>
                                        <th class="height-30 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit"
                                                    data-sort-by="guest_code" data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Tình trạng</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_code"></div>
                                            </span>
                                        </th>
                                        <th class="height-30 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit"
                                                    data-sort-by="guest_code" data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Trạng thái</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_code"></div>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-guest">
                                    @foreach ($quotations as $item)
                                        <tr class="position-relative guest-info height-30">
                                            <td class="text-13-black border-right border-bottom py-0 pl-4">
                                                <a
                                                    href="{{ route('quotations.edit', $item->id) }}">{{ $item->form_code_receiving }}</a>
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->customer->customer_name }}
                                            </td>
                                            <td
                                                class="text-13-black border border-left-0 text-left border-bottom py-0">
                                                {{ date_format(new DateTime($item->date_created), 'd/m/Y') }}
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->close_at ? date_format(new DateTime($item->close_at), 'd/m/Y') : '' }}
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                @if ($item->form_type == 1)
                                                    Bảo hành
                                                @elseif($item->form_type == 2)
                                                    Dịch vụ
                                                @elseif($item->form_type == 3)
                                                    Dịch vụ bảo hành
                                                @endif
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                @if ($item->branch_id == 1)
                                                    Nội bộ
                                                @elseif($item->branch_id == 2)
                                                    Bên ngoài
                                                @endif
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                @if ($item->status == 1)
                                                    Tiếp nhận
                                                @elseif($item->status == 2)
                                                    Xử lý
                                                @endif
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->state }}
                                            </td>
                                            <td class="position-absolute m-0 p-0 bg-hover-icon icon-center">
                                                <div class="d-flex w-100">
                                                    <a href="#">
                                                        <div class="rounded">
                                                            <form
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')"
                                                                action="{{ route('quotations.destroy', $item->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm">
                                                                    <svg width="16" height="16"
                                                                        viewBox="0 0 16 16" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path opacity="0.936" fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M6.40625 0.968766C7.44813 0.958304 8.48981 0.968772 9.53125 1.00016C9.5625 1.03156 9.59375 1.06296 9.625 1.09436C9.65625 1.49151 9.66663 1.88921 9.65625 2.28746C10.7189 2.277 11.7814 2.28747 12.8438 2.31886C12.875 2.35025 12.9063 2.38165 12.9375 2.41305C12.9792 2.99913 12.9792 3.58522 12.9375 4.17131C12.9063 4.24457 12.8542 4.2969 12.7813 4.32829C12.6369 4.35948 12.4911 4.36995 12.3438 4.35969C12.3542 7.45762 12.3438 10.5555 12.3125 13.6533C12.1694 14.3414 11.7632 14.7914 11.0938 15.0034C9.01044 15.0453 6.92706 15.0453 4.84375 15.0034C4.17433 14.7914 3.76808 14.3414 3.625 13.6533C3.59375 10.5555 3.58333 7.45762 3.59375 4.35969C3.3794 4.3844 3.18148 4.34254 3 4.2341C2.95833 3.62708 2.95833 3.02007 3 2.41305C3.03125 2.38165 3.0625 2.35025 3.09375 2.31886C4.15605 2.28747 5.21855 2.277 6.28125 2.28746C6.27088 1.88921 6.28125 1.49151 6.3125 1.09436C6.35731 1.06018 6.38856 1.01832 6.40625 0.968766ZM6.96875 1.65951C7.63544 1.65951 8.30206 1.65951 8.96875 1.65951C8.96875 1.86882 8.96875 2.07814 8.96875 2.28746C8.30206 2.28746 7.63544 2.28746 6.96875 2.28746C6.96875 2.07814 6.96875 1.86882 6.96875 1.65951ZM3.65625 2.9782C6.53125 2.9782 9.40625 2.9782 12.2813 2.9782C12.2813 3.18752 12.2813 3.39684 12.2813 3.60615C9.40625 3.60615 6.53125 3.60615 3.65625 3.60615C3.65625 3.39684 3.65625 3.18752 3.65625 2.9782ZM4.34375 4.35969C6.76044 4.35969 9.17706 4.35969 11.5938 4.35969C11.6241 7.5032 11.5929 10.643 11.5 13.7789C11.3553 14.05 11.1366 14.2279 10.8438 14.3127C8.92706 14.3546 7.01044 14.3546 5.09375 14.3127C4.80095 14.2279 4.5822 14.05 4.4375 13.7789C4.34462 10.643 4.31337 7.5032 4.34375 4.35969Z"
                                                                            fill="#6C6F74" />
                                                                        <path opacity="0.891" fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M5.78125 5.28118C6.0306 5.2259 6.20768 5.30924 6.3125 5.53118C6.35419 8.052 6.35419 10.5729 6.3125 13.0937C6.08333 13.427 5.85417 13.427 5.625 13.0937C5.58333 10.552 5.58333 8.01037 5.625 5.46868C5.69031 5.4141 5.7424 5.3516 5.78125 5.28118Z"
                                                                            fill="#6C6F74" />
                                                                        <path opacity="0.891" fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M7.78125 5.28118C8.03063 5.2259 8.20769 5.30924 8.3125 5.53118C8.35419 8.052 8.35419 10.5729 8.3125 13.0937C8.08331 13.427 7.85419 13.427 7.625 13.0937C7.58331 10.552 7.58331 8.01037 7.625 5.46868C7.69031 5.4141 7.74238 5.3516 7.78125 5.28118Z"
                                                                            fill="#6C6F74" />
                                                                        <path opacity="0.891" fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M9.78125 5.28118C10.0306 5.2259 10.2077 5.30924 10.3125 5.53118C10.3542 8.052 10.3542 10.5729 10.3125 13.0937C10.0833 13.427 9.85419 13.427 9.625 13.0937C9.58331 10.552 9.58331 8.01037 9.625 5.46868C9.69031 5.4141 9.74238 5.3516 9.78125 5.28118Z"
                                                                            fill="#6C6F74" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>