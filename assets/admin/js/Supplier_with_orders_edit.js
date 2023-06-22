$(document).ready(function () {
      $(document).on('input', '#quantity_add', function (e) {
        recaluclate_Add();
      });

      $(document).on('input', '#price-add', function (e) {
        recaluclate_Add();
      });
      $(document).on('click', '.editItemDetails', function(e) {
        var id = $(this).data("id");
        var autoserailparent = $("#parent_auto_serial").val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#ajax_load_edit_item_details").val();
        jQuery.ajax({
            url: ajax_search_url,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                autoserailparent: autoserailparent,
                "_token": token_search,
                id: id
            },
            success: function(data) {
              $("#edit_item_Modal_body").html(data);
              $("#edit_item_Modal").modal("show");  
              $("#Add_item_Modal_body").html("");
              $("#Add_item_Modal").modal("hide");          
            },
            error: function() {
            }
        });
    });
    $(document).on('click', '#load_modal_add_detailsBtn', function(e) {
      var id = $(this).data("id");
      var autoserailparent = $("#parent_auto_serial").val();
      var token_search = $("#token_search").val();
      var ajax_search_url = $("#Ajax_load_modal_add_details").val();
      jQuery.ajax({
          url: ajax_search_url,
          type: 'post',
          dataType: 'html',
          cache: false,
          data: {
              autoserailparent: autoserailparent,
              "_token": token_search,
          },
          success: function(data) {
              $("#Add_item_Modal_body").html(data);
              $("#Add_item_Modal").modal("show");
              $("#edit_item_Modal_body").html("");
              $("#edit_item_Modal").modal("hide"); 
            
          },
          error: function() {
          }
      });
    });

    $(document).on('click', '#EditDetailsItem', function (e) {
      var item_code_add = $("#item_code_add").val();
      if (item_code_add == "") {
        alert("من فضلك  اختر الصنف");
        $("#item_code_add").focus();
        return false;
      }
      var uom_id_Add = $("#uom_id_Add").val();
      if (uom_id_Add == "") {
        alert("من فضلك  اختر الوحدة");
        $("#uom_id_Add").focus();
        return false;
      }
      var isparentuom =$("#uom_id_Add").children('option:selected').data("isparentuom");

      var quantity_add = $("#quantity_add").val();
      if (quantity_add == "" || quantity_add == 0) {
        alert("من فضلك  ادخل الكمية المستلمة");
        $("#quantity_add").focus();
        return false;
      }


      var price_add = $("#price-add").val();
      if (price_add == "") {
        alert("من فضلك  ادخل سعر الوحدة ");
        $("#price-add").focus();
        return false;
      }
      var type = $("#item_code_add").children('option:selected').data("type");
      if (type == 2) {
        var production_date = $("#production-date").val();
        if (production_date == "") {
          alert("من فضلك  اختر تاريخ الانتاج  ");
          $("#production-date").focus();
          return false;
        }

        var expire_date = $("#expire-date").val();
        if (expire_date == "") {
          alert("من فضلك  اختر تاريخ انتهاء الصلاحية  ");
          $("#expire-date").focus();
          return false;
        }

        if (expire_date < production_date) {
          alert("عفوا لايمكن ان يكون تاريخ الإنتهاء اقل من تاريخ الانتاج !!!");
          $("#expire_date").focus();
          return false;
        }


      } else {
        var production_date = $("#production-date").val();
        var expire_date = $("#expire-date").val();
      }

      var total_add = $("#total-add").val();
      if (total_add == "") {
        alert("من فضلك  ادخل اجمالي   الاصناف  ");
        $("#total-add").focus();
        return false;
      }
      var parentautoserial = $("#parent_auto_serial").val();
      var token_search = $("#token_search").val();
      var ajax_details = $("#Ajax_edit_details").val();

          jQuery.ajax({
              url: ajax_details,
              type: 'post',
              dataType: 'json',
              cache: false,
              data: {
                  parentautoserial:parentautoserial,"_token": token_search , item_code_add:item_code_add,
                  uom_id_Add:uom_id_Add,isparentuom:isparentuom,quantity_add:quantity_add,price_add:price_add
                  ,expire_date:expire_date,production_date:production_date,total_add:total_add,type:type,
              },
              success: function (data) {
                  alert("تم التحديث بنجاح");
                  reload_parent_details();
                  reload_itemsdetails();
                
              },
              error: function (data) {
                alert(data)
              }
          });

    });



    function recaluclate_Add() {
      var quantity_add = $("#quantity_add").val();
      var price_add = $("#price-add").val();
      if (quantity_add == "") quantity_add = 0;
      if (price_add == "") price_add = 0;
      $("#total-add").val(parseFloat(quantity_add) * parseFloat(price_add));
    }

    function reload_itemsdetails() {
      var autoserailparent = $("#parent_auto_serial").val();
      var token_search = $("#token_search").val();
      var ajax_search_url = $("#ajax_reload_items").val();
      jQuery.ajax({
          url: ajax_search_url,
          type: 'post',
          dataType: 'html',
          cache: false,
          data: {
              autoserailparent: autoserailparent,
              "_token": token_search
          },
          success: function(data) {
              $("#ajax_responce_serarchDivDetails").html(data);
          },
          error: function() {}
      });
    }

    function reload_parent_details() {
    var autoserailparent = $("#parent_auto_serial").val();
    var token_search = $("#token_search").val();
    var ajax_search_url = $("#ajax_reload_parent_bill").val();
    jQuery.ajax({
        url: ajax_search_url,
        type: 'post',
        dataType: 'html',
        cache: false,
        data: {
            autoserailparent: autoserailparent,
            "_token": token_search
        },
        success: function(data) {
            $("#ajax_responce_serarchDiv_parent_details").html(data);
        },
        error: function() {}
    });
    }

  

    


});





