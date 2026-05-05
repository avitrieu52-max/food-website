<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function getCateList()
    {
        $cates = Category::withCount('foods')->orderBy('id')->paginate(15);
        return view('admin.category.cate-list', compact('cates'));
    }

    public function getCateAdd()
    {
        return view('admin.category.cate-add');
    }

    public function postCateAdd(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], ['name.required' => 'Vui lòng nhập tên danh mục']);

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'is_active'   => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->hasFile('image')) {
            $dir  = public_path('images/categories');
            if (!file_exists($dir)) mkdir($dir, 0755, true);
            $name = time() . '.' . $request->image->extension();
            $request->image->move($dir, $name);
            $data['image'] = 'images/categories/' . $name;
        }

        Category::create($data);
        return redirect()->route('admin.getCateList')->with('success', 'Thêm danh mục thành công!');
    }

    public function getCateEdit($id)
    {
        $cate = Category::findOrFail($id);
        return view('admin.category.cate-edit', compact('cate'));
    }

    public function postCateEdit(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], ['name.required' => 'Vui lòng nhập tên danh mục']);

        $cate = Category::findOrFail($id);
        $data = [
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->hasFile('image')) {
            if ($cate->image && file_exists(public_path($cate->image))) {
                unlink(public_path($cate->image));
            }
            $dir  = public_path('images/categories');
            if (!file_exists($dir)) mkdir($dir, 0755, true);
            $name = time() . '.' . $request->image->extension();
            $request->image->move($dir, $name);
            $data['image'] = 'images/categories/' . $name;
        }

        $cate->update($data);
        return redirect()->route('admin.getCateList')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function getCateDelete($id)
    {
        $cate = Category::withCount('foods')->findOrFail($id);
        if ($cate->foods_count > 0) {
            return redirect()->back()->with('error', 'Không thể xóa danh mục đang có ' . $cate->foods_count . ' sản phẩm!');
        }
        if ($cate->image && file_exists(public_path($cate->image))) {
            unlink(public_path($cate->image));
        }
        $cate->delete();
        return redirect()->route('admin.getCateList')->with('success', 'Xóa danh mục thành công!');
    }
}
