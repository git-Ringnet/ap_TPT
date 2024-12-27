@include('partials.header', ['activeGroup' => 'manageProfess', 'activeName' => 'quotations'])
@section('title', $title)
<form id="form-submit" action="{{ route('quotations.update', $quotation->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="content-wrapper--2Column m-0 min-height--none">
        <div class="content-header-fixed-report-1 p-0 border-bottom-0">
            <div class="content__header--inner pl-4">
                <div class="content__heading--left d-flex opacity-1">
                    <div class="d-flex mb-2 mr-2 p-1 border rounded" style="order: 0;">
                        <span class="text text-13-black m-0" style="flex: 2;">Chọn phiếu tiếp nhận :</span>
                        <div class="form-check form-check-inline mr-1">
                            <select class="form-check-input border-0 text text-13-black" name="reception_id" required
                                id="reception">
                                <option value="">Chưa chọn phiếu</option>
                                @foreach ($receivings as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $quotation->reception_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->form_code_receiving }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex content__heading--right">
                    <div class="row m-0">
                        <a href="{{ route('quotations.index') }}">
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
                        <button id="sideGuest" type="button" class="btn-option border-0 mx-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.375 3C3.68262 3 1.5 5.18262 1.5 7.875V16.1248C1.5 18.8173 3.68262 20.9998 6.375 20.9998H17.625C20.3174 20.9998 22.5 18.8173 22.5 16.1248V7.875C22.5 5.18262 20.3174 3 17.625 3H6.375ZM3.75 15.7498C3.75 17.4067 5.09314 18.7498 6.75 18.7498H17.625C19.0748 18.7498 20.25 17.5746 20.25 16.1248V7.875C20.25 6.42527 19.0748 5.25 17.625 5.25H6.75C5.09314 5.25 3.75 6.59314 3.75 8.25V15.7498Z"
                                    fill="#151516" />
                                <path d="M15.75 4.5H13.5V19.5H15.75V4.5Z" fill="#151516" />
                                <path d="M21 4.5H15V19.5H21V12.5V4.5Z" fill="#151516" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper2 px-0 py-0 margin-top-118">
            <div class="" id="main">
                {{-- Thông tin khách hàng --}}
                <div class="border">
                    <div class="info-form">
                        <div class="bg-filter-search border-0 text-center">
                            <p class="font-weight-bold text-uppercase info-chung--heading text-center">
                                THÔNG TIN PHIẾU BÁO GIÁ
                            </p>
                        </div>
                        <div class="d-flex w-100">
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3 required-label" style="flex: 1.5;">Mã
                                    phiếu</span>
                                <input type="text" id="quotation_code" name="quotation_code" style="flex:2;"
                                    placeholder="Nhập thông tin" value="{{ $quotation->quotation_code }}"
                                    class="text-13-black w-50 border-0 bg-input-guest date_picker bg-input-guest-blue py-2 px-2">
                            </div>
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black btn-click required-label font-weight-bold"
                                    style="flex: 1.6;">Khách hàng</span>
                                <input placeholder="Nhập thông tin" autocomplete="off" required id="customer_name"
                                    class="text-13-black w-100 border-0 bg-input-guest py-2 px-2"
                                    value="{{ $quotation->customer->customer_name }}" style="flex:2;" readonly />
                                <input type="hidden" name="customer_id" id="customer_id"
                                    value="{{ $quotation->customer_id }}">
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

                                    </div>
                                </div>
                            </div>
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3" style="flex: 1.5;">Người lập phiếu</span>
                                <input type="hidden" name="user_id" value="{{ $quotation->user_id }}">
                                <input class="text-13-black w-50 border-0 bg-input-guest py-2 px-2" autocomplete="off"
                                    placeholder="Nhập thông tin" style="flex:2;" name=""
                                    value="{{ Auth::user()->name }}" readonly />
                            </div>
                        </div>
                        <div class="d-flex w-100">
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3 required-label" style="flex: 1.5;">Ngày
                                    lập
                                    phiếu</span>
                                <input name="quotation_date" placeholder="Nhập thông tin" autocomplete="off"
                                    type="date"
                                    class="text-13-black w-50 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                    style=" flex:2;"
                                    value="{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('Y-m-d') }}" />
                            </div>
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black btn-click" style="flex: 1.6;"> Người liên hệ </span>
                                <input name="contact_person" placeholder="Nhập thông tin" autocomplete="off"
                                    class="text-13-black w-100 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                    id="contact_person" value="{{ $quotation->contact_person }}" style="flex:2;" />
                            </div>
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3" style="flex: 1.5;">SĐT liên hệ</span>
                                <input class="text-13-black w-50 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                    autocomplete="off" placeholder="Nhập thông tin" style="flex:2;" name="phone"
                                    id="phone" value="{{ $quotation->phone }}" />
                            </div>

                        </div>
                        <div class="d-flex w-100">
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3" style="flex:.3;">Địa chỉ</span>
                                <input placeholder="Nhập thông tin" name="address" value="{{ $quotation->address }}"
                                    id="address"
                                    class="text-13-black w-50 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"style="flex:2;" />
                            </div>
                        </div>
                        <div class="d-flex w-100">
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3" style="flex:.3;">Ghi chú</span>
                                <input name="notes" placeholder="Nhập thông tin" autocomplete="off"
                                    value="{{ $quotation->notes }}"
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
                            HOÁ/DỊCH
                            VỤ
                        </p>
                    </div>
                    <div class="container-fluided">
                        <section class="content overflow-content-quote">
                            <table class="table" id="inputcontent">
                                <thead>
                                    <tr style="height:44px;">
                                        <th class="border-right px-2 p-0 pl-4" style="width:5%;">
                                            <span class="text-table text-secondary">STT</span>
                                        </th>
                                        <th class="border-right px-2 p-0 text-left" style="width:25%;">
                                            <span class="text-table text-secondary">Thông tin hàng hoá/dịch vụ</span>
                                        </th>
                                        <th class="border-right px-2 p-0 text-left" style="width:10%;">
                                            <span class="text-table text-secondary">ĐVT</span>
                                        </th>
                                        <th class="border-right px-2 p-0 text-right" style="width:10%;">
                                            <span class="text-table text-secondary">Hãng</span>
                                        </th>
                                        <th class="border-right px-2 p-0 text-right" style="width:5%;">
                                            <span class="text-table text-secondary">Số lượng</span>
                                        </th>
                                        <th class="border-right px-2 p-0 text-right" style="width:8%;">
                                            <span class="text-table text-secondary">Đơn giá</span>
                                        </th>
                                        <th class="border-right note px-2 p-0 text-left" style="width:5%;">
                                            <span class="text-table text-secondary">Thuế</span>
                                        </th>
                                        <th class="border-right note px-2 p-0 text-left" style="width:10%;">
                                            <span class="text-table text-secondary">Thành tiền</span>
                                        </th>
                                        <th class="border-right note px-2 p-0 text-left" style="width:15%;">
                                            <span class="text-table text-secondary">Ghi chú</span>
                                        </th>
                                        <th class="" style="width:10%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-data">
                                    @foreach ($quotationServices as $id => $item)
                                        <tr class="row-product bg-white">
                                            <td
                                                class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                                {{ $loop->iteration }} <!-- Sử dụng vòng lặp Laravel để đánh số -->
                                            </td>
                                            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                                <input type="text" autocomplete="off"
                                                    class="border-0 pl-1 pr-2 py-1 w-100 service_name height-32 bg-input-guest-blue"
                                                    name="services[{{ $id }}][service_name]"
                                                    value="{{ $item->service_name }}" required>
                                            </td>
                                            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                                <input type="text" autocomplete="off"
                                                    class="border-0 pl-1 pr-2 py-1 w-100 unit height-32 bg-input-guest-blue"
                                                    name="services[{{ $id }}][unit]"
                                                    value="{{ $item->unit }}">
                                            </td>
                                            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                                <input type="text" autocomplete="off"
                                                    class="border-0 pl-1 pr-2 py-1 w-100 brand height-32 bg-input-guest-blue"
                                                    name="services[{{ $id }}][brand]"
                                                    value="{{ $item->brand }}">
                                            </td>
                                            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                                <input type="text" min="1" autocomplete="off"
                                                    class="border-0 pl-1 pr-2 py-1 w-100 quantity height-32 bg-input-guest-blue"
                                                    name="services[{{ $id }}][quantity]"
                                                    value="{{ $item->quantity }}">
                                            </td>
                                            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                                <input type="text" step="0.01" min="0"
                                                    autocomplete="off"
                                                    class="border-0 pl-1 pr-2 py-1 w-100 unit_price height-32 bg-input-guest-blue"
                                                    name="services[{{ $id }}][unit_price]"
                                                    value="{{ $item->unit_price }}">
                                            </td>
                                            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                                <select
                                                    class="border-0 pl-1 pr-2 py-1 w-100 height-32 bg-input-guest-blue tax_rate"
                                                    name="services[{{ $id }}][tax_rate]">
                                                    <option value="10"
                                                        {{ $item->tax_rate == 10 ? 'selected' : '' }}>
                                                        10%</option>
                                                    <option value="8"
                                                        {{ $item->tax_rate == 8 ? 'selected' : '' }}>8%
                                                    </option>
                                                    <option value="0"
                                                        {{ $item->tax_rate == 0 ? 'selected' : '' }}>
                                                        KCT</option>
                                                </select>
                                            </td>
                                            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                                <input type="text" step="0.01" min="0" readonly
                                                    class="border-0 pl-1 pr-2 py-1 w-100 total height-32"
                                                    name="services[{{ $id }}][total]"
                                                    value="{{ $item->total }}">
                                            </td>
                                            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                                <input type="text" autocomplete="off"
                                                    class="border-0 pl-1 pr-2 py-1 w-100 note height-32 bg-input-guest-blue"
                                                    name="services[{{ $id }}][note]"
                                                    value="{{ $item->note }}">
                                            </td>
                                            <td class="p-2 align-top activity border-bottom border-top-0 border-right">
                                                <button type="button" class="delete-row btn btn-sm"> <svg
                                                        class="delete-row" width="17" height="17"
                                                        viewBox="0 0 17 17" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M13.1417 6.90625C13.4351 6.90625 13.673 7.1441 13.673 7.4375C13.673 7.47847 13.6682 7.5193 13.6589 7.55918L12.073 14.2992C11.8471 15.2591 10.9906 15.9375 10.0045 15.9375H6.99553C6.00943 15.9375 5.15288 15.2591 4.92702 14.2992L3.34113 7.55918C3.27393 7.27358 3.45098 6.98757 3.73658 6.92037C3.77645 6.91099 3.81729 6.90625 3.85826 6.90625H13.1417ZM9.03125 1.0625C10.4983 1.0625 11.6875 2.25175 11.6875 3.71875H13.8125C14.3993 3.71875 14.875 4.19445 14.875 4.78125V5.3125C14.875 5.6059 14.6371 5.84375 14.3438 5.84375H2.65625C2.36285 5.84375 2.125 5.6059 2.125 5.3125V4.78125C2.125 4.19445 2.6007 3.71875 3.1875 3.71875H5.3125C5.3125 2.25175 6.50175 1.0625 7.96875 1.0625H9.03125ZM9.03125 2.65625H7.96875C7.38195 2.65625 6.90625 3.13195 6.90625 3.71875H10.0938C10.0938 3.13195 9.61805 2.65625 9.03125 2.65625Z"
                                                            fill="#6B6F76"></path>
                                                    </svg></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <input type="hidden" name="data-test" id="data-test">
                            <section class="content mt-2">
                                <div class="container-fluided">
                                    <div class="d-flex ml-4">
                                        <button type="button" data-toggle="modal" data-target="#modal-id"
                                            class="btn-save-print d-flex align-items-center h-100 py-1 px-2 rounded activity"
                                            id="btn-add-row" style="margin-right:10px">
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
                            <div class="content">
                                <div class="row" style="width:95%;">
                                    <div class="position-relative col-lg-6 px-0"></div>
                                    <div class="position-relative col-lg-3 col-md-7 col-sm-12 margin-left180">
                                        <div class="m-3 ">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-14-black">Giá trị trước thuế:</span>
                                                <span id="total-amount-sum" class="text-14-black">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2 align-items-center">
                                                <span class="text-14-black">VAT 8%</span>
                                                <span id="product-tax8" class="text-14-black">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2 align-items-center">
                                                <span class="text-14-black">VAT 10%</span>
                                                <span id="product-tax10" class="text-14-black">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <span class="text-20-bold">Tổng cộng:</span>
                                                <span id="grand-total" class="text-20-bold text-right">
                                                    0
                                                </span>
                                                <input type="hidden" name="total_amount" value="0"
                                                    id="total">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            {{-- View mini --}}
            <x-view_mini :guestOrProvider="$customers" :users="$users" name="BG" :data="$data"></x-view_mini>
        </div>
    </div>
</form>
<script src="{{ asset('js/quotation.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#reception').change(function() {
            var selectedId = $(this).val();
            if (selectedId != 0) {
                // Gửi yêu cầu AJAX tới server để lấy dữ liệu chi tiết
                $.ajax({
                    url: '{{ route('getReceiving') }}',
                    type: 'GET',
                    async: false,
                    data: {
                        selectedId: selectedId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#customer_name').val(response.data.customer.customer_name);
                        $('#customer_id').val(response.data.customer_id);
                        $('#contact_person').val(response.data.contact_person);
                        $('#phone').val(response.data.phone);
                        $('#address').val(response.data.address);
                    }
                });
            }
        });
    });
    $(document).ready(function() {
        calculateTotals();
    });
</script>
