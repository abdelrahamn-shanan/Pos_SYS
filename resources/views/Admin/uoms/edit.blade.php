@extends('layouts.admin')
@section('title')
تعديل بيانات الوحدات

@endsection

@section('contentheader')
وحدات القياس
@endsection

@section('contentheaderlink')
<a href="{{ route('admin.uoms.index') }}"> وحدات القياس </a>
@endsection

@section('contentheaderactive')
تعديل
@endsection



@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center">تعديل بيانات  الوحدات</h3>
        
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        @if (@isset($data) && !@empty($data))
      <form action="{{ route('admin.uoms.update',$data['id']) }}" method="post" enctype="multipart/form-data">
        @csrf
        
      <div class="form-group">
        <label>اسم وحدة القياس</label>
        <input name="name" id="name" class="form-control" value="{{$data['name']}}" placeholder="ادخل اسم الخزنة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}"  >
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        </div>
        <div class="form-group">
          <label>  نوع وحدة القياس</label> 
          <select name="is_master" id="is_master" class="form-control">
           <option value="">اختر النوع</option>
           <option {{  $data['is_master']==1 ? 'selected' : ''}}   value="1"> رئيسية</option>
           <option {{ $data['is_master']==0 ? 'selected' : ''}}  value="0">  تجزئة</option>
        
          </select>
        
          @error('is_master')
          <span class="text-danger">{{ $message }}</span>
          @enderror
          </div>
              <div class="form-group"> 
                <label>  حالة التفعيل</label>
                <select name="active" id="active" class="form-control">
                 <option value="">اختر الحالة</option>
                 <option {{  old('active',$data['active'])==1 ? 'selected' : ''}}   value="1"> مفعل</option>
                 <option {{ old('active',$data['active'])==0 ? 'selected' : ''}}  value="0"> معطل </option>
                </select>
                @error('active')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
      <div class="form-group text-center">
<button type="submit" class="btn btn-primary btn-sm">حفظ التعديلات</button>
<a href="{{ route('admin.uoms.index') }}" class="btn btn-sm btn-danger">الغاء</a>    

      </div>

    
    </form>  

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