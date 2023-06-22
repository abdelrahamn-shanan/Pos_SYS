@extends('layouts.admin')
@section('title')
فئات الاصناف
@endsection

@section('contentheader')
فئات الاصناف
@endsection

@section('contentheaderlink')
<a href="{{route('ItemCard_Categories.index') }}"> فئات الاصناف </a>
@endsection
@section('contentheaderactive')
عرض
@endsection



@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات فئات الاصناف </h3>
                <input type="hidden" id="token_search" value="{{csrf_token()}}">
                <input type="hidden" id="ajax_search_url" value="{{route('Ajax_search2')}}">
                <a href="{{route('ItemCard_Categories.create')}}" class="btn btn-sm btn-success">اضافة جديد</a>

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
                            <th>اسم الصنف</th>
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
                                <td>



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
                                <td>
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
                                    <a href="{{route('ItemCard_Categories.edit',$info->id)}}"
                                        class="btn btn-sm  btn-primary">تعديل</a>

                                    <a onclick="return confirm('Are you sure?')"
                                        href="{{route('admin.itemcard_categories.delete',$info->id)}}"
                                        class="btn btn-sm btn-danger are_you_shue">حذف</a>
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

<script src="{{ asset('assets/admin/js/treasuries.js')}}"></script>

@endsection