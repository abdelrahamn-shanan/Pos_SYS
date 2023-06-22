$(document).ready(function () {

    $(document).on('input', '#search_by_text', function (e) {
        make_search();
    });

    $(document).on('change', '#item_type', function (e) {
        make_search();
    });

    $(document).on('change', '#itemcard_category_search', function (e) {
        make_search();
    });

    $('input[type=radio][name=searchbyradio]').change(function () {
        make_search();

    });


    function make_search() {
        var search_by_text = $("#search_by_text").val();
        var item_type = $("#item_type").val();
        var itemcard_category_search = $("#itemcard_category_search").val();
        var searchbyradio = $("input[type=radio][name=searchbyradio]:checked").val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_search_url").val();

        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: { search_by_text: search_by_text, item_type: item_type, itemcard_category_search: itemcard_category_search, "_token": token_search, searchbyradio: searchbyradio },
            success: function (data) {

                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function () {

            }
        });

    }


    $(document).on('click', '#ajax_pagination_in_search a', function (e) {
        e.preventDefault();
        var search_by_text = $("#search_by_text").val();
        var item_type = $("#item_type").val();
        var itemcard_category_search = $("#itemcard_category_search").val();
        var searchbyradio = $("input[type=radio][name=searchbyradio]:checked").val();
        var token_search = $("#token_search").val();
        var url = $(this).attr("href");


        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: { search_by_text: search_by_text, item_type: item_type, itemcard_category_search: itemcard_category_search, "_token": token_search, searchbyradio: searchbyradio },
            success: function (data) {

                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function () {

            }
        });



    });

});