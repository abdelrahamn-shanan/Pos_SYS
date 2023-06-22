@extends('layouts.admin')
@section('title')
الخزن 
@endsection

@section('contentheader')
الخزن
@endsection

@section('contentheaderlink')
<a href="{{ route('Treasureies_index') }}"> الخزن </a>
@endsection
@section('contentheaderactive')
عرض
@endsection



@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center">بيانات الخزن </h3>
          <input type="hidden" id="token_search" value="{{csrf_token()}}">
          <input type="hidden" id="ajax_search_url" value="{{ route('Ajax_search1') }}">
          <a href="{{ route('Treasureies_create') }}" class="btn btn-sm btn-success" >اضافة جديد</a>

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
           <th>اسم الخزنة</th>
           <th> نوع الخزنة</th>
           <th>اخر ايصال صرف</th>
           <th>اخر ايصال تحصيل</th>
           <th>حالة التفعيل</th>
           <th>الاجراءات</th>

        </thead>
            <tbody>
         @foreach ($data as $infoo )
            <tr>
             <td>{{ $i }}</td>  
             <td>{{ $infoo->name }}</td>  
             <td>@if($infoo->is_master==1) رئيسية @else فرعية @endif</td>  
             <td>{{ $infoo->last_recieve_exchange }}</td>  
             <td>{{ $infoo->last_recieve_collect }}</td>  
             <td>@if($infoo->active==1) مفعل @else معطل @endif</td>  
             <td>
             <a href="{{route('Treasureies_edit',$infoo->id)}}" class="btn btn-sm  btn-primary">تعديل</a>   
             <a href="{{route('Treasureies_Details',$infoo->id)}}" class="btn btn-sm  btn-info">المزيد</a> 
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
@section('script')
<script src="{{asset('assets/admin/js/treasuries.js')}}"></script>

@endsection