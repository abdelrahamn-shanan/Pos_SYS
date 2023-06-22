@if (@isset($data) && !@empty($data) && count($data) >0 )
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
                <a href="{{route('ItemCard_Categories.edit',$info->id)}}" class="btn btn-sm  btn-primary">تعديل</a>

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
<div class="col-md-12" id="ajax_pagination_in_search">
    {{ $data->links() }}
</div>



@else
<div class="alert alert-danger">
    عفوا لاتوجد بيانات لعرضها !!
</div>
@endif