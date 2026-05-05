<?php

namespace App\Mail;

use App\Models\Bill;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable gửi email xác nhận đơn hàng cho khách hàng.
 * Được gọi ngay sau khi khách hàng đặt hàng thành công.
 */
class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /** Thông tin đơn hàng vừa được tạo */
    public Bill $bill;

    /**
     * Khởi tạo với đối tượng đơn hàng (bao gồm chi tiết sản phẩm và thông tin khách).
     */
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }

    /**
     * Tiêu đề email kèm mã đơn hàng.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đơn hàng #' . $this->bill->id . ' - AT10 Fashion',
        );
    }

    /**
     * View template hiển thị chi tiết đơn hàng trong email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmation',
        );
    }

    /**
     * Không đính kèm file nào.
     */
    public function attachments(): array
    {
        return [];
    }
}
