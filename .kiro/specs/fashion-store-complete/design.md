# Design Document: fashion-store-complete

## Overview

Tài liệu này mô tả thiết kế kỹ thuật cho 10 tính năng mới của hệ thống AT10 Fashion, xây dựng trên nền tảng Laravel 11, MySQL, Bootstrap 5 và Blade templates.

Các tính năng được thiết kế để tích hợp liền mạch với codebase hiện tại, tái sử dụng các pattern đã có (session-based cart, Admin middleware, Blade layouts) và mở rộng schema database theo hướng backward-compatible.

### Phạm vi tính năng

| # | Tính năng | Loại |
|---|-----------|------|
| 1 | Slide/Banner CRUD (Admin) | Admin feature |
| 2 | Wishlist (sản phẩm yêu thích) | Customer feature |
| 3 | Email xác nhận đặt hàng | Notification |
| 4 | Email cập nhật trạng thái đơn hàng | Notification |
| 5 | Messenger Chat Plugin | Frontend integration |
| 6 | Hệ thống Liên hệ | Customer + Admin feature |
| 7 | Trang tài khoản khách hàng | Customer feature |
| 8 | Mã giảm giá (Coupon) | Business logic |
| 9 | Phí vận chuyển (Shipping Fee) | Business logic |
| 10 | Tích hợp Coupon + Shipping vào Bill | Data integrity |

---

## Architecture

### Kiến trúc tổng thể

Hệ thống tiếp tục theo mô hình **MVC monolith** của Laravel:

```
Browser ──► Routes (web.php)
              │
              ▼
         Controllers
         ├── SlideController        (Req 1)
         ├── WishlistController     (Req 2)
         ├── ContactController      (Req 6)
         ├── AccountController      (Req 7)
         ├── CouponController       (Req 8)
         ├── ShippingFeeController  (Req 9)
         └── AdminController        (extended: Req 1,6,8,9)
              │
              ▼
         Models / Services
         ├── Slide, Wishlist, Contact
         ├── Coupon, ShippingFee
         ├── CouponService          (discount calculation)
         ├── ShippingFeeService     (fee calculation)
         └── Bill (extended)
              │
              ▼
         Database (MySQL)
         ├── slides
         ├── wishlists
         ├── contacts
         ├── coupons
         ├── shipping_fees
         └── bills (ALTER: +coupon_code, +discount_amount, +shipping_fee, +status)
```

### Email Architecture

```
Event (checkout / status update)
    │
    ▼
Mailable Class (OrderConfirmationMail / OrderStatusMail / ContactReplyMail)
    │
    ▼
Blade Email Template
    │
    ▼
SMTP (config/mail.php)
```

### Session Flow cho Coupon

```
POST /coupon/apply
    │
    ▼
CouponController::apply()
    ├── Validate coupon (CouponService)
    ├── Store in session: session(['applied_coupon' => $coupon])
    └── Return JSON {success, discount_amount, new_total}

POST /checkout
    │
    ▼
PageController::postCheckout()
    ├── Read session('applied_coupon')
    ├── Calculate final total
    ├── Save to Bill (coupon_code, discount_amount, shipping_fee)
    ├── Increment coupon used_count
    └── Clear session('applied_coupon')
```

---

## Components and Interfaces

### 1. SlideController

**File:** `app/Http/Controllers/SlideController.php`

| Method | Route | Mô tả |
|--------|-------|-------|
| `index()` | GET `/admin/slide/danhsach` | Danh sách slides |
| `create()` | GET `/admin/slide/them` | Form thêm slide |
| `store(Request)` | POST `/admin/slide/them` | Lưu slide mới |
| `edit($id)` | GET `/admin/slide/sua/{id}` | Form sửa slide |
| `update(Request, $id)` | POST `/admin/slide/sua/{id}` | Cập nhật slide |
| `destroy($id)` | GET `/admin/slide/xoa/{id}` | Xóa slide |

### 2. WishlistController

**File:** `app/Http/Controllers/WishlistController.php`

| Method | Route | Mô tả |
|--------|-------|-------|
| `index()` | GET `/yeu-thich` | Trang wishlist của customer |
| `toggle(Request, $foodId)` | POST `/yeu-thich/toggle/{foodId}` | Thêm/xóa khỏi wishlist |
| `remove($foodId)` | GET `/yeu-thich/xoa/{foodId}` | Xóa khỏi wishlist (non-AJAX) |

### 3. Mailable Classes

| Class | File | Trigger |
|-------|------|---------|
| `OrderConfirmationMail` | `app/Mail/OrderConfirmationMail.php` | Sau khi checkout thành công |
| `OrderStatusMail` | `app/Mail/OrderStatusMail.php` | Sau khi admin cập nhật trạng thái |
| `ContactReplyMail` | `app/Mail/ContactReplyMail.php` | Sau khi admin phản hồi liên hệ |

### 4. ContactController

**File:** `app/Http/Controllers/ContactController.php`

| Method | Route | Mô tả |
|--------|-------|-------|
| `store(Request)` | POST `/lien-he` | Lưu tin nhắn liên hệ |
| `adminIndex()` | GET `/admin/lienhe/danhsach` | Danh sách liên hệ (admin) |
| `adminReplyForm($id)` | GET `/admin/lienhe/phanhoi/{id}` | Form phản hồi |
| `adminReply(Request, $id)` | POST `/admin/lienhe/phanhoi/{id}` | Gửi phản hồi |

### 5. AccountController

**File:** `app/Http/Controllers/AccountController.php`

| Method | Route | Mô tả |
|--------|-------|-------|
| `index()` | GET `/tai-khoan` | Trang tài khoản (lịch sử đơn + thông tin) |
| `orderDetail($id)` | GET `/tai-khoan/don-hang/{id}` | Chi tiết đơn hàng |
| `updateProfile(Request)` | POST `/tai-khoan/cap-nhat` | Cập nhật thông tin cá nhân |
| `changePassword(Request)` | POST `/tai-khoan/doi-mat-khau` | Đổi mật khẩu |

### 6. CouponController

**File:** `app/Http/Controllers/CouponController.php`

| Method | Route | Mô tả |
|--------|-------|-------|
| `apply(Request)` | POST `/coupon/apply` | Áp dụng mã giảm giá (AJAX) |
| `remove()` | POST `/coupon/remove` | Xóa mã giảm giá khỏi session |
| `adminIndex()` | GET `/admin/magiamgia/danhsach` | Danh sách coupon |
| `adminCreate()` | GET `/admin/magiamgia/them` | Form thêm coupon |
| `adminStore(Request)` | POST `/admin/magiamgia/them` | Lưu coupon |
| `adminEdit($id)` | GET `/admin/magiamgia/sua/{id}` | Form sửa coupon |
| `adminUpdate(Request, $id)` | POST `/admin/magiamgia/sua/{id}` | Cập nhật coupon |
| `adminDelete($id)` | GET `/admin/magiamgia/xoa/{id}` | Xóa coupon |

### 7. ShippingFeeController

**File:** `app/Http/Controllers/ShippingFeeController.php`

| Method | Route | Mô tả |
|--------|-------|-------|
| `adminIndex()` | GET `/admin/phivanhuyen/danhsach` | Danh sách quy tắc phí |
| `adminCreate()` | GET `/admin/phivanhuyen/them` | Form thêm quy tắc |
| `adminStore(Request)` | POST `/admin/phivanhuyen/them` | Lưu quy tắc |
| `adminEdit($id)` | GET `/admin/phivanhuyen/sua/{id}` | Form sửa quy tắc |
| `adminUpdate(Request, $id)` | POST `/admin/phivanhuyen/sua/{id}` | Cập nhật quy tắc |
| `adminDelete($id)` | GET `/admin/phivanhuyen/xoa/{id}` | Xóa quy tắc (kiểm tra last rule) |

### 8. Service Classes

**CouponService** (`app/Services/CouponService.php`):
- `validate(string $code, float $cartTotal): array` — Kiểm tra coupon hợp lệ, trả về `['valid' => bool, 'coupon' => Coupon|null, 'error' => string|null]`
- `calculateDiscount(Coupon $coupon, float $cartTotal): float` — Tính discount amount

**ShippingFeeService** (`app/Services/ShippingFeeService.php`):
- `calculate(float $cartTotal): float` — Tính phí vận chuyển dựa trên cart total

---

## Data Models

### Bảng mới

#### `slides`

```sql
CREATE TABLE slides (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    subtitle    VARCHAR(255) NULL,
    description TEXT NULL,
    image       VARCHAR(500) NOT NULL,
    link        VARCHAR(500) NULL,
    button_text VARCHAR(100) NULL DEFAULT 'Xem ngay',
    `order`     TINYINT UNSIGNED NOT NULL DEFAULT 0,
    is_active   BOOLEAN NOT NULL DEFAULT TRUE,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL
);
```

**Model:** `app/Models/Slide.php`
```php
protected $table = 'slides';
protected $fillable = ['title', 'subtitle', 'description', 'image', 'link', 'button_text', 'order', 'is_active'];
protected $casts = ['is_active' => 'boolean', 'order' => 'integer'];
```

---

#### `wishlists`

```sql
CREATE TABLE wishlists (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    BIGINT UNSIGNED NOT NULL,
    food_id    BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY wishlists_user_food_unique (user_id, food_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (food_id) REFERENCES t_food(id) ON DELETE CASCADE
);
```

**Model:** `app/Models/Wishlist.php`
```php
protected $table = 'wishlists';
protected $fillable = ['user_id', 'food_id'];

// Relationships
public function user()    { return $this->belongsTo(User::class); }
public function food()    { return $this->belongsTo(Food::class, 'food_id'); }
```

**Thêm vào User model:**
```php
public function wishlists() { return $this->hasMany(Wishlist::class); }
public function wishlistFoodIds() { return $this->wishlists()->pluck('food_id')->toArray(); }
```

**Thêm vào Food model:**
```php
public function wishlists() { return $this->hasMany(Wishlist::class, 'food_id'); }
```

---

#### `contacts`

```sql
CREATE TABLE contacts (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    email       VARCHAR(255) NOT NULL,
    message     TEXT NOT NULL,
    status      ENUM('unread', 'replied') NOT NULL DEFAULT 'unread',
    replied_at  TIMESTAMP NULL,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL
);
```

**Model:** `app/Models/Contact.php`
```php
protected $table = 'contacts';
protected $fillable = ['name', 'email', 'message', 'status', 'replied_at'];
protected $casts = ['replied_at' => 'datetime'];
```

---

#### `coupons`

```sql
CREATE TABLE coupons (
    id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code              VARCHAR(50) NOT NULL UNIQUE,
    discount_type     ENUM('percent', 'fixed') NOT NULL,
    discount_value    DECIMAL(10, 2) NOT NULL,
    min_order_value   DECIMAL(16, 2) NOT NULL DEFAULT 0,
    max_uses          INT UNSIGNED NULL COMMENT 'NULL = unlimited',
    used_count        INT UNSIGNED NOT NULL DEFAULT 0,
    expires_at        TIMESTAMP NULL,
    is_active         BOOLEAN NOT NULL DEFAULT TRUE,
    created_at        TIMESTAMP NULL,
    updated_at        TIMESTAMP NULL
);
```

**Model:** `app/Models/Coupon.php`
```php
protected $table = 'coupons';
protected $fillable = ['code', 'discount_type', 'discount_value', 'min_order_value', 'max_uses', 'used_count', 'expires_at', 'is_active'];
protected $casts = [
    'discount_value'  => 'decimal:2',
    'min_order_value' => 'decimal:2',
    'expires_at'      => 'datetime',
    'is_active'       => 'boolean',
];
```

---

#### `shipping_fees`

```sql
CREATE TABLE shipping_fees (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                  VARCHAR(100) NOT NULL,
    min_order_value       DECIMAL(16, 2) NOT NULL DEFAULT 0,
    fee                   DECIMAL(10, 2) NOT NULL DEFAULT 0,
    is_active             BOOLEAN NOT NULL DEFAULT TRUE,
    created_at            TIMESTAMP NULL,
    updated_at            TIMESTAMP NULL
);

-- Seed data mặc định
INSERT INTO shipping_fees (name, min_order_value, fee, is_active)
VALUES
  ('Miễn phí vận chuyển', 500000, 0, 1),
  ('Phí vận chuyển tiêu chuẩn', 0, 30000, 1);
```

**Logic:** Hệ thống chọn rule có `min_order_value` cao nhất mà `cart_total >= min_order_value`. Nếu không có rule nào khớp, dùng rule có `min_order_value = 0`.

**Model:** `app/Models/ShippingFee.php`
```php
protected $table = 'shipping_fees';
protected $fillable = ['name', 'min_order_value', 'fee', 'is_active'];
protected $casts = ['min_order_value' => 'decimal:2', 'fee' => 'decimal:2', 'is_active' => 'boolean'];
```

---

### Bảng sửa đổi

#### `bills` — ALTER TABLE

```sql
ALTER TABLE bills
    ADD COLUMN status         VARCHAR(20)    NOT NULL DEFAULT 'pending' AFTER note,
    ADD COLUMN coupon_code    VARCHAR(50)    NULL AFTER status,
    ADD COLUMN discount_amount DECIMAL(16,2) NOT NULL DEFAULT 0 AFTER coupon_code,
    ADD COLUMN shipping_fee   DECIMAL(16,2)  NOT NULL DEFAULT 0 AFTER discount_amount;
```

> **Lưu ý:** `status` đã được thêm trong migration `2026_05_04_000001_add_status_to_bills_table.php`. Migration mới chỉ cần thêm `coupon_code`, `discount_amount`, `shipping_fee`.

**Cập nhật Bill model:**
```php
protected $fillable = [
    'id_customer', 'date_order', 'total', 'payment', 'note',
    'status', 'coupon_code', 'discount_amount', 'shipping_fee',
];
```

---

### Relationships Summary

```
User ──hasMany──► Wishlist ──belongsTo──► Food
User ──hasMany──► Bill (via email match với Customer)

Bill ──belongsTo──► Customer
Bill ──hasMany──►  BillDetail ──belongsTo──► Food
Bill ──belongsTo──► Coupon (via coupon_code, loose coupling)
```

---

### View Files

#### Admin Views (mới)

```
resources/views/admin/
├── slide/
│   ├── list.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── contact/
│   ├── list.blade.php
│   └── reply.blade.php
├── coupon/
│   ├── list.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── shipping/
    ├── list.blade.php
    ├── create.blade.php
    └── edit.blade.php
```

#### Customer Views (mới)

```
resources/views/
├── wishlist.blade.php
├── account/
│   ├── index.blade.php        (tabs: lịch sử đơn + thông tin cá nhân)
│   └── order-detail.blade.php
└── emails/
    ├── order_confirmation.blade.php
    ├── order_status_update.blade.php
    └── contact_reply.blade.php
```

#### Views sửa đổi

```
resources/views/
├── layouts/app.blade.php      (thêm Messenger plugin, wishlist icon, account link)
├── checkout.blade.php         (thêm coupon input, shipping fee display)
├── cart.blade.php             (thêm shipping fee preview)
├── foods/index.blade.php      (thêm slides carousel, wishlist button)
├── foods/show.blade.php       (thêm wishlist button)
├── foods/_product_item.blade.php (thêm wishlist button)
└── admin/order/detail.blade.php  (thêm coupon_code, discount_amount, shipping_fee)
```

---

## Correctness Properties


*A property is a characteristic or behavior that should hold true across all valid executions of a system — essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Slide creation round-trip

*For any* valid slide data (title, image path, order, is_active), creating a slide and then querying the database should return a record with all the same field values that were submitted.

**Validates: Requirements 1.2, 1.4**

---

### Property 2: Invalid slide data is rejected

*For any* slide submission missing a required field (title or image), the system should reject the submission, return validation errors, and leave the slides table unchanged.

**Validates: Requirements 1.3, 1.5**

---

### Property 3: Slide delete removes record

*For any* existing slide, deleting it should result in the record no longer existing in the database.

**Validates: Requirements 1.6**

---

### Property 4: Homepage only shows active slides in order

*For any* set of slides with mixed `is_active` values and arbitrary `order` values, the homepage carousel should contain exactly the slides where `is_active = true`, rendered in ascending `order` sequence.

**Validates: Requirements 1.7, 1.8**

---

### Property 5: Wishlist toggle round-trip

*For any* authenticated customer and any product, toggling the wishlist button twice (add then remove) should return the wishlist to its original state — the product should not be in the wishlist after two toggles, and should be in the wishlist after one toggle from an empty state.

**Validates: Requirements 2.2, 2.3, 2.6**

---

### Property 6: Wishlist heart icon reflects state

*For any* authenticated customer, for any product in their wishlist the rendered product card should show a filled heart icon, and for any product not in their wishlist the rendered product card should show an unfilled heart icon.

**Validates: Requirements 2.7**

---

### Property 7: Order confirmation email contains all required fields

*For any* completed checkout with random products, quantities, coupon, and shipping fee, the dispatched `OrderConfirmationMail` should contain: order ID, product list with quantities and unit prices, subtotal, discount amount, shipping fee, delivery address, and payment method.

**Validates: Requirements 3.1, 3.4, 9.6, 10.3**

---

### Property 8: Order status email dispatched on every status update

*For any* bill with a customer having a valid email address, updating the bill's status should dispatch exactly one `OrderStatusMail` to that customer's email address containing the new status label in Vietnamese, the order ID, and the update date.

**Validates: Requirements 4.1, 4.2, 4.4**

---

### Property 9: Contact form submission creates unread record

*For any* valid contact form submission (non-empty name, valid email, non-empty message up to 1000 chars), the system should save a Contact record with `status = 'unread'`, `replied_at = null`, and all submitted field values preserved exactly.

**Validates: Requirements 6.2**

---

### Property 10: Invalid contact form data is rejected

*For any* contact form submission with at least one invalid field (empty name, invalid email format, or empty message), the system should reject the submission and leave the contacts table unchanged.

**Validates: Requirements 6.3**

---

### Property 11: Admin reply updates contact status and sends email

*For any* unread contact record and any non-empty reply message, submitting the admin reply form should: send a `ContactReplyMail` to the contact's email address, update the contact's `status` to `'replied'`, and set `replied_at` to a non-null timestamp.

**Validates: Requirements 6.6**

---

### Property 12: Contact status label rendering

*For any* contact record, the rendered admin list should display "Chưa phản hồi" (warning badge) when `status = 'unread'` and "Đã phản hồi" (success badge) when `status = 'replied'`.

**Validates: Requirements 6.9**

---

### Property 13: Order history shows all customer orders in descending order

*For any* authenticated customer with N orders (N ≥ 0), the account page order history section should display exactly N orders, ordered by `created_at` descending, each showing the correct order ID, date, total, and status label.

**Validates: Requirements 7.2, 7.4**

---

### Property 14: Profile update round-trip

*For any* valid profile update submission (non-empty name, phone ≤ 20 chars, address ≤ 500 chars), the system should update the User record and the account page should subsequently display the new values.

**Validates: Requirements 7.6**

---

### Property 15: Password change with correct current password succeeds

*For any* user and any new password of at least 6 characters, submitting the password change form with the correct current password should update the stored password hash such that `Hash::check(newPassword, user.password)` returns true.

**Validates: Requirements 7.8**

---

### Property 16: Coupon validation rejects all invalid states

*For any* coupon code lookup, the system should reject the coupon if any of the following conditions hold: `is_active = false`, `expires_at` is in the past, `used_count >= max_uses` (when max_uses is set), or `cart_total < min_order_value`. The system should accept the coupon only when all conditions pass simultaneously.

**Validates: Requirements 8.2, 8.3**

---

### Property 17: Discount calculation is correct and capped

*For any* cart total and any coupon:
- If `discount_type = 'percent'`: `discount_amount = min(cart_total × discount_value / 100, cart_total)`
- If `discount_type = 'fixed'`: `discount_amount = min(discount_value, cart_total)`

The discount amount must never exceed the cart total (i.e., the final price before shipping is never negative).

**Validates: Requirements 8.6, 8.7**

---

### Property 18: Checkout with coupon saves correct bill fields

*For any* checkout with a valid coupon in session, the created Bill record should satisfy: `bill.coupon_code = coupon.code`, `bill.discount_amount = calculated_discount`, `bill.total = cart_total + shipping_fee - discount_amount`, and the coupon's `used_count` should be incremented by exactly 1.

**Validates: Requirements 8.5, 9.5**

---

### Property 19: Shipping fee calculation follows threshold rules

*For any* cart total value:
- If `cart_total >= 500,000`: `shipping_fee = 0`
- If `cart_total < 500,000`: `shipping_fee = 30,000`

The calculated shipping fee must always be non-negative.

**Validates: Requirements 9.2, 9.3**

---

### Property 20: Order total breakdown is mathematically consistent

*For any* bill record with `subtotal`, `discount_amount`, and `shipping_fee` values, the displayed `total` on both the admin order detail page and the customer account order detail page must equal `subtotal - discount_amount + shipping_fee`, and all three component values must be individually visible in the breakdown.

**Validates: Requirements 10.1, 10.2, 10.4**

---

## Error Handling

### Email Failures (Requirements 3.3, 4.3)

```php
// In PageController::postCheckout() and AdminController::orderUpdateStatus()
try {
    Mail::to($email)->send(new OrderConfirmationMail($bill));
} catch (\Exception $e) {
    Log::error('Failed to send order confirmation email', [
        'bill_id' => $bill->id,
        'email'   => $email,
        'error'   => $e->getMessage(),
    ]);
    // Checkout continues normally — email failure is non-fatal
}
```

### Coupon Session Expiry

Nếu coupon trong session đã hết hạn hoặc bị vô hiệu hóa trước khi checkout hoàn tất, `postCheckout()` phải re-validate coupon. Nếu coupon không còn hợp lệ, checkout vẫn tiếp tục nhưng không áp dụng discount.

### Shipping Fee Rule Missing

`ShippingFeeService::calculate()` phải luôn trả về một giá trị hợp lệ. Nếu không có rule nào trong database, fallback về `30,000 VND` (hardcoded default) và log warning.

### Image Upload Failures

Nếu `move()` thất bại khi upload ảnh slide, controller phải catch exception, log error, và trả về validation error cho user.

### Last Shipping Fee Rule Protection (Requirement 9.8)

```php
// In ShippingFeeController::adminDelete()
$count = ShippingFee::where('is_active', true)->count();
if ($count <= 1) {
    return redirect()->back()->with('error', 'Phải có ít nhất một quy tắc phí vận chuyển');
}
```

---

## Testing Strategy

### Dual Testing Approach

Dự án sử dụng **PHPUnit** (đã có sẵn trong Laravel) kết hợp với **[Pest PHP](https://pestphp.com/)** cho property-based testing style, và **[eris/eris](https://github.com/giorgiosironi/eris)** hoặc **[phpcheck](https://github.com/antecedent/phpcheck)** cho property-based testing thực sự.

> **Khuyến nghị:** Sử dụng **[Pest PHP](https://pestphp.com/)** với `pest-plugin-faker` để generate random data. Mỗi property test chạy tối thiểu **100 iterations** với dữ liệu ngẫu nhiên.

### Unit Tests (Example-based)

Tập trung vào:
- Specific examples cho CRUD operations (slides, coupons, shipping fees)
- Access control checks (wishlist requires auth, account page requires auth)
- UI element presence checks (contact form, coupon input, Messenger widget)
- Edge cases: empty reply, wrong password, last shipping rule deletion

```
tests/
├── Unit/
│   ├── Services/
│   │   ├── CouponServiceTest.php      (discount calculation)
│   │   └── ShippingFeeServiceTest.php (fee calculation)
│   └── Models/
│       └── BillTest.php               (statusLabels mapping)
└── Feature/
    ├── SlideTest.php
    ├── WishlistTest.php
    ├── ContactTest.php
    ├── AccountTest.php
    ├── CouponTest.php
    ├── ShippingFeeTest.php
    └── CheckoutWithCouponTest.php
```

### Property-Based Tests

Mỗi property test được tag với comment referencing design property:

```php
// Feature: fashion-store-complete, Property 17: Discount calculation is correct and capped
it('calculates percent discount correctly for any cart total', function () {
    $this->repeat(100, function () {
        $cartTotal     = fake()->randomFloat(2, 1000, 10_000_000);
        $discountValue = fake()->numberBetween(1, 100); // percent
        $coupon = Coupon::factory()->make([
            'discount_type'  => 'percent',
            'discount_value' => $discountValue,
        ]);
        $service  = new CouponService();
        $discount = $service->calculateDiscount($coupon, $cartTotal);

        expect($discount)->toBeLessThanOrEqual($cartTotal);
        expect($discount)->toEqual(min($cartTotal * $discountValue / 100, $cartTotal));
    });
});
```

### Property Test Configuration

| Property | Iterations | Test File |
|----------|-----------|-----------|
| P1: Slide creation round-trip | 100 | `SlideTest.php` |
| P2: Invalid slide rejected | 100 | `SlideTest.php` |
| P5: Wishlist toggle round-trip | 100 | `WishlistTest.php` |
| P7: Order confirmation email fields | 100 | `CheckoutWithCouponTest.php` |
| P8: Status email dispatched | 100 | `OrderStatusTest.php` |
| P9: Contact form creates unread record | 100 | `ContactTest.php` |
| P10: Invalid contact rejected | 100 | `ContactTest.php` |
| P13: Order history ordering | 100 | `AccountTest.php` |
| P14: Profile update round-trip | 100 | `AccountTest.php` |
| P15: Password change succeeds | 100 | `AccountTest.php` |
| P16: Coupon validation | 100 | `CouponTest.php` |
| P17: Discount calculation | 100 | `CouponTest.php` |
| P18: Checkout saves correct bill | 100 | `CheckoutWithCouponTest.php` |
| P19: Shipping fee calculation | 100 | `ShippingFeeTest.php` |
| P20: Order total breakdown | 100 | `CheckoutWithCouponTest.php` |

### Integration Tests

- Email sending với `Mail::fake()` để verify dispatch
- Session management cho coupon flow
- Database transactions cho checkout atomicity

### Smoke Tests

- Mail configuration validity
- Facebook SDK script presence in layout
- Admin routes accessible with admin middleware
