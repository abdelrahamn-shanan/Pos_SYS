@extends('layouts.admin')
@section('title')
عرض حساب مالي
@endsection

@section('contentheader')
عرض
@endsection

@section('contentheaderlink')
<a href="{{ route('admin.accounts.index') }}"> الحسابات المالية </a>
@endsection

@section('contentheaderactive')
عرض
@endsection



@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات حساب مالي</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (@isset($data) && !@empty($data))
                <table id="example2" class="table table-bordered table-hover">

                    <tr>
                        <td class="width30">اسم الحساب المالي </td>
                        <td> {{ $data['name'] }}</td>
                    </tr>

                    <tr>
                        <td class="width30"> نوع الحساب</td>
                        <td> {{ $data['account_type'] }}</td>

                    </tr>

                    <tr>
                        <td class="width30"> هل الحساب رئيسي؟!</td>
                        <td>{{ $data['is_parent'] == 1 ? 'فرعي' : 'رئيسي'}}</td>

                    </tr>

                    <tr>
                        <td class="width30">الحساب الرئيسي ان وجد </td>
                        <td> {{ $data['parent_account_number']== null ? 'لا يوجد' : $data['parent_account_number']}}
                        </td>
                    </tr>

                    <tr>
                        <td class="width30">ملاحظات </td>
                        <td> {{ $data['notes'] }}</td>
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