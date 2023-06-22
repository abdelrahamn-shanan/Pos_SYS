$(document).ready(function () {

    $(document).on('input', '#search_by_text', function(e) {
        make_search();
    });

    $(document).on('change', '#supplier_code_search', function(e) {
        make_search();
    });

    $(document).on('change', '#store_id', function(e) {
        make_search();
    });

    $(document).on('change', '#order_date_from', function(e) {
        make_search();
    });

    $(document).on('change', '#order_date_to', function(e) {
        make_search();
    });

    $('input[type=radio][name=searchbyradio]').change(function () {
        make_search();
  
    });
  
    function make_search() {
      var search_by_text = $("#search_by_text").val();
      var supplier_code = $("#supplier_code_search").val();
      var store_id = $("#store_id").val();
      var searchbyradio = $("input[type=radio][name=searchbyradio]:checked").val();
      var token_search = $("#token_search").val();
      var ajax_search_url = $("#ajax_search_url").val();
      var order_date_from = $("#order_date_from").val();
      var order_date_to = $("#order_date_to").val();
  
      jQuery.ajax({
          url: ajax_search_url,
          type: 'post',
          dataType: 'html',
          cache: false,
          data: { search_by_text: search_by_text, supplier_code: supplier_code,
             "_token": token_search,searchbyradio: searchbyradio , store_id: store_id , order_date_from: order_date_from
             ,order_date_to: order_date_to},
          success: function (data) {
  
              $("#ajax_responce_serarchDiv").html(data);
          },
          error: function () {
  
          }
      });
  
  }

  $(document).on('click', '#ajax_pagination_in_search a ', function (e) {
    e.preventDefault();
    var search_by_text = $("#search_by_text").val();
    var supplier_code = $("#supplier_code_search").val();
    var store_id = $("#store_id").val();
    var searchbyradio = $("input[type=radio][name=searchbyradio]:checked").val();
    var token_search = $("#token_search").val();
    var url = $(this).attr("href");

    jQuery.ajax({
        url: url,
        type: 'post',
        dataType: 'html',
        cache: false,
        data: { search_by_text: search_by_text, supplier_code: supplier_code, store_id: store_id,
           "_token": token_search,searchbyradio: searchbyradio },
        success: function (data) {

            $("#ajax_responce_serarchDiv").html(data);
        },
        error: function () {

        }
    });


});
  




})