$(document).ready(function () {

    const account_typ = Object.freeze({
        Supplier: 2,
        Client: 3,
        Bank : 6,
      });

      const mov_type = Object.freeze({
        Supplier_collect: 10,
        Client_collect: 5,
        Bank_collect: 25,
        general_collect: 4,
      });
      
    $(document).on('change', '#account_number', function (e) {
        var account_number = $(this).val();
        if(account_number==""){
         $("#mov_type").val("");

        }else{
            var account_type = $("#account_number option:selected").data('type');
            // if account_type is supllier مورد
            if (account_type == account_typ.Supplier){
                $("#mov_type").val(mov_type.Supplier_collect);
            // if account_type is client عميل
            }else if(account_type == account_typ.Client){
                $("#mov_type").val(mov_type.Client_collect);
            // if account_type is Bank بنكي
            } else if(account_type == account_typ.Bank){
            $("#mov_type").val(mov_type.Bank_collect);
            }

            else{
                $("#mov_type").val(mov_type.general_collect);

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
                $("#mov_type").val(mov_type.Supplier_collect);
            // if account_type is client عميل
            }else if(account_type == account_typ.Client){
                $("#mov_type").val(mov_type.Client_collect);
            // if account_type is Bank بنكي
            } else if(account_type == account_typ.Bank){
            $("#mov_type").val(mov_type.Bank_collect);
            }

            else{
                $("#mov_type").val(mov_type.general_collect);

        }

        }
    });

        

    $(document).on('click', '#do_collect_now_btn', function (e) {
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

        var account_number = $("#account_number").val();
        if (account_number == "") {
            alert("من فضلك ادخل الحساب المالي المحصل منه  ");
            $("#account_number").focus();
            return false;
        }

        
        var treasuries_id = $("#treasuries_id").val();
        if (treasuries_id == "") {
            alert(" من فضلك اختر خزنة التحصيل  ");
            $("#treasuries_id").focus();
            return false;
        }

        var money = $("#money").val();
        if (money == "") {
            alert(" من فضلك اختر مبلغ التحصيل  ");
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