@include('partials.header', ['activeGroup' => 'manageProfess', 'activeName' => 'receivings'])
<form id="form-submit" action="{{ route('receivings.store') }}" method="POST">
    @csrf
    <div class="content-wrapper--2Column m-0 min-height--none">
        <div class="content-header-fixed-report-1 p-0 border-bottom-0">
            <div class="content__header--inner pl-4">
                <div class="content__heading--left d-flex opacity-1">
                    <div class="d-flex mb-2 mr-2 p-1 border rounded box-shadow-border" style="order: 0;">
                        <span class="text text-13-black m-0" style="flex: 2;">Hãng tiếp nhận :</span>
                        <div class="form-check form-check-inline mr-1">
                            <label class="text text-13-black form-check-label mr-1" for="internal">Nội bộ</label>
                            <input type="radio" class="form-check-input hanguTiepNhan" id="internal" name="branch_id"
                                value="1">
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text text-13-black mr-1" for="external">Bên ngoài</label>
                            <input type="radio" class="form-check-input hanguTiepNhan" id="external" name="branch_id"
                                value="2">
                        </div>
                    </div>
                    <div class="d-flex mb-2 mr-2 p-1 border rounded box-shadow-border" style="order: 0;">
                        <span class="text text-13-black m-0" style="flex: 2;">Loại phiếu :</span>
                        <div class="form-check form-check-inline mr-1">
                            <label class="text text-13-black form-check-label mr-1" for="warranty">Bảo hành</label>
                            <input type="radio" class="form-check-input loaiPhieu" id="warranty" name="form_type"
                                value="1">
                        </div>
                        <div class="form-check form-check-inline mr-1">
                            <label class="text text-13-black form-check-label mr-1" for="service">Dịch vụ</label>
                            <input type="radio" class="form-check-input loaiPhieu" id="service" name="form_type"
                                value="2">
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text text-13-black mr-1" for="serviceWarranty">Bảo hành dịch
                                vụ</label>
                            <input type="radio" class="form-check-input loaiPhieu" id="serviceWarranty"
                                name="form_type" value="3">
                        </div>
                    </div>
                </div>
                <div class="d-flex content__heading--right">
                    <div class="row m-0">
                        <a href="#">
                            <button type="button" class="btn-destroy btn-light mx-1 d-flex align-items-center h-100">
                                <svg class="mx-1" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8 15C11.866 15 15 11.866 15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15ZM6.03033 4.96967C5.73744 4.67678 5.26256 4.67678 4.96967 4.96967C4.67678 5.26256 4.67678 5.73744 4.96967 6.03033L6.93934 8L4.96967 9.96967C4.67678 10.2626 4.67678 10.7374 4.96967 11.0303C5.26256 11.3232 5.73744 11.3232 6.03033 11.0303L8 9.06066L9.96967 11.0303C10.2626 11.3232 10.7374 11.3232 11.0303 11.0303C11.3232 10.7374 11.3232 10.2626 11.0303 9.96967L9.06066 8L11.0303 6.03033C11.3232 5.73744 11.3232 5.26256 11.0303 4.96967C10.7374 4.67678 10.2626 4.67678 9.96967 4.96967L8 6.93934L6.03033 4.96967Z"
                                        fill="black" />
                                </svg>
                                <p class="m-0 p-0 text-dark">Hủy</p>
                            </button>
                        </a>
                        <button type="submit" class="custom-btn d-flex align-items-center h-100 mx-1 mr-4"
                            id="btn-get-unique-products">
                            <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 16 16" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8 15C11.866 15 15 11.866 15 8C15 4.13401 11.866 1 8 1C4.13401 1 1 4.13401 1 8C1 11.866 4.13401 15 8 15ZM11.7836 6.42901C12.0858 6.08709 12.0695 5.55006 11.7472 5.22952C11.4248 4.90897 10.9186 4.9263 10.6164 5.26821L7.14921 9.19122L5.3315 7.4773C5.00127 7.16593 4.49561 7.19748 4.20208 7.54777C3.90855 7.89806 3.93829 8.43445 4.26852 8.74581L6.28032 10.6427C6.82041 11.152 7.64463 11.1122 8.13886 10.553L11.7836 6.42901Z"
                                    fill="white" />
                            </svg>
                            <p class="m-0 p-0">Xác nhận</p>
                        </button>
                        <button id="sideGuest" type="button" class="btn-option border-0 mx-1">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="16" width="16" height="16" rx="5" transform="rotate(90 16 0)"
                                    fill="#ECEEFA"></rect>
                                <path
                                    d="M15 11C15 13.2091 13.2091 15 11 15L5 15C2.7909 15 1 13.2091 1 11L1 5C1 2.79086 2.7909 1 5 1L11 1C13.2091 1 15 2.79086 15 5L15 11ZM10 13.5L10 2.5L5 2.5C3.6193 2.5 2.5 3.61929 2.5 5L2.5 11C2.5 12.3807 3.6193 13.5 5 13.5H10Z"
                                    fill="#26273B" fill-opacity="0.8"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper2 px-0 py-0 margin-top-118 blur-wrapper" id="content-wrapper">
            <div id="contextMenuPBH" class="dropdown-menu"
                style="display: none; background: #ffffff; position: absolute; width:13%;  padding: 3px 10px;  box-shadow: 0 0 10px -3px rgba(0, 0, 0, .3); border: 1px solid #ccc;">
                <a class="dropdown-item text-13-black" href="#" data-option="donhang">Tạo phiếu xuất kho</a>
            </div>
            {{-- Thông tin khách hàng --}}
            <div class="border">
                <div class="info-form">
                    <div class="bg-filter-search border-0 text-center">
                        <p class="font-weight-bold text-uppercase info-chung--heading text-center">
                            THÔNG TIN PHIẾU TIẾP NHẬN
                        </p>
                    </div>
                    <div class="d-flex w-100">
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3 required-label" style="flex: 1.5;">Mã
                                phiếu</span>
                            <input type="text" id="form_code_receiving" name="form_code_receiving"
                                style="flex:2;" placeholder="Nhập thông tin" value="{{ $quoteNumber }}"
                                class="text-13-black w-50 border-0 bg-input-guest date_picker bg-input-guest-blue py-2 px-2">
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black btn-click required-label font-weight-bold"
                                style="flex: 1.6;">Khách hàng</span>
                            <input placeholder="Nhập thông tin" autocomplete="off" required id="customer_name"
                                class="text-13-black w-100 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                style="flex:2;" />
                            <input type="hidden" name="customer_id" id="customer_id">
                            <div class="">
                                <div id="listCustomer"
                                    class="bg-white position-absolute rounded list-guest shadow p-1 z-index-block"
                                    style="z-index: 99;display: none;">
                                    <div class="p-1">
                                        <div class="position-relative">
                                            <input type="text" placeholder="Nhập thông tin"
                                                class="pr-4 w-100 input-search bg-input-guest" id="searchCustomer">
                                            <span id="search-icon" class="search-icon">
                                                <i class="fas fa-search text-table" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <ul class="m-0 p-0 scroll-data">
                                        @foreach ($customers as $item)
                                            <li class="p-2 align-items-center text-wrap border-top"
                                                data-id="{{ $item->id }}">
                                                <a href="#" title="{{ $item->customer_name }}" style="flex:2;"
                                                    id="{{ $item->id }}" data-name="{{ $item->customer_name }}"
                                                    data-phone="{{ $item->phone }}"
                                                    data-address="{{ $item->address }}" name="search-info"
                                                    class="search-info">
                                                    <span class="text-13-black-black">{{ $item->customer_name }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3" style="flex: 1.5;">Người lập phiếu</span>
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input class="text-13-black w-50 border-0 bg-input-guest py-2 px-2" autocomplete="off"
                                placeholder="Nhập thông tin" style="flex:2;" name=""
                                value="{{ Auth::user()->name }}" readonly />
                        </div>
                    </div>
                    <div class="d-flex w-100">
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3 required-label" style="flex: 1.5;">Ngày lập
                                phiếu</span>
                            <input name="date_created" placeholder="Nhập thông tin" autocomplete="off"
                                type="date"
                                class="text-13-black w-50 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                style=" flex:2;" value="{{ now()->format('Y-m-d') }}" />
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black btn-click" style="flex: 1.6;"> Người liên hệ </span>
                            <input name="contact_person" placeholder="Nhập thông tin" autocomplete="off"
                                class="text-13-black w-100 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                style="flex:2;" />
                            <div class="">
                                <div id="myUL"
                                    class="bg-white position-absolute rounded list-guest shadow p-1 z-index-block"
                                    style="z-index: 99;display: none;">
                                    <div class="p-1">
                                        <div class="position-relative">
                                            <input type="text" placeholder="Nhập công ty"
                                                class="pr-4 w-100 input-search bg-input-guest" id="companyFilter">
                                            <span id="search-icon" class="search-icon">
                                                <i class="fas fa-search text-table" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <ul class="m-0 p-0 scroll-data">
                                        {{-- @foreach ($guest as $guest_value)
                                            <li class="p-2 align-items-center text-wrap border-top"
                                                data-id="{{ $guest_value->id }}">
                                                <a href="#" title="{{ $guest_value->guest_name_display }}"
                                                    style="flex:2;" id="{{ $guest_value->id }}"
                                                    name="search-info" class="search-info">
                                                    <span
                                                        class="text-13-black">{{ $guest_value->guest_name_display }}</span>
                                                </a>
                                            </li>
                                        @endforeach --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3" style="flex: 1.5;">SĐT liên hệ</span>
                            <input class="text-13-black w-50 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                autocomplete="off" placeholder="Nhập thông tin" style="flex:2;" name="phone" />
                        </div>

                    </div>
                    <div class="d-flex w-100">
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3" style="flex:.3;">Địa chỉ</span>
                            <input id="" placeholder="Nhập thông tin" name="address"
                                class="text-13-black w-50 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"style="flex:2;" />
                        </div>
                    </div>
                    <div class="d-flex w-100">
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3" style="flex:.3;">Ghi chú</span>
                            <input name="notes" placeholder="Nhập thông tin" autocomplete="off"
                                class="text-13-black w-50 border-0 addr bg-input-guest addr bg-input-guest-blue py-2 px-2"style="flex:2;" />
                        </div>
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            {{-- Thông tin sản phẩm --}}
            <div class="content">
                <div id="title--fixed" class="bg-filter-search text-center border-custom border-0">
                    <p class="font-weight-bold text-uppercase info-chung--heading text-center">THÔNG TIN HÀNG
                    </p>
                </div>
                <div class="container-fluided">
                    <section class="content overflow-content-quote">
                        <table class="table" id="inputcontent">
                            <thead>
                                <tr style="height:44px;">
                                    <th class="border-right px-2 p-0 pl-4" style="width:10%;">
                                        <span class="text-table text-secondary">Mã hàng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-left" style="width:20%;">
                                        <span class="text-table text-secondary">Tên hàng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-left" style="width:10%;">
                                        <span class="text-table text-secondary">Hãng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-right" style="width:10%;">
                                        <span class="text-table text-secondary">Số lượng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-right" style="width:15%;">
                                        <span class="text-table text-secondary">Serial Number</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-right" style="width:20%;">
                                        <span class="text-table text-secondary">Tình trạng tiếp nhận</span>
                                    </th>
                                    <th class="border-right note px-2 p-0 text-left" style="width:10%;">
                                        <span class="text-table text-secondary">Ghi chú</span>
                                    </th>
                                    <th class="" style="width:10%;"></th>
                                </tr>
                            </thead>
                            <tbody id="tbody-product-data">
                            </tbody>
                        </table>
                        <input type="hidden" name="data-test" id="data-test">
                        <section class="content mt-2">
                            <div class="container-fluided">
                                <div class="d-flex ml-4">
                                    <button type="button" data-toggle="modal" data-target="#modal-id"
                                        class="btn-save-print d-flex align-items-center h-100 py-1 px-2 rounded activity"
                                        style="margin-right:10px">
                                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="12"
                                            height="12" viewBox="0 0 18 18" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9 0C9.58186 -2.96028e-08 10.0536 0.471694 10.0536 1.05356L10.0536 16.9464C10.0536 17.5283 9.58186 18 9 18C8.41814 18 7.94644 17.5283 7.94644 16.9464V1.05356C7.94644 0.471694 8.41814 -2.96028e-08 9 0Z"
                                                fill="#42526E" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M18 9C18 9.58187 17.5283 10.0536 16.9464 10.0536H1.05356C0.471694 10.0536 -2.07219e-07 9.58187 0 9C-7.69672e-07 8.41814 0.471695 7.94644 1.05356 7.94644H16.9464C17.5283 7.94644 18 8.41814 18 9Z"
                                                fill="#42526E" />
                                        </svg>
                                        <span class="text-table">Thêm sản phẩm</span>
                                    </button>
                                </div>
                            </div>
                        </section>
                        <x-add-product-modal :id="'modal-id'" title="Thêm sản phẩm" :data-product="$products"
                            name="TN" />
                    </section>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="{{ asset('js/addproduct.js') }}"></script>
<script>
    $(document).ready(function() {
        // Kiểm tra các radio button khi thay đổi giá trị
        $('input[type="radio"]').on('change', function() {
            // Kiểm tra xem có ít nhất một radio button được chọn cho mỗi nhóm
            let isBranchChecked = $('input[name="branch_id"]:checked').length > 0;
            let isFormTypeChecked = $('input[name="form_type"]:checked').length > 0;

            // Lấy phần tử có id="content-wrapper"
            let contentWrapper = $('#content-wrapper');

            // Nếu cả hai nhóm đều có radio button được chọn, remove class blur-wrapper
            if (isBranchChecked && isFormTypeChecked) {
                contentWrapper.removeClass('blur-wrapper');
            } else {
                // Nếu không, thêm class blur-wrapper
                contentWrapper.addClass('blur-wrapper');
            }
        });
    });
    $(document).ready(function() {
        function toggleListGuest(input, list, filterInput) {
            input.on("click", function() {
                list.show();
            });
            $(document).click(function(event) {
                if (
                    !$(event.target).closest(input).length &&
                    !$(event.target).closest(filterInput).length
                ) {
                    list.hide();
                }
            });
            var applyFilter = function() {
                var value = filterInput.val().toUpperCase();
                list.find("li").each(function() {
                    var text = $(this).find("a").text().toUpperCase();
                    $(this).toggle(text.indexOf(value) > -1);
                });
            };
            input.on("keyup", applyFilter);
            filterInput.on("keyup", applyFilter);
        }
        toggleListGuest(
            $("#customer_name"),
            $("#listCustomer"),
            $("#searchCustomer")
        );
        $('a[name="search-info"]').on('click', function() {
            const dataId = $(this).attr('id');
            const dataName = $(this).data('name');
            const phone = $(this).data('phone');
            const address = $(this).data('address');
            $("#customer_id").val(dataId);
            $("#customer_name").val(dataName);
            $('[name="phone"]').val(phone);
            $('[name="address"]').val(address);
        });
    });
</script>
