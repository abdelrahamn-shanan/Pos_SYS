@extends('layouts.admin')
@section('title')
تعديل بيانات خزنة

@endsection

@section('contentheader')
الخزن
@endsection

@section('contentheaderlink')
<a href="{{ route('Treasureies_index') }}"> الخزن </a>
@endsection

@section('contentheaderactive')
تعديل
@endsection



@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">تعديل بيانات خزنة</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (@isset($data) && !@empty($data))
                <form action="{{ route('Treasureies_update',$data['id']) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>اسم الخزنة</label>
                        <input name="name" id="name" class="form-control" value="{{old ('name',$data['name'])}}"
                            placeholder="ادخل اسم الخزنة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label> نوع الخزنة</label>
                        <select name="is_master" id="is_master" class="form-control">
                            <option value="">اختر النوع</option>
                            <option {{  $data['is_master']==1 ? 'selected' : ''}} value="1"> رئيسية</option>
                            <option {{ $data['is_master']==0 ? 'selected' : ''}} value="0"> فرعية</option>

                        </select>

                        @error('is_master')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label> اخر رقم ايصال صرف نقدية لهذة الخزنة</label>
                        <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="last_recieve_exchange"
                            id="last_isal_exhcange" class="form-control"
                            value="{{ old('last_recieve_exchange',$data['last_recieve_exchange']) }}"
                            placeholder="   ادخل اخر رقم ايصال صرف"
                            oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}">
                        @error('last_recieve_exchange')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label> اخر رقم ايصال تحصيل نقدية لهذة الخزنة</label>
                        <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="last_recieve_collect"
                            id="last_isal_collect" class="form-control"
                            value="{{ old('last_recieve_collect',$data['last_recieve_collect']) }}"
                            placeholder="ادخل اخر رقم ايصال تحصيل "
                            oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}">
                        @error('last_recieve_collect')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label> حالة التفعيل</label>
                        <select name="active" id="active" class="form-control">
                            <option value="">اختر الحالة</option>
                            <option {{  old('active',$data['active'])==1 ? 'selected' : ''}} value="1"> مفعل</option>
                            <option {{ old('active',$data['active'])==0 ? 'selected' : ''}} value="0"> غير مفعل</option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-sm">حفظ التعديلات</button>
                        <a href="{{ route('Treasureies_index') }}" class="btn btn-sm btn-danger">الغاء</a>

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