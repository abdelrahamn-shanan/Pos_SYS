@extends('layouts.admin')
@section('title')
ضبط الاصناف
@endsection

@section('contentheader')
الاصناف
@endsection

@section('contentheaderlink')
<a href="{{ route('ItemCard.index') }}"> الاصناف </a>
@endsection

@section('contentheaderactive')
عرض
@endsection



@section('content')



<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center">بيانات الاصناف</h3>
        <input type="hidden" id="token_search" value="{{csrf_token()}}">
        <input type="hidden" id="ajax_search_url" value="{{ route('item_card_Ajax_search') }}">

        <a href="{{ route('ItemCard.create') }}" class="btn btn-sm btn-success">اضافة جديد</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label> <input checked type="radio" name="searchbyradio" id="searchbyradio" value="barcode"> بالباركود
                    <input type="radio" name="searchbyradio" id="searchbyradio" value="item_code"> بكود الصنف
                    <input type="radio" name="searchbyradio" id="searchbyradio" value="name"> بالاسم
                </label>

                <input style="margin-top: 6px !important;" type="text" id="search_by_text"
                    placeholder=" اسم - باركود - كود للصنف" class="form-control"> <br>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث بنوع الصنف</label>
                    <select name="item_type" id="item_type" class="form-control">
                        <option value="all"> بحث بالكل</option>
                        <option value="1"> مخزني</option>
                        <option value="2"> استهلاكي بتاريخ صلاحية</option>
                        <option value="3"> عهدة</option>
                    </select>

                    @error('item_type_search')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث بفئة الصنف</label>

                    <select name="itemcard_category_search" id="itemcard_category_search" class="form-control ">
                        <option value="all"> بحث بالكل</option>
                        @if (@isset($inv_itemcard_categories) && !@empty($inv_itemcard_categories))
                        @foreach ($inv_itemcard_categories as $info )
                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>
                    @error('itemcard_category_search')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">

                @if (@isset($data) && !@empty($data))
                @php
                $i=1;
                @endphp
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="custom_thead">

                        <th> مسلسل </th>
                        <th>الاسم </th>
                        <th> النوع </th>
                        <th> الفئة </th>
                        <th> الصنف الاب </th>
                        <th> الوحدة الاب </th>
                        <th> الوحدة التجزئة </th>
                        <th>حالة التفعيل</th>
                        <th> الإجراءات </th>
                    </thead>
                    <tbody>
                        @foreach ($data as $info )
                        <tr>
                            <td>{{ $info->item_code }}</td>
                            <td>{{ $info->name }}</td>
                            <td>@if($info->item_type==1) مخزني @elseif($info->item_type==2) استهلاكي بتاريخ صلاحية
                                @elseif($info->item_type==3) عهدة
                                @else غير محدد @endif</td>
                            <td>{{ $info->ItemCardCategories_name }}</td>
                            <td>{{ $info->parent_itemcard_name }}</td>
                            <td>{{ $info->parent_uom_name }}</td>
                            <td> {{$info->retail_uom_name}}</td>
                            <td>@if($info->active==1) مفعل @else معطل @endif</td>

                            <td>

                                <a href="{{ route('ItemCard.edit',$info->id) }}"
                                    class="btn btn-sm  btn-primary">تعديل</a>
                                <a onclick="return confirm('Are you sure?')"
                                    href="{{route('admin.itemcard_categories.delete',$info->id)}}"
                                    class="btn btn-sm btn-danger are_you_shue">حذف</a>
                                <br>
                                <a href="{{route('ItemCard.show',$info->id)}}" class="btn btn-sm  btn-info">عرض</a>

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

@endsection
@section('script')
<script src="{{ asset('assets/admin/js/itemcard.js') }}"></script>
<script src="{{ asset('assets/admin/js/itemcardajaxsearch.js') }}"></script>

@endsection