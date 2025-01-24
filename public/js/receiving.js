$(document).ready(function () {
    // Hiển thị danh sách tên sản phẩm
    $(document).on("click", ".product_code", function (e) {
        e.stopPropagation();
        $(".list_product").hide();

        var clickedRow = $(this).closest("tr");
        var listProduct = clickedRow.find(".list_product");
        listProduct.toggle();
        // Lọc danh sách khi nhập vào product_code
        $(document).on("input", ".product_code", function () {
            var keyword = $(this).val().toLowerCase(); // Lấy từ khóa người dùng nhập
            var clickedRow = $(this).closest("tr");
            var listProduct = clickedRow.find(".list_product");

            listProduct.find("li").each(function () {
                var productName = $(this).find("span").text().toLowerCase(); // Lấy tên sản phẩm
                if (productName.includes(keyword)) {
                    $(this).show(); // Hiển thị nếu khớp từ khóa
                } else {
                    $(this).hide(); // Ẩn nếu không khớp
                }
            });

            listProduct.show(); // Hiển thị danh sách đã lọc
        });
    });

    $(document).on("click", ".warranty-input", function (e) {
        e.stopPropagation(); // Ngăn việc click lan rộng
        $(".warranty-dropdown").hide(); // Ẩn tất cả dropdown khác

        const $clickedRow = $(this).closest("tr");
        const $dropdown = $clickedRow.find(".warranty-dropdown");
        $dropdown.toggle(); // Hiển thị hoặc ẩn dropdown hiện tại

        // Lọc danh sách khi nhập vào name_warranty
        $(document).on("input", ".warranty-input", function () {
            const keyword = $(this).val().toLowerCase(); // Từ khóa tìm kiếm
            const $row = $(this).closest("tr");
            const $dropdown = $row.find(".warranty-dropdown");

            $dropdown.find("li").each(function () {
                const warrantyName = $(this)
                    .find(".warranty-name")
                    .text()
                    .toLowerCase();
                if (warrantyName.includes(keyword)) {
                    $(this).show(); // Hiển thị nếu khớp từ khóa
                } else {
                    $(this).hide(); // Ẩn nếu không khớp
                }
            });

            $dropdown.show(); // Hiển thị danh sách đã lọc
        });
    });

    // Chọn một mục trong dropdown
    $(document).on("click", ".dropdown-link", function (e) {
        e.preventDefault();
        const $clickedItem = $(this);
        const $row = $clickedItem.closest("tr");
        const nameWarranty = $clickedItem.data("name_warranty");
        const $input = $row.find(".warranty-input");
        const idWarranty = $clickedItem.data("id_warranty");
        const $inputIdWarranty = $row.find(".id_warranty");
        const id_seri = $clickedItem.data("seri");
        const $inputIdSeri = $row.find(".id_seri");
        const $dropdown = $row.find(".warranty-dropdown");

        $input.val(nameWarranty);
        $inputIdWarranty.val(idWarranty);
        $inputIdSeri.val(id_seri);
        console.log(id_seri);

        $dropdown.hide();
    });

    // Ẩn dropdown khi click ngoài
    $(document).on("click", function () {
        $(".warranty-dropdown").hide();
    });

    // Xử lý sự kiện khi người dùng chọn sản phẩm
    $(document).on("click", ".idProduct", function (e) {
        e.preventDefault();
        // Lấy giá trị sản phẩm từ danh sách
        var productCode = $(this).find("span").text();
        var productName = $(this).data("name");
        var productBrand = $(this).data("brand");
        var productId = $(this).data("id");
        var clickedRow = $(this).closest("tr");
        // Gán giá trị vào input
        clickedRow.find(".product_code").val(productName);
        clickedRow.find(".product_name").val(productName);
        clickedRow.find(".brand").val(productBrand);
        clickedRow.find(".product_id").val(productId);
        // Ẩn danh sách
        clickedRow.find(".list_product").hide();
    });

    // Ẩn danh sách khi click bên ngoài
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".product_code, .list_product").length) {
            $(".list_product").hide();
        }
    });
});

$(document).on("click", ".btn-add-item", function () {
    // Lấy tbody chứa các hàng sản phẩm
    const tbody = $("#tbody-product-data");

    // Đếm số lượng hàng hiện tại trong tbody
    const currentRowCount = tbody.find("tr.row-product").length;

    // Gán data-index dựa trên số lượng hàng hiện tại
    const newIndex = currentRowCount;
    var newRow = `
        <tr class="row-product bg-white" data-index="${newIndex}" data-product-code="" data-product-id="">
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0 d-none">
                <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32" readonly=""
                    name="product_id[]" value="">
            </td>
            <td class="border-right position-relative p-2 text-13 align-top border-bottom border-top-0 pl-4 bg-input-guest-blue">
                <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 product_code height-32" placeholder="Tìm mã hàng"
                     value="">
                <ul class="list_product bg-white position-absolute w-100 rounded shadow p-0 scroll-data"
                    style="z-index: 99;top: 75%;left: 1.5rem;display: none;">
                </ul>
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 product_name height-32"
                    readonly="" value="">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 brand height-32" readonly=""
                    value="">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 text-center py-1 w-100 height-32" readonly="" value="1">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0 bg-input-guest-blue">
                <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 serial height-32"
                    name="serial[]" value="">
            </td>
             <td
                class="border-right p-2 text-13 align-top border-bottom border-top-0 product-cell position-relative">
                <input type="hidden" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 id_seri height-32" name="id_seri[]"
                    value="">
                <input type="hidden" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 id_warranty height-32 bg-input-guest-blue"
                    name="id_warranty[]" value="">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 warranty-input name_warranty height-32 bg-input-guest-blue"
                    name="name_warranty[]" value="">
                <ul class='warranty-dropdown bg-white position-absolute w-100 rounded shadow p-0 scroll-data'
                    style='z-index: 99;top: 75%;display: none;'>
                </ul>
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 warranty height-32 bg-input-guest-blue" name="warranty[]"
                    value="">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off" name="note_seri[]"
                    class="border-0 pl-1 pr-2 py-1 w-100 note_seri height-32 bg-input-guest-blue" value="">
            </td>
            <td class="p-2 align-top border-bottom border-top-0 border-right">
             <svg class="delete-row" width="17" height="17" viewBox="0 0 17 17" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.1417 6.90625C13.4351 6.90625 13.673 7.1441 13.673 7.4375C13.673 7.47847 13.6682 7.5193 13.6589 7.55918L12.073 14.2992C11.8471 15.2591 10.9906 15.9375 10.0045 15.9375H6.99553C6.00943 15.9375 5.15288 15.2591 4.92702 14.2992L3.34113 7.55918C3.27393 7.27358 3.45098 6.98757 3.73658 6.92037C3.77645 6.91099 3.81729 6.90625 3.85826 6.90625H13.1417ZM9.03125 1.0625C10.4983 1.0625 11.6875 2.25175 11.6875 3.71875H13.8125C14.3993 3.71875 14.875 4.19445 14.875 4.78125V5.3125C14.875 5.6059 14.6371 5.84375 14.3438 5.84375H2.65625C2.36285 5.84375 2.125 5.6059 2.125 5.3125V4.78125C2.125 4.19445 2.6007 3.71875 3.1875 3.71875H5.3125C5.3125 2.25175 6.50175 1.0625 7.96875 1.0625H9.03125ZM9.03125 2.65625H7.96875C7.38195 2.65625 6.90625 3.13195 6.90625 3.71875H10.0938C10.0938 3.13195 9.61805 2.65625 9.03125 2.65625Z"
                        fill="#6B6F76"></path>
                </svg>
            </td>
        </tr>
         <tr id="row-add-warranty" data-index="${newIndex}" class="bg-white row-warranty" style="display: none" data-product-code="" data-product-id="">
            <td colspan="5"
                class="border-right p-2 text-13 align-top border-bottom border-top-0">
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <button type="button" class="btn-add-warranty btn">
                    +
                </button>
            </td>
            <td colspan="3"
                class="border-right p-2 text-13 align-top border-bottom border-top-0"></td>
        </tr>
    `;

    // Thêm hàng mới vào <tbody>
    $("#tbody-product-data").append(newRow);

    // Tạo danh sách sản phẩm từ mảng products
    var productList = "";
    products.forEach((product) => {
        productList += `
            <li data-id="${product.id}">
                <a href="javascript:void(0);" class="text-dark d-flex justify-content-between p-2 idProduct w-100"
                    data-name="${product.product_name}" data-brand="${product.brand}" data-id="${product.id}">
                    <span class="w-50 text-13-black" style="flex:2">${product.product_name}</span>
                </a>
            </li>
        `;
    });

    // Gán danh sách vào ul.list_product
    $("#tbody-product-data").find("ul.list_product").last().html(productList);
});

$(document).on("click", ".btn-add-warranty", function () {
    // Lấy hàng hiện tại của nút vừa bấm
    const currentRow = $(this).closest("tr");
    const index = currentRow.data("index"); // Lấy data-index
    // Lấy thông tin data-product-code và data-product-id nếu cần
    const productCode = currentRow.attr("data-product-code");
    const productId = currentRow.attr("data-product-id");
    // Tạo hàng mới
    const newRow = $(`
        <tr class="row-warranty bg-white" data-index="${index}" data-product-code="${productCode}" data-product-id="${productId}">
            <td colspan="5" class="border-right p-2 text-13 align-top border-bottom border-top-0">
            </td>
            <td
                class="border-right p-2 text-13 align-top border-bottom border-top-0 product-cell position-relative">
                <input type="hidden" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 id_seri height-32"
                    name="id_seri[]" value="">
                <input type="hidden" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 id_warranty height-32"
                    name="id_warranty[]" value="">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 warranty-input name_warranty height-32 bg-input-guest-blue"
                    name="name_warranty[]" value="">
                <ul class='warranty-dropdown bg-white position-absolute w-100 rounded shadow p-0 scroll-data'
                    style='z-index: 99;top: 75%;display: none;'>
                </ul>
            </td>
            <td colspan="2" class="border-right p-2 text-13 align-top border-bottom border-top-0">
            </td>
            <td class="p-2 align-top border-bottom border-top-0 border-right">
            </td>
        </tr>`);
    currentRow.before(newRow);
    const $dropdownList = newRow.find(".warranty-dropdown");

    if (responseData[index]) {
        populateWarrantyDropdown(responseData[index], $dropdownList);
    } else {
        console.log(`Không có dữ liệu trong responseData cho index ${index}`);
    }
});

$(document).ready(function () {
    function toggleListGuest(input, list, filterInput) {
        input.on("click", function () {
            list.show();
        });
        $(document).click(function (event) {
            if (
                !$(event.target).closest(input).length &&
                !$(event.target).closest(filterInput).length
            ) {
                list.hide();
            }
        });
        var applyFilter = function () {
            var value = filterInput.val().toUpperCase();
            list.find("li").each(function () {
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
    $('a[name="search-info"]').on("click", function () {
        const dataId = $(this).attr("id");
        const dataName = $(this).data("name");
        const phone = $(this).data("phone");
        const address = $(this).data("address");
        const contact = $(this).data("contact");
        $("#customer_id").val(dataId);
        $("#customer_name").val(dataName);
        $('[name="phone"]').val(phone);
        $('[name="contact_person"]').val(contact);
        $('[name="address"]').val(address);
    });
});
flatpickr("#dateCreate", {
    locale: "vn",
    dateFormat: "d/m/Y",
    defaultDate: "today",
    onChange: function (selectedDates) {
        // Lấy giá trị ngày đã chọn
        if (selectedDates.length > 0) {
            const formattedDate = flatpickr.formatDate(
                selectedDates[0],
                "Y-m-d"
            );
            document.getElementById("hiddenDateCreate").value = formattedDate;
        }
    },
});
// Hàm để đổ dữ liệu vào danh sách dropdown
function populateWarrantyDropdown(response, dropdownElement) {
    // Kiểm tra nếu response và danh sách warranty tồn tại
    if (response && response.warranty) {
        // Làm sạch danh sách dropdown trước khi thêm dữ liệu mới
        dropdownElement.empty();

        // Lặp qua từng item trong danh sách warranty và thêm vào dropdown
        response.warranty.forEach((item) => {
            const listItem = `
                <li data-id="${item.id}">
                    <a href="javascript:void(0);" 
                        class="dropdown-link text-dark d-flex justify-content-between p-2 w-100" 
                        data-name_warranty="${item.name_warranty}" 
                        data-warranty="${item.warranty}"
                        data-id_warranty="${item.id}"
                        data-status="${item.status}"
                        data-seri="${item.sn_id}">
                        <span class="warranty-name text-13-black w-50" style="flex:2">${item.name_warranty}</span>
                    </a>
                </li>`;
            dropdownElement.append(listItem);
        });
    }
}
function showRowWarrantyByIndex(index) {
    const $rowWarranty = $(`.row-warranty[data-index="${index}"]`);
    if ($rowWarranty.length > 0) {
        $rowWarranty.show();
    }
}
