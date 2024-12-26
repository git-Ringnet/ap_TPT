@include('partials.header', ['activeGroup' => 'systemFirst', 'activeName' => 'products'])
<div class="content-header-fixed px-1">
    <div class="content__header--inner border-bottom">
        <x-search-filter :keywords="request('keywords')" :filters="['Mã', 'Tên', 'Hãng', 'Bảo hành']">
            <x-filter-text name="ma" title="Mã" />
            <x-filter-text name="ten" title="Tên" />
            <x-filter-text name="hang" title="Hãng" />
            <x-filter-compare name="bao-hanh" title="Bảo hành" />
        </x-search-filter>
        <div class="content__heading--left opacity-0">
            <span class="ml-4">Thiết lập</span>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                    fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.69269 13.9741C7.43577 13.7171 7.43577 13.3006 7.69269 13.0437L10.7363 10.0001L7.69269 6.95651C7.43577 6.69959 7.43577 6.28303 7.69269 6.02611C7.94962 5.76918 8.36617 5.76918 8.6231 6.02611L12.1319 9.53488C12.3888 9.7918 12.3888 10.2084 12.1319 10.4653L8.6231 13.9741C8.36617 14.231 7.94962 14.231 7.69269 13.9741Z"
                        fill="#26273B" fill-opacity="0.8" />
                </svg>
            </span>
            <span class="last-span">Nhóm đối tượng</span>
        </div>
        <div class="d-flex content__heading--right">
            <div class="row m-0">
                <a href="{{ route('products.create') }}" class="activity mr-3" data-name1="KH" data-des="Tạo mới">
                    <button type="button" class="custom-btn mx-1 d-flex align-items-center h-100">
                        <svg class="mr-1" width="12" height="12" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9 0C9.58186 -2.96028e-08 10.0536 0.471694 10.0536 1.05356L10.0536 16.9464C10.0536 17.5283 9.58186 18 9 18C8.41814 18 7.94644 17.5283 7.94644 16.9464V1.05356C7.94644 0.471694 8.41814 -2.96028e-08 9 0Z"
                                fill="white" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M18 9C18 9.58187 17.5283 10.0536 16.9464 10.0536H1.05356C0.471694 10.0536 -2.07219e-07 9.58187 0 9C-7.69672e-07 8.41814 0.471695 7.94644 1.05356 7.94644H16.9464C17.5283 7.94644 18 8.41814 18 9Z"
                                fill="white" />
                        </svg>
                        <p class="m-0 ml-1">Tạo mới</p>
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="content margin-top-75">
    <section class="content">
        <div class="container-fluided">
            <div class="row result-filter-product margin-left30 my-1">
            </div>
            <div class="col-12 p-0 m-0">
                <div class="card">
                    <div class="outer2 table-responsive text-nowrap">
                        <table id="example2" class="table table-hover bg-white rounded">
                            <thead class="border-custom">
                                <tr>
                                    <th class="height-30 py-0 border-right pl-4" scope="col" style="">
                                        <span class="d-flex justify-content-start">
                                            <a href="#" class="sort-link btn-submit"
                                                data-sort-by="group_name_display" data-sort-type="DESC">
                                                <button class="btn-sort" type="submit">
                                                    <span class="text-14">Mã hàng</span>
                                                </button>
                                            </a>
                                            <div class="icon" id="icon-group_name_display"></div>
                                        </span>
                                    </th>
                                    <th class="height-30 py-0 border-right" scope="col" style="">
                                        <span class="d-flex justify-content-start">
                                            <a href="#" class="sort-link btn-submit"
                                                data-sort-by="group_name_display" data-sort-type="DESC">
                                                <button class="btn-sort" type="submit">
                                                    <span class="text-14">Tên hàng</span>
                                                </button>
                                            </a>
                                            <div class="icon" id="icon-group_name_display"></div>
                                        </span>
                                    </th>
                                    <th class="height-30 py-0 border-right" scope="col" style="">
                                        <span class="d-flex justify-content-start">
                                            <a href="#" class="sort-link btn-submit" data-sort-by="group_type"
                                                data-sort-type="DESC">
                                                <button class="btn-sort" type="submit">
                                                    <span class="text-14">Hãng</span>
                                                </button>
                                            </a>
                                            <div class="icon" id="icon-group_code"></div>
                                        </span>
                                    </th>
                                    <th class="height-30 py-0 border-right" scope="col" style="">
                                        <span class="d-flex justify-content-start">
                                            <a href="#" class="sort-link btn-submit" data-sort-by="group_type"
                                                data-sort-type="DESC">
                                                <button class="btn-sort" type="submit">
                                                    <span class="text-14">Bảo hành</span>
                                                </button>
                                            </a>
                                            <div class="icon" id="icon-group_code"></div>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="tbody-product">
                                <tr>
                                    <td class="text-purble font-weight-bold border-bottom py-1 border-right pl-4"
                                        style="font-size: 16px;" colspan="10" class="border-bottom">Nhóm hàng hóa:
                                        Chưa chọn nhóm
                                    </td>
                                </tr>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($products as $item)
                                    @if ($item->group_id == 0)
                                        <tr class="position-relative product-info height-30">
                                            <input type="hidden" name="id-product" class="id-product"
                                                id="id-product" value="{{ $item->id }}">
                                            <td class="text-13-black pl-0 border-bottom border py-0 pl-4">
                                                {{ $item->product_code }}
                                            </td>
                                            <td class="text-13-black border-bottom border py-0">
                                                <a class="duongdan" href="{{ route('products.edit', $item->id) }}">
                                                    {{ $item->product_name }}
                                                </a>
                                            </td>
                                            <td class="text-13-black border-bottom border py-0">
                                                {{ $item->brand }}
                                            </td>
                                            <td class="text-13-black border-bottom border text-left py-0">
                                                {{ $item->warranty }} tháng
                                            </td>
                                            <td class="position-absolute m-0 p-0 border-0 bg-hover-icon icon-center">
                                                <div class="d-flex w-100">
                                                    <a href="{{ route('products.edit', $item->id) }}">
                                                        <div class="m-0 px-2 py-1 mx-2 rounded">
                                                            <svg width="16" height="16" viewBox="0 0 16 16"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.985" fill-rule="evenodd"
                                                                    clip-rule="evenodd"
                                                                    d="M11.1719 1.04696C11.7535 0.973552 12.2743 1.11418 12.7344 1.46883C13.001 1.72498 13.2562 1.9906 13.5 2.26571C13.9462 3.00226 13.9358 3.73143 13.4688 4.45321C10.9219 7.04174 8.35416 9.60946 5.76563 12.1563C5.61963 12.245 5.46338 12.3075 5.29688 12.3438C4.59413 12.4153 3.891 12.483 3.1875 12.547C2.61265 12.4982 2.32619 12.1857 2.32813 11.6095C2.3716 10.8447 2.44972 10.0843 2.5625 9.32821C2.60666 9.22943 2.65874 9.13568 2.71875 9.04696C5.26563 6.50008 7.8125 3.95321 10.3594 1.40633C10.6073 1.22846 10.8781 1.10867 11.1719 1.04696ZM11.3594 2.04696C11.5998 2.02471 11.8185 2.08201 12.0156 2.21883C12.2188 2.42196 12.4219 2.62508 12.625 2.82821C12.8393 3.14436 12.8497 3.4673 12.6562 3.79696C12.4371 4.02136 12.2131 4.24011 11.9844 4.45321C11.4427 3.93236 10.9115 3.40111 10.3906 2.85946C10.5933 2.64116 10.8016 2.42762 11.0156 2.21883C11.1255 2.14614 11.2401 2.08885 11.3594 2.04696ZM9.60938 3.60946C10.1552 4.13961 10.6968 4.67608 11.2344 5.21883C9.21353 7.23968 7.19272 9.26049 5.17188 11.2813C4.571 11.3686 3.96684 11.4364 3.35938 11.4845C3.41572 10.8909 3.473 10.2971 3.53125 9.70321C5.56359 7.67608 7.58962 5.64483 9.60938 3.60946Z"
                                                                    fill="#6C6F74"></path>
                                                                <path opacity="0.979" fill-rule="evenodd"
                                                                    clip-rule="evenodd"
                                                                    d="M1.17188 14.1406C5.71356 14.1354 10.2552 14.1406 14.7969 14.1563C15.0348 14.2355 15.1598 14.4022 15.1719 14.6563C15.147 14.915 15.0116 15.0816 14.7656 15.1563C10.2448 15.1771 5.72397 15.1771 1.20312 15.1563C0.807491 14.9903 0.708531 14.7143 0.90625 14.3281C0.978806 14.2377 1.06735 14.1752 1.17188 14.1406Z"
                                                                    fill="#6C6F74"></path>
                                                            </svg>
                                                        </div>
                                                    </a>
                                                    <a href="#">
                                                        <div class="rounded">
                                                            <form
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')"
                                                                action="{{ route('products.destroy', $item->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm">
                                                                    <svg width="16" height="16"
                                                                        viewBox="0 0 16 16" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path opacity="0.936" fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M6.40625 0.968766C7.44813 0.958304 8.48981 0.968772 9.53125 1.00016C9.5625 1.03156 9.59375 1.06296 9.625 1.09436C9.65625 1.49151 9.66663 1.88921 9.65625 2.28746C10.7189 2.277 11.7814 2.28747 12.8438 2.31886C12.875 2.35025 12.9063 2.38165 12.9375 2.41305C12.9792 2.99913 12.9792 3.58522 12.9375 4.17131C12.9063 4.24457 12.8542 4.2969 12.7813 4.32829C12.6369 4.35948 12.4911 4.36995 12.3438 4.35969C12.3542 7.45762 12.3438 10.5555 12.3125 13.6533C12.1694 14.3414 11.7632 14.7914 11.0938 15.0034C9.01044 15.0453 6.92706 15.0453 4.84375 15.0034C4.17433 14.7914 3.76808 14.3414 3.625 13.6533C3.59375 10.5555 3.58333 7.45762 3.59375 4.35969C3.3794 4.3844 3.18148 4.34254 3 4.2341C2.95833 3.62708 2.95833 3.02007 3 2.41305C3.03125 2.38165 3.0625 2.35025 3.09375 2.31886C4.15605 2.28747 5.21855 2.277 6.28125 2.28746C6.27088 1.88921 6.28125 1.49151 6.3125 1.09436C6.35731 1.06018 6.38856 1.01832 6.40625 0.968766ZM6.96875 1.65951C7.63544 1.65951 8.30206 1.65951 8.96875 1.65951C8.96875 1.86882 8.96875 2.07814 8.96875 2.28746C8.30206 2.28746 7.63544 2.28746 6.96875 2.28746C6.96875 2.07814 6.96875 1.86882 6.96875 1.65951ZM3.65625 2.9782C6.53125 2.9782 9.40625 2.9782 12.2813 2.9782C12.2813 3.18752 12.2813 3.39684 12.2813 3.60615C9.40625 3.60615 6.53125 3.60615 3.65625 3.60615C3.65625 3.39684 3.65625 3.18752 3.65625 2.9782ZM4.34375 4.35969C6.76044 4.35969 9.17706 4.35969 11.5938 4.35969C11.6241 7.5032 11.5929 10.643 11.5 13.7789C11.3553 14.05 11.1366 14.2279 10.8438 14.3127C8.92706 14.3546 7.01044 14.3546 5.09375 14.3127C4.80095 14.2279 4.5822 14.05 4.4375 13.7789C4.34462 10.643 4.31337 7.5032 4.34375 4.35969Z"
                                                                            fill="#6C6F74" />
                                                                        <path opacity="0.891" fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M5.78125 5.28118C6.0306 5.2259 6.20768 5.30924 6.3125 5.53118C6.35419 8.052 6.35419 10.5729 6.3125 13.0937C6.08333 13.427 5.85417 13.427 5.625 13.0937C5.58333 10.552 5.58333 8.01037 5.625 5.46868C5.69031 5.4141 5.7424 5.3516 5.78125 5.28118Z"
                                                                            fill="#6C6F74" />
                                                                        <path opacity="0.891" fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M7.78125 5.28118C8.03063 5.2259 8.20769 5.30924 8.3125 5.53118C8.35419 8.052 8.35419 10.5729 8.3125 13.0937C8.08331 13.427 7.85419 13.427 7.625 13.0937C7.58331 10.552 7.58331 8.01037 7.625 5.46868C7.69031 5.4141 7.74238 5.3516 7.78125 5.28118Z"
                                                                            fill="#6C6F74" />
                                                                        <path opacity="0.891" fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M9.78125 5.28118C10.0306 5.2259 10.2077 5.30924 10.3125 5.53118C10.3542 8.052 10.3542 10.5729 10.3125 13.0937C10.0833 13.427 9.85419 13.427 9.625 13.0937C9.58331 10.552 9.58331 8.01037 9.625 5.46868C9.69031 5.4141 9.74238 5.3516 9.78125 5.28118Z"
                                                                            fill="#6C6F74" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @php
                                            $total++;
                                        @endphp
                                    @endif
                                @endforeach
                                @foreach ($groups as $value)
                                    <tr>
                                        <td class="text-purble font-weight-bold border-bottom py-1 border-right pl-4"
                                            style="font-size: 16px;" colspan="10" class="border-bottom">Nhóm
                                            hàng
                                            hóa: {{ $value->group_name }}
                                        </td>
                                    </tr>
                                    @foreach ($products as $item)
                                        @if ($item->group_id == $value->id)
                                            <tr class="position-relative product-info height-30"
                                                onclick="handleRowClick('checkbox', event);">
                                                <input type="hidden" name="id-product" class="id-product"
                                                    id="id-product" value="{{ $item->id }}">
                                                <td class="text-13-black pl-0 border-bottom border py-0 pl-4">
                                                    {{ $item->product_code }}
                                                </td>
                                                <td class="text-13-black border-bottom border py-0">
                                                    <a class="duongdan"
                                                        href="{{ route('products.edit', $item->id) }}">
                                                        {{ $item->product_name }}
                                                    </a>
                                                </td>
                                                <td class="text-13-black border-bottom border py-0">
                                                    {{ $item->brand }}
                                                </td>
                                                <td class="text-13-black border-bottom border text-left py-0">
                                                    {{ $item->warranty }} tháng
                                                </td>
                                                <td
                                                    class="position-absolute m-0 p-0 border-0 bg-hover-icon icon-center">
                                                    <div class="d-flex w-100">
                                                        <a href="{{ route('products.edit', $item->id) }}">
                                                            <div class="m-0 px-2 py-1 mx-2 rounded">
                                                                <svg width="16" height="16"
                                                                    viewBox="0 0 16 16" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path opacity="0.985" fill-rule="evenodd"
                                                                        clip-rule="evenodd"
                                                                        d="M11.1719 1.04696C11.7535 0.973552 12.2743 1.11418 12.7344 1.46883C13.001 1.72498 13.2562 1.9906 13.5 2.26571C13.9462 3.00226 13.9358 3.73143 13.4688 4.45321C10.9219 7.04174 8.35416 9.60946 5.76563 12.1563C5.61963 12.245 5.46338 12.3075 5.29688 12.3438C4.59413 12.4153 3.891 12.483 3.1875 12.547C2.61265 12.4982 2.32619 12.1857 2.32813 11.6095C2.3716 10.8447 2.44972 10.0843 2.5625 9.32821C2.60666 9.22943 2.65874 9.13568 2.71875 9.04696C5.26563 6.50008 7.8125 3.95321 10.3594 1.40633C10.6073 1.22846 10.8781 1.10867 11.1719 1.04696ZM11.3594 2.04696C11.5998 2.02471 11.8185 2.08201 12.0156 2.21883C12.2188 2.42196 12.4219 2.62508 12.625 2.82821C12.8393 3.14436 12.8497 3.4673 12.6562 3.79696C12.4371 4.02136 12.2131 4.24011 11.9844 4.45321C11.4427 3.93236 10.9115 3.40111 10.3906 2.85946C10.5933 2.64116 10.8016 2.42762 11.0156 2.21883C11.1255 2.14614 11.2401 2.08885 11.3594 2.04696ZM9.60938 3.60946C10.1552 4.13961 10.6968 4.67608 11.2344 5.21883C9.21353 7.23968 7.19272 9.26049 5.17188 11.2813C4.571 11.3686 3.96684 11.4364 3.35938 11.4845C3.41572 10.8909 3.473 10.2971 3.53125 9.70321C5.56359 7.67608 7.58962 5.64483 9.60938 3.60946Z"
                                                                        fill="#6C6F74"></path>
                                                                    <path opacity="0.979" fill-rule="evenodd"
                                                                        clip-rule="evenodd"
                                                                        d="M1.17188 14.1406C5.71356 14.1354 10.2552 14.1406 14.7969 14.1563C15.0348 14.2355 15.1598 14.4022 15.1719 14.6563C15.147 14.915 15.0116 15.0816 14.7656 15.1563C10.2448 15.1771 5.72397 15.1771 1.20312 15.1563C0.807491 14.9903 0.708531 14.7143 0.90625 14.3281C0.978806 14.2377 1.06735 14.1752 1.17188 14.1406Z"
                                                                        fill="#6C6F74"></path>
                                                                </svg>
                                                            </div>
                                                        </a>
                                                        <a href="#">
                                                            <div class="rounded">
                                                                <form
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')"
                                                                    action="{{ route('products.destroy', $item->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm">
                                                                        <svg width="16" height="16"
                                                                            viewBox="0 0 16 16" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path opacity="0.936" fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M6.40625 0.968766C7.44813 0.958304 8.48981 0.968772 9.53125 1.00016C9.5625 1.03156 9.59375 1.06296 9.625 1.09436C9.65625 1.49151 9.66663 1.88921 9.65625 2.28746C10.7189 2.277 11.7814 2.28747 12.8438 2.31886C12.875 2.35025 12.9063 2.38165 12.9375 2.41305C12.9792 2.99913 12.9792 3.58522 12.9375 4.17131C12.9063 4.24457 12.8542 4.2969 12.7813 4.32829C12.6369 4.35948 12.4911 4.36995 12.3438 4.35969C12.3542 7.45762 12.3438 10.5555 12.3125 13.6533C12.1694 14.3414 11.7632 14.7914 11.0938 15.0034C9.01044 15.0453 6.92706 15.0453 4.84375 15.0034C4.17433 14.7914 3.76808 14.3414 3.625 13.6533C3.59375 10.5555 3.58333 7.45762 3.59375 4.35969C3.3794 4.3844 3.18148 4.34254 3 4.2341C2.95833 3.62708 2.95833 3.02007 3 2.41305C3.03125 2.38165 3.0625 2.35025 3.09375 2.31886C4.15605 2.28747 5.21855 2.277 6.28125 2.28746C6.27088 1.88921 6.28125 1.49151 6.3125 1.09436C6.35731 1.06018 6.38856 1.01832 6.40625 0.968766ZM6.96875 1.65951C7.63544 1.65951 8.30206 1.65951 8.96875 1.65951C8.96875 1.86882 8.96875 2.07814 8.96875 2.28746C8.30206 2.28746 7.63544 2.28746 6.96875 2.28746C6.96875 2.07814 6.96875 1.86882 6.96875 1.65951ZM3.65625 2.9782C6.53125 2.9782 9.40625 2.9782 12.2813 2.9782C12.2813 3.18752 12.2813 3.39684 12.2813 3.60615C9.40625 3.60615 6.53125 3.60615 3.65625 3.60615C3.65625 3.39684 3.65625 3.18752 3.65625 2.9782ZM4.34375 4.35969C6.76044 4.35969 9.17706 4.35969 11.5938 4.35969C11.6241 7.5032 11.5929 10.643 11.5 13.7789C11.3553 14.05 11.1366 14.2279 10.8438 14.3127C8.92706 14.3546 7.01044 14.3546 5.09375 14.3127C4.80095 14.2279 4.5822 14.05 4.4375 13.7789C4.34462 10.643 4.31337 7.5032 4.34375 4.35969Z"
                                                                                fill="#6C6F74" />
                                                                            <path opacity="0.891" fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M5.78125 5.28118C6.0306 5.2259 6.20768 5.30924 6.3125 5.53118C6.35419 8.052 6.35419 10.5729 6.3125 13.0937C6.08333 13.427 5.85417 13.427 5.625 13.0937C5.58333 10.552 5.58333 8.01037 5.625 5.46868C5.69031 5.4141 5.7424 5.3516 5.78125 5.28118Z"
                                                                                fill="#6C6F74" />
                                                                            <path opacity="0.891" fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M7.78125 5.28118C8.03063 5.2259 8.20769 5.30924 8.3125 5.53118C8.35419 8.052 8.35419 10.5729 8.3125 13.0937C8.08331 13.427 7.85419 13.427 7.625 13.0937C7.58331 10.552 7.58331 8.01037 7.625 5.46868C7.69031 5.4141 7.74238 5.3516 7.78125 5.28118Z"
                                                                                fill="#6C6F74" />
                                                                            <path opacity="0.891" fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M9.78125 5.28118C10.0306 5.2259 10.2077 5.30924 10.3125 5.53118C10.3542 8.052 10.3542 10.5729 10.3125 13.0937C10.0833 13.427 9.85419 13.427 9.625 13.0937C9.58331 10.552 9.58331 8.01037 9.625 5.46868C9.69031 5.4141 9.74238 5.3516 9.78125 5.28118Z"
                                                                                fill="#6C6F74" />
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                $total++;
                                            @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/filter.js') }}"></script>
<script type="text/javascript">
    $(document).on('click', '.btn-submit', function(e) {
        if (!$(e.target).is('input[type="checkbox"]')) e.preventDefault();
        var buttonElement = this;
        // Thu thập dữ liệu từ form
        var formData = {
            search: $('#search').val(),
            ma: getData('#ma', this),
            ten: getData('#ten', this),
            hang: getData('#hang', this),
            bao_hanh: retrieveComparisonData(this, 'bao-hanh'),
            sort: getSortData(buttonElement)
        };
        console.log(formData);
        // Ẩn tùy chọn nếu cần
        if (!$(e.target).closest('li, input[type="checkbox"]').length) {
            $('#' + $(this).data('button-name') + '-options').hide();
        }
        // Gọi hàm AJAX
        var route = "{{ route('filter-products') }}"; // Thay route phù hợp
        var nametable = 'product'; // Thay tên bảng phù hợp
        handleAjaxRequest(formData, route, nametable);
    });
</script>
