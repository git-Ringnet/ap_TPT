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
        $("#provider_name"),
        $("#listProvider"),
        $("#searchProvider")
    );
    //Người lập
    toggleListGuest(
        $("#user_name"),
        $("#listUser"),
        $("#searchUser")
    );
    //lấy tên nhà cung cấp và id
    $('a[name="search-info"]').on('click', function() {
        const dataId = $(this).attr('id');
        const dataName = $(this).data('name');
        const phone = $(this).data('phone');
        const address = $(this).data('address');
        $("#provider_id").val(dataId);
        $("#provider_name").val(dataName);
        $('[name="phone"]').val(phone);
        $('[name="address"]').val(address);
    });
    //lấy người lập
    $('a[name="create-info"]').on('click', function() {
        const dataId = $(this).attr('id');
        const dataName = $(this).data('name');
        $("#user_id").val(dataId);
        $("#user_name").val(dataName);
    });
});