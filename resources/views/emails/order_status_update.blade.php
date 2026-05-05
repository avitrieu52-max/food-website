<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #1a1a1a; color: #c9a96e; padding: 30px; text-align: center; }
        .body { padding: 30px; }
        .status-box { background: #f9f9f9; border-left: 4px solid #c9a96e; padding: 20px; border-radius: 4px; margin: 20px 0; }
        .footer { background: #f5f5f5; padding: 20px; text-align: center; color: #888; font-size: 13px; }
        .btn { display: inline-block; background: #c9a96e; color: #fff; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin-top: 15px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>👗 AT10 FASHION</h1>
        <p style="margin:5px 0 0; color:rgba(255,255,255,0.8);">Cập nhật trạng thái đơn hàng</p>
    </div>
    <div class="body">
        <p>Xin chào <strong>{{ $bill->customer->name ?? 'Quý khách' }}</strong>,</p>
        <p>Đơn hàng <strong>#{{ $bill->id }}</strong> của bạn vừa được cập nhật trạng thái.</p>

        <div class="status-box">
            <p style="margin:0 0 8px;"><strong>Mã đơn hàng:</strong> #{{ $bill->id }}</p>
            <p style="margin:0 0 8px;"><strong>Ngày đặt:</strong> {{ $bill->date_order }}</p>
            <p style="margin:0 0 8px;"><strong>Tổng tiền:</strong> {{ number_format($bill->total) }}đ</p>
            <p style="margin:0; font-size:18px;"><strong>Trạng thái mới:</strong>
                <span style="color:#c9a96e; font-weight:bold;">{{ $bill->status_label['label'] }}</span>
            </p>
        </div>

        <p>Ngày cập nhật: <strong>{{ now()->format('d/m/Y H:i') }}</strong></p>
        <p>Nếu có thắc mắc, vui lòng liên hệ: <strong>0123.456.789</strong></p>
    </div>
    <div class="footer">
        © 2026 AT10 Fashion — 123 Nguyễn Văn Linh, Đà Nẵng
    </div>
</div>
</body>
</html>
