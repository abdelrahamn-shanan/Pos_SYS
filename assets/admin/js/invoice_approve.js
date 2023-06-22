$(document).ready(function () {
  
    $(document).on('click', '#load_invoice_approve', function (e) {

        var autoserailparent = $("#parent_auto_serial").val();
        var token_search = $("#token_search").val();
        var ajax_search_url = $("#load_modal_approve_invoice").val();
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
                $("#Modal_Approve_Invoice_body").html(data);
                $("#Modal_Approve_Invoice").modal("show");
            },
            error: function() {
              alert('حدث خطأ')
            }
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
      if (what_paid > treasuries_balance) {
          alert("عفوا لايوجد رصيد كافي بالخزنة");
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
  var total_cost_items = $("#total_cost_items").val();
  if (total_cost_items == "") {
      total_cost_items = 0;
  }
  total_cost_items = parseFloat(total_cost_items);
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

$(document).on('mouseenter', '#do_invoice_approve', function (e) {
    var token_search = $("#token_search").val();
    var ajax_search_url = $("#load_modal_load_userShift").val();
    jQuery.ajax({
        url: ajax_search_url,
        type: 'post',
        dataType: 'html',
        cache: false,
        data: {
            "_token": token_search,
        },
        success: function(data) {
            $("#ShiftDiv").html(data);
        },
        error: function() {
          alert('حدث خطأ')
        }
    });
})
$(document).on('click', '#do_invoice_approve', function(e) {
    var total_cost_items = $("#total_cost_items").val();
    if (total_cost_items == "") {
        alert("من فضلك ادخل اجمالي الاصناف");
        $('#total_cost_items').focus();
        return false;
    }
    var tax_percent = $("#tax_percent").val();
    if (tax_percent == "") {
        alert("من فضلك ادخل نسبة ضريبة القيمة المضافة ");
        $("#tax_percent").val();
        $('#tax_percent').focus();
        return false;
    }
    var tax_value = $("#tax_value").val();
    if (tax_value == "") {
        alert("من فضلك ادخل قيمة ضريبة القيمة المضافة ");
        $('#tax_value').focus();
        return false;
    }
    var total_befor_discount = $("#total_befor_discount").val();
    if (total_befor_discount == "") {
        alert("من فضلك ادخل قيمة الاجمالي قبل الخصم   ");
        return false;
    }
    var discount_type = $("#discount_type").val();
    if (discount_type == 1) {
        var discount_percent = $("#discount_percent").val();
        if (discount_percent > 100) {
            alert("عفوا لايمكن ان يكون نسبة الخصم المئوية اكبر من 100 % !!!");
            $("#discount_percent").focus();
            return false;
        }
    } else if (discount_type == 2) {
        var discount_value = $("#discount_value").val();
        if (parseFloat(discount_value) > parseFloat(total_befor_discount)) {
            alert("عفوا لايمكن ان يكون قيمة الخصم اكبر من اجمالي الفاتورة قبل الخصم   !!!");
            $("#discount_percent").focus();
            return false;
        }
    } else {
        var discount_value = $("#discount_value").val();
        if (discount_value > 0) {
            alert(" عفوا لايمكن ان يوجد خصم مع اختيارك لنوع الخصم لايوجد  !!!");
            return false;
        }
    }
    var discount_value = $("#discount_value").val();
    if (discount_value == "") {
        alert("من فضلك ادخل قيمة الخصم ");
        $('#discount_value').focus();
        return false;
    }
    var total_cost = $("#total_cost").val();
    if (total_cost == "") {
        alert("من فضلك ادخل قيمة اجمالي الفاتورة النهائي ");
        $('#total_cost').focus();
        return false;
    }
    var bill_type = $("#bill_type").val();
    if (bill_type == "") {
        alert("من فضلك اختر نوع الفاتورة ");
        $('#bill_type').focus();
        return false;
    }
    var what_remain = $("#what_remain").val();
    var what_paid = $("#what_paid").val();
    if (what_paid == "") {
        alert("من فضلك ادخل المبلغ المدفوع ");
        $('#what_paid').focus();
        return false;
    }
    if (parseFloat(what_paid) > parseFloat(total_cost)) {
        alert("عفوا لايمكن ان يكون المبلغ المصروف اكبر من اجمالي الفاتورة   ");
        $('#what_paid').focus();
        return false;
    }
    if (bill_type == 1) {
        if (parseFloat(what_paid) <  parseFloat(total_cost)) {
            alert("عفوا يجب ان يكون كل المبلغ مدفوع في حالة ان الفاتورة كاش       ");
            return false;
        }
    } else {
        if (parseFloat(what_paid) == parseFloat(total_cost)) {
            alert("عفوا لايمكن ان يكون المبلغ المدفوع يساوي اجمالي الفاتورة في حالة ان الفاتورة اجل      ");
            $('#what_paid').focus();
            return false;
        }
    }
    var what_remain = $("#what_remain").val();
    if (what_remain == "") {
        alert("من فضلك ادخل المبلغ المتبقي   ");

        return false;
    }
    if (bill_type == 1) {
        if (what_remain > 0) {
            alert("عفوا لايمكن ان يكون المبلغ المتبقي اكبر من الصفر في حالة ان الفاتورة كاش ");
            return false;
        }
    }
    if (what_paid > 0) {
        var treasuries_id = $("#treasuries_id").val();
        if (treasuries_id == "") {
            alert("من فضلك اختر خزنة الصرف ");
            $('#treasuries_id').focus();
            return false;
        }
        var treasuries_balance = $("#treasuries_balance").val();
        if (treasuries_balance == "") {
            alert("من فضلك  ادخل رصيد الخزنة ");
            $('#treasuries_balance').focus();
            return false;
        }
        if (parseFloat(what_paid) > parseFloat(treasuries_balance)) {
            alert("عفوا لايوجد لديك رصيد كافي في خزنة الصرف !!!");
            $('#what_paid').focus();
            return false;

        }
    }
});

})