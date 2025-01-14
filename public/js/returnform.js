function populateTableRows(products, tableSelector, dataProduct, type) {
    // Clear existing rows
    $(tableSelector).find(".row-product").remove();

    // Determine visibility classes for columns based on 'type'
    const hideReplacement = type === 2 || type === 3 ? "d-none" : "";
    const hideExtraWarranty = type === 1 || type === 3 ? "d-none" : "";
    const hideAll = type === 3 ? "d-none" : "";

    // Loop through the product data and create rows dynamically
    products.forEach((product, index) => {
        // Construct the HTML row
        let row = `
            <tr class="row-product bg-white">
                <td class="border-right p-2 text-13 align-top border-bottom border-top-0 pl-4">
                    <input type="hidden" name="return[${index}][product_id]" value="${
            product.product_id || ""
        }">
                    <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 product_code height-32" readonly name="return[${index}][product_code]" value="${
            product.product?.product_code || ""
        }">
                </td>
                <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                    <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 product_name height-32" readonly name="return[${index}][product_name]" value="${
            product.product?.product_name || ""
        }">
                </td>
                <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                    <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 brand height-32" readonly name="return[${index}][brand]" value="${
            product.product?.brand || ""
        }">
                </td>
                <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                    <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 quantity height-32" readonly name="return[${index}][quantity]" value="${
            product.quantity || ""
        }">
                </td>
                <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                 <input type="hidden" autocomplete="off"class="border-0 pl-1 pr-2 py-1 w-100 serial_id height-32" readonly name="return[${index}][serial_id]" value="${
            product.serial?.id || ""
        }">
                    <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 serial_code height-32" readonly name="return[${index}][serial_code]" value="${
            product.serial?.serial_code || ""
        }">
                </td>
                <td class="border-right p-2 text-13 align-top border-bottom border-top-0 ${hideReplacement}">
                    <input type="hidden" min="0" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 replacement_code height-32 bg-input-guest-blue" id="replacement_code_${index}" name="return[${index}][replacement_code]" value="">
                    <div class="search-container">
                        <input type="text" class="search-input border-0 pl-1 pr-2 py-1 w-100 serial_code height-32" placeholder="Search..." />
                        <ul class="search-list border rounded">
                            ${dataProduct
                                .map(
                                    (item) => `
                                <li class="search-item p-2 border-bottom" data-id="${index}" data-replace_id="${item.id}" data-code="${item.product_code}">
                                    ${item.product_code}
                                </li>
                            `
                                )
                                .join("")}
                        </ul>
                    </div>
                </td>
                <td class="border-right p-2 text-13 align-top border-bottom border-top-0 position-relative ${hideReplacement}">
                    <input type="text" min="0" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 replacement_serial_number_id height-32 bg-input-guest-blue" name="return[${index}][replacement_serial_number_id]">
                    <span class="check-icon"></span>
                </td>
                <td class="border-right p-2 text-13 align-top border-bottom border-top-0 ${hideExtraWarranty}">
                    <input type="number" min="0" max="100" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 extra_warranty height-32 bg-input-guest-blue" name="return[${index}][extra_warranty]">
                </td>
                <td class="border-right p-2 text-13 align-top border-bottom border-top-0">
                    <input type="text" autocomplete="off" class="border-0 pl-1 pr-2 py-1 w-100 note height-32 bg-input-guest-blue" name="return[${index}][note]">
                </td>
            </tr>
        `;
        // Append the row to the table
        $(tableSelector).append(row);
    });
}

$(document).ready(function () {
    // Hiển thị danh sách khi focus vào input
    $(document).on("focus", ".search-input", function () {
        const $input = $(this);
        const $searchList = $input.next(".search-list");

        // Hiển thị danh sách nếu có mục
        if ($searchList.find(".search-item").length > 0) {
            $searchList.addClass("active"); // Hiển thị danh sách
        }
    });

    // Ẩn danh sách khi input mất focus
    $(document).on("blur", ".search-input", function () {
        const $searchList = $(this).next(".search-list");
        // Đợi 100ms để không ảnh hưởng click
        setTimeout(() => {
            $searchList.removeClass("active");
        }, 100);
    });

    // Lọc danh sách khi nhập vào input
    $(document).on("input", ".search-input", function () {
        const filter = $(this).val().toLowerCase(); // Chuyển giá trị nhập vào thành chữ thường
        const $searchList = $(this).next(".search-list");

        // Hiển thị tất cả mục nếu input rỗng
        if (filter === "") {
            $searchList.find(".search-item").show(); // Hiển thị lại toàn bộ mục
        } else {
            // Lọc các item trong danh sách
            $searchList.find(".search-item").each(function () {
                const text = $(this).text().toLowerCase(); // Chuyển text của item thành chữ thường
                if (text.includes(filter)) {
                    $(this).show(); // Hiển thị item nếu khớp
                } else {
                    $(this).hide(); // Ẩn item nếu không khớp
                }
            });
        }

        // Hiển thị hoặc ẩn danh sách dựa trên kết quả lọc
        if ($searchList.find(".search-item:visible").length > 0) {
            $searchList.addClass("active");
        } else {
            // $searchList.removeClass("active");
        }
    });

    // Sự kiện click vào item trong danh sách
    $(document).on("click", ".search-item", function () {
        const $item = $(this);
        const $input = $item.closest(".search-container").find(".search-input");
        var code = $item.data("code");
        var id = $item.data("id");
        var replace_id = $item.data("replace_id");
        // Gán giá trị của item vào input
        $input.val(code);
        $(`#replacement_code_${id}`).val(replace_id);
        // Ẩn danh sách
        $item.closest(".search-list").removeClass("active");
    });
});
