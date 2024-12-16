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
                        <button class="btn btn-sm delete-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 14 14" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10.8226 5.6875C11.0642 5.6875 11.2601 5.88338 11.2601 6.125C11.2601 6.15874 11.2562 6.19237 11.2485 6.2252L9.94245 11.7758C9.75642 12.5663 9.05109 13.125 8.23897 13.125H5.76103C4.94894 13.125 4.24355 12.5663 4.05755 11.7758L2.75152 6.2252C2.69618 5.99001 2.84198 5.75447 3.07718 5.69913C3.11002 5.6914 3.14365 5.6875 3.17739 5.6875H10.8226ZM7.4375 0.875C8.64562 0.875 9.625 1.85438 9.625 3.0625H11.375C11.8583 3.0625 12.25 3.45426 12.25 3.9375V4.375C12.25 4.61662 12.0541 4.8125 11.8125 4.8125H2.1875C1.94588 4.8125 1.75 4.61662 1.75 4.375V3.9375C1.75 3.45426 2.14175 3.0625 2.625 3.0625H4.375C4.375 1.85438 5.35438 0.875 6.5625 0.875H7.4375ZM7.4375 2.1875H6.5625C6.07926 2.1875 5.6875 2.57925 5.6875 3.0625H8.3125C8.3125 2.57925 7.92074 2.1875 7.4375 2.1875Z"
                                fill="#151516" />
                            </svg>
                        </button>
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
        const productId = currentRow.data("product-id");
        const productCode = currentRow.data("product-code");
        currentRow.remove();
        const remainingRows = $(
            `#tbody-product-data tr[data-product-id="${productId}"]`
        ).not("#serials-count, #add-row-product");
        if (remainingRows.length === 0) {
            $(
                `#tbody-product-data tr#serials-count[data-product-id="${productId}"]`
            ).remove();
            $(
                `#tbody-product-data tr#add-row-product[data-product-code="${productCode}"]`
            ).remove();
        } else {
            const serialCount = remainingRows.length;
            const serialCountRow = $(
                `#tbody-product-data tr#serials-count[data-product-id="${productId}"]`
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
    //kiểm tra trang hiện tại
    let name_modal = $("#name_modal").val();
    let product = {};
    let productCode = $("#product_code_input").val().trim();
    let productName = $("#product_name_input").val().trim();
    let productBrand = $("#product_brand_input").val().trim();
    let productId = $("#product_id_input").val().trim();
    if (name_modal == "XH") {
        let productWarranty = $("#product_warranty_input").val().trim();
        if (productWarranty !== "") {
            product.warranty = productWarranty;
        }
    }
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

$(document).on("click", ".btn-destroy-modal,.btn-save-print", function () {
    let modalId = $(this).data("modal-id");
    $("#" + modalId)
        .find("input")
        .not("#name_modal") // Loại trừ phần tử có ID là name_modal
        .val("");
});
// Hàm xử lý khi bấm nút Xác nhận
$(document).on("click", ".submit-button", function (event) {
    event.preventDefault(); // Ngăn chặn hành vi mặc định của nút

    let name_modal = $("#name_modal").val();
    let serialNumbers = getSerialNumbers(); // Lấy mảng serial numbers
    let product = getProduct(); // Lấy thông tin sản phẩm

    if (!product || Object.keys(product).length === 0) {
        alert("Vui lòng nhập thông tin sản phẩm.");
        return;
    }
    if (serialNumbers.length === 0) {
        alert("Vui lòng nhập ít nhất một serial number");
        return;
    }

    // Kiểm tra nhập S/N trùng
    let duplicates = [];
    let seen = new Set();

    // Duyệt qua từng input để lấy giá trị
    $('input[name="form_code"]').each(function () {
        let value = $(this).val().trim().toLowerCase(); // Chuẩn hóa về chữ thường
        if (seen.has(value) && value !== "") {
            duplicates.push(value); // Thêm giá trị trùng vào mảng
        } else {
            seen.add(value); // Thêm giá trị vào tập hợp
        }
    });

    // Nếu có giá trị trùng, thông báo
    if (duplicates.length > 0) {
        alert("Các S/N bị trùng: " + duplicates.join(", "));
        return;
    }

    if (!product || Object.keys(product).length === 0) {
        alert("Vui lòng nhập thông tin sản phẩm.");
        return;
    }

    let $tbody = $("#tbody-product-data");

    let dataType = $("#modal-id").attr("data-type");
    if (dataType === "update") {
        console.log(product);
        $tbody.find("tr[data-product-id='" + product.id + "']").remove();
        $("#modal-id").attr("data-type", "create");
    }
    // Thêm các hàng mới từ serialNumbers
    serialNumbers.forEach(function (serial, index) {
        let newRow = createSerialRow(index, product, serial, name_modal); // Hàm tạo dòng mới
        $tbody.append(newRow); // Thêm dòng mới vào tbody
    });

    // Thêm hàng cuối cùng để đếm số lượng serial
    let countRow = createCountRow(serialNumbers.length, product, name_modal);
    $tbody.append(countRow); // Thêm dòng đếm số lượng vào cuối bảng

    $(".btn-destroy-modal").click(); // Đóng modal
    console.log(product);
});

// Hàm tạo hàng dữ liệu với serial
function createSerialRow(index, product, serial, name) {
    const hideLastColumn = name === "TN" ? "d-block" : "d-none";
    const hideLastWarranty = name === "XH" ? "d-block" : "d-none";
    return `
        <tr id="serials-data" class="row-product bg-white" data-index="${
            index + 1
        }" data-product-code="${product.product_code}"  data-product-id="${
        product.id
    }">
         <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4 d-none">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 product_id height-32" readonly
                    name="product_id[]" value="${product.id || ""}">
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
                    class="border-0 pl-1 pr-2 py-1 w-100 serial height-32 bg-input-guest-blue"
                    name="serial[]" value="${serial}">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0 ${hideLastColumn}">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 status_recept height-32 bg-input-guest-blue"
                    name="status_recept[]" value="">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0 ${hideLastWarranty}">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 warranty height-32 bg-input-guest-blue"
                    name="warranty[]" value="${product.warranty || ""}">
            </td>
            <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 note_seri height-32 bg-input-guest-blue"
                    name="note_seri[]" value="">
            </td>
            <td class="p-2 align-top activity border-bottom border-top-0 border-right">
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
function createCountRow(count, product, name) {
    let colspanValue1, colspanValue2;
    if (name === "TN" || name === "XH") {
        colspanValue1 = 4;
        colspanValue2 = 8;
    } else if (name === "NH" || name === "CNH") {
        colspanValue1 = 3;
        colspanValue2 = 7;
    }
    return `
        <tr id="serials-count" class="bg-white" data-product-code="${product.product_code}" data-product-id="${product.id}">
            <td colspan="2" class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4"></td>
            <td class="border-right p-2 text-13 align-center border-bottom border-top-0 text-right">Số lượng serial:</td>
            <td class="border-right p-2 text-13 align-center border-bottom border-top-0">
                <input type="text" autocomplete="off"
                    class="border-0 pl-1 pr-2 py-1 w-100 height-32" readonly
                    name="serial_count" value="${count}">
            </td>
            <td colspan="${colspanValue1}" class="border-right p-2 text-13 align-top border-bottom border-top-0"></td>
        </tr>
        <tr id="add-row-product" class="bg-white" data-product-code="${product.product_code}" data-product-id="${product.id}">
            <td colspan="${colspanValue2}" class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                <button type="button" class="save-info-product btn" data-product-id="${product.id}" data-product-code="${product.product_code}"
                 data-product-name="${product.product_name}" data-product-brand="${product.product_brand}" data-product-warranty="${product.warranty}">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.65625 2.625C7.65625 2.26257 7.36243 1.96875 7 1.96875C6.63757 1.96875 6.34375 2.26257 6.34375 2.625V6.34375H2.625C2.26257 6.34375
                    1.96875 6.63757 1.96875 7C1.96875 7.36243 2.26257 7.65625 2.625 7.65625H6.34375V11.375C6.34375 11.7374 6.63757 12.0312 7 12.0312C7.36243
                    12.0312 7.65625 11.7374 7.65625 11.375V7.65625H11.375C11.7374 7.65625 12.0312 7.36243 12.0312 7C12.0312 6.63757 11.7374 6.34375 11.375
                    6.34375H7.65625V2.625Z" fill="#151516"/>
                </svg>
                </button>
            </td>
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
        const dataWarranty = $(this).data("warranty");
        const data_id = $(this).data("id");
        $("#product_code_input").val(dataCode);
        $("#product_name_input").val(dataName);
        $("#product_brand_input").val(dataBrand);
        $("#product_id_input").val(data_id);
        $("#product_warranty_input").val(dataWarranty);
    });
});
$(document).on("click", ".save-info-product", function (e) {
    e.preventDefault();
    // Lấy giá trị của data-product-id từ phần tử được click
    const productId = $(this).data("product-id");
    const productName = $(this).data("product-name");
    const productCode = $(this).data("product-code");
    const productBrand = $(this).data("product-brand");
    const productWarranty = $(this).data("product-warranty");
    // Khai báo mảng để lưu thông tin sản phẩm
    const products = [];
    // Duyệt qua từng dòng có data-product-id trùng với productId
    $(
        "#tbody-product-data .row-product[data-product-id='" + productId + "']"
    ).each(function () {
        const $row = $(this); // Dòng hiện tại
        // Lấy tất cả thông tin sản phẩm trong dòng này
        const productInfo = {
            serial: $row.find(".serial").val(), // Chỉ lấy thông tin serial
        };
        // Thêm thông tin sản phẩm vào mảng
        products.push(productInfo);
    });

    // Xóa nội dung cũ trong tbody trước khi đổ dữ liệu mới
    $("#table-body").empty();
    $(".btn-save-print").click();
    $("#product_code_input").val(productCode);
    $("#product_name_input").val(productName || "");
    $("#product_brand_input").val(productBrand || "");
    $("#product_warranty_input").val(productWarranty || "");
    $("#product_id_input").val(productId);
    $("#modal-id").attr("data-type", "update");

    // Đổ dữ liệu chỉ với serial vào tbody
    products.forEach((product, index) => {
        const row = `
            <tr class="height-40">
                <td class="text-13-black border py-0 text-center">0${
                    index + 1
                }</td>
                <td class="text-13-black border py-0 pl-3">
                    <input type="text" name="form_code" value="${
                        product.serial
                    }" class="text-13-black w-100 border-0 serial-input" placeholder="Nhập thông tin">
                </td>
                <td class="text-13-black border py-0 text-center">
                    <button class="btn btn-sm delete-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.8226 5.6875C11.0642 5.6875 11.2601 5.88338 11.2601 6.125C11.2601 6.15874 11.2562 6.19237 11.2485 6.2252L9.94245 11.7758C9.75642 12.5663 9.05109 13.125 8.23897 13.125H5.76103C4.94894 13.125 4.24355 12.5663 4.05755 11.7758L2.75152 6.2252C2.69618 5.99001 2.84198 5.75447 3.07718 5.69913C3.11002 5.6914 3.14365 5.6875 3.17739 5.6875H10.8226ZM7.4375 0.875C8.64562 0.875 9.625 1.85438 9.625 3.0625H11.375C11.8583 3.0625 12.25 3.45426 12.25 3.9375V4.375C12.25 4.61662 12.0541 4.8125 11.8125 4.8125H2.1875C1.94588 4.8125 1.75 4.61662 1.75 4.375V3.9375C1.75 3.45426 2.14175 3.0625 2.625 3.0625H4.375C4.375 1.85438 5.35438 0.875 6.5625 0.875H7.4375ZM7.4375 2.1875H6.5625C6.07926 2.1875 5.6875 2.57925 5.6875 3.0625H8.3125C8.3125 2.57925 7.92074 2.1875 7.4375 2.1875Z" fill="#151516"></path>
                        </svg>
                    </button>
                </td>
            </tr>
        `;
        $("#table-body").append(row);
    });
    // Log thông tin sản phẩm tìm được
    // $(".btn-save-print").click();
    $("#product_code_input").val(productCode);
    $("#product_name_input").val(
        productName !== "undefined" ? productName : ""
    );
    $("#product_brand_input").val(
        productBrand !== "undefined" ? productBrand : ""
    );
    $("#product_warranty_input").val(
        productWarranty !== "undefined" ? productWarranty : ""
    );
    $("#product_id_input").val(productId);
    $("#modal-id").attr("data-type", "update");
});
