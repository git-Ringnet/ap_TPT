@include('partials.header', ['activeGroup' => 'manageProfess', 'activeName' => 'inventoryLookup'])
@section('title', $title)
<div class="content-wrapper m-0 min-height--none p-0">
    <div class="content-header-fixed p-0 border-0">
        <div class="content__header--inner">
            <div class="d-flex align-items-center ml-3">
                <form action="" method="get" id="search-filter" class="p-0 m-0">
                    <div class="d-flex align-items-center">
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
                        <button class="custom-btn mx-2 d-flex align-items-center h-100" style="font-size: 13px;">Tra
                            cứu</button>
                    </div>
                </form>
                <div class="dropdown filter-all">
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
                                        <th class="height-30 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit" data-sort-by="key"
                                                    data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Mã hàng</span>
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
                                                        <span class="text-14">Tên hàng</span>
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
                                                        <span class="text-14">Hãng</span>
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
                                                        <span class="text-14">S/N</span>
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
                                                        <span class="text-14">Nhà cung cấp</span>
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
                                                        <span class="text-14">Ngày nhập hàng</span>
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
                                                        <span class="text-14">Thời gian tồn kho</span>
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
                                    @foreach ($inventory as $item)
                                        <tr class="position-relative guest-info height-30">
                                            <td class="text-13-black border-right border-bottom py-0">
                                                {{ $item->product->product_code }}
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->product->product_name }}
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->product->brand }}
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                <a href="{{ route('invenroryLookup.edit', $item->id) }}">
                                                    {{ $item->serialNumber->serial_code }}
                                                </a>
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->provider->provider_name }}
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ date_format(new DateTime($item->import_date), 'd/m/Y') }}
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->storage_duration }} ngày
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->status }}
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
