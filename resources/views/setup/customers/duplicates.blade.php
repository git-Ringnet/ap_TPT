@include('partials.header', ['activeGroup' => 'systemFirst', 'activeName' => 'customers'])
@section('title', $title)
<div class="content-header-fixed px-1">
    <div class="content__header--inner">
    </div>
</div>
</div>
<div class="content margin-top-127">
    <section class="content">
        <div class="container-fluided">
            <div class="row result-filter-guest margin-left20 my-1 mr-0">
            </div>
            <div class="col-12 p-0 m-0">
                <div class="card">
                    <h3 class="text-center">Danh sách trùng lặp</h3>
                    <div class="outer2 table-responsive text-nowrap">
                        @if (!empty($duplicates))
                            <form action="{{ route('customers.bulkConfirm') }}" method="POST">
                                @csrf
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select_all">
                                                <!-- Checkbox chọn/deselect tất cả --></th>
                                            <th>Customer Code</th>
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Tax Code</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($duplicates as $duplicate)
                                            <tr>
                                                <td><input type="checkbox" name="customers[]"
                                                        value="{{ json_encode($duplicate) }}"></td>
                                                <!-- Gửi dữ liệu của từng row -->
                                                <td>{{ $duplicate['row_data']->get(0) ?? 'N/A' }}</td>
                                                <!-- Truy cập qua get() -->
                                                <td>{{ $duplicate['row_data']->get(1) ?? 'N/A' }}</td>
                                                <td>{{ $duplicate['row_data']->get(2) ?? 'N/A' }}</td>
                                                <td>{{ $duplicate['row_data']->get(4) ?? 'N/A' }}</td>
                                                <td>{{ $duplicate['row_data']->get(5) ?? 'N/A' }}</td>
                                                <td>{{ $duplicate['row_data']->get(6) ?? 'N/A' }}</td>
                                                <td>{{ $duplicate['row_data']->get(7) ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary">Xác nhận hàng loạt</button>
                            </form>
                        @else
                            <p>Không có khách hàng trùng lặp nào.</p>
                        @endif

                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('#select_all').on('change', function() {
                            $('input[name="customers[]"]').prop('checked', this.checked);
                        });
                    });
                </script>

            </div>
        </div>
    </section>
</div>
