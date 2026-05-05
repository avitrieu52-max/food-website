<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller quản lý sản phẩm phía frontend (dành cho người dùng thông thường).
 * Xử lý: danh sách sản phẩm, chi tiết, tạo/sửa/xóa (dành cho người có quyền),
 * lọc theo danh mục và trang quản lý nhanh.
 */
class FoodController extends Controller
{
    /**
     * Danh sách tất cả sản phẩm đang hoạt động.
     * Hỗ trợ lọc theo danh mục và sắp xếp theo giá hoặc ngày tạo.
     */
    public function index(Request $request)
    {
        $query = Food::with('category')->where('status', true);

        // Lọc theo danh mục nếu có
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sắp xếp: giá tăng dần, giảm dần, hoặc mới nhất (mặc định)
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $foods      = $query->paginate(12)->withQueryString(); // Phân trang 12 sản phẩm/trang
        $categories = Category::where('is_active', true)->orderBy('id')->get();

        return view('foods.list', compact('foods', 'categories'));
    }

    /**
     * Hiển thị form tạo sản phẩm mới (frontend).
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('id')->get();
        return view('foods.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới vào database.
     * Xử lý upload ảnh và tạo slug từ input.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'slug'        => 'required|string|max:200|unique:t_food,slug', // Slug phải duy nhất
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:1000',
            'sale_price'  => 'nullable|numeric|min:0|lt:price',            // Giá KM < giá gốc
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'category_id' => 'required|exists:type_products,id',
            'stock'       => 'required|integer|min:0',
        ]);

        $data = $request->only(['name', 'description', 'price', 'sale_price', 'category_id', 'stock']);
        $data['slug']        = Str::slug($request->slug);
        $data['is_featured'] = $request->has('is_featured');
        $data['status']      = $request->has('status');

        // Upload ảnh nếu có
        if ($request->hasFile('image')) {
            $dir  = public_path('images/foods');
            if (!file_exists($dir)) mkdir($dir, 0755, true);
            $name = time() . '.' . $request->image->extension();
            $request->image->move($dir, $name);
            $data['image'] = 'images/foods/' . $name;
        }

        Food::create($data);

        return redirect()->route('foods.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    /**
     * Hiển thị trang chi tiết sản phẩm kèm các sản phẩm liên quan cùng danh mục.
     */
    public function show(Food $food)
    {
        $food->load('category');

        // Lấy tối đa 4 sản phẩm cùng danh mục (trừ sản phẩm hiện tại)
        $relatedProducts = Food::with('category')
            ->where('category_id', $food->category_id)
            ->where('id', '!=', $food->id)
            ->limit(4)->get();

        return view('foods.show', compact('food', 'relatedProducts'));
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm (frontend).
     */
    public function edit(Food $food)
    {
        $categories = Category::where('is_active', true)->orderBy('id')->get();
        return view('foods.edit', compact('food', 'categories'));
    }

    /**
     * Cập nhật thông tin sản phẩm.
     * Nếu có ảnh mới thì xóa ảnh cũ và lưu ảnh mới.
     */
    public function update(Request $request, Food $food)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'slug'        => 'required|string|max:200|unique:t_food,slug,' . $food->id, // Bỏ qua unique của chính nó
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:1000',
            'sale_price'  => 'nullable|numeric|min:0|lt:price',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'category_id' => 'required|exists:type_products,id',
            'stock'       => 'required|integer|min:0',
        ]);

        $data = $request->only(['name', 'description', 'price', 'sale_price', 'category_id', 'stock']);
        $data['slug']        = Str::slug($request->slug);
        $data['is_featured'] = $request->has('is_featured');
        $data['status']      = $request->has('status');

        // Nếu có ảnh mới: xóa ảnh cũ rồi lưu ảnh mới
        if ($request->hasFile('image')) {
            if ($food->image && file_exists(public_path($food->image))) {
                unlink(public_path($food->image));
            }
            $dir  = public_path('images/foods');
            if (!file_exists($dir)) mkdir($dir, 0755, true);
            $name = time() . '.' . $request->image->extension();
            $request->image->move($dir, $name);
            $data['image'] = 'images/foods/' . $name;
        }

        $food->update($data);

        return redirect()->route('foods.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Xóa sản phẩm và file ảnh liên quan.
     */
    public function destroy(Food $food)
    {
        if ($food->image && file_exists(public_path($food->image))) {
            unlink(public_path($food->image));
        }
        $food->delete();

        return redirect()->route('foods.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    /**
     * Trang danh sách sản phẩm theo danh mục.
     * Được gọi khi người dùng click vào một danh mục trong menu.
     */
    public function showByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        // Lấy tất cả sản phẩm đang hoạt động thuộc danh mục này
        $foods = Food::with('category')
            ->where('category_id', $categoryId)
            ->where('status', true)
            ->get();

        return view('foods.category', compact('foods', 'category'));
    }

    /**
     * Trang quản lý nhanh sản phẩm (hiển thị toàn bộ danh sách không phân trang).
     */
    public function manage()
    {
        $foods = Food::with('category')->orderBy('created_at', 'desc')->get();
        return view('foods.manage', compact('foods'));
    }
}
