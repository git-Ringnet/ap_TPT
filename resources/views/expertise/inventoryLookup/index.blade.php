@include('partials.header', ['activeGroup' => 'manageProfess', 'activeName' => 'inventoryLookup'])
@section('title', $title)
<div class="content-wrapper m-0 min-height--none p-0">
    <div class="content-header-fixed p-0 border-0">
        <div class="content__header--inner border-bottom">
            <x-search-filter :keywords="request('keywords')" :filters="[
                'Mã hàng',
                'Tên hàng',
                'Hãng',
                'Serial',
                'Nhà cung cấp',
                'Ngày nhập hàng',
                'Thời gian tồn kho',
                'Trạng thái',
            ]">
                <x-filter-text name="ma-hang" title="Mã hàng" />
                <x-filter-text name="ten-hang" title="Tên hàng" />
                <x-filter-text name="serial" title="Serial" />
                <x-filter-text name="hang" title="Hãng" />
                <x-filter-checkbox :dataa='$providers' name="nha-cung-cap" title="Nhà cung cấp" button="nha-cung-cap"
                    namedisplay="provider_name" />
                <x-filter-date name="ngay-nhap-hang" title="Ngày nhập hàng" />
                <x-filter-status name="trang-thai" title="Trạng thái" :filters="[
                    ['key' => '1', 'value' => 'Tới hạn bào trì', 'color' => '#858585'],
                    ['key' => '0', 'value' => 'Blank', 'color' => '#08AA36BF'],
                ]" />
                <x-filter-compare name="thoi-gian-ton-kho" title="Thời gian tồn kho" />
            </x-search-filter>
        </div>
    </div>
    <div class="content margin-top-127">
        <section class="content">
            <div class="container-fluided">
                <div class="row result-filter-inven-lookup margin-left20 my-1">
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
                                                        <span class="text-14">S/N</span>
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
                                                        <span class="text-14">Nhà cung cấp</span>
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
                                                        <span class="text-14">Ngày nhập hàng</span>
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
                                                        <span class="text-14">Thời gian tồn kho</span>
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
                                                        <span class="text-14">Trạng thái</span>
                                                    </button>
                                                </a>
                                                <div class="icon" id="icon-guest_code"></div>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-inven-lookup">
                                    @foreach ($inventory as $item)
                                        <tr class="position-relative inven-lookup-info height-40">
                                            <input type="hidden" name="id-inven-lookup" class="id-inven-lookup"
                                                id="id-inven-lookup" value="{{ $item->id }}">
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
                                                <a href="{{ route('inventoryLookup.edit', $item->id) }}">
                                                    {{ $item->serialNumber->serial_code ?? '' }}
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
                                                @if ($item->status == '1')
                                                    <span class="text-danger">Tới hạn bảo trì</span>
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
            date: retrieveDateData(this, 'ngay-nhap-hang'),
            provider: getStatusData(this, 'nha-cung-cap'),
            status: getStatusData(this, 'trang-thai'),
            time_inven: retrieveComparisonData(this, 'thoi-gian-ton-kho'),
            sort: getSortData(buttonElement)
        };
        // Ẩn tùy chọn nếu cần
        if (!$(e.target).closest('li, input[type="checkbox"]').length) {
            $('#' + $(this).data('button-name') + '-options').hide();
        }
        // Gọi hàm AJAX
        var route = "{{ route('filter-inven-lookup') }}"; // Thay route phù hợp
        var nametable = 'inven-lookup'; // Thay tên bảng phù hợp
        handleAjaxRequest(formData, route, nametable);
    });
</script>
