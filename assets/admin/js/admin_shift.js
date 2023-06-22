$(document).ready(function () {

    $(document).on('click', '.addnewshift', function (e) {
        var treasuries_id = $("#treasuries_id").val();
        if (treasuries_id == "") {
      alert(' من فضلك اختر الخزنة لاستلامها وبدء الشفت')
            $("#treasuries_id").focus();
            return false;
        }
    });

});