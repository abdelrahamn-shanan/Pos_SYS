@extends('layouts.admin')
@section('title')
 الصلاحيات
@endsection

@section('contentheader')
المستخدمين
@endsection

@section('contentheaderlink')
<a href="{{ route('Admin_accounts.index') }}"> المستخدمين </a>
@endsection

@section('contentheaderactive')
عرض
@endsection



@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center">بيانات  المستخدمين</h3>
          <input type="hidden" id="token_search" value="{{csrf_token() }}">
          <input type="hidden" id="ajax_search_url" value="">
        
          <a href="{{ route('Admin_accounts.create') }}" class="btn btn-sm btn-success" >اضافة جديد</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="col-md-4">
<input type="text" id="search_by_text" placeholder="بحث بالاسم" class="form-control"> <br>

          </div>
       
        <div id="ajax_responce_serarchDiv">
          
          @if (@isset($data) && !@empty($data))
          @php
           $i=1;   
          @endphp
          <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
           <th>مسلسل</th>
           <th>اسم المستخدم</th>
           <th>حالة التفعيل</th>
           <th>الإجراءات</th>
          
            </thead>
            <tbody>
         @foreach ($data as $info )
            <tr>
             <td>{{ $i }}</td>  
             <td>{{ $info->name }}</td>  
             <td>@if($info->active==1) مفعل @else معطل @endif</td>  
         <td>
        <a href="{{route('Admin_accounts.edit',$info->id)}}" class="btn btn-sm  btn-primary">تعديل</a>   
        <a href="{{route('Admin_details',$info->id)}}" class="btn btn-sm  btn-info">صلاحيات خاصة</a>   
   
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





@endsection
