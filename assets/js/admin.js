jQuery(document).ready(function ($) {

    $('.onlyNumbersInput').on('input paste', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    let clickNasrUpdateRow = true;

    $('.oni_update_row').click(function (e) {

        e.preventDefault();

        if (clickNasrUpdateRow) {

            clickNasrUpdateRow = false;

            let dataId = $(this).attr('data-id');
            if (confirm('آیا از حذف این سوال اطمینان دارید؟ ')) {
                $.ajax({
                    type: "post",
                    async: false,
                    url: oni_js.ajaxurl,
                    dataType: "json",
                    data: {
                        action: 'oni_update_row',
                        dataId: dataId,

                    },
                    beforeSend: function () {
                        $('.oni-loader ').show();
                    },
                    success: function (response) {
                        location.reload(true);

                    },
                    error: function (response) {
                        console.error(response);

                    },
                });
            }
        }
    });

    $('#del_question').click(function (e) {

        e.preventDefault();



        if (confirm('آیا از حذف کل سوال ها اطمینان دارید؟ ')) {


            $.ajax({
                type: "post",
                async: false,
                url: oni_js.ajaxurl,
                dataType: "json",
                data: {
                    action: 'oni_del_all_question',

                },
                beforeSend: function () {
                    $('.oni-loader ').show();
                },
                success: function (response) {
                    location.reload(true);

                },
                error: function (response) {
                    console.error(response);


                },
            });
        }






    });
    jalaliDatepicker.startWatch({
        minDate: "attr",
        maxDate: "attr"
    });


})

