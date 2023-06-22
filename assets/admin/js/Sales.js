$(document).ready(function () {
    $(document).on('change', '#item_code', function (e) {
        get_item_uoms();
    });

    $(document).on('change', '#store_id', function (e) {
        get_item_uoms();
    });



    $(document).on('change', '#uom_id', function (e) {
        get_inv_itemcard_batches();
    });

 

    $(document).on('change', '#Sale_type', function (e) {
        get_item_unit_price();
    });

    $(document).on('input', '#item_qty', function (e) {
        calc_itemTotalPrice();
    });

    $(document).on('input', '#item_price', function (e) {
        calc_itemTotalPrice();
    });

    function get_item_uoms(){
        var item_code = $('#item_code').val();
        var store_id = $('#store_id').val();

        if(item_code !="" && store_id !=""){
            var token_search = $("#token_search").val();
            var Ajax_Sales_get_uom = $("#Ajax_Sales_get_uom").val();
    
            jQuery.ajax({
                url: Ajax_Sales_get_uom,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": token_search , "item_code":item_code
                },
                success: function (data) {
                   $("#UomDiv").html(data);
                    $("#UomDiv").show();
                    get_inv_itemcard_batches();

                },
                error: function () {

                    $("#UomDiv").hide();
                }
            });    
        }else{
            $("#UomDiv").html("");
            $("#UomDiv").hide(); 
            $("#ItemsQtyDiv").html("");           
            $("#ItemsQtyDiv").hide();           
                      
        }
    }

    function get_item_unit_price(){
        var item_code = $('#item_code').val();
        var uom_id = $('#uom_id').val();
        var Sale_type = $('#Sale_type').val();      
            var token_search = $("#token_search").val();
            var Ajax_Sales_get_price = $("#Ajax_Sales_get_price").val();
            jQuery.ajax({
                url: Ajax_Sales_get_price,
                type: 'post',
                dataType: 'json',
                cache: false,
                data: {
                    "_token": token_search , item_code:item_code , uom_id :uom_id , Sale_type :Sale_type
                },
                success: function (data) {
                 $('#item_price').val(data*1);
                 calc_itemTotalPrice();


                },
                error: function () {
                    $('#item_price').val("");
                    alert("حدث خطأ ");
                }
            });    

    }

    function get_inv_itemcard_batches(){
        var item_code = $('#item_code').val();
        var uom_id = $('#uom_id').val();
        var store_id = $('#store_id').val();
        if (item_code != "" && uom_id != "" && store_id != "") {
                var token_search = $("#token_search").val();
                var Ajax_Sales_get_qty = $("#Ajax_Sales_get_qty").val();
                jQuery.ajax({
                    url: Ajax_Sales_get_qty,
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": token_search , item_code:item_code , uom_id :uom_id ,store_id :store_id
                    },
                    success: function (data) {
                       $("#ItemsQtyDiv").html(data);
                        $("#ItemsQtyDiv").show();
                        get_item_unit_price();
                    },
                    error: function () {
                        $("#ItemsQtyDiv").hide();
                    }
                });    
                
            }else{
                $("#UomDiv").hide();
                $("#ItemsQtyDiv").hide();
            }
    }

    function calc_itemTotalPrice(){
        var item_price = $('#item_price').val();
        if(item_price=="") item_price=0;
        var item_qty = $('#item_qty').val();
        if(item_qty=="") item_qty=0;
        $('#item_total_price').val((parseFloat(item_price) * parseFloat(item_qty)) * 1);



    }

    $(document).on('click', '#add_items_to_invoice_Details', function (e) {
        var store_id = $("#store_id").val();
        if (store_id == "") {
            alert("من فضلك اختر المخزن ");
            $("#store_id").focus();
            return false;
        }
        var Sale_type = $("#Sale_type").val();
        if (Sale_type == "") {
            alert("من فضلك اختر نوع البيع ");
            $("#Sale_type").focus();
            return false;
        }
        var item_code = $("#item_code").val();
        if (item_code == "") {
            alert("من فضلك اختر  الصنف ");
            $("#item_code").focus();
            return false;
        }
        var uom_id = $("#uom_id").val();
        if (uom_id == "") {
            alert("من فضلك اختر  وحدة البيع ");
            $("#uom_id").focus();
            return false;
        }
        var inv_itemcard_batches_autoserial = $("#inv_itemcard_batches_autoserial").val();
        if (inv_itemcard_batches_autoserial == "") {
            alert("من فضلك اختر  الباتش ");
            $("#inv_itemcard_batches_autoserial").focus();
            return false;
        }
        var item_qty = $("#item_qty").val();
        if (item_qty == "") {
            alert("من فضلك  ادخل الكمية ");
            $("#item_qty").focus();
            return false;
        }
        var BatchQuantity = $("#inv_itemcard_batches_autoserial option:selected").data("qunatity");
        if (parseFloat(item_qty) > parseFloat(BatchQuantity)) {
            alert("عفوا الكمية المطلوبة اكبر من كمية الباتش  الموجوده بالمخزن");
            $("#item_qty").focus();
            return false;
        }
        var item_price = $("#item_price").val();
        if (item_price == "") {
            alert("من فضلك ادخل  السعر ");
            $("#item_price").focus();
            return false;
        }
        var is_bonus_or_normal = $("#is_bonus or normal").val();
        if (is_bonus_or_normal == "") {
            alert("من فضلك اختر هل بيع عادي ؟   ");
            $("#is_bonus or normal").focus();
            return false;
        }
        var item_total_price = $("#item_total_price").val();
        if (item_total_price == "") {
            alert("من فضلك  حقل الاجمالي مطلوب ! ");
            $("#item_total_price").focus();
            return false;
        }
        var store_name = $("#store_id option:selected").text();
        var uom_id_name = $("#uom_id option:selected").text();
        var item_code_name = $("#item_code option:selected").text();
        var sales_item_type_name = $("#Sale_type option:selected").text();
        var is_bonus_or_normal = $("#is_bonus or normal option:selected").text();
        var isparentuom = $("#uom_id option:selected").data("isparentuom");
        var token_search = $("#token_search").val();
        var add_new_item_row = $("#add_new_item_row").val();
        jQuery.ajax({
            url: add_new_item_row,
            type: 'post',
            dataType: 'html',
            cache: false,
            data: {
                "_token": token_search , item_code:item_code , uom_id :uom_id , Sale_type :Sale_type , store_id:store_id
                ,inv_itemcard_batches_autoserial : inv_itemcard_batches_autoserial , item_price:item_price, is_bonus_or_normal:is_bonus_or_normal
                , item_total_price:item_total_price , item_qty:item_qty ,sales_item_type_name:sales_item_type_name, item_code_name:item_code_name
                , uom_id_name:uom_id_name, store_name:store_name ,isparentuom:isparentuom
            },
            success: function (data) {
            $('#itemstableContainterBody').append(data);
            recalculate();


            },
            error: function () {
                alert("حدث خطأ ");
            }
        });    


        $(document).on('click', '.remove_current_row', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            recalculate();

        });

       

    });


    $(document).on('input', '#tax_percent', function (e) {
        var tax_percent = $(this).val();
        if(tax_percent==""){
          tax_percent=0;
        }
        if (tax_percent > 100) {
          alert("عفوا لايمكن ان يكون نسبة الضريبة اكبر من 100 % !!!");
          $("#tax_percent").val(0);
      }
        recalculate();
      })

      $(document).on('input', '#discount_percent', function (e) {
        var total_befor_discount = $('#total_befor_discount').val();
        total_befor_discount = parseFloat(total_befor_discount);
        var discount_percent = $(this).val();
        if (discount_percent == "") {
            discount_percent = 0;
        }
        var discount_type = $("#discount_type").val();
        if (discount_type == 1) {   // type is percent
            if (discount_percent > 100) {
                alert("عفوا لايمكن ان يكون نسبة الخصم المئوية اكبر من 100 % !!!");
                $("#discount_percent").focus();
                $("#discount_percent").val(0);
            }
        }else if (discount_type == 2 ){       // type is money
          if (discount_percent > total_befor_discount) {
            alert("عفوا لايمكن ان يكون مبلغ  الخصم  اكبر من  اجمالي الفاتورة قبل الخصم  !!!");
            $("#discount_percent").focus();
            $("#discount_percent").val(0);
        }

        }
        recalculate();

      })

      $(document).on('change', '#discount_type', function (e) {
        discount_type = $(this).val();
        if (discount_type == "") {
            $("#discount_percent").val(0);
            $("#discount_value").val(0);
            $("#discount_percent").attr("readonly", true);
        } else {
            $("#discount_percent").attr("readonly", false);
        }
        var discount_percent = $("#discount_percent").val();
        if (discount_percent == "") {
            discount_percent = 0;
        }
        if (discount_type == 1) {   // type is percent
            if (discount_percent > 100) {
                alert("عفوا لايمكن ان يكون نسبة الخصم المئوية اكبر من 100 % !!!");
                $("#discount_percent").focus();
                $("#discount_percent").val(0);
            }
        }

        recalculate();

      })
      $(document).on('input', '#what_paid', function(e) {
      total_cost = parseFloat($("#total_cost").val());
      treasuries_balance = parseFloat($("#treasuries_balance").val());
      total_cost = parseFloat(total_cost);
      what_paid = $(this).val();
      if (what_paid == "") {
          what_paid = 0;
      }
      what_paid = parseFloat(what_paid);
      var bill_type = $("#bill_type").val();
      if (bill_type == 1) {
          //cash
          if (what_paid < total_cost) {
              alert("عفوا يجب ان يكون المبلغ كاملا مدفوع في حالة ان الفاتورة كاش");
              $("#what_paid").val(total_cost);
          }
      } else {
          if (what_paid == total_cost) {
              alert("عفوا يجب ان لايكون كل المبلغ  مدفوع في حالة ان الفاتورة اجل");
              $("#what_paid").val(0);
          }
      }
      if (what_paid > total_cost) {
          alert("عفوا لايمكن ان يكون المبلغ المدفوع اكبر من  اجمالي الفاتورة");
          $("#what_paid").val(0);
      }
     
      recalculate();
  });


      $(document).on('change', '#bill_type', function (e) {
        var total_cost = $('#total_cost').val();
        var bill_type = $(this).val();
        // if bill type cash
        if(bill_type == 1){
          $('#what_paid').val(total_cost*1);
          $('#what_paid').attr("readOnly",true);
          $('#what_remain').val(0);
          recalculate();

            // اجل 
        }else if (bill_type == 2){
          $('#what_paid').val(0);
          $('#what_paid').attr("readOnly",false);
          $('#what_remain').val(total_cost*1);
          recalculate();

        }

      })

      function recalculate(){
        var total_cost_items = 0
        $(".itemTotalArray").each(function(){
            total_cost_items+=parseFloat($(this).val());
        })

        $total_cost_items = $("#total_cost_items").val(total_cost_items);
       
        var tax_percent = $("#tax_percent").val();
        if (tax_percent == "") {
            tax_percent = 0
        };
        tax_percent = parseFloat(tax_percent);
        var tax_value = total_cost_items * tax_percent / 100;
        tax_value = parseFloat(tax_value);
        $("#tax_value").val(tax_value * 1);
      
          var total_befor_discount = total_cost_items + tax_value;
          $('#total_befor_discount').val(total_befor_discount);
          var discount_type = $('#discount_type').val();
          if (discount_type!=""){
              // discount type is percent
              if (discount_type == 1){
                  var discount_percent = $('#discount_percent').val();
                  if(discount_percent ==""){discount_percent = 0;}
                  discount_percent = parseFloat(discount_percent);
                  var discount_value = total_befor_discount * discount_percent/100;
                  $('#discount_value').val(discount_value * 1);
                  var total_cost = total_befor_discount - discount_value;
                  $('#total_cost').val(total_cost * 1 );
      
              }else{
                  // discount type is money
                  var discount_percent = $('#discount_percent').val();
                  if(discount_percent ==""){discount_percent = 0;}
                  discount_percent = parseFloat(discount_percent);
                  $('#discount_value').val(discount_percent * 1);
                  var total_cost = total_befor_discount - discount_percent;
                  $('#total_cost').val(total_cost * 1 );
              }
          }else{
            $("#discount_value").val(0);
            var total_cost = total_befor_discount;
            $("#total_cost").val(total_cost); 
             }
      
          what_paid = $("#what_paid").val();
          if (what_paid == ""){what_paid = 0;} 
          what_paid = parseFloat(what_paid);
          total_cost = parseFloat(total_cost);
          $what_remain = total_cost - what_paid;
          $("#what_remain").val($what_remain * 1)  
      }
});