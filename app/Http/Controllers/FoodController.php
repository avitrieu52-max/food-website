<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::with('category')->where('status', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $foods      = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('id')->get();

        return view('foods.list', compact('foods', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('id')->get();
        return view('foods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'slug'        => 'required|string|max:200|unique:t_food,slug',
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

    public function show(Food $food)
    {
        $food->load('category');
        $relatedProducts = Food::with('category')
            ->where('category_id', $food->category_id)
            ->where('id', '!=', $food->id)
            ->limit(4)->get();

        return view('foods.show', compact('food', 'relatedProducts'));
    }

    public function edit(Food $food)
    {
        $categories = Category::where('is_active', true)->orderBy('id')->get();
        return view('foods.edit', compact('food', 'categories'));
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'slug'        => 'required|string|max:200|unique:t_food,slug,' . $food->id,
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

    public function destroy(Food $food)
    {
        if ($food->image && file_exists(public_path($food->image))) {
            unlink(public_path($food->image));
        }
        $food->delete();

        return redirect()->route('foods.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    // Trang loại sản phẩm — dùng category_id
    public function showByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $foods    = Food::with('category')
            ->where('category_id', $categoryId)
            ->where('status', true)
            ->get();

        return view('foods.category', compact('foods', 'category'));
    }

    public function manage()
    {
        $foods = Food::with('category')->orderBy('created_at', 'desc')->get();
        return view('foods.manage', compact('foods'));
    }
}
