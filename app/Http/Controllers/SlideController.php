<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

/**
 * Controller quản lý slide/banner trang chủ trong admin.
 * Xử lý CRUD: danh sách, thêm, sửa, xóa slide.
 */
class SlideController extends Controller
{
    /**
     * Danh sách tất cả slide, sắp xếp theo thứ tự hiển thị (order).
     */
    public function index()
    {
        $slides = Slide::orderBy('order', 'asc')->paginate(15);
        return view('admin.slide.list', compact('slides'));
    }

    /**
     * Hiển thị form thêm slide mới.
     */
    public function create()
    {
        return view('admin.slide.create');
    }

    /**
     * Lưu slide mới vào database kèm upload ảnh.
     * Ảnh là bắt buộc khi tạo slide mới.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Ảnh bắt buộc
            'link'        => 'nullable|url|max:500',                           // URL liên kết khi click
            'button_text' => 'nullable|string|max:100',                        // Nội dung nút CTA
            'order'       => 'required|integer|min:0',                         // Thứ tự hiển thị
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'image.required' => 'Vui lòng chọn hình ảnh',
            'image.image'    => 'File phải là hình ảnh',
            'order.required' => 'Vui lòng nhập thứ tự',
        ]);

        $data = $request->only(['title', 'subtitle', 'description', 'link', 'button_text', 'order']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Upload ảnh slide vào thư mục public/images/slides
        if ($request->hasFile('image')) {
            $imageDir = public_path('images/slides');
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0755, true);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($imageDir, $imageName);
            $data['image'] = 'images/slides/' . $imageName;
        }

        Slide::create($data);

        return redirect()->route('admin.slide.list')->with('success', 'Thêm slide thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa slide.
     */
    public function edit($id)
    {
        $slide = Slide::findOrFail($id);
        return view('admin.slide.edit', compact('slide'));
    }

    /**
     * Cập nhật thông tin slide.
     * Nếu có ảnh mới thì xóa ảnh cũ và lưu ảnh mới.
     */
    public function update(Request $request, $id)
    {
        $slide = Slide::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Ảnh không bắt buộc khi sửa
            'link'        => 'nullable|url|max:500',
            'button_text' => 'nullable|string|max:100',
            'order'       => 'required|integer|min:0',
        ]);

        $data = $request->only(['title', 'subtitle', 'description', 'link', 'button_text', 'order']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Nếu có ảnh mới: xóa ảnh cũ rồi lưu ảnh mới
        if ($request->hasFile('image')) {
            if ($slide->image && file_exists(public_path($slide->image))) {
                unlink(public_path($slide->image)); // Xóa file ảnh cũ
            }
            $imageDir = public_path('images/slides');
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0755, true);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($imageDir, $imageName);
            $data['image'] = 'images/slides/' . $imageName;
        }

        $slide->update($data);

        return redirect()->route('admin.slide.list')->with('success', 'Cập nhật slide thành công!');
    }

    /**
     * Xóa slide và file ảnh liên quan.
     */
    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);

        // Xóa file ảnh nếu tồn tại
        if ($slide->image && file_exists(public_path($slide->image))) {
            unlink(public_path($slide->image));
        }
        $slide->delete();

        return redirect()->route('admin.slide.list')->with('success', 'Xóa slide thành công!');
    }
}
