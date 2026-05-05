<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #1a1a1a; color: #c9a96e; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .order-info { background: #f9f9f9; border-radius: 6px; padding: 15px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1a1a1a; color: #fff; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-row { font-weight: bold; background: #f9f9f9; }
        .footer { background: #f5f5f5; padding: 20px; text-align: center; color: #888; font-size: 13px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; background: #c9a96e; color: #fff; font-size: 13px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>👗 AT10 FASHION</h1>
        <p style="margin:5px 0 0; color:rgba(255,255,255,0.8);">Xác nhận đơn hàng</p>
    </div>
    <div class="body">
        <p>Xin chào <strong>{{ $bill->customer->name ?? 'Quý khách' }}</strong>,</p>
        <p>Cảm ơn bạn đã đặt hàng tại AT10 Fashion! Đơn hàng của bạn đã được tiếp nhận thành công.</p>

        <div class="order-info">
            <strong>Mã đơn hàng:</strong> #{{ $bill->id }}<br>
            <strong>Ngày đặt:</strong> {{ $bill->date_order }}<br>
            <strong>Phương thức thanh toán:</strong> {{ $bill->payment }}<br>
            <strong>Địa chỉ giao hàng:</strong> {{ $bill->customer->address ?? '-' }}<br>
            <strong>Trạng thái:</strong> <span class="badge">{{ $bill->status_label['label'] }}</span>
        </div>

        <h3>Sản phẩm đã đặt</h3>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th style="text-align:center;">SL</th>
                    <th style="text-align:right;">Đơn giá</th>
                    <th style="text-align:right;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bill->details as $detail)
                <tr>
                    <td>{{ $detail->food->name ?? 'Sản phẩm #'.$detail->id_product }}</td>
                    <td style="text-align:center;">{{ $detail->quantity }}</td>
                    <td style="text-align:right;">{{ number_format($detail->unit_price) }}đ</td>
                    <td style="text-align:right;">{{ number_format($detail->unit_price * $detail->quantity) }}đ</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="text-align:right;">Tạm tính:</td>
                    <td style="text-align:right;">{{ number_format($bill->total - $bill->shipping_fee + $bill->discount_amount) }}đ</td>
                </tr>
                @if($bill->discount_amount > 0)
                <tr>
                    <td colspan="3" style="text-align:right; color:#e74c3c;">Giảm giá ({{ $bill->coupon_code }}):</td>
                    <td style="text-align:right; color:#e74c3c;">-{{ number_format($bill->discount_amount) }}đ</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3" style="text-align:right;">Phí vận chuyển:</td>
                    <td style="text-align:right;">{{ $bill->shipping_fee > 0 ? number_format($bill->shipping_fee).'đ' : 'Miễn phí' }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right;">Tổng thanh toán:</td>
                    <td style="text-align:right; color:#c9a96e; font-size:16px;">{{ number_format($bill->total) }}đ</td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top:20px;">Nếu có thắc mắc, vui lòng liên hệ: <strong>0123.456.789</strong> hoặc <strong>contact@demo.com</strong></p>
    </div>
    <div class="footer">
        © 2026 AT10 Fashion — 123 Nguyễn Văn Linh, Đà Nẵng
    </div>
</div>
</body>
</html>
