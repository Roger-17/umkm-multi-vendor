<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.category.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = category::all();

            return datatables()->of($data)
                ->editColumn('image', function ($data) {
                    if ($data->image == null || empty($data->image)) {
                        return '-';
                    } else {
                        return '<img src="' . Storage::url($data->image) . '" width="100" class="img-thumbnail"/>';
                    }
                })
                ->addColumn('aksi', function ($data) {
                    if (Auth::user()->hasRole('super admin')) {
                        $button = '<div class="dropdown d-inline mr-2"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-sm fa-edit"></i> Aksi
                        </button>';

                        $button .= ' <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item" href="' . route('category.edit', $data->id) . '">
                                 Edit</a>
                            <a class="dropdown-item hapus" href="javascript:void(0)" data-id="' . $data->id . '">
                                 Delete</a>
                        </div></div>';

                        return $button;
                    }else{
                        return '-';
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'image'])
                ->toJson();
        }
    }

    public function create()
    {
        return view('pages.category.create');
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'sometimes|nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error validation',
                'error' => $validator->errors()
            ]);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('assets/image-category', 'public');

            $category = new category();
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->image = $image;
            $category->save();

            if ($category) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Category created'
                ]);
            }
        } else {
            $category = new category();
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->save();

            if ($category) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Category created'
                ]);
            }
        }
    }

    public function edit($id)
    {
        $category = category::find($id);
        return view('pages.category.edit', [
            'category' => $category
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'sometimes|nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error validation',
                'error' => $validator->errors()
            ]);
        }

        $category = category::find($request->id);

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('assets/image-category', 'public');
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->image = $image;
            $category->save();
        } else {
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->save();
        }

        if ($category) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated'
            ]);
        }
    }

    public function destroy($id)
    {
        $category = category::find($id);

        $category->delete();

        if ($category) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted'
            ]);
        }
    }
}
