@extends('layouts.admin')
@section('title')
اضافة خزنة جديدة
@endsection

@section('contentheader')
الخزن
@endsection

@section('contentheaderlink')
<a href="{{ route('Treasureies_index') }}"> الخزن </a>
@endsection

@section('contentheaderactive')
اضافة
@endsection



@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">اضافة خزنة جديدة</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <form action="{{ route('Treasureies_store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>اسم الخزنة</label>
                        <input name="name" id="name" class="form-control" value="" placeholder="ادخل اسم الخزنة"
                            oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label> نوع الخزنة</label>
                        <select name="is_master" id="is_master" class="form-control">
                            <option value="">اختر النوع</option>
                            <option value="1"> رئيسية</option>
                            <option value="0"> فرعية</option>

                        </select>

                        @error('is_master')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label> اخر رقم ايصال صرف نقدية لهذة الخزنة</label>
                        <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="last_recieve_exchange"
                            id="last_recieve_exchange" class="form-control" value=""
                            placeholder=" ادخل  رقم اخر إيصال صرف"
                            oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}">
                        @error('last_recieve_exchange')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label> اخر رقم ايصال تحصيل نقدية لهذة الخزنة</label>
                        <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="last_recieve_collect"
                            id="last_recieve_collect" class="form-control" value=""
                            placeholder="ادخل اسم اخر ايصال تحصيل"
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
                            <option value="1"> مفعل</option>
                            <option value="0"> معطل</option>


                        </select>

                        @error('active')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-sm"> اضافة</button>
                        <a href="{{route('Treasureies_index')}}" class="btn btn-sm btn-danger">الغاء</a>

                    </div>


                </form>



            </div>




        </div>
    </div>
</div>
</div>





@endsection