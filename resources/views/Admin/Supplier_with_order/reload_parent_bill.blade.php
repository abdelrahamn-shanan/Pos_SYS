@if (@isset($data) && !@empty($data))
<table id="example2" class="table table-bordered table-hover">

    <tr>
        <td class="width30"> الكود الآلي للفاتورة</td>
        <td> {{ $data['auto_serial'] }}</td>
    </tr>

    <tr>
        <td class="width30"> كود فاتورة المشتريات لدى المورد</td>
        <td> {{ $data['Doc_no'] }}</td>
    </tr>

    <tr>
        <td class="width30">اسم المورد</td>
        <td> {{ $data['Supplier_name'] }}</td>
    </tr>

    <tr>
        <td class="width30"> المخزن المستلم للفاتورة</td>
        <td> {{ $data['store_name'] }}</td>
    </tr>

    <tr>
        <td class="width30">تاريخ فاتورة المشتريات </td>
        <td> {{ $data['order_date'] }}</td>
    </tr>

    <tr>
        <td class="width30">نوع فاتورة المشتريات </td>
        <td> @if($data['bill_type']==1) كاش @else آجل @endif</td>
    </tr>

    <tr>
        <td class="width30">حالة فاتورة المشتريات </td>
        <td> @if($data['is_approved']==1) معتمده @else مفتوحة @endif</td>
    </tr>

    <tr>
        <td class="width30">إجمالي الفاتورة المورد</td>
        <td> {{ $data['total_cost']*(1) }}</td>
    </tr>

    @if( $data['discount_type']!=null)
    <tr>
        <td class="width30"> الخصم علي الفاتورة </td>
        <td>
            @if ($data['discount_type']==1)
            خصم نسبة ( {{ $data['discount_percent']*1 }} ) % وقيمتها (
            {{ $data["discount_value"]*1 }} )

            @else

            خصم يدوي وقيمته( {{ $data["discount_value"]*1 }} )

            @endif


        </td>
    </tr>
    @else
    <tr>
        <td class="width30"> الخصم على الفاتورة</td>
        <td> لايوجد</td>
    </tr>
    @endif
    <tr>
        <td class="width30"> نسبة القيمة المضافة</td>
        <td>
            @if($data['tax_percent']>0)
            لايوجد
            @else
            بنسبة ({{ $data["tax_percent"]*1 }}) % وقيمتها ( {{ $data['tax_value']*1 }} )
            @endif

        </td>
    </tr>






    <td class="width30"> تاريخ الاضافة</td>
    <td>

        @php
        $dt=new DateTime($data['created_at']);
        $date=$dt->format("Y-m-d");
        $time=$dt->format("h:i");
        $newDateTime=date("A",strtotime($time));
        $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء');
        @endphp
        {{ $date }}
        {{ $time }}
        {{ $newDateTimeType }}
        بواسطة
        {{ $data['added_by_admin'] }}


    </td>
    </tr>

    <tr>
        <td class="width30"> تاريخ اخر تحديث</td>
        <td>
            @if($data['updated_by']>0 and $data['updated_by']!=null )
            @php
            $dt=new DateTime($data['updated_at']);
            $date=$dt->format("Y-m-d");
            $time=$dt->format("h:i");
            $newDateTime=date("A",strtotime($time));
            $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء');
            @endphp
            {{ $date }}
            {{ $time }}
            {{ $newDateTimeType }}
            بواسطة
            {{ $data['updated_by_admin'] }}

            @else
            لايوجد تحديث
            @endif

            <a href="{{ route('Supplier_with_orders.edit',$data['id']) }}" class="btn btn-sm btn-success">تعديل</a>

            @if ($data['is_approved']==0)

            <button type="button" class="btn btn-sm btn-primary" id="load_invoice_approve">اعتماد
                وترحيل
                الفاتورة</button>
            @endif

        </td>
    </tr>

</table>
@else
<div class="alert alert-danger">
    عفوا لاتوجد بيانات لعرضها !!
</div>
@endif