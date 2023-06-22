@extends('layouts.admin')
@section('title')
شاشة تحصيل النقدية
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('contentheader')
الحسابات
@endsection
@section('contentheaderlink')
<a href="{{ route('collect_transaction.index') }}"> شاشه تحصيل النقدية </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> شاشة تحصيل النقدية </h3>


            </div>

            <!-- /.card-header -->
            <div class="card-body">
                @if(!@empty($checkExistsOpenShift))
                <form action="{{ route('collect_transaction.store') }}" method="post" style=" border: 1px solid lightgray;
                            border-radius: 7px;
                            padding: 10px;
                            background-color:beige;
                            margin-bottom: 7px;">
                    <div class="row">
                        @csrf
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>تاريخ الحركة </label>
                                <input type="date" name="move_date" id="move_date" class="form-control"
                                    value="{{ old('date',date("Y-m-d")) }}">
                                @error('move_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-4">
                            <label> الحسابات المالية</label>
                            <div class="form-group" style="background-color:white">
                                <select name="account_number" id="account_number" class="form-control select2 ">
                                    <option value="">اختر الحساب المالي المحصل منه</option>

                                    @if (@isset($accounts) && !@empty($accounts))
                                    @foreach ($accounts as $info )
                                    <option data-type="{{ $info->account_type }}" @if(old('account_number')==$info->
                                        account_number) selected="selected" @endif value="{{ $info->account_number }}">
                                        {{ $info->name }} ({{$info->Account_type_name}}) </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('account_number')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-4">
                            <label> نوع الحركة</label>
                            <div class="form-group" style="background-color:white">
                                <select name="mov_type" id="mov_type" class="form-control ">
                                    <option value="">اختر نوع الحركة</option>
                                    @if (@isset($mov_types) && !@empty($mov_types))
                                    @foreach ($mov_types as $info )
                                    <option @if(old('mov_type')==$info->id) selected="selected" @endif
                                        value="{{ $info->id }}"> {{ $info->name }} </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('mov_type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>




                        <div class="col-md-4" id="account_numberStatusDiv" style="display: none;">
                        </div>
                        <div class="col-md-4" id="get_account_blancesDiv" style="display: none;">
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label> بيانات الخزن</label>
                                <select name="treasuries_id" id="treasuries_id" class="form-control ">
                                    <option value="{{ $checkExistsOpenShift['treasury_id'] }}">
                                        {{ $checkExistsOpenShift['treasury_name'] }} </option>
                                </select>
                                @error('treasuries_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class=" form-group">
                                <label> الرصيد المتاح بالخزنة </label>
                                <input readonly name="treasuries_balance" id="treasuries_balance" class="form-control"
                                    value="{{ $checkExistsOpenShift['shift_balance_now']*1 }}">
                                @error('treasuries_balance')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class=" form-group">
                                <label>قيمة المبلغ المحصل </label>
                                <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="money" id="money"
                                    class="form-control" value="{{ old('money') }}">
                                @error('money')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class=" form-group">
                                <label>البيان</label>
                                <textarea name="byan" id="byan" class="form-control" rows="4"
                                    cols="6"> {{ old("byan","تحصيل نظير ") }} </textarea>
                                @error('byan')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button id="do_collect_now_btn" type="submit" class="btn btn-success btn-sm"> تحصيل
                                    الان</button>
                            </div>
                        </div>

                    </div>
                </form>
                @else
                <div class="alert alert-warning">
                    !!تنبيه لا يوجد شفت حالي
                </div>

                @endif

                <div id="ajax_responce_serarchDiv">
                    @if (@isset($data) && !@empty($data) && count($data)>0)
                    @php
                    $i=1;
                    @endphp
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th>كود</th>
                            <th>رقم إيصال التحصيل</th>
                            <th> نوع الحركة</th>
                            <th>اسم الخزنة</th>
                            <th> المبلغ</th>
                            <th> البيان</th>
                            <th>المستخدم</th>
                            <th>الاجراءات</th>

                        </thead>
                        <tbody>
                            @foreach ($data as $infoo )
                            <tr>
                                <td>{{ $infoo->auto_serial }}</td>
                                <td>{{ $infoo->Isal_number }}</td>
                                <td>{{ $infoo->mov_type_name }}</td>
                                <td>{{ $infoo->treasuries_name }}</td>
                                <td>{{ $infoo->money *(1) }}</td>
                                <td>{{ $infoo->byan }}</td>
                                <td>

                                    @php
                                    $dt=new DateTime($infoo->created_at);
                                    $date=$dt->format("Y-m-d");
                                    $time=$dt->format("h:i");
                                    $newDateTime=date("A",strtotime($time));
                                    $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء');
                                    @endphp
                                    {{ $date }} <br>
                                    {{ $time }}
                                    {{ $newDateTimeType }} <br>
                                    بواسطة
                                    {{ $infoo->admin_name}}
                                </td>
                                <td>
                                    <a href="{{route('collect_transaction.edit',$infoo->id)}}"
                                        class="btn btn-sm  btn-primary">طباعة</a>
                                    <a href="{{route('Treasureies_Details',$infoo->id)}}"
                                        class="btn btn-sm  btn-info">المزيد</a>
                                </td>

                            </tr>
                            @php
                            $i++;
                            @endphp
                            @endforeach



                        </tbody>
                    </table>

                    <br></br>
                    {{$data->links()}}
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
@endsection
@section("script")

<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script src="{{ asset('assets/admin/js/collect_transactions.js') }}"> </script>

<script>
//Initialize Select2 Elements
$('.select2').select2({
    theme: 'bootstrap4'
});
</script>
@endsection