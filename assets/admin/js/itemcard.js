$(document).ready(function () {

    $(document).on('change', '#does_has_retailunit', function (e) {
        var uom_id = $("#uom_id").val();
        if (uom_id == '') {
            alert("اختر الوحده الاب اولا");
            $("#does_has_retailunit").val("");
            return false;
        }

        if ($(this).val() == 1) {
            $("#retail_uom_idDiv").show();
            var retail_uom_id = $("#retail_uom_id").val();
            if (retail_uom_id != '') {
                $(".relatied_retial_counter").show();
            } else {
                $(".relatied_retial_counter").hide();
            }
        } else {
            $(".relatied_retial_counter").hide();
            $("#retail_uom_idDiv").hide();
        }

        $("#retail_uom_id").val("");

    });

    $(document).on('change', '#uom_id', function (e) {
        if ($(this).val() != '') {
            var name = $("#uom_id option:selected").text();
            $(".parentuomname").text(name);
            var does_has_retailunit = $("#does_has_retailunit").val();
            if (does_has_retailunit == 1) {
                var retail_uom_id = $("#retail_uom_id").val();
                if (retail_uom_id != '') {
                    $(".relatied_retial_counter").show();
                } else {
                    $(".relatied_retial_counter").hide();
                }


            } else {
                $(".relatied_retial_counter").hide();
                $("#retail_uom_idDiv").hide();
            }
            $(".relatied_parent_counter").show();



        } else {
            $(".parentuomname").text('');
            $(".relatied_retial_counter").hide();
            $(".relatied_parent_counter").hide();
            $("#retail_uom_idDiv").hide();

        }

    });


    $(document).on('change', '#retail_uom_id', function (e) {
        if ($(this).val() != '') {
            var name = $("#retail_uom_id option:selected").text();
            $(".childuomname").text(name);
            $(".relatied_retial_counter").show();

        } else {
            $(".childuomname").text('');
            $(".relatied_retial_counter").hide();

        }

    });

    $(document).on('click', '#do_add_item_card', function (e) {
        var name = $("#name").val();
        if (name == "") {
            alert("من فضلك ادخل اسم الصنف");
            $("#name").focus();
            return false;
        }

        var item_type = $("#item_type").val();
        if (item_type == "") {
            alert("من فضلك اختر نوع الصنف  ");
            $("#item_type").focus();

            return false;
        }

        var inv_itemcard_categories_id = $("#inv_itemcard_categories_id").val();
        if (inv_itemcard_categories_id == "") {
            alert("من فضلك اختر فئة الصنف  ");
            $("#inv_itemcard_categories_id").focus();

            return false;
        }

        var uom_id = $("#uom_id").val();
        if (uom_id == "") {
            alert(" من فضلك اختر وحدةالاب للصنف");
            $("#uom_id").focus();

            return false;
        }

        var does_has_retailunit = $("#does_has_retailunit").val();
        if (does_has_retailunit == "") {
            alert(" من فضلك اختر هلل للصنف وحدة تجزئة ام لا !");
            $("#does_has_retailunit").focus();

            return false;
        }
        if (does_has_retailunit == 1) {
            var retail_uom_id = $("#retail_uom_id").val();
            if (retail_uom_id == "") {
                alert(" من فضلك اختر وحدة قياس التجزئة بالنسبة للاب ");
                $("#retail_uom_id").focus();

                return false;
            }

            var retail_uom_quntToParent = $("#retail_uom_quntToParent").val();
            if (retail_uom_quntToParent == "" || retail_uom_quntToParent == 0) {
                alert(" من فضلك ادخل عدد وحدات وحدة التجزئة بالنسبة للأب  ");
                $("#retail_uom_quntToParent").focus();

                return false;
            }
        }

        var price = $("#price").val();
        if (price == "") {
            alert(" من فضلك ادخل السعر القطاعي للصنف");
            $("#price").focus();

            return false;
        }


        var nos_gomla_price = $("#nos_gomla_price").val();
        if (nos_gomla_price == "") {
            alert(" من فضلك ادخل السعر النص جمله للصنف");
            $("#nos_gomla_price").focus();

            return false;
        }

        var gomla_price = $("#gomla_price").val();
        if (gomla_price == "") {
            alert(" من فضلك ادخل السعر الجمله للصنف");
            $("#gomla_price").focus();

            return false;
        }


        var cost_price = $("#cost_price").val();
        if (cost_price == "") {
            alert(" من فضلك ادخل سعر تكلفة الشراء للوحدة الاب ");
            $("#cost_price").focus();

            return false;
        }


        if (does_has_retailunit == 1) {

            var price_retail = $("#price_retail").val();
            if (price_retail == "") {
                alert(" من فضلك ادخل السعر القطاعي لوحدة التجزئة ");
                $("#price_retail").focus();

                return false;
            }

            var nos_gomla_price_retail = $("#nos_gomla_price_retail").val();
            if (nos_gomla_price_retail == "") {
                alert(" من فضلك ادخل السعر النص جملة لوحدة التجزئة ");
                $("#nos_gomla_price_retail").focus();

                return false;
            }

            var gomla_price_retail = $("#gomla_price_retail").val();
            if (gomla_price_retail == "") {
                alert(" من فضلك ادخل سعر  الجملة لوحدة التجزئة ");
                $("#gomla_price_retail").focus();

                return false;
            }

            var cost_price_retail = $("#cost_price_retail").val();
            if (cost_price_retail == "") {
                alert(" من فضلك ادخل سعر تكلفة الشراء بالنسبة لوحدة التجزئة ");
                $("#cost_price_retail").focus();

                return false;
            }

        }

        var has_fixed_price = $("#has_fixed_price").val();
        if (has_fixed_price == "") {
            alert(" من فضلك اختر هل للصنف سعر ثابت او لا ");
            $("#has_fixed_price").focus();

            return false;
        }


        var active = $("#active").val();
        if (active == "") {
            alert(" من فضلك اختر حالة الصنف  ");
            $("#active").focus();

            return false;
        }

    });

    $(document).on('click', '#do_edit_item_cardd', function (e) {

        var barcode = $("#barcode").val();
        if (barcode == "") {
            alert("من فضلك ادخل باركود الصنف");
            $("#barcode").focus();
            return false;
        }

        var name = $("#name").val();
        if (name == "") {
            alert("من فضلك ادخل اسم الصنف");
            $("#name").focus();
            return false;
        }

        var item_type = $("#item_type").val();
        if (item_type == "") {
            alert("من فضلك اختر نوع الصنف  ");
            $("#item_type").focus();

            return false;
        }

        var inv_itemcard_categories_id = $("#inv_itemcard_categories_id").val();
        if (inv_itemcard_categories_id == "") {
            alert("من فضلك اختر فئة الصنف  ");
            $("#inv_itemcard_categories_id").focus();

            return false;
        }

        var uom_id = $("#uom_id").val();
        if (uom_id == "") {
            alert(" من فضلك اختر وحدةالاب للصنف");
            $("#uom_id").focus();

            return false;
        }

        var does_has_retailunit = $("#does_has_retailunit").val();
        if (does_has_retailunit == "") {
            alert(" من فضلك اختر هلل للصنف وحدة تجزئة ام لا !");
            $("#does_has_retailunit").focus();

            return false;
        }
        if (does_has_retailunit == 1) {
            var retail_uom_id = $("#retail_uom_id").val();
            if (retail_uom_id == "") {
                alert(" من فضلك اختر وحدة قياس التجزئة بالنسبة للاب ");
                $("#retail_uom_id").focus();

                return false;
            }

            var retail_uom_quntToParent = $("#retail_uom_quntToParent").val();
            if (retail_uom_quntToParent == "" || retail_uom_quntToParent == 0) {
                alert(" من فضلك ادخل عدد وحدات وحدة التجزئة بالنسبة للأب  ");
                $("#retail_uom_quntToParent").focus();

                return false;
            }
        }

        var price = $("#price").val();
        if (price == "") {
            alert(" من فضلك ادخل السعر القطاعي للصنف");
            $("#price").focus();

            return false;
        }


        var nos_gomla_price = $("#nos_gomla_price").val();
        if (nos_gomla_price == "") {
            alert(" من فضلك ادخل السعر النص جمله للصنف");
            $("#nos_gomla_price").focus();

            return false;
        }

        var gomla_price = $("#gomla_price").val();
        if (gomla_price == "") {
            alert(" من فضلك ادخل السعر الجمله للصنف");
            $("#gomla_price").focus();

            return false;
        }


        var cost_price = $("#cost_price").val();
        if (cost_price == "") {
            alert(" من فضلك ادخل سعر تكلفة الشراء للوحدة الاب ");
            $("#cost_price").focus();

            return false;
        }


        if (does_has_retailunit == 1) {

            var price_retail = $("#price_retail").val();
            if (price_retail == "") {
                alert(" من فضلك ادخل السعر القطاعي لوحدة التجزئة ");
                $("#price_retail").focus();

                return false;
            }

            var nos_gomla_price_retail = $("#nos_gomla_price_retail").val();
            if (nos_gomla_price_retail == "") {
                alert(" من فضلك ادخل السعر النص جملة لوحدة التجزئة ");
                $("#nos_gomla_price_retail").focus();

                return false;
            }

            var gomla_price_retail = $("#gomla_price_retail").val();
            if (gomla_price_retail == "") {
                alert(" من فضلك ادخل سعر  الجملة لوحدة التجزئة ");
                $("#gomla_price_retail").focus();

                return false;
            }

            var cost_price_retail = $("#cost_price_retail").val();
            if (cost_price_retail == "") {
                alert(" من فضلك ادخل سعر تكلفة الشراء بالنسبة لوحدة التجزئة ");
                $("#cost_price_retail").focus();

                return false;
            }

        }

        var has_fixed_price = $("#has_fixed_price").val();
        if (has_fixed_price == "") {
            alert(" من فضلك اختر هل للصنف سعر ثابت او لا ");
            $("#has_fixed_price").focus();

            return false;
        }


        var active = $("#active").val();
        if (active == "") {
            alert(" من فضلك اختر حالة الصنف  ");
            $("#active").focus();

            return false;
        }

    });



});
