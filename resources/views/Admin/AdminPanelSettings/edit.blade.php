@extends('layouts.admin')
@section('title')
تعديل الضبط العام
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('contentheader')
الضبط
@endsection

@section('contentheaderlink')
<a href="{{ route('settings') }}"> الضبط </a>
@endsection

@section('contentheaderactive')
تعديل
@endsection



@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center">تعديل بيانات الضبط العام</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        @if (@isset($data) && !@empty($data))
        <form action="{{route('AdminPanelupdate')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$data -> id }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>اسم الشركة</label>
                        <input name="system_name" id="system_name" class="form-control" value="{{ $data['sys_name'] }}"
                            placeholder="ادخل اسم الشركة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}">
                        @error('system_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>عنوان الشركة</label>
                        <input name="address" id="address" class="form-control" value="{{ $data['address'] }}"
                            placeholder="ادخل عنوان الشركة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}">
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>هاتف الشركة</label>
                        <input name="phone" id="phone" class="form-control" value="{{ $data['phone'] }}"
                            placeholder="ادخل رقم هاتف الشركة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}">
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <label> الحساب الاب للعملاء بالشجره المحاسبية </label>
                    <select name="customer_parent_account_number" id="customer_parent_account_number"
                        class="form-control ">
                        <option value="">اختر الحساب الاب</option>
                        @if (@isset($parent_accounts) && !@empty($parent_accounts))
                        @foreach ($parent_accounts as $info )
                        <option @if(old('customer_parent_account_number',$data['customer_parent_account_number'])==$info->account_number) selected="selected"
                            @endif
                            value="{{ $info->account_number }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>
                    @error('customer_parent_account_number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label> الحساب الاب للموردين بالشجره المحاسبية </label>
                    <select name="suppliers_parent_account_number" id="suppliers_parent_account_number"
                        class="form-control ">
                        <option value="">اختر الحساب الاب</option>
                        @if (@isset($parent_accounts) && !@empty($parent_accounts))
                        @foreach ($parent_accounts as $info )
                        <option @if(old('suppliers_parent_account_number',$data['suppliers_parent_account_number'])==$info->account_number) selected="selected"
                            @endif
                            value="{{ $info->account_number }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>
                    @error('suppliers_parent_account_number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>رسالة تنبية اعلي الشاشة </label>
                        <input name="general_alert" id="general_alert" class="form-control"
                            value="{{ $data['general_alert'] }}" placeholder="ادخل رسالة تنبية الشركة"
                            oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>شعار الشركة</label>
                        <div class="image">
                            <img name="photo" class="custom_img" src="" alt="لوجو الشركة">
                            <button type="button" class="btn btn-sm btn-danger" id="update_image">تغير الصورة</button>
                            <button type="button" class="btn btn-sm btn-danger" style="display: none;"
                                id="cancel_update_image"> الغاء</button>


                        </div>
                    </div>
                    <div id="oldimage">

                    </div>




                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button href="" type="submit" class="btn btn-primary btn-sm">حفظ التعديلات</button>
                        </div>
                    </div>
                </div>
        </form>

        @else
        <div class="alert alert-danger">
            عفوا لاتوجد بيانات لعرضها !!
        </div>
        @endif



    </div>
</div>


@endsection