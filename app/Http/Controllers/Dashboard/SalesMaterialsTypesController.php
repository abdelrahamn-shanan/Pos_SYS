<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesMaterialTypeRequest;
use Illuminate\Http\Request;
use App\Models\SalesMaterialType;
use App\Models\Admin;

class SalesMaterialsTypesController extends Controller
{
    public function index()
    {
        $data = SalesMaterialType::select()->orderby('id', 'desc')->paginate(PAGINATION_COUNT);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
            }
        }
        return view('admin.SalesMaterialType.index', ['data' => $data]);
    }
    public function create()
    {
        return view('admin.SalesMaterialType.create');
    }

    public function store(SalesMaterialTypeRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            //check if not exsits
            $checkExists = SalesMaterialType::where(['name' => $request->name, 'com_code' => $com_code])->first();
            if ($checkExists == null) {
                $data['name'] = $request->name;
                $data['active'] = $request->active;
                $data['created_at'] = date("Y-m-d H:i:s");
                $data['added_by'] = auth()->user()->id;
                $data['com_code'] = $com_code;
                $data['date'] = date("Y-m-d");
                SalesMaterialType::create($data);
                return redirect()->route('SalesMaterialsTypesindex')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
            } else {
                return redirect()->back()
                    ->with(['error' => 'عفوا اسم الخزنة مسجل من قبل'])
                    ->withInput();
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }



    }


    public function edit($id)
    {
        $data = SalesMaterialType::select()->find($id);
        return view('admin.SalesMaterialType.edit', ['data' => $data]);

    }

    public function update(SalesMaterialTypeRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = SalesMaterialType::select()->find($id);
            if (empty($data)) {
                return redirect()->route('SalesMaterialsTypesindex')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }
            //check if not exsits
            $checkExists = SalesMaterialType::where(['name' => $request->name, 'com_code' => $com_code])->where('id', '!=', $id)->first();
            if ($checkExists == null) {
                $data['name'] = $request->name;
                $data['active'] = $request->active;
                $data['created_at'] = date("Y-m-d H:i:s");
                $data['added_by'] = auth()->user()->id;
                $data['com_code'] = $com_code;
                $data['date'] = date("Y-m-d");
                $data->update();
                return redirect()->route('SalesMaterialsTypesindex')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
            } else {
                return redirect()->back()
                    ->with(['error' => 'عفوا اسم الفئة مسجل من قبل'])
                    ->withInput();
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }

    }

    public function delete($id)
    {
        try {
            $SalesMaterialType = SalesMaterialType::find($id);
            if (!empty($SalesMaterialType)) {
                $flag = $SalesMaterialType->delete();
                if ($flag) {
                    return redirect()->back()
                        ->with(['success' => '   تم حذف البيانات بنجاح']);
                } else {
                    return redirect()->back()
                        ->with(['error' => 'عفوا حدث خطأ ما']);
                }
            } else {
                return redirect()->back()
                    ->with(['error' => 'عفوا غير قادر الي الوصول للبيانات المطلوبة']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()]);
        }
    }
}