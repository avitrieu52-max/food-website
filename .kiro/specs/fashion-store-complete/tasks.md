# Implementation Tasks: fashion-store-complete

## Tasks

- [ ] 1. Database migrations & Models
  - [ ] 1.1 Migration: tạo bảng slides
  - [ ] 1.2 Migration: tạo bảng wishlists
  - [ ] 1.3 Migration: tạo bảng contacts
  - [ ] 1.4 Migration: tạo bảng coupons
  - [ ] 1.5 Migration: tạo bảng shipping_fees
  - [ ] 1.6 Migration: ALTER bills thêm coupon_code, discount_amount, shipping_fee
  - [ ] 1.7 Tạo Model Slide, Wishlist, Contact, Coupon, ShippingFee
  - [ ] 1.8 Cập nhật Model Bill (fillable + relationships)
  - [ ] 1.9 Cập nhật Model User (wishlist relationship)
  - [ ] 1.10 Cập nhật Model Food (wishlist relationship)

- [ ] 2. Service Classes
  - [ ] 2.1 Tạo CouponService (validate + calculateDiscount)
  - [ ] 2.2 Tạo ShippingFeeService (calculate)

- [ ] 3. Slide/Banner Admin CRUD
  - [ ] 3.1 Tạo SlideController (index, create, store, edit, update, destroy)
  - [ ] 3.2 Thêm routes slide vào web.php
  - [ ] 3.3 Tạo views: admin/slide/list, create, edit
  - [ ] 3.4 Thêm menu Slide vào admin header
  - [ ] 3.5 Cập nhật homepage để load slides từ DB thay vì hardcode

- [ ] 4. Wishlist (Sản phẩm yêu thích)
  - [ ] 4.1 Tạo WishlistController (index, toggle, remove)
  - [ ] 4.2 Thêm routes wishlist vào web.php
  - [ ] 4.3 Tạo view wishlist.blade.php
  - [ ] 4.4 Thêm nút yêu thích vào _product_item, show, list
  - [ ] 4.5 Thêm link Yêu thích vào navbar (khi đã đăng nhập)

- [ ] 5. Email Notifications
  - [ ] 5.1 Tạo Mailable OrderConfirmationMail + view email
  - [ ] 5.2 Tạo Mailable OrderStatusMail + view email
  - [ ] 5.3 Tạo Mailable ContactReplyMail + view email
  - [ ] 5.4 Gửi OrderConfirmationMail trong PageController::postCheckout()
  - [ ] 5.5 Gửi OrderStatusMail trong AdminController::orderUpdateStatus()

- [ ] 6. Messenger Chat Plugin
  - [ ] 6.1 Thêm FACEBOOK_PAGE_ID vào .env
  - [ ] 6.2 Nhúng Facebook Messenger Customer Chat Plugin vào layouts/app.blade.php

- [ ] 7. Hệ thống Liên hệ
  - [ ] 7.1 Tạo ContactController (store, adminIndex, adminReplyForm, adminReply)
  - [ ] 7.2 Thêm routes contact vào web.php
  - [ ] 7.3 Cập nhật form liên hệ trên homepage (section #contact)
  - [ ] 7.4 Tạo views: admin/contact/list, reply
  - [ ] 7.5 Thêm menu Liên hệ vào admin header

- [ ] 8. Trang tài khoản khách hàng
  - [ ] 8.1 Tạo AccountController (index, orderDetail, updateProfile, changePassword)
  - [ ] 8.2 Thêm routes account vào web.php
  - [ ] 8.3 Tạo view account/index.blade.php (tabs: lịch sử đơn + thông tin cá nhân)
  - [ ] 8.4 Tạo view account/order-detail.blade.php
  - [ ] 8.5 Thêm link Tài khoản vào navbar

- [ ] 9. Mã giảm giá (Coupon)
  - [ ] 9.1 Tạo CouponController (apply, remove, admin CRUD)
  - [ ] 9.2 Thêm routes coupon vào web.php
  - [ ] 9.3 Tạo views: admin/coupon/list, create, edit
  - [ ] 9.4 Thêm coupon input vào checkout.blade.php
  - [ ] 9.5 Tích hợp coupon vào PageController::postCheckout()
  - [ ] 9.6 Thêm menu Mã giảm giá vào admin header

- [ ] 10. Phí vận chuyển (Shipping Fee)
  - [ ] 10.1 Tạo ShippingFeeController (admin CRUD)
  - [ ] 10.2 Thêm routes shipping fee vào web.php
  - [ ] 10.3 Tạo views: admin/shipping/list, create, edit
  - [ ] 10.4 Hiển thị phí vận chuyển trên checkout.blade.php
  - [ ] 10.5 Tích hợp shipping fee vào PageController::postCheckout()
  - [ ] 10.6 Thêm menu Phí vận chuyển vào admin header

- [ ] 11. Cập nhật admin order detail
  - [ ] 11.1 Hiển thị coupon_code, discount_amount, shipping_fee trên admin/order/detail
