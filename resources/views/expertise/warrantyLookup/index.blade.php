@include('partials.header', ['activeGroup' => 'manageProfess', 'activeName' => 'warrantyLookup'])
@section('title', $title)
<div class="content-wrapper m-0 min-height--none p-0">
    <div class="content-header-fixed p-0 border-0">
        <div class="content__header--inner border-bottom">
            <x-search-filter :keywords="request('keywords')" :filters="[
                'Mã hàng',
                'Tên hàng',
                'Hãng',
                'Serial',
                'Khách hàng',
                'Ngày xuất trả hàng',
                'Bảo hành',
                'Tình trạng',
            ]">
                <x-filter-text name="ma-hang" title="Mã hàng" />
                <x-filter-text name="ten-hang" title="Tên hàng" />
                <x-filter-text name="serial" title="Serial" />
                <x-filter-text name="hang" title="Hãng" />
                <x-filter-checkbox :dataa='$customers' name="khach-hang" title="Khách hàng" button="khach-hang"
                    namedisplay="customer_name" />
                <x-filter-date name="ngay-xuat-tra-hang" title="Ngày xuất trả hàng" />
                <x-filter-status name="tinh-trang" title="Tình trạng" :filters="[
                    ['key' => '0', 'value' => 'Còn bảo hành', 'color' => '#858585'],
                    ['key' => '1', 'value' => 'Hết bảo hành', 'color' => '#08AA36BF'],
                ]" />
                <x-filter-compare name="bao-hanh" title="Bảo hành" />
            </x-search-filter>
        </div>
    </div>
    <div class="content margin-top-127">
        <section class="content">
            <div class="container-fluided">
                <div class="row result-filter-warran-lookup margin-left20 my-1">
                </div>
                <div class="col-12 p-0 m-0">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="outer2 table-responsive text-nowrap">
                            <table id="example2" class="table table-hover bg-white rounded">
                                <thead class="border-custom">
                                    <tr>
                                        <th class="height-40 py-0 border" scope="col">
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
                                        <th class="height-40 py-0 border" scope="col">
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
                                        <th class="height-40 py-0 border" scope="col">
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
                                        <th class="height-40 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit"
                                                    data-sort-by="guest_name_display" data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Serial Number</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_name_display"></div>
                                            </span>
                                        </th>
                                        <th class="height-40 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit" data-sort-by="guest_code"
                                                    data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Khách hàng</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_code"></div>
                                            </span>
                                        </th>
                                        <th class="height-40 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit" data-sort-by="guest_code"
                                                    data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Ngày xuất/trả hàng</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_code"></div>
                                            </span>
                                        </th>
                                        <th class="height-40 py-0 border" scope="col">
                                            <span class="d-flex justify-content-start">
                                                <a href="#" class="sort-link btn-submit"
                                                    data-sort-by="guest_code" data-sort-type="DESC">
                                                    <button class="btn-sort" type="submit">
                                                        <span class="text-14">Bảo hành</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_code"></div>
                                            </span>
                                        </th>
                                        <th class="height-40 py-0 border" scope="col">
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
                                    </tr>
                                </thead>
                                <tbody class="tbody-warran-lookup">
                                    @foreach ($warranty as $item)
                                        <tr class="position-relative warran-lookup-info height-40">
                                            <input type="hidden" name="id-warran-lookup" class="id-warran-lookup"
                                                id="id-warran-lookup" value="{{ $item->id }}">
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
                                                <a href="{{ route('warrantyLookup.edit', $item->id) }}">
                                                    {{ $item->serialNumber->serial_code }}
                                                </a>
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->customer->customer_name }}
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ date_format(new DateTime($item->export_return_date), 'd/m/Y') }}
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                {{ $item->warranty }} tháng
                                            </td>
                                            <td class="text-13-black border border-left-0 border-bottom py-0">
                                                @if (@$item->status == '1')
                                                    <span class="text-13-black">Hết bảo hành</span>
                                                @else
                                                    <span class="text-13-black">Còn bảo hành</span>
                                                @endif
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
<script src="{{ asset('js/filter.js') }}"></script>
<script>
    $(document).on('click', '.btn-submit', function(e) {
        if (!$(e.target).is('input[type="checkbox"]')) e.preventDefault();
        var buttonElement = this;
        // Thu thập dữ liệu từ form
        var formData = {
            search: $('#search').val(),
            ma: getData('#ma-hang', this),
            ten: getData('#ten-hang', this),
            brand: getData('#hang', this),
            sn: getData('#serial', this),
            date: retrieveDateData(this, 'ngay-xuat-tra-hang'),
            customer: getStatusData(this, 'khach-hang'),
            status: getStatusData(this, 'tinh-trang'),
            bao_hanh: retrieveComparisonData(this, 'bao-hanh'),
            sort: getSortData(buttonElement)
        };
        // Ẩn tùy chọn nếu cần
        if (!$(e.target).closest('li, input[type="checkbox"]').length) {
            $('#' + $(this).data('button-name') + '-options').hide();
        }
        // Gọi hàm AJAX
        var route = "{{ route('filter-warran-lookup') }}"; // Thay route phù hợp
        var nametable = 'warran-lookup'; // Thay tên bảng phù hợp
        handleAjaxRequest(formData, route, nametable);
    });
</script>
