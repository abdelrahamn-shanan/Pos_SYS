@extends('layouts.admin')
@section('title')
فئات الفواتير 
@endsection

@section('contentheader')
فئات الفواتير
@endsection

@section('contentheaderlink')
<a href="{{route('SalesMaterialsTypesindex') }}"> فئات الفواتير  </a>
@endsection
@section('contentheaderactive')
عرض
@endsection



@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center">بيانات فئات الفواتير </h3>
          <a href="{{route('Sales_Materials_Types_create')}}" class="btn btn-sm btn-success" >اضافة جديد</a>

        </div>

        <!-- /.card-header -->
        <div class="card-body">
     
       
        <div id="ajax_responce_serarchDiv">
        @if (@isset($data) && !@empty($data))
        @php
           $i=1;   
          @endphp
        <table id="example2" class="table table-bordered table-hover">
        <thead class="custom_thead">
           <th>مسلسل</th>
           <th>اسم الفئة</th>
           <th>حالة التفعيل</th>
           <th>تاريخ الاضافة</th>
           <th>تاريخ التحديث</th>
           <th> الاجراءات</th>



        </thead>
            <tbody>
         @foreach ($data as $info )
            <tr>
             <td>{{ $i }}</td>  
             <td>{{ $info->name }}</td>  
             <td>@if($info->active==1) مفعل @else معطل @endif</td>  
             <td > 
     
     @php
    $dt=new DateTime($info->created_at);
    $date=$dt->format("Y-m-d");
    $time=$dt->format("h:i");
    $newDateTime=date("A",strtotime($time));
    $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء'); 
     @endphp
 {{ $date }}
 {{ $time }}
 {{ $newDateTimeType }}
 بواسطة 
 {{ $info->added_by_admin}}
 
 
                 </td>
                 <td > 
     
     @php
    $dt=new DateTime($info->updatetd_at);
    $date=$dt->format("Y-m-d");
    $time=$dt->format("h:i");
    $newDateTime=date("A",strtotime($time));
    $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء'); 
     @endphp
 {{ $date }}
 {{ $time }}
 {{ $newDateTimeType }}
 بواسطة 
 {{ $info->updated_by_admin}}
 
 
                 </td>

             <td>
             <a href="{{route('Sales_Materials_Types_edit',$info->id)}}" class="btn btn-sm  btn-primary">تعديل</a>   
             <a  onclick="return confirm('Are you sure?')"  href="{{route('Sales_Materials_Types_delete',$info->id)}}"class="btn btn-sm btn-danger are_you_shue">حذف</a> 
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