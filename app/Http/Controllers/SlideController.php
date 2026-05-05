<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('order', 'asc')->paginate(15);
        return view('admin.slide.list', compact('slides'));
    }

    public function create()
    {
        return view('admin.slide.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'link'        => 'nullable|url|max:500',
            'button_text' => 'nullable|string|max:100',
            'order'       => 'required|integer|min:0',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'image.required' => 'Vui lòng chọn hình ảnh',
            'image.image'    => 'File phải là hình ảnh',
            'order.required' => 'Vui lòng nhập thứ tự',
        ]);

        $data = $request->only(['title', 'subtitle', 'description', 'link', 'button_text', 'order']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

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

    public function edit($id)
    {
        $slide = Slide::findOrFail($id);
        return view('admin.slide.edit', compact('slide'));
    }

    public function update(Request $request, $id)
    {
        $slide = Slide::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'link'        => 'nullable|url|max:500',
            'button_text' => 'nullable|string|max:100',
            'order'       => 'required|integer|min:0',
        ]);

        $data = $request->only(['title', 'subtitle', 'description', 'link', 'button_text', 'order']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($slide->image && file_exists(public_path($slide->image))) {
                unlink(public_path($slide->image));
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

    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);
        if ($slide->image && file_exists(public_path($slide->image))) {
            unlink(public_path($slide->image));
        }
        $slide->delete();

        return redirect()->route('admin.slide.list')->with('success', 'Xóa slide thành công!');
    }
}
