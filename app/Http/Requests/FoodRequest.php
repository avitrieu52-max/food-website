<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $foodId = $this->food?->id;
        
        return [
            'name' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:t_food,slug,' . $foodId,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'category' => 'required|in:hoa_qua,thuc_pham_huu_co,thuc_pham_kho,san_pham_noi_bat',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'status' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.max' => 'Tên sản phẩm không được vượt quá 200 ký tự',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'price.required' => 'Giá sản phẩm không được để trống',
            'price.min' => 'Giá sản phẩm phải lớn hơn 1.000đ',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc',
            'category.required' => 'Vui lòng chọn danh mục',
            'category.in' => 'Danh mục không hợp lệ',
            'stock.min' => 'Số lượng tồn kho không thể âm',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, gif',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB'
        ];
    }
}