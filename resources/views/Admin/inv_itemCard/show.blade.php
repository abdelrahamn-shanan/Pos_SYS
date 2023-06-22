@extends('layouts.admin')
@section('title')
ضبط الاصناف
@endsection

@section('contentheader')
الضبط
@endsection

@section('contentheaderlink')
<a href="{{ route('ItemCard.index') }}"> الاصناف </a>
@endsection

@section('contentheaderactive')
عرض التفاصيل
@endsection



@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات الصنف </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (@isset($data) && !@empty($data))
                <table id="example2" class="table table-bordered table-hover">

                    <tr>
                        <td>
                            <label>باركود الصنف</label><br>
                            {{ $data['barcode'] }}
                        </td>
                        <td>
                            <label>اسم الصنف</label><br>
                            {{ $data['name'] }}
                        </td>

                        <td>
                            <label>نوع الصنف</label><br>
                            @if($data['item_type']==1) مخزني @elseif($data['item_type']==2) استهلاكي بصلاحية
                            @elseif($data['item_type']==3) عهدة
                            @else غير محدد @endif
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <label>فئة الصنف</label><br>
                            {{ $data['inv_itemcard_categories_name'] }}
                        </td>
                        <td>
                            <label>الصنف الاب </label><br>
                            {{ $data['parent_item_name'] }}
                        </td>

                        <td>
                            <label>وحدة قياس الاب </label><br>
                            {{ $data['Uom_name'] }}

                        </td>

                    </tr>



                    <tr>
                        <td @if($data['does_has_retailunit']==0) colspan='3' @endif>
                            <label> هل للصنف وحدة تجزئة ؟</label><br>
                            @if($data['does_has_retailunit']==1) نعم @else لا @endif

                        </td>
                        @if($data['does_has_retailunit']==1)
                        <td>
                            <label> وحدة قياس التجزئة </label><br>
                            {{ $data['retail_uom_name'] }}

                        </td>

                        <td>
                            <label> عدد وحدات التجزئة بالنسبة للأب </label><br>
                            {{ $data['retail_uom_quantityToParent']*1 }}

                        </td>
                        @endif

                    </tr>

                    <tr>
                        <td>
                            <label> سعر القطاعي جملة بوحدة ( {{ $data['Uom_name']  }})</label> <br>
                            {{ $data['price']*1 }}
                        </td>
                        <td>
                            <label> سعر النص جملة بوحدة ( {{ $data['Uom_name']  }})</label> <br>
                            {{ $data['nos_gomla_price']*1 }}
                        </td>
                        <td>
                            <label> سعر جملة بوحدة ( {{ $data['Uom_name']  }})</label> <br>
                            {{ $data['gomla_price']*1 }}
                        </td>
                    </tr>

                    <tr>
                        <td @if($data['does_has_retailunit']==0) colspan="3" @endif>
                            <label> سعر تكلفة الشراء بوحدة ( {{ $data['Uom_name']  }})</label> <br>
                            {{ $data['cost_price']*1 }}
                        </td>
                        @if($data['does_has_retailunit']==1)
                        <td>
                            <label> سعر القطاعي بوحدة ( {{ $data['retail_uom_name']  }})</label> <br>
                            {{ $data['retail_price']*1 }}
                        </td>
                        <td>
                            <label> سعر النص جملة بوحدة ( {{ $data['retail_uom_name']  }})</label> <br>
                            {{ $data['nos_gomla_retail_price'] *1}}
                        </td>

                        @endif
                    </tr>
                    @if($data['does_has_retailunit']==1)
                    <tr>
                        <td>
                            <label> سعر الجملة بوحدة ( {{ $data['retail_uom_name']  }})</label> <br>
                            {{ $data['gomla_retail_price'] *1}}
                        </td>
                        <td>
                            <label> سعر تكلفة الشراء بوحدة ( {{ $data['retail_uom_name']  }})</label> <br>
                            {{ $data['retail_cost_price']*1 }}
                        </td>

                    </tr>
                    @endif

                    <tr>
                        <td colspan="3">
                            كمية الصنف الحالية
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <label> هل للصنف سعر ثابت</label> <br>
                            @if($data['has_fixed_price']==1) نعم @else لا @endif
                        </td>
                        <td colspan="2">
                            <label> حالة التفعيل</label> <br>
                            @if($data['active']==1) نعم @else لا @endif
                        </td>

                    </tr>
                    <tr>
                        <td>صورة الصنف</td>
                        <td colspan='3'>
                            <div class="image">
                                <img class="custom_img" src="{{asset($data['photo'])}}" alt="صورة الصنف">

                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td> تاريخ اخر تحديث</td>
                        <td colspan='2'>
                            @if($data['updated_by']>0 and $data['updated_by']!=null )
                            @php
                            $dt=new DateTime($data['updated_at']);
                            $date=$dt->format("Y-m-d");
                            $time=$dt->format("h:i");
                            $newDateTime=date("A",strtotime($time));
                            $newDateTimeType= (($newDateTime=='AM')?'صباحاً ':'مساءاً');
                            @endphp
                            {{ $date }}
                            {{ $time }}
                            {{ $newDateTimeType }}
                            بواسطة
                            {{ $data['updated_by_admin'] }}
                            @else
                            لا يوجد تحديث
                            @endif

                            <a href="{{route('ItemCard.edit',$data['id'])}}" class="btn btn-sm btn-success">تعديل</a>


                        </td>
                    </tr>

                </table>

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