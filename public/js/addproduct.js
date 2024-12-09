$(document).ready(function () {
    let rowCount = $("#table-body tr").length; // Đếm số dòng ban đầu
    // Hàm thêm dòng
    $("#add-rows").click(function (event) {
        event.preventDefault(); // Ngăn nút submit form
        const numRows = parseInt($("#row-count").val(), 10); // Lấy số lượng dòng cần thêm từ input

        if (isNaN(numRows) || numRows <= 0) {
            alert("Vui lòng nhập số dòng hợp lệ (lớn hơn 0)");
            return;
        }

        for (let i = 0; i < numRows; i++) {
            rowCount++; // Tăng số thứ tự
            const newRow = `
                <tr class="height-40">
                    <td class="text-13-black border py-0 text-center">${rowCount
                        .toString()
                        .padStart(2, "0")}</td>
                    <td class="text-13-black border py-0 pl-3">
                        <input type="text" id="form_code" name="form_code" style="flex:2;" 
                            placeholder="Nhập thông tin" class="text-13-black w-100 border-0">
                    </td>
                    <td class="text-13-black border py-0 text-center">
                        <button class="btn btn-danger btn-sm delete-row">Xoá</button>
                    </td>
                </tr>
            `;
            $("#table-body").append(newRow); // Thêm dòng mới vào bảng
        }

        $("#row-count").val(""); // Xóa giá trị trong input sau khi thêm
    });

    // Hàm xóa dòng
    $(document).on("click", ".delete-row", function (event) {
        event.preventDefault();
        const currentRow = $(this).closest("tr");
        const productCode = currentRow.data("product-code");
        currentRow.remove();
        const remainingRows = $(
            `#tbody-product-data tr[data-product-code="${productCode}"]`
        ).not("#serials-count");

        if (remainingRows.length === 0) {
            $(
                `#tbody-product-data tr#serials-count[data-product-code="${productCode}"]`
            ).remove();
        } else {
            const serialCount = remainingRows.length;
            const serialCountRow = $(
                `#tbody-product-data tr#serials-count[data-product-code="${productCode}"]`
            );
            serialCountRow.find('input[name="serial_count"]').val(serialCount);
        }
        updateRowNumbers();
    });

    // Hàm cập nhật số thứ tự
    function updateRowNumbers() {
        rowCount = 0; // Đặt lại số thứ tự
        $("#table-body tr").each(function (index) {
            rowCount = index + 1; // Cập nhật lại số thứ tự
            $(this).find("td:first").text(rowCount.toString().padStart(2, "0"));
        });
    }
});

function getSerialNumbers() {
    let serialNumbers = [];
    // Lặp qua tất cả các dòng trong bảng
    $("#table-body tr").each(function () {
        let serialInput = $(this).find('input[name="form_code"]').val(); // Lấy giá trị input
        if (serialInput.trim() !== "") {
            // Nếu ô input không trống
            serialNumbers.push(serialInput); // Thêm vào mảng
        }
    });

    return serialNumbers; // Trả về mảng serial numbers
}

function getProduct() {
    let product = {};
    let productCode = $("#product_code_input").val().trim();
    let productName = $("#product_name_input").val().trim();
    let productBrand = $("#product_brand_input").val().trim();
    let productId = $("#product_id_input").val().trim();
    if (productCode !== "") {
        product.product_code = productCode;
    }
    if (productName !== "") {
        product.product_name = productName;
    }
    if (productBrand !== "") {
        product.product_brand = productBrand;
    }
    if (productId !== "") {
        product.id = productId;
    }

    return product;
}

$(document).on("click", ".btn-destroy-modal", function () {
    let modalId = $(this).data("modal-id");
    $("#" + modalId)
        .find("input")
        .val("");
});
// Hàm xử lý khi bấm nút Xác nhận
$(document).on("click", ".submit-button", function (event) {
    event.preventDefault(); // Ngăn chặn hành vi mặc định của nút

    let serialNumbers = getSerialNumbers(); // Lấy mảng serial numbers
    let product = getProduct(); // Lấy thông tin sản phẩm

    if (
        serialNumbers.length === 0 &&
        (!product || Object.keys(product).length === 0)
    ) {
        alert(
            "Vui lòng nhập ít nhất một serial number hoặc thông tin sản phẩm."
        );
        return;
    }

    // Render dữ liệu vào tbody-product-data
    let $tbody = $("#tbody-product-data"); // Lấy tbody bằng jQuery
    serialNumbers.forEach(function (serial, index) {
        let newRow = createSerialRow(index, product, serial);
        $tbody.append(newRow); // Thêm dòng mới vào tbody
    });
    // Thêm hàng cuối cùng để đếm số lượng serial
    let countRow = createCountRow(serialNumbers.length, product.product_code);
    $tbody.append(countRow); // Thêm dòng đếm số lượng vào cuối bảng

    $(".btn-destroy-modal").click(); // Đóng modal
});

// Hàm tạo hàng dữ liệu với serial
function createSerialRow(index, product, serial) {
    return `
        <tr id="serials-data" class="bg-white" data-index="${
            index + 1
        }" data-product-code="${product.product_code}">
         <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32" readonly
                    name="product_id" value="${product.id || ""}">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 product_code height-32" readonly
                    name="product_code" value="${product.product_code || ""}">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 product_name height-32" readonly
                    name="product_name" value="${product.product_name || ""}">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 brand height-32" readonly
                    name="brand" value="${product.product_brand || ""}">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 height-32" readonly
                    name="" value="1">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 serial height-32"
                    name="serial[]" value="${serial}">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 note height-32 bg-input-guest-blue"
                    name="note_seri[]" value="">
            </td>
            <td class="p-2 align-top activity border-bottom border-top-0">
                <svg class="delete-row" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.1417 6.90625C13.4351 6.90625 13.673 7.1441 13.673 7.4375C13.673 7.47847 13.6682 7.5193 13.6589 7.55918L12.073 14.2992C11.8471 15.2591 10.9906 15.9375 10.0045 15.9375H6.99553C6.00943 15.9375 5.15288 15.2591 4.92702 14.2992L3.34113 7.55918C3.27393 7.27358 3.45098 6.98757 3.73658 6.92037C3.77645 6.91099 3.81729 6.90625 3.85826 6.90625H13.1417ZM9.03125 1.0625C10.4983 1.0625 11.6875 2.25175 11.6875 3.71875H13.8125C14.3993 3.71875 14.875 4.19445 14.875 4.78125V5.3125C14.875 5.6059 14.6371 5.84375 14.3438 5.84375H2.65625C2.36285 5.84375 2.125 5.6059 2.125 5.3125V4.78125C2.125 4.19445 2.6007 3.71875 3.1875 3.71875H5.3125C5.3125 2.25175 6.50175 1.0625 7.96875 1.0625H9.03125ZM9.03125 2.65625H7.96875C7.38195 2.65625 6.90625 3.13195 6.90625 3.71875H10.0938C10.0938 3.13195 9.61805 2.65625 9.03125 2.65625Z"
                        fill="#6B6F76"></path>
                </svg>
            </td>
        </tr>
    `;
}

// Hàm tạo hàng cuối cùng để đếm số lượng serial
function createCountRow(count, product_code) {
    return `
        <tr id="serials-count" class="bg-white" data-product-code="${product_code}">
            <td colspan="3" class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">Số lượng serial:</td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 height-32" readonly
                    name="serial_count" value="${count}">
            </td>
            <td colspan="3" class="border-right p-2 text-13 align-top border-bottom border-top-0"></td>
        </tr>
    `;
}

//lấy thông tin hàng
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

    //nhà cung cấp
    toggleListGuest(
        $("#product_code_input"),
        $("#listProducts"),
        $("#searchProduct")
    );
    //lấy tên nhà cung cấp và id
    $('a[name="info-product"]').on("click", function () {
        const dataName = $(this).data("name");
        const dataCode = $(this).data("code");
        const dataBrand = $(this).data("brand");
        const data_id = $(this).data("id");
        $("#product_code_input").val(dataCode);
        $("#product_name_input").val(dataName);
        $("#product_brand_input").val(dataBrand);
        $("#product_id_input").val(data_id);
    });
});
