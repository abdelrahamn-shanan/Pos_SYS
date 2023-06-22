@extends('layouts.admin')
@section('title')
تعديل صنف
@endsection
@section('contentheader')
الاصناف
@endsection
@section('contentheaderlink')
<a href="{{ route('ItemCard.index') }}"> الاصناف </a>
@endsection
@section('contentheaderactive')
تعديل
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> تعديل صنف </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form action="{{route('ItemCard.update',$data['id'])}}" method="post" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <div class="form-group">
                        <label> باركود الصنف</label>
                        <input name="barcode" id="barcode" class="form-control"
                            value="{{old('barcode',$data['barcode'])}}" placeholder="باركود الصنف">
                        @error('barcode')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>اسم الصنف</label>
                        <input name="name" id="name" class="form-control" value="{{ old('name',$data['name']) }}"
                            placeholder="ادخل اسم الصنف">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> نوع الصنف</label>
                        <select name="item_type" id="item_type" class="form-control">
                            <option {{  old('item_type',$data['item_type'])==1 ? 'selected' : ''}} value="1"> مخزني
                            </option>
                            <option {{  old('item_type',$data['item_type'])==2 ? 'selected' : ''}} value="2"> استهلاكي
                                بتاريخ صلاحية</option>
                            <option {{  old('item_type',$data['item_type'])==3 ? 'selected' : ''}} value="3"> عهدة
                            </option>
                        </select>

                        @error('item_type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> فئة الصنف</label>
                        <select name="itemcard_category" id="itemcard_category" class="form-control ">
                            @if (@isset($inv_itemcard_categories) && !@empty($inv_itemcard_categories))
                            @foreach ($inv_itemcard_categories as $info )
                            <option
                                {{ old('itemcard_category' , $data['itemcard_category'])== $info->id ? 'selected' :''}}
                                value="{{ $info->id }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('itemcard_category')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> الصنف الاب له</label>
                        <select name="parent_itemcard_id" id="parent_itemcard_id" class="form-control ">
                            <option selected value=""> هو اب</option>
                            @if (@isset($item_card_data) && !@empty($item_card_data))
                            @foreach ($item_card_data as $info )
                            <option
                                {{ old('parent_itemcard_id' , $data['parent_itemcard_id'])== $info->id ? 'selected' :''}}
                                value="{{ $info->id }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('parent_itemcard_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> وحدة القياس الاب</label>
                        <select name="uom_id" id="uom_id" class="form-control ">
                            <option value="">اختر الوحدة الاب</option>
                            @if (@isset($inv_itemcard_Uoms_parent) && !@empty($inv_itemcard_Uoms_parent))
                            @foreach ($inv_itemcard_Uoms_parent as $info )
                            <option {{ old('uom_id' , $data['uom_id'])== $info->id ? 'selected' :''}}
                                value="{{ $info->id }}">
                                {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('uom_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> هل للصنف وحدة تجزئة ابن</label>
                        <select name="does_has_retailunit" id="does_has_retailunit" class="form-control">
                            <option {{  old('does_has_retailunit',$data['does_has_retailunit'])==1 ? 'selected' : ''}}
                                value="1"> نعم
                            </option>
                            <option {{  old('does_has_retailunit',$data['does_has_retailunit'])==0 ? 'selected' : ''}}
                                value="0"> لا
                            </option>
                        </select>

                        @error('does_has_retailunit')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 )
                    style="display: none;" @endif id="retail_uom_idDiv">
                    <div class="form-group">
                        <label> وحدة القياس التجزئة الابن بالنسبة للأب(<span class="parentuomname"></span>)</label>
                        <select name="retail_uom_id" id="retail_uom_id" class="form-control ">
                            <option value="">اختر وحدة التجزئة </option>
                            @if (@isset($inv_itemcard_Uoms_child) && !@empty($inv_itemcard_Uoms_child))
                            @foreach ($inv_itemcard_Uoms_child as $info )
                            <option @if(old('retail_uom_id',$data['retail_uom_id'])==$info->id ) selected="selected"
                                @endif
                                value="{{ $info->id }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('retail_uom_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 relatied_retial_counter "
                    @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>

                    <div class="form-group">
                        <label>عدد وحدات التجزئة (<span class="childuomname"></span>) بالنسبة للأب (<span
                                class="parentuomname"></span>) </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                            name="retail_uom_quantityToParent" id="retail_uom_quntToParent" class="form-control"
                            value="{{old('retail_uom_quantityToParent',$data['retail_uom_quantityToParent'] )}}"
                            placeholder="ادخل  عدد وحدات التجزئة">
                        @error('retail_uom_quantityToParent')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6 relatied_parent_counter " @if(old('uom_id',$data['uom_id'])=='' )
                    style="display: none;" @endif>

                    <div class="form-group">
                        <label>سعر القطاعي بوحدة (<span class="parentuomname"></span>) </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="price" id="price"
                            class="form-control" value="{{ old('price',$data['price']) }}" placeholder="ادخل السعر ">
                        @error('price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 relatied_parent_counter " @if(old('uom_id',$data['uom_id'])=='' )
                    style="display: none;" @endif>
                    <div class="form-group">
                        <label>سعر النص جملة بوحدة (<span class="parentuomname"></span>) </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="nos_gomla_price"
                            id="nos_gomla_price" class="form-control"
                            value="{{ old('nos_gomla_price',$data['nos_gomla_price']) }}" placeholder="ادخل السعر ">
                        @error('nos_gomla_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 relatied_parent_counter " @if(old('uom_id',$data['uom_id'])=='' )
                    style="display: none;" @endif>
                    <div class="form-group">
                        <label>سعر جملة بوحدة (<span class="parentuomname"></span>) </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="gomla_price"
                            id="gomla_price" class="form-control" value="{{ old('gomla_price',$data['gomla_price']) }}"
                            placeholder="ادخل السعر ">
                        @error('gomla_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 relatied_parent_counter " @if(old('uom_id',$data['uom_id'])=='' )
                    style="display: none;" @endif>
                    <div class="form-group">
                        <label>سعر تكلفة الشراء لوحدة (<span class="parentuomname"></span>) </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="cost_price" id="cost_price"
                            class="form-control" value="{{ old('cost_price',$data['cost_price']) }}"
                            placeholder="ادخل السعر ">
                        @error('cost_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 relatied_retial_counter "
                    @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>

                    <div class="form-group">
                        <label>سعر القطاعي بوحدة (<span class="childuomname"></span>) </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="retail_price"
                            id="price_retail" class="form-control"
                            value="{{ old('retail_price',$data['retail_price']) }}" placeholder="ادخل السعر ">
                        @error('retail_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 relatied_retial_counter "
                    @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
                    <div class="form-group">
                        <label>سعر النص جملة بوحدة (<span class="childuomname"></span>) </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="nos_gomla_retail_price"
                            id="nos_gomla_price_retail" class="form-control"
                            value="{{ old('nos_gomla_retail_price',$data['nos_gomla_retail_price']) }}"
                            placeholder="ادخل السعر ">
                        @error('nos_gomla_retail_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 relatied_retial_counter "
                    @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
                    <div class="form-group">
                        <label>سعر الجملة بوحدة (<span class="childuomname"></span>) </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="gomla_retail_price"
                            id="gomla_price_retail" class="form-control"
                            value="{{ old('gomla_retail_price',$data['gomla_retail_price']) }}"
                            placeholder="ادخل السعر ">
                        @error('gomla_retail_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6 relatied_retial_counter "
                    @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
                    <div class="form-group">
                        <label>سعر الشراء بوحدة (<span class="childuomname"></span>) </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="retail_cost_price"
                            id="cost_price_retail" class="form-control"
                            value="{{ old('retail_cost_price',$data['retail_cost_price']) }}" placeholder="ادخل السعر ">
                        @error('retail_cost_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> هل للصنف سعر ثابت !؟</label>
                        <select name="has_fixed_price" id="has_fixed_price" class="form-control">
                            <option @if(old('has_fixed_price',$data['has_fixed_price'])==1) selected="selected" @endif
                                value="1"> نعم ثابت ولا
                                يتغير بالفواتير</option>
                            <option @if(old('has_fixed_price',$data['has_fixed_price'])==0 and
                                $data['has_fixed_price']!="" ) selected="selected" @endif value="0">
                                قابل للتغيير بالفواتير</option>
                        </select>
                        @error('has_fixed_price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label> حالة التفعيل</label>
                        <select name="active" id="active" class="form-control">
                            <option @if(old('active',$data['active'])==1) selected="selected" @endif value="1"> نعم
                            </option>
                            <option @if(old('active',$data['active'])==0) and old('active')!="" ) selected="selected"
                                @endif value="0">
                                لا</option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6" style="border:solid 5px #000 ; margin:10px;">
                    <div class="form-group">
                        <label> صورة الصنف ان وجدت</label>
                        <img id="uploadedimg" src="{{asset($data['photo'])}}" alt="uploaded img"
                            style="width: 200px; width: 200px;">
                        <input onchange="readURL(this)" type="file" id="Item_img" name="photo" class="form-control">
                        @error('')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_edit_item_cardd" type="submit" class="btn btn-primary btn-sm"> حفظ
                            التعديلات</button>
                        <a href="{{ route('ItemCard.index') }}" class="btn btn-sm btn-danger">الغاء</a>

                    </div>
                </div>

            </div>
        </form>



    </div>

</div>






@endsection

<script type="text/javascript">
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#uploadedimg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@section('script')
<script src="{{asset('assets/admin/js/itemcard.js')}}"></script>

<script>
var uom_id = $("#uom_id").val();
if (uom_id != "") {
    var name = $("#uom_id option:selected").text();
    $(".parentuomname").text(name);
}

var retail_uom_id = $("#retail_uom_id").val();
if (retail_uom_id != "") {
    var name = $("#retail_uom_id option:selected").text();
    $(".childuomname").text(name);
}
</script>
@endsection