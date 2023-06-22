@extends('layouts.admin')
@section('title')
المشتريات
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('contentheader')
حركات مخزنية
@endsection
@section('contentheaderlink')
<a href="{{ route('Supplier_with_orders.index') }}"> فواتير المشتريات </a>
@endsection
@section('contentheaderactive')
عرض التفاصيل
@endsection



@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">تفاصيل فاتورة مشتريات </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div id="ajax_responce_serarchDiv_parent_details">

                    @if (@isset($data) && !@empty($data))
                    <table id="example2" class="table table-bordered table-hover">

                        <tr>
                            <td class="width30"> الكود الآلي للفاتورة</td>
                            <td> {{ $data['auto_serial'] }}</td>
                        </tr>

                        <tr>
                            <td class="width30"> كود فاتورة المشتريات لدى المورد</td>
                            <td> {{ $data['Doc_no'] }}</td>
                        </tr>

                        <tr>
                            <td class="width30">اسم المورد</td>
                            <td> {{ $data['Supplier_name'] }}</td>
                        </tr>

                        <tr>
                            <td class="width30"> المخزن المستلم للفاتورة</td>
                            <td> {{ $data['store_name'] }}</td>
                        </tr>

                        <tr>
                            <td class="width30">تاريخ فاتورة المشتريات </td>
                            <td> {{ $data['order_date'] }}</td>
                        </tr>

                        <tr>
                            <td class="width30">نوع فاتورة المشتريات </td>
                            <td> @if($data['bill_type']==1) كاش @else آجل @endif</td>
                        </tr>

                        <tr>
                            <td class="width30">حالة فاتورة المشتريات </td>
                            <td> @if($data['is_approved']==1) معتمده @else مفتوحة @endif</td>
                        </tr>

                        <tr>
                            <td class="width30">إجمالي الفاتورة المورد</td>
                            <td> {{ $data['total_before_discount']*(1) }}</td>
                        </tr>

                        @if( $data['discount_type'!=null])
                        <tr>
                            <td class="width30"> الخصم علي الفاتورة </td>
                            <td>
                                @if ($data['discount_type']==1)
                                خصم نسبة ( {{ $data['discount_percent']*1 }} ) % وقيمتها (
                                {{ $data["discount_value"]*1 }} )

                                @else

                                خصم يدوي وقيمته( {{ $data["discount_value"]*1 }} )

                                @endif


                            </td>
                        </tr>
                        @else
                        <tr>
                            <td class="width30"> الخصم على الفاتورة</td>
                            <td> لايوجد</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="width30"> نسبة القيمة المضافة</td>
                            <td>
                                @if($data['tax_percent']>0)
                                لايوجد
                                @else
                                بنسبة ({{ $data["tax_percent"]*1 }}) % وقيمتها ( {{ $data['tax_value']*1 }} )
                                @endif

                            </td>
                        </tr>






                        <td class="width30"> تاريخ الاضافة</td>
                        <td>

                            @php
                            $dt=new DateTime($data['created_at']);
                            $date=$dt->format("Y-m-d");
                            $time=$dt->format("h:i");
                            $newDateTime=date("A",strtotime($time));
                            $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء');
                            @endphp
                            {{ $date }}
                            {{ $time }}
                            {{ $newDateTimeType }}
                            بواسطة
                            {{ $data['added_by_admin'] }}


                        </td>
                        </tr>

                        <tr>
                            <td class="width30"> تاريخ اخر تحديث</td>
                            <td>
                                @if($data['updated_by']>0 and $data['updated_by']!=null )
                                @php
                                $dt=new DateTime($data['updated_at']);
                                $date=$dt->format("Y-m-d");
                                $time=$dt->format("h:i");
                                $newDateTime=date("A",strtotime($time));
                                $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء');
                                @endphp
                                {{ $date }}
                                {{ $time }}
                                {{ $newDateTimeType }}
                                بواسطة
                                {{ $data['updated_by_admin'] }}

                                @else
                                لايوجد تحديث
                                @endif

                                <a href="{{ route('Supplier_with_orders.edit',$data['id']) }}"
                                    class="btn btn-sm btn-success">تعديل</a>

                                @if ($data['is_approved']==0)

                                <button type="button" class="btn btn-sm btn-primary" id="load_invoice_approve">اعتماد
                                    وترحيل
                                    الفاتورة</button>
                                @endif

                            </td>
                        </tr>

                    </table>
                    @else
                    <div class="alert alert-danger">
                        عفوا لاتوجد بيانات لعرضها !!
                    </div>
                    @endif


                </div>
            </div>

            <div class="card-header">
                @if($data['is_approved']==0)
                <h3 class="card-title card_title_center">إضافة اصناف للفاتورة

                    <button type="button" class="btn btn-info" id="load_modal_add_detailsBtn">
                        إضافة صنف للفاتورة
                    </button>

                </h3>
                @endif
                <input type="hidden" id="token_search" value="{{csrf_token() }}">

                <input type="hidden" id="ajax_get_uom_url" value="{{route('Supplier_with_orders.get_uom')}}">

                <input type="hidden" id="ajax_add_new_details"
                    value="{{route('Supplier_with_orders.add_new_details')}}">

                <input type="hidden" id="ajax_reload_items" value="{{route('Supplier_with_orders_reload_items')}}">

                <input type="hidden" id="ajax_reload_parent_bill"
                    value="{{route('Supplier_with_orders_reload_parent_bill')}}">




                <input type="hidden" id="Ajax_load_modal_add_details"
                    value="{{route('Supplier_with_orders_load_modal_add_details')}}">
                <input type="hidden" id="Ajax_add_details" value="{{route('Supplier_with_orders.add_new_details')}}">



                <input type="hidden" id="Ajax_load_modal_editdetails"
                    value="{{route('Supplier_with_orders_load_edit_item_details')}}">
                <input type="hidden" id="ajax_edit_item_details"
                    value="{{ route('admin.suppliers_orders.edit_item_details') }}">

                <input type="hidden" id="parent_auto_serial" value="{{$data['auto_serial']}}">

                <input type="hidden" id="load_modal_approve_invoice"
                    value="{{route('Supplier_with_orders.load_modal_approve_invoice')}}">

                <input type="hidden" id="load_modal_load_userShift"
                    value="{{route('Supplier_with_orders.load_userShift')}}">


            </div>
            <div id="ajax_responce_serarchDivDetails">

                @if (@isset($details) && !@empty($details) && count($details)>0)
                @php
                $i=1;
                @endphp

                <table id="example2" class="table table-bordered table-hover">
                    <thead class="custom_thead">
                        <th>مسلسل</th>
                        <th>الصنف </th>
                        <th> الوحده</th>
                        <th> الكمية</th>
                        <th> السعر</th>
                        <th> الاجمالي</th>
                        @if($data['is_approved']==0)
                        <th>الإجراءات</th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach ($details as $info )
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $info->ItemCard_name }}

                                @if($info->item_card_type==2) <br>
                                تاريخ الإنتاج {{$info->production_date}}<br>
                                تاريخ الانتهاء {{$info->expire_date}}<br>
                                @endif
                            </td>
                            <td>{{ $info->uom_name }}</td>
                            <td>{{ $info->delivered_quantity*(1) }}</td>
                            <td>{{ $info->unit_price*(1) }}</td>
                            <td>{{ $info->total_price*(1) }}</td>

                            <td>
                                @if($data['is_approved']==0)
                                <button data-id="{{ $info->id }}"
                                    class="btn btn-sm editItemDetails btn-primary">تعديل</button>

                                <a href="{{route('Supplier_with_orders_delete_details',['id'=>$info->id,'id_parent'=>$data['id']])}}"
                                    class="btn btn-sm are_you_shue   btn-danger">حذف</a>

                                @endif

                            </td>



                        </tr>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-danger">
                    عفوا لاتوجد بيانات لعرضها !!
                </div>
                @endif





            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Add_item_Modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h4 class="modal-title">إضافة أصناف للفاتورة </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </div>
            <div class="modal-body" id="Add_item_Modal_body" style="background-color: white !important;color:black;">


            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="Modal_Approve_Invoice">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align: center;width: 100%;"> اعتماد فاتورة المشتريات </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </div>
            <div class="modal-body" id="Modal_Approve_Invoice_body"
                style="background-color: white !important;color:black;">


            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- /.modal -->
<div class="modal fade " id="edit_item_Modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h4 class="modal-title text-center">تحديث صنف بالفاتورة</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="edit_item_Modal_body" style="background-color: white !important; color:black;">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@section("script")
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/Supplier_with_orders.js') }}"></script>
<script src="{{ asset('assets/admin/js/invoice_approve.js') }}"></script>

<script>
//Initialize Select2 Elements
$('.select2').select2({
    theme: 'bootstrap4'
});
</script>
@endsection