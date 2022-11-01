$(".common_btn").each(function (index) {
    $(this).on("click", function () {
        $(".common_btn").each(function () {
            this.innerHTML = +this.innerHTML % 3 + 1
        })
    });
});