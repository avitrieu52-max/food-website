# Requirements Document

## Introduction

Dự án AT10 Fashion là một website bán quần áo xây dựng trên Laravel 11 và MySQL. Codebase hiện tại đã có sẵn các chức năng cơ bản: đăng ký/đăng nhập khách hàng, đăng nhập admin, CRUD sản phẩm, CRUD loại sản phẩm, CRUD user, quản lý đơn hàng, trang chủ, tìm kiếm, giỏ hàng và checkout.

Tài liệu này mô tả các **tính năng mới cần implement** bao gồm: quản lý Slide/Banner, sản phẩm yêu thích (wishlist), thông báo email đặt hàng và cập nhật trạng thái, tích hợp Messenger chat, hệ thống liên hệ với phản hồi từ admin, trang quản lý tài khoản khách hàng, mã giảm giá (coupon) và phí vận chuyển.

---

## Glossary

- **System**: Hệ thống web AT10 Fashion (Laravel 11)
- **Admin**: Người dùng có `level = 1` hoặc `level = 2`, đăng nhập qua `/admin/dangnhap`
- **Customer**: Người dùng đã đăng ký tài khoản (`level = 3`), đăng nhập qua `/dangnhap`
- **Guest**: Người dùng chưa đăng nhập
- **Slide**: Bản ghi trong bảng `slides` gồm tiêu đề, mô tả, hình ảnh, link, thứ tự và trạng thái hiển thị
- **Wishlist**: Danh sách sản phẩm yêu thích của Customer, lưu trong bảng `wishlists`
- **Bill**: Đơn hàng, lưu trong bảng `bills` với các trạng thái: `pending`, `confirmed`, `shipping`, `delivered`, `cancelled`
- **Contact**: Tin nhắn liên hệ từ khách, lưu trong bảng `contacts` với trạng thái `unread`/`replied`
- **Coupon**: Mã giảm giá, lưu trong bảng `coupons` với các trường: `code`, `discount_type` (`percent`/`fixed`), `discount_value`, `min_order_value`, `max_uses`, `used_count`, `expires_at`, `is_active`
- **ShippingFee**: Phí vận chuyển, lưu trong bảng `shipping_fees` theo khu vực hoặc giá trị đơn hàng
- **Mailer**: Dịch vụ gửi email của Laravel (Mail facade, Mailable classes)
- **MessengerPlugin**: Facebook Messenger Customer Chat Plugin nhúng vào frontend
- **OrderEmail**: Email thông báo đơn hàng gửi cho Customer
- **ContactReplyEmail**: Email phản hồi liên hệ gửi cho khách từ Admin

---

## Requirements

### Requirement 1: Quản lý Slide/Banner (Admin)

**User Story:** As an Admin, I want to manage homepage slides/banners, so that I can control the promotional content displayed to customers on the homepage.

#### Acceptance Criteria

1. THE System SHALL provide an admin interface at `/admin/slide` to list all Slides with columns: tiêu đề, hình ảnh thumbnail, thứ tự, trạng thái, và các nút hành động.
2. WHEN an Admin submits the slide creation form with valid data (title, image file, order, status), THE System SHALL save the Slide to the `slides` table and redirect to the slide list with a success message.
3. IF an Admin submits the slide creation form without a title or without an image file, THEN THE System SHALL display validation error messages and not save the Slide.
4. WHEN an Admin submits the slide edit form with valid data, THE System SHALL update the corresponding Slide record and redirect to the slide list with a success message.
5. IF an Admin uploads an image file that is not of type jpg, jpeg, png, or gif, or exceeds 2MB, THEN THE System SHALL reject the upload and display a validation error.
6. WHEN an Admin clicks the delete button for a Slide, THE System SHALL delete the Slide record and its associated image file from storage, then redirect to the slide list with a success message.
7. WHEN the homepage is loaded, THE System SHALL query all Slides with `is_active = true`, ordered by `order` ascending, and render them in the hero carousel section.
8. WHERE a Slide has a non-empty `link` field, THE System SHALL render the slide's call-to-action button as an anchor tag pointing to that link.

---

### Requirement 2: Sản phẩm yêu thích (Wishlist)

**User Story:** As a Customer, I want to save products to a wishlist, so that I can easily find and purchase them later.

#### Acceptance Criteria

1. WHILE a Customer is authenticated, THE System SHALL display a "Yêu thích" (heart icon) button on each product card and product detail page.
2. WHEN an authenticated Customer clicks the "Yêu thích" button on a product not yet in their Wishlist, THE System SHALL insert a record into the `wishlists` table with `user_id` and `food_id`, and return a success response.
3. WHEN an authenticated Customer clicks the "Yêu thích" button on a product already in their Wishlist, THE System SHALL delete the corresponding `wishlists` record (toggle off), and return a success response.
4. IF a Guest clicks the "Yêu thích" button, THEN THE System SHALL redirect the Guest to the login page at `/dangnhap`.
5. THE System SHALL provide a wishlist page at `/yeu-thich` accessible only to authenticated Customers, displaying all products in their Wishlist with product image, name, price, and buttons to add to cart or remove from wishlist.
6. WHEN a Customer removes a product from the wishlist page, THE System SHALL delete the corresponding `wishlists` record and refresh the wishlist page.
7. THE System SHALL highlight the "Yêu thích" button (filled heart icon) for products that are already in the authenticated Customer's Wishlist.

---

### Requirement 3: Gửi email xác nhận đặt hàng

**User Story:** As a Customer, I want to receive an email confirmation after placing an order, so that I have a record of my purchase details.

#### Acceptance Criteria

1. WHEN a Customer successfully completes checkout (POST `/checkout` returns success), THE System SHALL dispatch an OrderEmail to the email address provided in the checkout form containing: mã đơn hàng, danh sách sản phẩm, số lượng, đơn giá, tổng tiền, phí vận chuyển, địa chỉ giao hàng, và phương thức thanh toán.
2. THE Mailer SHALL send the OrderEmail using the configured SMTP settings in `config/mail.php` within the same request cycle (synchronous) or via a queued job.
3. IF the Mailer fails to send the OrderEmail due to an SMTP error, THEN THE System SHALL log the error to `storage/logs/laravel.log` and continue the checkout process without displaying an error to the Customer.
4. THE System SHALL render the OrderEmail using a Blade template at `resources/views/emails/order_confirmation.blade.php` with a clear layout showing order details.

---

### Requirement 4: Gửi email cập nhật trạng thái đơn hàng

**User Story:** As a Customer, I want to receive an email notification when my order status changes, so that I can track my order in real time.

#### Acceptance Criteria

1. WHEN an Admin updates a Bill's status via POST `/admin/donhang/trangthai/{id}`, THE System SHALL dispatch an OrderStatusEmail to the Customer's email address (retrieved from the Bill's associated Customer record).
2. THE OrderStatusEmail SHALL contain: mã đơn hàng, trạng thái mới (hiển thị nhãn tiếng Việt), ngày cập nhật, và link xem chi tiết đơn hàng tại trang khách hàng.
3. IF the Bill's associated Customer record does not have a valid email address, THEN THE System SHALL skip sending the email and log a warning to `storage/logs/laravel.log`.
4. THE System SHALL render the OrderStatusEmail using a Blade template at `resources/views/emails/order_status_update.blade.php`.

---

### Requirement 5: Tích hợp Messenger Chat Plugin

**User Story:** As a Customer, I want to chat with the store via Messenger directly on the website, so that I can get quick support without leaving the page.

#### Acceptance Criteria

1. THE System SHALL embed the Facebook Messenger Customer Chat Plugin JavaScript SDK in the main layout file `resources/views/layouts/app.blade.php` before the closing `</body>` tag.
2. THE System SHALL render the `<div class="fb-customerchat">` element with the `page_id` attribute configured via an environment variable `FACEBOOK_PAGE_ID` in `.env`.
3. WHERE `FACEBOOK_PAGE_ID` is empty or not set, THE System SHALL not render the Messenger chat widget to avoid JavaScript errors.
4. THE System SHALL load the Facebook SDK script asynchronously to avoid blocking page render performance.

---

### Requirement 6: Hệ thống Liên hệ

**User Story:** As a Customer, I want to send a contact message to the store, so that I can ask questions or request support.

#### Acceptance Criteria

1. THE System SHALL provide a contact form on the homepage (section `#contact`) with fields: họ tên (required), email (required, valid email format), và nội dung tin nhắn (required, max 1000 characters).
2. WHEN a user submits the contact form with valid data, THE System SHALL save a Contact record to the `contacts` table with fields: `name`, `email`, `message`, `status = 'unread'`, `replied_at = null`, and redirect back with a success message.
3. IF a user submits the contact form with missing required fields or an invalid email format, THEN THE System SHALL display validation error messages and not save the Contact record.
4. THE System SHALL provide an admin contact list page at `/admin/lienhe` displaying all Contact records with columns: họ tên, email, nội dung (truncated to 100 chars), trạng thái, ngày gửi, và nút hành động.
5. WHEN an Admin clicks "Phản hồi" on a Contact record, THE System SHALL display a reply form pre-filled with the customer's name and email.
6. WHEN an Admin submits the reply form with a non-empty reply message, THE System SHALL send a ContactReplyEmail to the Contact's email address containing the Admin's reply content, update the Contact record's `status` to `'replied'` and set `replied_at` to the current timestamp.
7. IF an Admin submits the reply form with an empty reply message, THEN THE System SHALL display a validation error and not send the email.
8. THE System SHALL render the ContactReplyEmail using a Blade template at `resources/views/emails/contact_reply.blade.php`.
9. THE System SHALL display the contact status as "Chưa phản hồi" (badge warning) or "Đã phản hồi" (badge success) in the admin contact list.

---

### Requirement 7: Trang quản lý tài khoản khách hàng

**User Story:** As a Customer, I want to view my order history, track order status, and update my personal information, so that I can manage my account and purchases in one place.

#### Acceptance Criteria

1. THE System SHALL provide a customer account page at `/tai-khoan` accessible only to authenticated Customers (redirect to `/dangnhap` if not authenticated).
2. THE System SHALL display on the account page a tab or section "Lịch sử đơn hàng" listing all Bills associated with the Customer's email address, ordered by `created_at` descending, with columns: mã đơn hàng, ngày đặt, tổng tiền, trạng thái.
3. WHEN a Customer clicks on an order in the history list, THE System SHALL display the order detail including: danh sách sản phẩm, số lượng, đơn giá, phí vận chuyển, mã giảm giá (nếu có), và tổng tiền.
4. THE System SHALL display the current status of each Bill using the Vietnamese label from `Bill::statusLabels()` with the corresponding Bootstrap badge color.
5. THE System SHALL provide a "Thông tin cá nhân" section on the account page displaying the Customer's current: họ tên, email (read-only), số điện thoại, và địa chỉ.
6. WHEN a Customer submits the personal information update form with valid data (name required, phone max 20 chars, address max 500 chars), THE System SHALL update the corresponding User record and redirect back with a success message.
7. IF a Customer submits the personal information update form with an empty name field, THEN THE System SHALL display a validation error and not update the User record.
8. WHEN a Customer submits a password change form with the correct current password and a new password of at least 6 characters confirmed, THE System SHALL update the User's hashed password and redirect back with a success message.
9. IF a Customer submits the password change form with an incorrect current password, THEN THE System SHALL display an error message "Mật khẩu hiện tại không đúng" and not update the password.

---

### Requirement 8: Mã giảm giá (Coupon)

**User Story:** As a Customer, I want to apply a discount coupon at checkout, so that I can reduce the total cost of my order.

#### Acceptance Criteria

1. THE System SHALL provide a coupon input field on the checkout page (`/checkout`) where Customers can enter a coupon code.
2. WHEN a Customer submits a coupon code via AJAX or form POST to `/coupon/apply`, THE System SHALL look up the Coupon by `code` (case-insensitive) and validate: `is_active = true`, `expires_at` is null or in the future, `used_count < max_uses` (if `max_uses` is set), and the cart total meets `min_order_value`.
3. IF the coupon code does not exist or fails any validation condition, THEN THE System SHALL return a JSON response with `success: false` and a descriptive error message in Vietnamese.
4. WHEN a valid coupon is applied, THE System SHALL store the coupon code in the session, return a JSON response with `success: true`, the discount amount, and the new total, and display the discount line on the checkout page.
5. WHEN a Customer completes checkout with an active coupon in session, THE System SHALL calculate the final total as: `cart_total + shipping_fee - discount_amount`, save the `coupon_code` and `discount_amount` to the Bill record, and increment the Coupon's `used_count` by 1.
6. WHERE a Coupon has `discount_type = 'percent'`, THE System SHALL calculate `discount_amount = cart_total * discount_value / 100`, capped at the cart total.
7. WHERE a Coupon has `discount_type = 'fixed'`, THE System SHALL calculate `discount_amount = discount_value`, capped at the cart total.
8. THE System SHALL provide admin CRUD for Coupons at `/admin/magiamgia` with fields: code, discount_type, discount_value, min_order_value, max_uses, expires_at, is_active.
9. WHEN an Admin creates or updates a Coupon with a duplicate `code` value, THE System SHALL display a validation error "Mã giảm giá đã tồn tại" and not save the record.

---

### Requirement 9: Phí vận chuyển (Shipping Fee)

**User Story:** As a Customer, I want to see the shipping fee calculated at checkout, so that I know the total cost before placing my order.

#### Acceptance Criteria

1. THE System SHALL calculate and display the shipping fee on the checkout page before the Customer submits the order.
2. THE System SHALL provide at least one shipping fee rule: orders with `cart_total >= 500,000 VND` have `shipping_fee = 0`; orders with `cart_total < 500,000 VND` have `shipping_fee = 30,000 VND`.
3. WHEN the checkout page is loaded, THE System SHALL compute the shipping fee based on the current cart total and display it as a separate line item "Phí vận chuyển" in the order summary.
4. WHEN a Customer applies a valid coupon, THE System SHALL recalculate and display the updated total as `cart_total - discount_amount + shipping_fee`.
5. WHEN a Customer completes checkout, THE System SHALL save the `shipping_fee` value to the Bill record.
6. THE System SHALL display the shipping fee in the OrderEmail sent to the Customer after checkout.
7. THE System SHALL provide an admin interface at `/admin/phivanhuyen` to configure shipping fee rules (minimum order value threshold and fee amount), with at least one active rule required at all times.
8. IF an Admin attempts to delete the last remaining shipping fee rule, THEN THE System SHALL display an error "Phải có ít nhất một quy tắc phí vận chuyển" and not delete the record.

---

### Requirement 10: Tích hợp Coupon và Shipping Fee vào Bill

**User Story:** As an Admin, I want to see coupon and shipping fee details on each order, so that I can accurately track revenue and discounts.

#### Acceptance Criteria

1. THE System SHALL display `coupon_code`, `discount_amount`, và `shipping_fee` on the admin order detail page at `/admin/donhang/chitiet/{id}`.
2. THE System SHALL display the breakdown: tổng tiền hàng, mã giảm giá (nếu có), phí vận chuyển, và tổng thanh toán cuối cùng on the order detail page.
3. THE System SHALL include `coupon_code`, `discount_amount`, và `shipping_fee` in the OrderEmail sent to the Customer.
4. WHEN a Bill is displayed on the Customer's account order history page, THE System SHALL show the final total including shipping fee and discount.
