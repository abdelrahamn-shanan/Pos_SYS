@extends('layouts.admin')
@section('title')
الضبط العام
@endsection

@section('contentheader')
الضبط
@endsection

@section('contentheaderlink')
<a href="{{ route('settings') }}"> الضبط </a>
@endsection

@section('contentheaderactive')
عرض
@endsection



@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات الضبط العام</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (@isset($data) && !@empty($data))
                <table id="example2" class="table table-bordered table-hover">

                    <tr>
                        <td class="width30">اسم الشركة</td>
                        <td> {{ $data['sys_name'] }}</td>
                    </tr>

                    <tr>
                        <td class="width30">كود الشركة</td>
                        <td> {{ $data['com_code'] }}</td>

                    </tr>

                    <tr>
                        <td class="width30"> اسم الحساب المالي للعملاء الاب</td>
                        <td>{{ ($data['customer_parent_account_name'])}} رقم حساب مالي
                            {{ ($data['customer_parent_account_number'])}}</td>

                    </tr>

                    
                    <tr>
                        <td class="width30"> اسم الحساب المالي للموردين الاب</td>
                        <td>{{ ($data['suppliers_parent_account_name'])}} رقم حساب مالي
                            {{ ($data['suppliers_parent_account_number'])}}</td>

                    </tr>

                    <tr>
                        <td class="width30">حالة الشركة</td>
                        <td>{{ ($data['active'] == 1) ? 'مفعل' : 'معطل'}}</td>

                    </tr>

                    <tr>
                        <td class="width30">عنوان الشركة</td>
                        <td> {{ $data['address'] }}</td>
                    </tr>

                    <tr>
                        <td class="width30">هاتف الشركة</td>
                        <td> {{ $data['phone'] }}</td>
                    </tr>

                    <tr>
                        <td class="width30"> رسالة التنبية اعلي الشاشة للشركة</td>
                        <td> {{ $data['general_alert'] }}</td>
                    </tr>
                    <tr>
                        <td class="width30">لوجو الشركة</td>
                        <td>
                            <div class="image">
                                <img class="custom_img" src="{{$data->photo}}" alt="لوجو الشركة">

                            </div>

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
                            $newDateTimeType= (($newDateTime=='AM')?'صباحاً ':'مساءاً');
                            @endphp
                            {{ $date }}
                            {{ $time }}
                            {{ $newDateTimeType }}
                            بواسطة
                            {{ $data['updated_by_admin'] }}
                            @else
                            لا يوجد تحديث
                            @endif

                            <a href="{{route('AdminPanelEdit',$data['id'])}}" class="btn btn-sm btn-success">تعديل</a>


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
    </div>
</div>





@endsection