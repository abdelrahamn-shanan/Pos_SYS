$(document).ready(function () {
      
    $(document).on('change', '#account_number', function (e) {
        const account_typ = Object.freeze({
            Supplier: 2,
            Client: 3,
            Bank : 6,
          });
    
          const mov_type = Object.freeze({
            Supplier_exchange: 9,
            Client_exchange: 6,
            Bank_exchange: 18,
            general_exchange: 3,
          });
        var account_number = $(this).val();
        if(account_number==""){
         $("#mov_type").val("");

        }else{
            var account_type = $("#account_number option:selected").data('type');
            // if account_type is supllier مورد
            if (account_type == account_typ.Supplier){
                $("#mov_type").val(mov_type.Supplier_exchange);
            // if account_type is client عميل
            }else if(account_type == account_typ.Client){
                $("#mov_type").val(mov_type.Client_exchange);
            // if account_type is Bank بنكي
            } else if(account_type == account_typ.Bank){
            $("#mov_type").val(mov_type.Bank_exchange);
            }

            else{
                $("#mov_type").val(mov_type.general_exchange);

           }

        }
    });

    $(document).on('change', '#mov_type', function (e) {
        var account_number = $('#account_number').val();
        if(account_number==""){
            alert('من فضلك اختر الحساب المالي اولاً')
            $("#mov_type").val("");
            $("#account_number").focus();
            return false;

        }else{
            var account_type = $("#account_number option:selected").data('type');
            // if account_type is supllier مورد
            if (account_type == account_typ.Supplier){
                $("#mov_type").val(mov_type.Supplier_exchange);
            // if account_type is client عميل
            }else if(account_type == account_typ.Client){
                $("#mov_type").val(mov_type.Client_exchange);
            // if account_type is Bank بنكي
            } else if(account_type == account_typ.Bank){
            $("#mov_type").val(mov_type.Bank_exchange);
            } else{
                $("#mov_type").val(mov_type.general_exchange);

        }

        }
    });

  
    $(document).on('click', '#do_exchange_now_btn', function (e) {

        var account_number = $("#account_number").val();
        if (account_number == "") {
            alert("من فضلك ادخل الحساب المالي المحصل منه  ");
            $("#account_number").focus();
            return false;
        }

        var move_date = $("#move_date").val();
        if (move_date == "") {
            alert("من فضلك ادخل تاريخ الحركة");
            $("#move_date").focus();
            return false;
            
        }
        var mov_type = $("#mov_type").val();
        if (mov_type == "") {
            alert("من فضلك ادخل نوع الحركة ");
            $("#mov_type").focus();
            return false;
        }

       

        
        var treasuries_id = $("#treasuries_id").val();
        if (treasuries_id == "") {
            alert(" من فضلك اختر خزنة التحصيل  ");
            $("#treasuries_id").focus();
            return false;
        }

        var money = $("#money").val();
        if (money == "" || money <=0) {
            alert(" من فضلك اختر مبلغ الصرف  ");
            $("#money").focus();
            return false;
        }

        var treasuries_balance = $('#treasuries_balance').val();
        if (treasuries_balance == "" || treasuries_balance == 0) {
            alert("عفوا لايوجد رصيد كافي لديك بالخزنة !!! ");
            $("#money").focus();
            return false;
        }
        if (parseFloat(money) > parseFloat(treasuries_balance)) {
            alert("عفوا لايوجد رصيد كافي لديك بالخزنة !!! ");
            $("#money").focus();
            return false;
        }

        var byan = $("#byan").val();
        if (byan == "") {
            alert("  من فضلك اختر بيان التحصيل بشكل واضح ");
            $("#byan").focus();
            return false;
        }
    
    });


        
});