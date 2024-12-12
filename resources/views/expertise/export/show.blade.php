@include('partials.header', ['activeGroup' => 'manageProfess', 'activeName' => 'exports'])
@section('title', $title)
<div class="content-wrapper--2Column m-0 min-height--none">
    <div class="content-header-fixed-report-1 p-0 border-bottom-0">
        <div class="content__header--inner">
            <div class="content__heading--left opacity-0"></div>
            <div class="d-flex content__heading--right">
                <div class="row m-0">
                    <div class="dropdown mx-1">
                        <a href="{{ route('exports.index') }}">
                            <button type="button"
                                class="btn-save-print rounded mx-1 py-1 px-2 d-flex align-items-center h-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16" fill="none">
                                    <path
                                        d="M5.6738 11.4801C5.939 11.7983 6.41191 11.8413 6.73012 11.5761C7.04833 11.311 7.09132 10.838 6.82615 10.5198L5.3513 8.75H12.25C12.6642 8.75 13 8.41421 13 8C13 7.58579 12.6642 7.25 12.25 7.25L5.3512 7.25L6.82615 5.4801C7.09132 5.1619 7.04833 4.689 6.73012 4.4238C6.41191 4.1586 5.939 4.2016 5.6738 4.5198L3.1738 7.51984C2.942 7.79798 2.942 8.20198 3.1738 8.48012L5.6738 11.4801Z"
                                        fill="black" />
                                </svg>
                                <span class="text-button text-dark ml-2 font-weight-bold">Trở về</span>
                            </button>
                        </a>
                    </div>
                    <div class="dropdown mx-1">
                        <button type="button" data-toggle="dropdown"
                            class="btn-save-print border-0 rounded d-flex align-items-center h-100 dropdown-toggle px-2 bg-click">
                            <span class="text-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M18 2C18 0.89543 17.1046 0 16 0C14.8954 0 14 0.89543 14 2C14 3.10457 14.8954 4 16 4C17.1046 4 18 3.10457 18 2Z"
                                        fill="#151516" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11 2C11 0.89543 10.1046 0 9 0C7.89543 0 7 0.89543 7 2C7 3.10457 7.89543 4 9 4C10.1046 4 11 3.10457 11 2Z"
                                        fill="#151516" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4 2C4 0.89543 3.10457 0 2 0C0.895431 0 0 0.89543 0 2C0 3.10457 0.895431 4 2 4C3.10457 4 4 3.10457 4 2Z"
                                        fill="#151516" />
                                </svg>
                            </span>
                        </button>
                        <div class="dropdown-menu mt-1 p-0" style="z-index: 9999;width:180px!important;">
                            <ul class="m-0 p-0">
                                <li class="p-1 w-100" style="border-radius:4px;">
                                    <a href="#">
                                        <button name="action" value="action_5" type="submit"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')"
                                            class="btn-save-print border-0 p-2 d-flex mx-1 align-items-center h-100 w-100">
                                            <svg class="mr-2" width="16" height="16" viewBox="0 0 16 16"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M12.3687 6.5C12.6448 6.5 12.8687 6.72386 12.8687 7C12.8687 7.03856 12.8642 7.07699 12.8554 7.11452L11.3628 13.4581C11.1502 14.3615 10.3441 15 9.41597 15H6.58403C5.65593 15 4.84977 14.3615 4.6372 13.4581L3.14459 7.11452C3.08135 6.84572 3.24798 6.57654 3.51678 6.51329C3.55431 6.50446 3.59274 6.5 3.6313 6.5H12.3687ZM8.5 1C9.88071 1 11 2.11929 11 3.5H13C13.5523 3.5 14 3.94772 14 4.5V5C14 5.27614 13.7761 5.5 13.5 5.5H2.5C2.22386 5.5 2 5.27614 2 5V4.5C2 3.94772 2.44772 3.5 3 3.5H5C5 2.11929 6.11929 1 7.5 1H8.5ZM8.5 2.5H7.5C6.94772 2.5 6.5 2.94772 6.5 3.5H9.5C9.5 2.94772 9.05228 2.5 8.5 2.5Z"
                                                    fill="#26273B" fill-opacity="0.8" />
                                            </svg>
                                            <span class="text-14 font-weight-bold">
                                                Xóa
                                            </span>
                                        </button>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
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
        <div id="main">
            <div class="border">
                <div>
                    <div class="bg-filter-search border-0 text-center">
                        <p class="font-weight-bold text-uppercase info-chung--heading text-center">
                            THÔNG TIN PHIẾU XUẤT HÀNG
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-4 m-0 p-0">
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3 font-weight-bold" style="flex: 1.5;">Mã
                                    phiếu</span>
                                <input type="text" style="flex:2;"
                                    value="{{ $export->export_code }}" readonly
                                    class="text-13-black w-50 border-0 bg-input-guest date_picker py-2 px-2">
                            </div>
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3 font-weight-bold" style="flex: 1.5;">Ngày
                                    lập
                                    phiếu</span>
                                <input name="date_create" autocomplete="off" readonly
                                    value="{{ date_format(new DateTime($export->date_create), 'd/m/Y') }}"
                                    class="text-13-black w-50 border-0 bg-input-guest py-2 px-2"style=" flex:2;" />
                            </div>
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black font-weight-bold text-nowrap mr-3" style="flex: 1.5;">Ghi
                                    chú</span>
                                <input class="text-13-black w-50 border-0 bg-input-guest py-2 px-2" autocomplete="off"
                                    readonly style="flex:2;" name="note" value="{{ $export->note }}" />
                            </div>
                        </div>
                        <div class="col-md-4 m-0 p-0">
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3 font-weight-bold" style="flex: 1.5;">Người
                                    lập
                                    phiếu</span>
                                <input autocomplete="off"
                                    class="text-13-black w-50 border-0 bg-input-guest py-2 px-2"style="flex:2;"
                                    readonly name="user_id" value="{{ $export->user->name }}" />
                            </div>
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black btn-click font-weight-bold" style="flex: 1.6;">Khách hàng</span>
                                <input name="provider_id" autocomplete="off" readonly
                                    value="{{ $export->customer->customer_name }}"
                                    class="text-13-black w-100 border-0 bg-input-guest py-2 px-2" style="flex:2;" />
                            </div>
                            <div
                                class="d-flex w-100 justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            </div>
                        </div>
                        <div class="col-md-4 m-0 p-0">
                            <div style="width: 99%;"
                                class="d-flex justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3 font-weight-bold" style="flex: 1.5;">SĐT
                                    liên hệ</span>
                                <input name="phone" type="number" readonly value="{{ $export->phone }}"
                                    class="text-13-black w-50 border-0 bg-input-guest py-2 px-2" style="flex:2;" />
                            </div>
                            <div style="width: 99%;"
                                class="d-flex justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                                <span class="text-13-black text-nowrap mr-3 font-weight-bold" style="flex: 1.5;">Địa
                                    chỉ</span>
                                <input name="address" autocomplete="off" readonly value="{{ $export->address }}"
                                    class="text-13-black w-50 border-0 addr bg-input-guest addr py-2 px-2"style="flex:2;" />
                            </div>
                            <div style="width: 99%;"
                                class="d-flex justify-content-between py-2 px-3 border align-items-center text-left text-nowrap position-relative height-44">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <th class="border-right px-2 p-0" style="width: 8%">
                                        <span class="text-table text-13-black font-weight-bold pl-3">Mã hàng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-left" style="width: 15%; z-index:99;">
                                        <span class="text-table text-13-black font-weight-bold">Tên hàng</span>
                                    </th>
                                    <th class="border-right px-2 p-0 text-left" style="width: 8%;">
                                        <span class="text-table text-13-black font-weight-bold">Hãng</span>
                                    </th>
                                    <th class="border-right px-2 p-0" style="width: 8%;">
                                        <span class="text-table text-13-black font-weight-bold">Số lượng</span>
                                    </th>
                                    <th class="border-right px-2 p-0" style="width: 10%;">
                                        <span class="text-table text-13-black font-weight-bold">Serial Number</span>
                                    </th>
                                    <th class="border-right px-2 p-0" style="width: 10%;">
                                        <span class="text-table text-13-black font-weight-bold">Bảo hành (Tháng)</span>
                                    </th>
                                    <th class="border-right note px-2 p-0 text-left" style="width: 15%;">
                                        <span class="text-table text-13-black font-weight-bold">Ghi chú</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 0; ?>
                                @foreach ($productExports as $product_value)
                                    <tr class="bg-white">
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32"
                                                readonly="" value="{{ $product_value->product->product_code }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32"
                                                readonly="" value="{{ $product_value->product->product_name }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32"
                                                readonly="" value="{{ $product_value->product->brand }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32"
                                                readonly="" value="1">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32"
                                                readonly="" value="{{ $product_value->serialNumber->serial_code }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32"
                                                readonly="" value="{{ $product_value->warranty }}">
                                        </td>
                                        <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                            <input type="text" autocomplete="off"
                                                class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32"
                                                readonly="" value="{{ $product_value->ghichu }}">
                                        </td>
                                    </tr>
                                    <?php $count++; ?>
                                @endforeach
                                <tr>
                                    <td colspan="2"
                                        class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                    </td>
                                    <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                        <span class="text-purble">Tổng số lượng:</span>
                                    </td>
                                    <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                        <span class="text-purble">{{ $count }}</span>
                                    </td>
                                    <td colspan="3"
                                        class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </div>
        {{-- View mini --}}
        <x-view_mini></x-view_mini>
    </div>
</div>