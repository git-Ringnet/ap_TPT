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
        event.preventDefault(); // Ngăn nút submit form
        $(this).closest("tr").remove(); // Xóa dòng chứa nút "Xoá" được bấm
        updateRowNumbers(); // Cập nhật lại số thứ tự
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
    if (productCode !== "") {
        product.product_code = productCode;
    }
    if (productName !== "") {
        product.product_name = productName;
    }
    if (productBrand !== "") {
        product.product_brand = productBrand;
    }

    return product;
}

// Hàm xử lý khi bấm nút Xác nhận
$(document).on("click", ".submit-button", function (event) {
    event.preventDefault();

    let serialNumbers = getSerialNumbers(); // Lấy mảng serial numbers
    let product = getProduct(); // Lấy mảng product

    if (serialNumbers.length > 0 || product.length > 0) {
        // Kiểm tra xem có dữ liệu không
        // Tạo mảng JSON
        let data = JSON.stringify({
            serial_numbers: serialNumbers,
            products: product,
        });
        console.log(data); // In dữ liệu JSON ra console
    } else {
        alert(
            "Vui lòng nhập ít nhất một serial number hoặc thông tin sản phẩm."
        );
    }
});
