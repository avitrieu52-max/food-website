<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request xác thực dữ liệu sản phẩm (Food).
 * Dùng cho cả tạo mới và cập nhật sản phẩm.
 */
class FoodRequest extends FormRequest
{
    /**
     * Cho phép tất cả người dùng sử dụng request này.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Các quy tắc xác thực dữ liệu đầu vào.
     */
    public function rules(): array
    {
        // Lấy ID sản phẩm hiện tại (dùng khi cập nhật để bỏ qua unique của chính nó)
        $foodId = $this->food?->id;
        
        return [
            'name'        => 'required|string|max:200',                              // Tên sản phẩm bắt buộc
            'slug'        => 'required|string|max:200|unique:t_food,slug,' . $foodId, // Slug duy nhất
            'description' => 'nullable|string',                                       // Mô tả không bắt buộc
            'price'       => 'required|numeric|min:1000',                             // Giá gốc tối thiểu 1.000đ
            'sale_price'  => 'nullable|numeric|min:0|lt:price',                       // Giá KM phải nhỏ hơn giá gốc
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',        // Ảnh tối đa 2MB
            'category'    => 'required|in:ao_nam,ao_nu,quan_nam,quan_nu,vay_dam,phu_kien', // Danh mục hợp lệ
            'stock'       => 'required|integer|min:0',                                // Tồn kho không âm
            'is_featured' => 'boolean',                                               // Sản phẩm nổi bật
            'status'      => 'boolean',                                               // Trạng thái hiển thị
        ];
    }

    /**
     * Thông báo lỗi tùy chỉnh bằng tiếng Việt.
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'Tên sản phẩm không được để trống',
            'name.max'          => 'Tên sản phẩm không được vượt quá 200 ký tự',
            'slug.required'     => 'Slug không được để trống',
            'slug.unique'       => 'Slug đã tồn tại',
            'price.required'    => 'Giá sản phẩm không được để trống',
            'price.min'         => 'Giá sản phẩm phải lớn hơn 1.000đ',
            'sale_price.lt'     => 'Giá khuyến mãi phải nhỏ hơn giá gốc',
            'category.required' => 'Vui lòng chọn danh mục',
            'category.in'       => 'Danh mục không hợp lệ',
            'stock.min'         => 'Số lượng tồn kho không thể âm',
            'image.image'       => 'File phải là hình ảnh',
            'image.mimes'       => 'Hình ảnh phải có định dạng: jpg, jpeg, png, gif',
            'image.max'         => 'Kích thước hình ảnh không được vượt quá 2MB',
        ];
    }
}
