<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCateList()
    {
        $cates = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.category.cate-list', compact('cates'));
    }

    public function getCateAdd()
    {
        return view('admin.category.cate-add');
    }

    public function postCateAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|url|max:1000',
        ],[
            'name.required' => 'Vui lòng nhập tên danh mục',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
        ]);

        return redirect()->route('admin.getCateList')->with('success', 'Thêm danh mục thành công');
    }

    public function getCateEdit($id)
    {
        $cate = Category::findOrFail($id);
        return view('admin.category.cate-edit', compact('cate'));
    }

    public function postCateEdit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|url|max:1000',
        ],[
            'name.required' => 'Vui lòng nhập tên danh mục',
        ]);

        $cate = Category::findOrFail($id);
        $cate->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
        ]);

        return redirect()->route('admin.getCateList')->with('success', 'Cập nhật danh mục thành công');
    }

    public function getCateDelete($id)
    {
        $cate = Category::findOrFail($id);
        $cate->delete();

        return redirect()->route('admin.getCateList')->with('success', 'Xóa danh mục thành công');
    }
}
