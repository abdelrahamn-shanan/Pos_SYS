@extends('layouts.admin')
@section('title')
المبيعات
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endsection
@section('contentheader')
المبيعات@endsection
@section('contentheaderlink')
<a href="{{ route('Sales.index') }}"> فواتير المبيعات </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')


<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> فواتير المبيعات </h3>
        <input type="hidden" id="token_search" value="{{csrf_token() }}">
        <input type="hidden" id="ajax_search_url" value="{{ route('suppliers_orders_Ajax_search') }}">
        <input type="hidden" id="Ajax_Sales_get_uom" value="{{ route('Sales_get_uom') }}">
        <input type="hidden" id="Ajax_Sales_get_qty" value="{{route('get_item_batches')}}">
        <input type="hidden" id="Ajax_Sales_get_price" value="{{route('get_item_unit_price')}}">
        <input type="hidden" id="add_new_item_row" value="{{route('add_new_item_row')}}">





        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#Add_Sales_Invoice_Modal">إصدار فاتورة
            مبيعات جديدة</button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">


            <div class="clearfix"></div>
            <div class="col-md-12">
                <div id="ajax_responce_serarchDiv">

                    @if (@isset($data) && !@empty($data) && count($data) >0)
                    @php
                    $i=1;
                    @endphp
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th>المسلسل</th>
                            <th> العميل</th>
                            <th> المخزن المسلم</th>
                            <th> تاريخ الفاتورة</th>
                            <th> نوع الفاتورة</th>
                            <th> إجمالي الفاتورة</th>
                            <th>حالة الفاتورة</th>

                            <th></th>

                        </thead>
                        <tbody>
                            @foreach ($data as $info )
                            <tr>
                                <td>{{ $info->auto_serial }}</td>
                                <td>{{ $info->Customer_name }}</td>
                                <td>{{ $info->store_name }}</td>
                                <td>{{ $info->sales_invoice_date }}</td>
                                <td>@if($info->bill_type==1) كاش @elseif($info->bill_type==2) اجل @else غير محدد @endif
                                </td>
                                <td>{{ $info->total_cost * 1 }}</td>
                                <td>@if($info->is_approved==1) معتمدة @else مفتوحة @endif</td>

                                <td>

                                    @if($info->is_approved==0)
                                    <a href="{{ route('Supplier_with_orders.edit',$info->id)}}"
                                        class="btn btn-sm btn-success">تعديل</a>

                                    <a href="{{route('Supplier_with_orders_delete_bill',$info->id)}}"
                                        class="btn btn-sm are_you_shue btn-danger">حذف</a>
                                    @endif
                                    <a href="{{ route('Supplier_with_orders.show',$info->id) }}"
                                        class="btn btn-sm   btn-info">التفاصيل</a>
                                    <a style="font-size: .875rem; padding: 0.25rem 0.5rem;color:white" target="_blank"
                                        href="" class="btn btn-primary btn-xs"> WA4</a>
                                    <a style="font-size: .875rem; padding: 0.25rem 0.5rem;color:white" target="_blank"
                                        href="" class="btn btn-warning btn-xs"> WA6</a>

                                </td>


                            </tr>
                            @php
                            $i++;
                            @endphp
                            @endforeach



                        </tbody>
                    </table>
                    <br>
                    {{ $data->links() }}

                    @else
                    <div class="alert alert-danger">
                        عفوا لاتوجد بيانات لعرضها !!
                    </div>
                    @endif

                </div>
            </div>

        </div>

    </div>


</div>
<div class="modal fade" id="Add_Sales_Invoice_Modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h4 class="modal-title">إصدار فاتورة مبيعات جديدة</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </div>
            <div class="modal-body" id="Add_Sales_Invoice_Modal_body"
                style="background-color: white !important;color:black;">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label> تاريخ الفاتورة</label>
                            <input type="date" name="sales_invoice_date" class="form-control"
                                value="@php echo date('Y-m-d'); @endphp">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> هل يوجد عميل ؟</label>
                            <select name="has_customer" id="has_customer" class="form-control">
                                <option value="1" selected> يوجد عميل</option>
                                <option value="0">لايوجد عميل ( طياري )</option>

                            </select>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label> بيانات العملاء
                                (<a title="إضافة عميل جديد" href="#"> جديد <i class="fa fa-plus-circle"></i></a>)
                            </label>
                            <select name="customer_code" id="customer_code" class="form-control select2">
                                <option value=""> لا يوجد عميل</option>
                                @if (@isset($customers) && !@empty($customers))
                                @foreach ($customers as $info )
                                <option value="{{ $info->customer_code }}"> {{ $info->name }} </option>
                                @endforeach
                                @endif
                            </select>
                            @error('customer_code')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label> بيانات المناديب</label>
                            <select name="customer_code" id="customer_code" class="form-control select2">
                                <option value=""> اختر المندوب</option>
                                @if (@isset($customers) && !@empty($customers))
                                @foreach ($customers as $info )
                                <option value="{{ $info->customer_code }}"> {{ $info->name }} </option>
                                @endforeach
                                @endif
                            </select>
                            @error('customer_code')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="clear-fix"></div>
                <hr style="border:1px solid #3c8dbc;">
                <div class="row">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label> المخزن</label>
                            <select name="store_id" id="store_id" class="form-control select2">
                                <option value="">اختر المخزن</option>
                                @if (@isset($stores) && !@empty($stores) && count($stores)>0)
                                @foreach ($stores as $info )
                                <option value="{{ $info->id }}">
                                    {{ $info->name }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label> نوع البيع</label>
                            <select name="Sale_type" id="Sale_type" class="form-control">
                                <option value="1" selected> قطاعي</option>
                                <option value="2"> نص جملة</option>
                                <option value="3"> جملة</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label> الأصناف</label>
                            <select name="item_code" id="item_code" class="form-control select2">
                                <option value="">اختر الصنف</option>
                                @if (@isset($items) && !@empty($items))
                                @foreach ($items as $info )
                                <option data-type="{{$info->item_type}}" value="{{ $info->item_code }}">
                                    {{ $info->name }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- item uoms-->
                    <div class="col-md-3" style="display: none;" id="UomDiv">


                    </div>


                    <!-- item qty in batches-->
                    <div class="col-md-3" style="display: none;" id="ItemsQtyDiv">

                    </div>


                    <div class="col-md-2">
                        <div class="form-group">
                            <label> الكمية</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="item_qty" id="item_qty"
                                class="form-control" value="1">
                            @error('item_qty')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-2">
                        <div class="form-group">
                            <label> السعر</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="item_price"
                                id="item_price" class="form-control" value="">
                            @error('item_price')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بونص أو دعايه</label>
                            <select name="is_bonus or normal" id="is_bonus or normal" class="form-control">
                                <option value="0" selected> بيع عادي</option>
                                <option value="1"> بونص </option>
                                <option value="2"> دعايا</option>
                                <option value="3"> هالك</option>

                            </select>
                        </div>
                    </div>


                    <div class="col-md-2">
                        <div class="form-group">
                            <label> الإجمالي</label>
                            <input readOnly oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                name="item_total_price" id="item_total_price" class="form-control" value="">
                            @error('item_total_price')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <button style="margin-top: 38px;" class="btn btn-sm btn-danger"
                                id="add_items_to_invoice_Details">اضف
                                للفاتورة</button>
                        </div>
                    </div>
                </div>

                <div class="clear-fix"></div>
                <hr style="border:1px solid #3c8dbc;">
                <div class="row">
                    <h2 class="card-title card_title_center"> الاصناف المضافة علي الفاتورة </h2>
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th>المخزن</th>
                            <th>نوع البيع</th>
                            <th>الصنف</th>
                            <th>وحدة البيع</th>
                            <th>سعر الوحدة</th>
                            <th>الكمية</th>
                            <th>الاجمالي</th>
                            <th>الإجراءات</th>

                        </thead>
                        <tbody id="itemstableContainterBody">
                        </tbody>
                    </table>
                </div>
                <div class="clear-fix"></div>
                <hr style="border:1px solid #3c8dbc;">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>اجمالي الاصناف بالفاتورة </label>
                            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                name="total_cost_items" id="total_cost_items" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> نسبة الضريبة </label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="tax_percent"
                                id="tax_percent" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> قيمة الضريبة </label>
                            <input readonly id="tax_value" class="form-control" name="tax_value" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> الاجمالي قبل الخصم </label>
                            <input readonly id="total_befor_discount" name="total_befor_discount" class="form-control"
                                value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> نوع الخصم </label>
                            <select class="form-control" name="discount_type" id="discount_type">
                                <option value="">لايوجد خصم</option>
                                <option value="1" @if($data['discount_type']==1) selected @endif> نسبة مئوية</option>
                                <option value="2" @if($data['discount_type']==2) selected @endif> قيمة يدوي</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> نسبة الخصم </label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="discount_percent"
                                id="discount_percent" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> قيمة الخصم </label>
                            <input readonly name="discount_value" id="discount_value" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> الاجمالي النهائي بعد الخصم </label>
                            <input readonly name="total_cost" id="total_cost" class="form-control" value="0">
                        </div>
                    </div>

                </div>


                <div class="row" id="ShiftDiv">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label> خزنة التحصيل </label>
                            <select id="treasuries_id" name="treasuries_id" class="form-control">
                                @if(!@empty($current_user_shift))
                                <option selected value="{{ $current_user_shift['treasury_id']  }}">
                                    {{ $current_user_shift['tresuries_name'] }}
                                </option>
                                @else
                                <option value=""> عفوا لاتوجد خزنة لديك الان</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class=" form-group">
                            <label> الرصيد المتاح بالخزنة </label>
                            <input readonly name="treasuries_balance" id="treasuries_balance" class="form-control"
                                @if(!@empty($current_user_shift))
                                value="{{$current_user_shift['treasuries_balance']*1 }}" @else value="0" @endif>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label> نوع الفاتورة </label>
                            <select class="form-control" name="bill_type" id="bill_type">
                                <option value="1"> كاش</option>
                                <option value="2"> اجل</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class=" form-group">
                            <label> المحصل الان </label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="what_paid"
                                id="what_paid" class="form-control" value="0">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class=" form-group">
                            <label> المتبقي تحصيله </label>
                            <input readonly name="what_remain" id="what_remain" class="form-control" value="0">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class=" form-group">
                            <label> ملاحظات </label>
                            <input style="background-color :lightgoldenrodyellow" name="notes" id="notes"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 text-center ">
                        <button type="submit" class="btn btn-sm btn-danger" id="do_save_invoice">
                            حفظ الان</button>
                    </div>

                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>




@endsection

@section('script')
<script src="{{ asset('assets/admin/js/Sales.js') }}"></script>


<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
//Initialize Select2 Elements
$('.select2').select2({
    theme: 'bootstrap4'
});
</script>
@endsection