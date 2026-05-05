<?php

namespace App\Models;

/**
 * Model giỏ hàng (Cart) - không lưu vào database, chỉ lưu trong session.
 * Quản lý danh sách sản phẩm, tổng số lượng và tổng giá trị giỏ hàng.
 */
class Cart
{
    /** Danh sách sản phẩm trong giỏ: [id => ['qty', 'price', 'item']] */
    public $items = [];

    /** Tổng số lượng sản phẩm trong giỏ */
    public $totalQty = 0;

    /** Tổng giá trị giỏ hàng (đã tính giá khuyến mãi nếu có) */
    public $totalPrice = 0;

    /**
     * Khởi tạo giỏ hàng.
     * Nếu có giỏ hàng cũ từ session thì khôi phục lại dữ liệu.
     * Hỗ trợ cả dạng object và array (do session có thể serialize khác nhau).
     */
    public function __construct($oldCart = null)
    {
        if (!$oldCart) {
            return;
        }

        // Khôi phục từ dạng array (khi session serialize thành array)
        if (is_array($oldCart)) {
            $this->items      = $oldCart['items'] ?? [];
            $this->totalQty   = $oldCart['totalQty'] ?? 0;
            $this->totalPrice = $oldCart['totalPrice'] ?? 0;

            // Chuyển đổi item từ array sang object nếu cần
            foreach ($this->items as $id => $item) {
                if (isset($item['item']) && is_array($item['item'])) {
                    $this->items[$id]['item'] = (object) $item['item'];
                }
            }
            return;
        }

        // Khôi phục từ dạng object
        $this->items      = $oldCart->items ?? [];
        $this->totalQty   = $oldCart->totalQty ?? 0;
        $this->totalPrice = $oldCart->totalPrice ?? 0;
    }

    /**
     * Thêm một sản phẩm vào giỏ hàng.
     * Nếu sản phẩm đã có thì tăng số lượng lên 1.
     *
     * @param object $item Đối tượng sản phẩm (Food model)
     * @param int    $id   ID sản phẩm (dùng làm key trong mảng items)
     */
    public function add($item, $id)
    {
        // Khởi tạo dữ liệu mặc định cho sản phẩm mới
        $storedItem = [
            'qty'   => 0,
            'price' => 0,
            'item'  => $item,
        ];

        // Nếu sản phẩm đã có trong giỏ thì lấy dữ liệu hiện tại
        if (isset($this->items[$id])) {
            $storedItem = $this->items[$id];
        }

        $storedItem['qty']++;

        // Tính lại tổng giá cho sản phẩm này
        $unitPrice            = $this->getUnitPrice($item);
        $storedItem['price']  = $unitPrice * $storedItem['qty'];

        $this->items[$id]  = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $unitPrice;
    }

    /**
     * Xóa một sản phẩm khỏi giỏ hàng theo ID.
     * Trừ đi số lượng và giá trị tương ứng khỏi tổng.
     */
    public function removeItem($id)
    {
        if (!isset($this->items[$id])) {
            return;
        }

        $this->totalQty   -= $this->items[$id]['qty'];
        $this->totalPrice -= $this->items[$id]['price'];
        unset($this->items[$id]);
    }

    /**
     * Cập nhật số lượng của một sản phẩm trong giỏ hàng.
     * Tính lại tổng số lượng và tổng giá trị.
     *
     * @param int $id  ID sản phẩm
     * @param int $qty Số lượng mới
     */
    public function updateQty($id, $qty)
    {
        if (!isset($this->items[$id])) {
            return;
        }

        $item      = $this->items[$id];
        $unitPrice = $this->getUnitPrice($item['item']);

        // Trừ đi giá trị cũ
        $this->totalQty   -= $item['qty'];
        $this->totalPrice -= $item['price'];

        // Cập nhật số lượng và giá mới
        $item['qty']   = $qty;
        $item['price'] = $unitPrice * $qty;

        $this->items[$id]  = $item;
        $this->totalQty   += $qty;
        $this->totalPrice += $item['price'];
    }

    /**
     * Lấy giá đơn vị của sản phẩm.
     * Ưu tiên giá khuyến mãi (sale_price) nếu có, ngược lại dùng giá gốc.
     *
     * @param object $item Đối tượng sản phẩm
     * @return float Giá đơn vị
     */
    private function getUnitPrice($item)
    {
        if (isset($item->sale_price) && $item->sale_price > 0) {
            return $item->sale_price; // Dùng giá khuyến mãi
        }

        return $item->price; // Dùng giá gốc
    }
}
