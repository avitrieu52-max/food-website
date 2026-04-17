<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Http\Requests\FoodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::where('status', true);

        // Lọc theo danh mục nếu có tham số category trên URL
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Xử lý sắp xếp
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // withQueryString() giúp giữ lại các tham số lọc khi chuyển trang
        $foods = $query->paginate(12)->withQueryString();
        $categories = Food::getCategories();
        
        return view('foods.list', compact('foods', 'categories'));
    }

    public function create()
    {
        $categories = Food::getCategories();
        return view('foods.create', compact('categories'));
    }

    public function store(FoodRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $imageDir = public_path('images/foods');
            if (! file_exists($imageDir)) {
                mkdir($imageDir, 0755, true);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($imageDir, $imageName);
            $data['image'] = 'images/foods/' . $imageName;
        }
        
        $data['slug'] = Str::slug($data['slug']);
        $data['is_featured'] = $request->has('is_featured');
        $data['status'] = $request->has('status');
        
        Food::create($data);
        
        return redirect()->route('foods.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function show(Food $food)
    {
        $relatedProducts = Food::where('category', $food->category)
            ->where('id', '!=', $food->id)
            ->limit(4)
            ->get();
            
        return view('foods.show', compact('food', 'relatedProducts'));
    }

    public function edit(Food $food)
    {
        $categories = Food::getCategories();
        return view('foods.edit', compact('food', 'categories'));
    }

    public function update(FoodRequest $request, Food $food)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($food->image && file_exists(public_path($food->image))) {
                unlink(public_path($food->image));
            }
            
            $imageDir = public_path('images/foods');
            if (! file_exists($imageDir)) {
                mkdir($imageDir, 0755, true);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($imageDir, $imageName);
            $data['image'] = 'images/foods/' . $imageName;
        }
        
        $data['slug'] = Str::slug($data['slug']);
        $data['is_featured'] = $request->has('is_featured');
        $data['status'] = $request->has('status');
        
        $food->update($data);
        
        return redirect()->route('foods.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Food $food)
    {
        if ($food->image && file_exists(public_path($food->image))) {
            unlink(public_path($food->image));
        }
        
        $food->delete();
        
        return redirect()->route('foods.index')
            ->with('success', 'Xóa sản phẩm thành công!');
    }
    
    public function showByCategory($category)
    {
        $foods = Food::where('category', $category)->get();
        $categoryLabel = Food::getCategories()[$category] ?? $category;
        
        return view('foods.category', compact('foods', 'categoryLabel', 'category'));
    }
    
    public function manage()
    {
        $foods = Food::orderBy('created_at', 'desc')->get();
        return view('foods.manage', compact('foods'));
    }
}