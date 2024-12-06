<div id="mySidenav" class="sidenav border margin-top-118">
    <div id="show_info_Guest" class="position-relative">
        <div class="bg-filter-search border-0 text-center border-custom">
            <p class="font-weight-bold text-uppercase info-chung--heading text-center">
                DANH SÁCH
            </p>
        </div>
        <div class="d-flex w-100 my-2">
            <div class="px-1">
                <p class="m-0 p-0 text-13-black">Từ ngày</p>
                <input type="date" class="w-100 form-control mr-1" id="fromDate">
                <input type="hidden" id="hiddenInputDateFrom">
            </div>
            <div class="mr-4">
                <p class="m-0 p-0 text-13-black">Đến ngày</p>
                <input type="date" class="w-100 form-control ml-1" id="toDate">
                <input type="hidden" id="hiddenInputDateTo">
            </div>
        </div>
        <div class="w-100 my-2 px-1">
            <div class="position-relative">
                <p class="m-0 p-0 text-13-black">Nhà cung cấp</p>
                <input type="text" placeholder="Chọn thông tin" readonly
                    class="w-100 bg-input-guest py-2 px-2 form-control text-13-black nameProviderMiniView bg-white"
                    autocomplete="off" id="inputProvider">
                <input type="hidden" class="idProviderMiniView">
                <div id="listGuestMiniView"
                    class="bg-white position-absolute rounded list-guest shadow p-1 z-index-block list-guest w-100"
                    style="z-index: 99;display: none;">
                    <ul class="m-0 p-0 scroll-data">
                        <div class="p-1">
                            <div class="position-relative">
                                <input type="text" placeholder="Nhập nhà cung cấp"
                                    class="pr-4 w-100 input-search bg-input-guest" id="searchProviderMiniView">
                                <span id="search-icon" class="search-icon">
                                    <i class="fas fa-search text-table" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        {{-- @foreach ($provider as $provider_value)
                            <li class="p-2 align-items-center text-wrap"
                                style="border-radius:4px;border-bottom: 1px solid #d6d6d6;">
                                <a href="javascript:void(0)" style="flex:2" data-id="{{ $provider_value->id }}"
                                    name="search-infoEdit" class="search-infoEdit">
                                    <span
                                        class="text-13-black">{{ $provider_value->provider_name }}</span></span>
                                </a>
                            </li>
                        @endforeach --}}
                    </ul>
                </div>
            </div>
            <div class="mt-2">
                <p class="m-0 p-0 text-13-black">Người lập</p>
                <select id="creator" class="form-control text-13-black">

                </select>
            </div>
        </div>
        <div class="d-flex w-100 my-2 justify-content-between align-items-center">
            <div class="m-0 p-0">
                <p class="m-0 p-0 text-13-black"></p>
            </div>
            <a href="#" class="custom-btn d-flex align-items-center h-100 mx-1" id="search-view-mini">
                <span>
                    <svg class="mx-1" width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10.4614 7.38499C10.4614 9.08433 9.08384 10.4619 7.3845 10.4619C5.68517 10.4619 4.30758 9.08433 4.30758 7.38499C4.30758 5.68566 5.68517 4.30807 7.3845 4.30807C9.08384 4.30807 10.4614 5.68566 10.4614 7.38499ZM10.1515 11.4575C9.36298 11.9942 8.41039 12.3081 7.3845 12.3081C4.66556 12.3081 2.46143 10.1039 2.46143 7.38499C2.46143 4.66605 4.66556 2.46191 7.3845 2.46191C10.1034 2.46191 12.3076 4.66605 12.3076 7.38499C12.3076 8.41087 11.9937 9.36347 11.457 10.152L14.4987 13.1939C14.8592 13.5543 14.8592 14.1387 14.4987 14.4992C14.1382 14.8597 13.5539 14.8597 13.1934 14.4992L10.1515 11.4575Z"
                            fill="white" />
                    </svg>
                </span>
                <p class="m-0 p-0">Tìm kiếm</p>
            </a>
        </div>
        <div class="outerViewMini text-nowrap">
            <table class="table table-hover bg-white rounded">
                <thead>
                    <tr>
                        <th scope="col" class="height-52">
                            <span class="d-flex justify-content-start text-13-black font-weight-bold">
                                Mã phiếu
                            </span>
                        </th>
                        <th scope="col" class="height-52">
                            <span class="d-flex justify-content-start text-13-black font-weight-bold">
                                Ngày lập phiếu
                            </span>
                        </th>
                        <th scope="col" class="height-52">
                            <span class="d-flex justify-content-start text-13-black font-weight-bold">
                                Nhà cung cấp
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ asset('/js/view-mini.js') }}"></script>
