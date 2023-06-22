@if (@isset($data) && !@empty($data) &&count($data)>0)
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
            <td>@if($info->item_type==1) مخزني @elseif($info->item_type==2) استهلاكي بصلاحية
                @elseif($info->item_type==3) عهدة
                @else غير محدد @endif</td>
            <td>{{ $info->ItemCardCategories_name }}</td>
            <td>{{ $info->parent_itemcard_name }}</td>
            <td>{{ $info->parent_uom_name }}</td>
            <td> {{$info->retail_uom_name}}</td>
            <td>@if($info->active==1) مفعل @else معطل @endif</td>

            <td>

                <a href="{{ route('ItemCard.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
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
<div class="col-md-12" id="ajax_pagination_in_search">
    {{ $data->links() }}
</div>

@else
<div class="alert alert-danger">
    عفوا لاتوجد بيانات لعرضها !!
</div>
@endif