<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller quản lý danh mục sản phẩm (Loại sản phẩm) trong trang admin.
 * Xử lý CRUD: danh sách, thêm, sửa, xóa danh mục.
 */
class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả danh mục kèm số lượng sản phẩm trong mỗi danh mục.
     */
    public function getCateList()
    {
        // withCount('foods') tự động đếm số sản phẩm thuộc mỗi danh mục
        $cates = Category::withCount('foods')->orderBy('id')->paginate(15);
        return view('admin.category.cate-list', compact('cates'));
    }

    /**
     * Hiển thị form thêm danh mục mới.
     */
    public function getCateAdd()
    {
        return view('admin.category.cate-add');
    }

    /**
     * Xử lý lưu danh mục mới vào database.
     * Hỗ trợ upload ảnh đại diện cho danh mục.
     */
    public function postCateAdd(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], ['name.required' => 'Vui lòng nhập tên danh mục']);

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . time(), // Tạo slug duy nhất từ tên
            'description' => $request->description,
            'is_active'   => $request->has('is_active') ? 1 : 0,
        ];

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image')) {
            $dir  = public_path('images/categories');
            if (!file_exists($dir)) mkdir($dir, 0755, true); // Tạo thư mục nếu chưa có
            $name = time() . '.' . $request->image->extension();
            $request->image->move($dir, $name);
            $data['image'] = 'images/categories/' . $name;
        }

        Category::create($data);
        return redirect()->route('admin.getCateList')->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa danh mục.
     */
    public function getCateEdit($id)
    {
        $cate = Category::findOrFail($id);
        return view('admin.category.cate-edit', compact('cate'));
    }

    /**
     * Xử lý cập nhật thông tin danh mục.
     * Nếu có ảnh mới thì xóa ảnh cũ và lưu ảnh mới.
     */
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

        // Nếu có ảnh mới: xóa ảnh cũ rồi lưu ảnh mới
        if ($request->hasFile('image')) {
            if ($cate->image && file_exists(public_path($cate->image))) {
                unlink(public_path($cate->image)); // Xóa file ảnh cũ
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

    /**
     * Xóa danh mục.
     * Không cho phép xóa nếu danh mục đang có sản phẩm.
     */
    public function getCateDelete($id)
    {
        $cate = Category::withCount('foods')->findOrFail($id);

        // Kiểm tra còn sản phẩm không trước khi xóa
        if ($cate->foods_count > 0) {
            return redirect()->back()->with('error', 'Không thể xóa danh mục đang có ' . $cate->foods_count . ' sản phẩm!');
        }

        // Xóa ảnh đại diện nếu có
        if ($cate->image && file_exists(public_path($cate->image))) {
            unlink(public_path($cate->image));
        }

        $cate->delete();
        return redirect()->route('admin.getCateList')->with('success', 'Xóa danh mục thành công!');
    }
}
