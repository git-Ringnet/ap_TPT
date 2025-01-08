let barcodeData = ""; // Biến lưu trữ dữ liệu barcode
let barcodeTimeout; // Timeout để nhận dạng chuỗi barcode
const barcodeDelay = 50; // Thời gian delay giữa các ký tự từ máy quét

// Xử lý sự kiện khi quét mã barcode hoặc nhấn Enter/Tab
$(document).on("keydown", function (e) {
    // Chặn mở Developer Tools
    if (
        (e.ctrlKey &&
            (e.key === "i" ||
                e.key === "I" ||
                e.key === "j" ||
                e.key === "J" ||
                e.key === "u" ||
                e.key === "U")) ||
        e.key === "F12"
    ) {
        e.preventDefault();
        return;
    }

    // Nếu timeout đang chạy, hủy timeout cũ
    if (barcodeTimeout) clearTimeout(barcodeTimeout);

    // Chỉ xử lý các ký tự hoặc Enter, Tab
    if (e.key.length === 1 || e.key === "Enter" || e.key === "Tab") {
        if (e.key !== "Enter" && e.key !== "Tab") {
            barcodeData += e.key; // Thêm ký tự vào chuỗi barcode (trừ Enter và Tab)
        }

        // Nếu nhấn Enter, hoàn thành chuỗi barcode và di chuyển xuống ô input tiếp theo
        if (e.key === "Enter" || e.key === "Tab") {
            e.preventDefault(); // Ngăn hành động mặc định của Enter hoặc Tab

            if (barcodeData.trim() !== "") {
                // Xử lý barcode và điền vào ô input hiện tại
                fillBarcodeAndMoveNext(barcodeData.trim());
            } else {
                // Nếu không có dữ liệu barcode, chỉ di chuyển đến ô input tiếp theo
                moveToNextInput();
            }

            barcodeData = ""; // Reset chuỗi barcode
            return;
        }

        // Đặt timeout để reset chuỗi nếu không có thêm ký tự sau một khoảng thời gian
        barcodeTimeout = setTimeout(() => {
            barcodeData = ""; // Reset chuỗi barcode
        }, barcodeDelay);
    }
});

// Hàm điền barcode vào ô input hiện tại và chuyển đến ô tiếp theo
function fillBarcodeAndMoveNext(barcode) {
    // Tìm tất cả các input có class `.seri-input-check`
    const inputs = $(".seri-input-check");

    // Tìm input đang focus hiện tại
    const currentInput = $("input:focus");

    // Lấy chỉ số của input hiện tại
    const currentIndex = inputs.index(currentInput);

    if (currentIndex !== -1) {
        // Điền giá trị barcode vào ô hiện tại
        $(currentInput).val(barcode);

        // Tìm ô input tiếp theo
        const nextInput = inputs[currentIndex + 1];

        if (nextInput) {
            // Focus vào ô input tiếp theo
            $(nextInput).focus();
        } else {
            $("#add-rows").click();
        }
    }
}

// Hàm di chuyển con trỏ đến ô input tiếp theo
function moveToNextInput() {
    // Tìm tất cả các input có class `.seri-input-check`
    const inputs = $(".seri-input-check");

    // Tìm input đang focus hiện tại
    const currentInput = $("input:focus");

    // Lấy chỉ số của input hiện tại
    const currentIndex = inputs.index(currentInput);

    if (currentIndex !== -1) {
        // Tìm ô input tiếp theo
        const nextInput = inputs[currentIndex + 1];

        if (nextInput) {
            // Focus vào ô input tiếp theo
            $(nextInput).focus();
        } else {
            // Nếu không có ô input tiếp theo, tự động thêm dòng mới
            $("#add-rows").click();
        }
    }
}
