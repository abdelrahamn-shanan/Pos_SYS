@extends('layouts.admin')
@section('title')
اضافة مخزن جديد
@endsection

@section('contentheader')
المخازن
@endsection

@section('contentheaderlink')
<a href="{{ route('admin.stores.index') }}"> المخازن </a>
@endsection

@section('contentheaderactive')
اضافة
@endsection



@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center">اضافة مخزن جديد</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
    
      <form action="{{ route('admin.stores.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        
      <div class="form-group">
<label>اسم المخزن</label>
<input name="name" id="name" class="form-control" value="" placeholder="ادخل اسم المخزن" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}"  >
@error('name')
<span class="text-danger">{{ $message }}</span>
@enderror
</div>

<label> الهاتف</label>
<input name="phone" id="phone" class="form-control" value="" placeholder="ادخل هاتف المخزن" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}"  >
@error('phone')
<span class="text-danger">{{ $message }}</span>
@enderror
</div>

<label> العنوان</label>
<input name="address" id="address" class="form-control" value="" placeholder="ادخل عنوان المخزن" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}"  >
@error('address')
<span class="text-danger">{{ $message }}</span>
@enderror
</div>

      <div class="form-group"> 
        <label>  حالة التفعيل</label>
        <select name="active" id="active" class="form-control">
         <option value="">اختر الحالة</option>
        <option   value="1"> مفعل</option>
         <option  value="0"> معطل</option>
      
      
        </select>
      
        @error('active')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary btn-sm"> اضافة</button>
        <a href="{{route('admin.stores.index')}}" class="btn btn-sm btn-danger">الغاء</a>    
      
      </div>
        
            
            </form>  
        
            

            </div>  

      


        </div>
      </div>
    </div>
</div>





@endsection
