<?php

namespace App\Mail;

use App\Models\Bill;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable gửi email thông báo cập nhật trạng thái đơn hàng.
 * Được gọi mỗi khi admin thay đổi trạng thái đơn hàng trong trang quản trị.
 */
class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /** Thông tin đơn hàng đã được cập nhật trạng thái */
    public Bill $bill;

    /**
     * Khởi tạo với đối tượng đơn hàng đã cập nhật.
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
            subject: 'Cập nhật trạng thái đơn hàng #' . $this->bill->id . ' - AT10 Fashion',
        );
    }

    /**
     * View template hiển thị trạng thái mới của đơn hàng.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_update',
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
