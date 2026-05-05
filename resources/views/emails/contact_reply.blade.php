<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #1a1a1a; color: #c9a96e; padding: 30px; text-align: center; }
        .body { padding: 30px; }
        .original-msg { background: #f9f9f9; border-left: 4px solid #ccc; padding: 15px; border-radius: 4px; margin: 15px 0; color: #666; }
        .reply-msg { background: #fff8ee; border-left: 4px solid #c9a96e; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .footer { background: #f5f5f5; padding: 20px; text-align: center; color: #888; font-size: 13px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>👗 AT10 FASHION</h1>
        <p style="margin:5px 0 0; color:rgba(255,255,255,0.8);">Phản hồi liên hệ</p>
    </div>
    <div class="body">
        <p>Xin chào <strong>{{ $contact->name }}</strong>,</p>
        <p>Cảm ơn bạn đã liên hệ với AT10 Fashion. Chúng tôi xin phản hồi tin nhắn của bạn:</p>

        <div class="original-msg">
            <strong>Tin nhắn của bạn:</strong><br>
            {{ $contact->message }}
        </div>

        <div class="reply-msg">
            <strong>Phản hồi từ AT10 Fashion:</strong><br>
            {!! nl2br(e($replyMessage)) !!}
        </div>

        <p>Nếu cần hỗ trợ thêm, vui lòng liên hệ: <strong>0123.456.789</strong> hoặc <strong>contact@demo.com</strong></p>
    </div>
    <div class="footer">
        © 2026 AT10 Fashion — 123 Nguyễn Văn Linh, Đà Nẵng
    </div>
</div>
</body>
</html>
