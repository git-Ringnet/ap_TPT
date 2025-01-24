<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}"
    aria-hidden="true">
    <input type="hidden" name="modal_id" value="{{ $id }}">
    <input type="hidden" name="name_modal" id="name_modal" value="{{ $name }}">
    <div class="modal-dialog modal-dialog-centered" role="document"
        @if ($name == 'XH' || $name == 'CXH') style="max-width: 800px;" @endif>
        <div class="modal-content">
            <div class="modal-header justify-content-end">
                <div class="d-flex content__heading--right">
                    <div class="row m-0">
                        <a href="#">
                            <button type="button" data-dismiss="modal" data-modal-id="{{ $id }}"
                                class="btn-destroy btn-destroy-modal btn-light mx-1 d-flex align-items-center h-100">
                                <svg class="mx-1" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8 15C11.866 15 15 11.866 15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15ZM6.03033 4.96967C5.73744 4.67678 5.26256 4.67678 4.96967 4.96967C4.67678 5.26256 4.67678 5.73744 4.96967 6.03033L6.93934 8L4.96967 9.96967C4.67678 10.2626 4.67678 10.7374 4.96967 11.0303C5.26256 11.3232 5.73744 11.3232 6.03033 11.0303L8 9.06066L9.96967 11.0303C10.2626 11.3232 10.7374 11.3232 11.0303 11.0303C11.3232 10.7374 11.3232 10.2626 11.0303 9.96967L9.06066 8L11.0303 6.03033C11.3232 5.73744 11.3232 5.26256 11.0303 4.96967C10.7374 4.67678 10.2626 4.67678 9.96967 4.96967L8 6.93934L6.03033 4.96967Z"
                                        fill="black" />
                                </svg>
                                <p class="m-0 p-0 text-dark">Hủy</p>
                            </button>
                        </a>
                        <button type="button" class="submit-button custom-btn d-flex align-items-center h-100 ml-1">
                            <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 16 16" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8 15C11.866 15 15 11.866 15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15ZM11.7836 6.42901C12.0858 6.08709 12.0695 5.55006 11.7472 5.22952C11.4248 4.90897 10.9186 4.9263 10.6164 5.26821L7.14921 9.19122L5.3315 7.4773C5.00127 7.16593 4.49561 7.19748 4.20208 7.54777C3.90855 7.89806 3.93829 8.43445 4.26852 8.74581L6.28032 10.6427C6.82041 11.152 7.64463 11.1122 8.13886 10.553L11.7836 6.42901Z"
                                    fill="white" />
                            </svg>
                            <p class="m-0 p-0">Xác nhận</p>
                        </button>
                    </div>
                </div>
            </div>
            <div class="content-wrapper2 px-0 py-0">
                {{-- Thông tin hàng --}}
                <div class="border">
                    <div>
                        <div class="bg-filter-search border-0 d-flex justify-content-center align-items-center"
                            style="height:40px">
                            <p class="font-weight-bold text-uppercase mb-0" style="line-height: 40px;">
                                THÔNG TIN HÀNG
                            </p>
                        </div>
                        <table class="info-product">
                            <thead class="border-custom border-bottom">
                                <tr class="">
                                    <th class="height-40 py-0 border pl-3" style="width: 20%;">Mã hàng</th>
                                    <th class="height-40 py-0 border pl-3" style="width: 25%;">Tên hàng</th>
                                    <th class="height-40 py-0 border pl-3" style="width: 20%;">Hãng</th>
                                    @if ($name == 'XH' || $name == 'CXH')
                                        <th class="height-40 py-0 border pl-3" colspan="2" style="width: 20%;">Bảo
                                            hành (Tháng)</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="body-warranty">
                                <tr class="height-40 position-relative">
                                    <input type="hidden" name="product_id" id="product_id_input">
                                    <td class="text-13-black border border-bottom-0 py-0 pl-3">
                                        <input type="text" id="product_code_input" name="product_code_input"
                                            style="flex:2;" placeholder="Chọn thông tin" readonly
                                            class="text-13-black w-100 border-0">
                                        <div class="">
                                            <div id="listProducts"
                                                class="bg-white position-absolute rounded list-product shadow p-1 z-index-block"
                                                style="z-index: 99;display: none;">
                                                <div class="p-1">
                                                    <div class="position-relative">
                                                        <input type="text" placeholder="Nhập thông tin"
                                                            autocomplete="off"
                                                            class="pr-4 w-100 input-search bg-input-guest"
                                                            id="searchProduct">
                                                        <span id="search-icon" class="search-icon">
                                                            <i class="fas fa-search text-table" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <ul class="m-0 p-0 scroll-data">
                                                    @foreach ($dataProduct as $product_value)
                                                        <li class="p-2 align-items-center text-wrap border-top"
                                                            data-id="{{ $product_value->id }}">
                                                            <a href="#" title="{{ $product_value->product_code }}"
                                                                style="flex:2;" id="{{ $product_value->id }}"
                                                                data-code="{{ $product_value->product_code }}"
                                                                data-name="{{ $product_value->product_name }}"
                                                                data-brand="{{ $product_value->brand }}"
                                                                data-id="{{ $product_value->id }}"
                                                                data-warranty="{{ $product_value->warranty }}"
                                                                name="info-product" class="search-info">
                                                                <span
                                                                    class="text-13-black">{{ $product_value->product_code }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-13-black border border-bottom-0 py-0 pl-3">
                                        <input type="text" id="product_name_input" name="product_name_input"
                                            style="flex:2;" readonly class="text-13-black w-100 border-0">
                                    </td>
                                    <td class="text-13-black border border-bottom-0 py-0 pl-3">
                                        <input type="text" id="product_brand_input" name="product_brand_input"
                                            style="flex:2;" readonly class="text-13-black w-100 border-0">
                                    </td>
                                    @if ($name == 'XH' || $name == 'CXH')
                                        <td class="text-13-black border border-bottom-0 py-0">
                                            <input type="text" name="name_warranty" id="name_warranty"
                                                style="flex:2;"
                                                class="text-13-black w-100 border-0 bg-input-guest-blue p-2">
                                        </td>
                                        <td class="text-13-black border border-bottom-0 py-0">
                                            <input type="number" id="product_warranty_input"
                                                name="product_warranty_input" style="flex:2;"
                                                class="text-13-black w-100 border-0 bg-input-guest-blue p-2">
                                        </td>
                                    @endif
                                </tr>
                                @if ($name == 'XH' || $name == 'CXH')
                                    <tr id="option-warranty">
                                        <td class="text-13-black border border-bottom-0 border-top-0 py-0"></td>
                                        <td class="text-13-black border border-bottom-0 border-top-0 py-0"></td>
                                        <td class="text-13-black border border-bottom-0 border-top-0 py-0"></td>
                                        <td class="text-13-black border border-bottom-0 py-0 p-2 cursor-pointer"
                                            id="add-warranty">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 14 14" fill="none">
                                                <path
                                                    d="M7.65625 2.625C7.65625 2.26257 7.36243 1.96875 7 1.96875C6.63757 1.96875 6.34375 2.26257 6.34375 2.625V6.34375H2.625C2.26257 6.34375 1.96875 6.63757 1.96875 7C1.96875 7.36243 2.26257 7.65625 2.625 7.65625H6.34375V11.375C6.34375 11.7374 6.63757 12.0312 7 12.0312C7.36243 12.0312 7.65625 11.7374 7.65625 11.375V7.65625H11.375C11.7374 7.65625 12.0312 7.36243 12.0312 7C12.0312 6.63757 11.7374 6.34375 11.375 6.34375H7.65625V2.625Z"
                                                    fill="black" />
                                            </svg>
                                        </td>
                                        <td class="text-13-black border border-bottom-0 py-0"></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Thông tin SERIAL NUMBER --}}
                <div class="border">
                    <div>
                        <div class="bg-filter-search border-0 d-flex justify-content-center align-items-center"
                            style="height:40px">
                            <p class="font-weight-bold text-uppercase mb-0" style="line-height: 40px;">
                                THÔNG TIN SERIAL NUMBER
                            </p>
                        </div>
                        <table class="info-serial w-100">
                            <thead class="border-custom border-bottom">
                                <tr class="">
                                    <th class="height-40 py-0 border text-center" style="width:15%">STT</th>
                                    <th class="height-40 py-0 border pl-3" style="width:70%">Serial Number</th>
                                    <th class="height-40 py-0 border pl-3" style="width:15%"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <tr class="height-40">
                                    <td class="text-13-black border py-0 text-center">01</td>
                                    <td class="text-13-black border py-0 pl-3 position-relative">
                                        <input type="text" id="form_code" name="form_code" style="flex:2;"
                                            autocomplete="off" placeholder="Nhập thông tin"
                                            class="text-13-black w-100 border-0 seri-input-check">
                                        <span class="check-icon"></span>
                                    </td>
                                    <td class="text-13-black border py-0 text-center">
                                        <button class="btn btn-sm delete-row">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 14 14" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M10.8226 5.6875C11.0642 5.6875 11.2601 5.88338 11.2601 6.125C11.2601 6.15874 11.2562 6.19237 11.2485 6.2252L9.94245 11.7758C9.75642 12.5663 9.05109 13.125 8.23897 13.125H5.76103C4.94894 13.125 4.24355 12.5663 4.05755 11.7758L2.75152 6.2252C2.69618 5.99001 2.84198 5.75447 3.07718 5.69913C3.11002 5.6914 3.14365 5.6875 3.17739 5.6875H10.8226ZM7.4375 0.875C8.64562 0.875 9.625 1.85438 9.625 3.0625H11.375C11.8583 3.0625 12.25 3.45426 12.25 3.9375V4.375C12.25 4.61662 12.0541 4.8125 11.8125 4.8125H2.1875C1.94588 4.8125 1.75 4.61662 1.75 4.375V3.9375C1.75 3.45426 2.14175 3.0625 2.625 3.0625H4.375C4.375 1.85438 5.35438 0.875 6.5625 0.875H7.4375ZM7.4375 2.1875H6.5625C6.07926 2.1875 5.6875 2.57925 5.6875 3.0625H8.3125C8.3125 2.57925 7.92074 2.1875 7.4375 2.1875Z"
                                                    fill="#151516" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="height-40">
                                    <td class="text-13-black border py-0 text-center">02</td>
                                    <td class="text-13-black border py-0 pl-3 position-relative">
                                        <input type="text" id="form_code" name="form_code" style="flex:2;"
                                            autocomplete="off" placeholder="Nhập thông tin"
                                            class="text-13-black w-100 border-0 seri-input-check">
                                        <span class="check-icon"></span>
                                    </td>
                                    <td class="text-13-black border py-0 text-center">
                                        <button class="btn btn-sm delete-row">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 14 14" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M10.8226 5.6875C11.0642 5.6875 11.2601 5.88338 11.2601 6.125C11.2601 6.15874 11.2562 6.19237 11.2485 6.2252L9.94245 11.7758C9.75642 12.5663 9.05109 13.125 8.23897 13.125H5.76103C4.94894 13.125 4.24355 12.5663 4.05755 11.7758L2.75152 6.2252C2.69618 5.99001 2.84198 5.75447 3.07718 5.69913C3.11002 5.6914 3.14365 5.6875 3.17739 5.6875H10.8226ZM7.4375 0.875C8.64562 0.875 9.625 1.85438 9.625 3.0625H11.375C11.8583 3.0625 12.25 3.45426 12.25 3.9375V4.375C12.25 4.61662 12.0541 4.8125 11.8125 4.8125H2.1875C1.94588 4.8125 1.75 4.61662 1.75 4.375V3.9375C1.75 3.45426 2.14175 3.0625 2.625 3.0625H4.375C4.375 1.85438 5.35438 0.875 6.5625 0.875H7.4375ZM7.4375 2.1875H6.5625C6.07926 2.1875 5.6875 2.57925 5.6875 3.0625H8.3125C8.3125 2.57925 7.92074 2.1875 7.4375 2.1875Z"
                                                    fill="#151516" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="height-40">
                                    <td class="text-13-black border py-0 text-center">03</td>
                                    <td class="text-13-black border py-0 pl-3 position-relative">
                                        <input type="text" id="form_code" name="form_code" style="flex:2;"
                                            autocomplete="off" placeholder="Nhập thông tin"
                                            class="text-13-black w-100 border-0 seri-input-check">
                                        <span class="check-icon"></span>
                                    </td>
                                    <td class="text-13-black border py-0 text-center">
                                        <button class="btn btn-sm delete-row">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 14 14" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M10.8226 5.6875C11.0642 5.6875 11.2601 5.88338 11.2601 6.125C11.2601 6.15874 11.2562 6.19237 11.2485 6.2252L9.94245 11.7758C9.75642 12.5663 9.05109 13.125 8.23897 13.125H5.76103C4.94894 13.125 4.24355 12.5663 4.05755 11.7758L2.75152 6.2252C2.69618 5.99001 2.84198 5.75447 3.07718 5.69913C3.11002 5.6914 3.14365 5.6875 3.17739 5.6875H10.8226ZM7.4375 0.875C8.64562 0.875 9.625 1.85438 9.625 3.0625H11.375C11.8583 3.0625 12.25 3.45426 12.25 3.9375V4.375C12.25 4.61662 12.0541 4.8125 11.8125 4.8125H2.1875C1.94588 4.8125 1.75 4.61662 1.75 4.375V3.9375C1.75 3.45426 2.14175 3.0625 2.625 3.0625H4.375C4.375 1.85438 5.35438 0.875 6.5625 0.875H7.4375ZM7.4375 2.1875H6.5625C6.07926 2.1875 5.6875 2.57925 5.6875 3.0625H8.3125C8.3125 2.57925 7.92074 2.1875 7.4375 2.1875Z"
                                                    fill="#151516" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="height-40">
                                    <td class="text-13-black border py-0 text-center">04</td>
                                    <td class="text-13-black border py-0 pl-3 position-relative">
                                        <input type="text" id="form_code" name="form_code" style="flex:2;"
                                            autocomplete="off" placeholder="Nhập thông tin"
                                            class="text-13-black w-100 border-0 seri-input-check">
                                        <span class="check-icon"></span>
                                    </td>
                                    <td class="text-13-black border py-0 text-center">
                                        <button class="btn btn-sm delete-row">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 14 14" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M10.8226 5.6875C11.0642 5.6875 11.2601 5.88338 11.2601 6.125C11.2601 6.15874 11.2562 6.19237 11.2485 6.2252L9.94245 11.7758C9.75642 12.5663 9.05109 13.125 8.23897 13.125H5.76103C4.94894 13.125 4.24355 12.5663 4.05755 11.7758L2.75152 6.2252C2.69618 5.99001 2.84198 5.75447 3.07718 5.69913C3.11002 5.6914 3.14365 5.6875 3.17739 5.6875H10.8226ZM7.4375 0.875C8.64562 0.875 9.625 1.85438 9.625 3.0625H11.375C11.8583 3.0625 12.25 3.45426 12.25 3.9375V4.375C12.25 4.61662 12.0541 4.8125 11.8125 4.8125H2.1875C1.94588 4.8125 1.75 4.61662 1.75 4.375V3.9375C1.75 3.45426 2.14175 3.0625 2.625 3.0625H4.375C4.375 1.85438 5.35438 0.875 6.5625 0.875H7.4375ZM7.4375 2.1875H6.5625C6.07926 2.1875 5.6875 2.57925 5.6875 3.0625H8.3125C8.3125 2.57925 7.92074 2.1875 7.4375 2.1875Z"
                                                    fill="#151516" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="height-40">
                                    <td class="text-13-black border py-0 text-center">05</td>
                                    <td class="text-13-black border py-0 pl-3 position-relative">
                                        <input type="text" id="form_code" name="form_code" style="flex:2;"
                                            autocomplete="off" placeholder="Nhập thông tin"
                                            class="text-13-black w-100 border-0 seri-input-check">
                                        <span class="check-icon"></span>
                                    </td>
                                    <td class="text-13-black border py-0 text-center">
                                        <button class="btn btn-sm delete-row">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 14 14" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M10.8226 5.6875C11.0642 5.6875 11.2601 5.88338 11.2601 6.125C11.2601 6.15874 11.2562 6.19237 11.2485 6.2252L9.94245 11.7758C9.75642 12.5663 9.05109 13.125 8.23897 13.125H5.76103C4.94894 13.125 4.24355 12.5663 4.05755 11.7758L2.75152 6.2252C2.69618 5.99001 2.84198 5.75447 3.07718 5.69913C3.11002 5.6914 3.14365 5.6875 3.17739 5.6875H10.8226ZM7.4375 0.875C8.64562 0.875 9.625 1.85438 9.625 3.0625H11.375C11.8583 3.0625 12.25 3.45426 12.25 3.9375V4.375C12.25 4.61662 12.0541 4.8125 11.8125 4.8125H2.1875C1.94588 4.8125 1.75 4.61662 1.75 4.375V3.9375C1.75 3.45426 2.14175 3.0625 2.625 3.0625H4.375C4.375 1.85438 5.35438 0.875 6.5625 0.875H7.4375ZM7.4375 2.1875H6.5625C6.07926 2.1875 5.6875 2.57925 5.6875 3.0625H8.3125C8.3125 2.57925 7.92074 2.1875 7.4375 2.1875Z"
                                                    fill="#151516" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="my-2 d-flex justify-content-between">
                            <span>
                                <button id="add-rows" class="border-0 bg-transparent pl-3 text-purble">Thêm</button>
                                <input type="number" id="row-count" class="rounded d-inline-block p-0 text-center"
                                    style="width: 36px; height: 22px; font-size: 12px;" value="5"
                                    min="1">
                                dòng
                            </span>
                            <span class="mr-5 text-danger font-weight-bold">Số lượng : <b
                                    class="count-seri">0</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/barcode.js') }}"></script>
<script src="{{ asset('dist/js/scripts.js') }}"></script>
<script>
    // Name modal
    let nameModal = $("#name_modal").val();
    //id phiếu nhập/xuất
    let import_id = $("#import_id").val();
    // Khi bấm vào nút

    // Check serial number
    $(document).ready(function() {
        $(document).on('change', '.seri-input-check', function() {
            const $input = $(this);
            const $checkIcon = $input.siblings('.check-icon');
            const serialNumber = $input.val().trim(); // Giá trị nhập vào
            const product_id = $('#product_id_input').val(); // Giá trị nhập vào
            const branch_id = $('input[name="branch_id"]:checked').val();
            const form_type = $('input[name="form_type"]:checked').val();

            // Kiểm tra trùng lặp trong các ô nhập liệu khác
            let isDuplicate = false;
            $('.seri-input-check').each(function() {
                const otherValue = $(this).val().trim();
                if ($(this)[0] !== $input[0] && otherValue === serialNumber && serialNumber !==
                    '') {
                    isDuplicate = true;
                    return false; // Thoát khỏi vòng lặp nếu tìm thấy trùng lặp
                }
            });

            if (isDuplicate) {
                $checkIcon.text('✖').css('color', 'red');
                console.error('Serial bị trùng lặp.');
                return; // Không thực hiện kiểm tra AJAX nếu trùng lặp
            }

            // Kiểm tra nếu ô nhập liệu không trống và thực hiện AJAX ngay lập tức
            if (serialNumber !== "") {
                $.ajax({
                    url: '{{ route('checkSNImport') }}',
                    type: 'GET',
                    data: {
                        form_type: form_type,
                        branch_id: branch_id,
                        serial_number: serialNumber,
                        product_id: product_id,
                        nameModal: nameModal,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response.message);
                        if (response.status === 'success' && serialNumber !== "") {
                            console.log(serialNumber);
                            $checkIcon.text('✔').css('color', 'green').attr('title',
                                response.message);
                        } else if (response.status === 'error') {
                            $checkIcon.text('✖').css('color', 'red').attr('title', response
                                .message);
                        }
                    },
                    error: function() {
                        $checkIcon.text('?').css('color', 'orange');
                        console.error('Có lỗi xảy ra khi kiểm tra số serial.');
                    }
                });
            } else {
                // Nếu ô trống, giữ biểu tượng mặc định hoặc không làm gì cả
                $checkIcon.text('').css('color', 'transparent');
                console.log(serialNumber);
            }
            updateSerialCount();
        });
    });

    $('#btn-get-unique-products').click(function(e) {
        // e.preventDefault();
        if ($('#tbody-product-data tr#serials-data').length === 0) {
            showAutoToast("warning", "Vui lòng thêm sản phẩm.");
            e.preventDefault();
        }

        // Kiểm tra nhập S/N trùng
        let duplicates = [];
        let seen = new Set();

        // Duyệt qua từng input để lấy giá trị
        $('input[name="serial[]"]').each(function() {
            let value = $(this).val().trim().toLowerCase(); // Chuẩn hóa về chữ thường
            if (seen.has(value) && value !== "") {
                duplicates.push(value); // Thêm giá trị trùng vào mảng
            } else {
                seen.add(value); // Thêm giá trị vào tập hợp
            }
        });

        // Nếu có giá trị trùng, thông báo
        if (duplicates.length > 0) {
            showAutoToast("warning", "Các S/N bị trùng: " + duplicates.join(", "));
            e.preventDefault();
        }

        //Kiểm tra S/N tồn tại
        let SNExist = []; //mảng chứa S/N tồn tại

        let isDuplicate = false; // Cờ kiểm tra xem có serial nào bị trùng không
        const rows = $(
            '#tbody-product-data .row-product[data-product-id]'); // Lấy tất cả các hàng có product_id

        rows.each(function() {
            const $row = $(this); // Lấy dòng hiện tại
            const product_id = $row.find('.product_id').val();
            const serial = $row.find('.serial').val().trim();

            // Nếu serial rỗng, bỏ qua
            if (!serial) return;
            // Gửi AJAX kiểm tra từng serial
            $.ajax({
                url: '{{ route('checkSN') }}',
                type: 'GET',
                async: false, // Sử dụng đồng bộ để đợi kết quả trước khi xử lý tiếp
                data: {
                    product_id: product_id,
                    serial: serial,
                    nameModal: nameModal,
                    import_id: import_id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (nameModal === "NH" && response.exists) {
                        SNExist.push(serial);
                        isDuplicate = true; // Đánh dấu có lỗi trùng lặp
                    }
                    if (nameModal === "CNH" && response.exists) {
                        SNExist.push(serial);
                        isDuplicate = true; // Đánh dấu có lỗi trùng lặp
                    }
                    if (nameModal === "XH" && !response.exists) {
                        SNExist.push(serial);
                        isDuplicate = true; // Đánh dấu lỗi không tồn tại
                    }
                    if (nameModal === "CXH" && !response.exists) {
                        SNExist.push(serial);
                        isDuplicate = true; // Đánh dấu lỗi không tồn tại
                    }
                }
            });
        });

        // Nếu có serial trùng, dừng submit
        if (isDuplicate) {
            let message = '';

            if (nameModal === "NH" || nameModal === "CNH") {
                message = `Serial này đã có trong hệ thống: ${SNExist.join(", ")}`;
            } else if (nameModal === "XH" || nameModal === "CXH") {
                message = `Serial này không tồn tại hoặc đã được xuất: ${SNExist.join(", ")}`;
            }

            showAutoToast("warning", message);
            e.preventDefault(); // Ngăn form submit
        }

        // e.preventDefault(); // Nếu cần, hãy giữ lại để ngăn mặc định
        // Khởi tạo một Map để lưu sản phẩm duy nhất
        const uniqueProducts = new Map();

        // Duyệt qua từng hàng có thuộc tính data-product-id trong tbody
        $('#tbody-product-data .row-product[data-product-id]').each(function() {
            const $row = $(this); // Dòng hiện tại
            const product_id = $row.find('.product_id').val();
            const serial = $row.find('.serial').val();
            const note_seri = $row.find('.note_seri').val();
            const status_recept = $row.find('.status_recept').val();

            // Lấy bảo hành từ hàng .row-product
            const warranties = [];
            const productWarrantyName = $row.find('.name_warranty').val() || "Trọn bộ";
            const productWarrantyMonth = $row.find('.warranty').val() || 12;

            if (productWarrantyName && productWarrantyMonth) {
                warranties.push([productWarrantyName, productWarrantyMonth]);
            }

            // Tìm các hàng .row-warranty ngay sau .row-product
            let $nextRow = $row.next();

            while ($nextRow.hasClass('row-warranty')) {
                const name_warranty = $nextRow.find('.name_warranty').val() || "Trọn bộ";
                const warranty_month = $nextRow.find('.warranty').val() || 12;

                if (name_warranty && warranty_month) {
                    warranties.push([name_warranty, warranty_month]);
                }

                $nextRow = $nextRow.next();
            }

            // Khởi tạo object chứa dữ liệu cơ bản
            const productData = {
                product_id,
                serial,
                note_seri,
                status_recept
            };

            if (nameModal === "XH" || nameModal === "CXH") {
                productData.warranty = warranties;
            }

            // Tạo khóa duy nhất bao gồm cả note_seri
            const uniqueKey = `${product_id}-${serial}-${note_seri}`;

            // Thêm vào Map nếu chưa tồn tại
            if (!uniqueProducts.has(uniqueKey)) {
                uniqueProducts.set(uniqueKey, productData);
            }
        });

        // Chuyển Map thành mảng
        const uniqueProductsArray = Array.from(uniqueProducts.values());

        // Chuyển mảng thành chuỗi JSON và gán vào data-test
        $('#data-test').val(JSON.stringify(uniqueProductsArray));
    });

    if (nameModal === "XH") {
        $(document).on('click', '.search-info', function(e) {
            e.preventDefault();

            // Lấy thông tin từ data-* attributes
            const productId = $(this).data('id');
            const serialInputs = $('.seri-input-check').map(function() {
                return $(this).val().trim(); // Lấy giá trị từ input và loại bỏ khoảng trắng
            }).get();

            // Gửi serialNumbers tới server để kiểm tra
            if (serialInputs.length > 0) {
                $.ajax({
                    url: '/check-serial-numbers',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        productId: productId,
                        serialNumbers: serialInputs,
                    },
                    success: function(response) {
                        $('.seri-input-check').each(function() {
                            const serial = $(this).val().trim();

                            // Kiểm tra nếu serial hợp lệ
                            if (response.exists.includes(serial)) {
                                $(this).siblings('.check-icon').css('color', 'green').text(
                                    '✔');
                            }
                            // Nếu không hợp lệ
                            else if (response.invalid.includes(serial)) {
                                $(this).siblings('.check-icon').css('color', 'red').text(
                                    '✖');
                            }
                        });
                    },
                    error: function(xhr) {
                        console.error('Error checking serial numbers:', xhr.responseText);
                    },
                });
            }
        });
    }
</script>
