@include('partials.header', ['activeGroup' => 'manageProfess', 'activeName' => 'returnforms'])
@section('title', $title)
<form id="form-submit" action="{{ route('returnforms.update', $returnForm->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="content-wrapper--2Column m-0 min-height--none pr-2">
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
                                        {{ $returnForm->reception_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->form_code_receiving }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex mb-2 mr-2 p-1 border rounded" style="order: 0;">
                        <span class="text text-13-black m-0" style="flex: 2;">Loại phiếu :</span>
                        <div class="form-check form-check-inline mr-1">
                            <select class="form-check-input border-0 text text-13-black select-nodropdown"
                                name="status" id="status" disabled>
                                <option value="0">Chưa chọn</option>
                                <option value="1" {{ $returnForm->reception->form_type == 1 ? 'selected' : '' }}>
                                    Bảo hành</option>
                                <option value="2" {{ $returnForm->reception->form_type == 2 ? 'selected' : '' }}>
                                    Dịch
                                    vụ</option>
                                <option value="3" {{ $returnForm->reception->form_type == 3 ? 'selected' : '' }}>
                                    Bảo
                                    hành dịch vụ</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex mb-2 mr-2 p-1 border rounded" style="order: 0;">
                        <span class="text text-13-black m-0" style="flex: 2;">Trạng thái :</span>
                        <div class="form-check form-check-inline mr-1">
                            <select class="form-check-input border-0 text text-13-black" name="status" required
                                id="status">
                                <option value="1" {{ $returnForm->status == 1 ? 'selected' : '' }}>Hoàn
                                    thành</option>
                                <option value="2" {{ $returnForm->status == 2 ? 'selected' : '' }}>Không đồng ý
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex content__heading--right">
                    <div class="row m-0">
                        <a href="{{ route('returnforms.index') }}">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper2 px-0 py-0 margin-top-118">
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
                            <input type="text" id="return_code" name="return_code" style="flex:2;"
                                placeholder="Nhập thông tin" value="{{ $returnForm->return_code }}"
                                class="text-13-black w-50 border-0 bg-input-guest date_picker bg-input-guest-blue py-2 px-2">
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black btn-click required-label font-weight-bold"
                                style="flex: 1.6;">Khách
                                hàng</span>
                            <input placeholder="Nhập thông tin" autocomplete="off" required id="customer_name"
                                class="text-13-black w-100 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                value="{{ $returnForm->customer->customer_name }}" style="flex:2;" />
                            <input type="hidden" name="customer_id" id="customer_id"
                                value="{{ $returnForm->customer_id }}">
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
                            <input type="hidden" name="user_id" value="{{ $returnForm->user_id }}">
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
                                style=" flex:2;"
                                value="{{ \Carbon\Carbon::parse($returnForm->date_created)->format('Y-m-d') }}" />
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black btn-click" style="flex: 1.6;"> Người liên hệ </span>
                            <input name="contact_person" placeholder="Nhập thông tin" autocomplete="off"
                                class="text-13-black w-100 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                id="contact_person" value="{{ $returnForm->contact_person }}" style="flex:2;" />
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3" style="flex: 1.5;">SĐT liên hệ</span>
                            <input class="text-13-black w-50 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"
                                autocomplete="off" placeholder="Nhập thông tin" style="flex:2;" name="phone_number"
                                id="phone_number" value="{{ $returnForm->phone_number }}" />
                        </div>

                    </div>
                    <div class="d-flex w-100">
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3" style="flex:.3;">Địa chỉ</span>
                            <input placeholder="Nhập thông tin" name="address" value="{{ $returnForm->address }}"
                                id="address"
                                class="text-13-black w-50 border-0 bg-input-guest bg-input-guest-blue py-2 px-2"style="flex:2;" />
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3" style="flex:.3;">Phương thức trả hàng</span>
                            <select
                                class="text-13-black w-50 border-0 addr bg-input-guest addr bg-input-guest-blue py-2 px-2"
                                style="flex:2;" name="return_method" id="return_method">
                                <option value="1" {{ $returnForm->return_method == 1 ? 'selected' : '' }}>Khách
                                    nhận trực tiếp</option>
                                <option value="2" {{ $returnForm->return_method == 2 ? 'selected' : '' }}>Chuyển
                                    phát nhanh</option>
                                <option value="3" {{ $returnForm->return_method == 3 ? 'selected' : '' }}>Gửi
                                    chành xe</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex w-100">
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            <span class="text-13-black text-nowrap mr-3" style="flex:.3;">Ghi chú</span>
                            <input name="notes" placeholder="Nhập thông tin" autocomplete="off"
                                value="{{ $returnForm->notes }}"
                                class="text-13-black w-50 border-0 addr bg-input-guest addr bg-input-guest-blue py-2 px-2"style="flex:2;" />
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                        </div>
                        <div
                            class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
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
                    <p class="font-weight-bold text-uppercase info-chung--heading text-center">THÔNG TIN HÀNG HOÁ/DỊCH
                        VỤ
                    </p>
                </div>
                <div class="container-fluided">
                    <section class="content overflow-content-quote" style="overflow-x:visible">
                        <table class="table" id="inputcontent">
                            @php
                                $hideReplacement =
                                    $returnForm->reception->form_type == 2 || $returnForm->reception->form_type == 3
                                        ? 'd-none'
                                        : '';
                                $hideExtraWarranty =
                                    $returnForm->reception->form_type == 1 || $returnForm->reception->form_type == 3
                                        ? 'd-none'
                                        : '';
                            @endphp
                            <thead>
                                <tr style="height:44px;">
                                    <th class="border-right px-2 p-0 pl-4" style="width:8%;">
                                        <span class="text-table text-secondary">Mã hàng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-left" style="width:25%;">
                                        <span class="text-table text-secondary">Tên Hàng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-left" style="width:10%;">
                                        <span class="text-table text-secondary">Hãng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-right" style="width:7%;">
                                        <span class="text-table text-secondary">Số lượng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-right" style="width:10%;">
                                        <span class="text-table text-secondary">Serial Number</span>
                                    </th>
                                    <th class="border-right note px-2 p-0 text-left {{ $hideReplacement }}"
                                        style="width:10%;">
                                        <span class="text-table text-secondary">Mã hàng đổi</span>
                                    </th>
                                    <th class="border-right note px-2 p-0 text-left {{ $hideReplacement }}"
                                        style="width:10%;">
                                        <span class="text-table text-secondary">Serial Number đổi</span>
                                    </th>
                                    <th class="border-right note px-2 p-0 text-left {{ $hideExtraWarranty }}"
                                        style="width:8%;">
                                        <span class="text-table text-secondary">Bảo hành thêm</span>
                                    </th>
                                    <th class="border-right note px-2 p-0 text-left" style="width:12%;">
                                        <span class="text-table text-secondary">Ghi chú</span>
                                    </th>
                                </tr>

                            </thead>
                            <tbody id="tbody-data">
                                @foreach ($returnProducts as $id => $item)
                                    <tr class="row-product bg-white">
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                            <input type="hidden" name="return[{{ $id }}][product_id]"
                                                value="{{ $item->product_id }}">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 product_code height-32" readonly
                                                name="return[{{ $id }}][product_code]"
                                                value="{{ $item->product->product_code }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 product_name height-32" readonly
                                                name="return[{{ $id }}][product_name]"
                                                value="{{ $item->product->product_name }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 brand height-32" readonly
                                                name="return[{{ $id }}][brand]"
                                                value="{{ $item->product->brand }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 quantity height-32" readonly
                                                name="return[{{ $id }}][quantity]"
                                                value="{{ $item->quantity }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                            <input type="hidden" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 serial_id height-32" readonly
                                                name="return[{{ $id }}][serial_id]"
                                                value="{{ $item->serialNumber->id }}">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 serial_code height-32" readonly
                                                name="return[{{ $id }}][serial_code]"
                                                value="{{ $item->serialNumber->serial_code }}">
                                        </td>
                                        <td
                                            class="border-right p-2 text-13 align-top border-bottom border-top-0 position-relative {{ $hideReplacement }}">
                                            <input type="hidden" min="0" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 replacement_code height-32 bg-input-guest-blue"
                                                id="replacement_code_{{ $id }}"
                                                name="return[{{ $id }}][replacement_code]"
                                                value="{{ $item->product_replace->id ?? null }}">
                                            <div class="search-container">
                                                <input type="text"
                                                    class="search-input border-0 pl-1 pr-2 py-1 w-100 serial_code height-32"
                                                    value="{{ $item->product_replace->product_code ?? '' }}"
                                                    placeholder="Search..." />
                                                <ul class="search-list border rounded">
                                                    @foreach ($dataProduct as $product)
                                                        <li class="search-item p-2 border-bottom"
                                                            data-id="{{ $id }}"
                                                            data-replace_id="{{ $product->id }}"
                                                            data-code="{{ $product->product_code }}">
                                                            {{ $product->product_code }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                        <td
                                            class="border-right p-2 text-13 align-top border-bottom border-top-0 {{ $hideReplacement }}">
                                            <input type="text" min="0" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 replacement_serial_number_id height-32 bg-input-guest-blue"
                                                name="return[{{ $id }}][replacement_serial_number_id]"
                                                value="{{ $item->replacementSerialNumber->serial_code ?? '' }}">
                                        </td>
                                        <td
                                            class="border-right p-2 text-13 align-top border-bottom border-top-0 {{ $hideExtraWarranty }}">
                                            <input type="number" min="0" max="100" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 extra_warranty height-32 bg-input-guest-blue"
                                                name="return[{{ $id }}][extra_warranty]"
                                                value="{{ $item->extra_warranty ?? '' }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 note height-32 bg-input-guest-blue"
                                                name="return[{{ $id }}][note]"
                                                value="{{ $item->notes ?? '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="{{ asset('js/returnform.js') }}"></script>
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
                        $('#status').val(response.data.form_type).change();
                        populateTableRows(response.product, "#tbody-data");
                        $('#customer_name').val(response.data.customer.customer_name);
                        $('#customer_id').val(response.data.customer_id);
                        $('#contact_person').val(response.data.contact_person);
                        $('#phone_number').val(response.data.phone);
                        $('#address').val(response.data.address);
                    }
                });
            }
        });
    });
</script>
