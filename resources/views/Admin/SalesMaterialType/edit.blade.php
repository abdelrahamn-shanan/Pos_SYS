@extends('layouts.admin')
@section('title')
تعديل بيانات فئات الفواتير

@endsection

@section('contentheader')
فئات الفواتير
@endsection

@section('contentheaderlink')
<a href="{{ route('SalesMaterialsTypesindex') }}"> فئات الفواتير </a>
@endsection

@section('contentheaderactive')
تعديل
@endsection



@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center">تعديل بيانات  فئة فاتوره</h3>
        
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        @if (@isset($data) && !@empty($data))
      <form action="{{ route('Sales_Materials_Types_update',$data['id']) }}" method="post" enctype="multipart/form-data">
        @csrf
        
      <div class="form-group">
        <label>اسم الفئة</label>
        <input name="name" id="name" class="form-control" value="{{$data['name']}}" placeholder="ادخل اسم الفئة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}"  >
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        </div>
        
              <div class="form-group"> 
                <label>  حالة التفعيل</label>
                <select name="active" id="active" class="form-control">
                 <option value="">اختر الحالة</option>
                 <option {{  old('active',$data['active'])==1 ? 'selected' : ''}}   value="1"> مفعل</option>
                 <option {{ old('active',$data['active'])==0 ? 'selected' : ''}}  value="0"> غير مفعل</option>
                </select>
                @error('is_master')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
      <div class="form-group text-center">
<button type="submit" class="btn btn-primary btn-sm">حفظ التعديلات</button>
<a href="{{ route('SalesMaterialsTypesindex') }}" class="btn btn-sm btn-danger">الغاء</a>    

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