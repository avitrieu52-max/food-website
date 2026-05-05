<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable gửi email phản hồi liên hệ từ admin đến khách hàng.
 * Được gọi khi admin nhấn "Gửi phản hồi" trong trang quản trị liên hệ.
 */
class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    /** Thông tin liên hệ của khách hàng */
    public Contact $contact;

    /** Nội dung phản hồi từ admin */
    public string $replyMessage;

    /**
     * Khởi tạo với thông tin liên hệ và nội dung phản hồi.
     */
    public function __construct(Contact $contact, string $replyMessage)
    {
        $this->contact      = $contact;
        $this->replyMessage = $replyMessage;
    }

    /**
     * Tiêu đề email gửi đi.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Phản hồi liên hệ từ AT10 Fashion',
        );
    }

    /**
     * View template hiển thị nội dung email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact_reply',
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
