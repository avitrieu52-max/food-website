<?php

namespace App\Http\Controllers;

use App\Mail\ContactReplyMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'contact_name'    => 'required|string|max:255',
            'contact_email'   => 'required|email|max:255',
            'contact_message' => 'required|string|max:1000',
        ], [
            'contact_name.required'    => 'Vui lòng nhập họ tên',
            'contact_email.required'   => 'Vui lòng nhập email',
            'contact_email.email'      => 'Email không hợp lệ',
            'contact_message.required' => 'Vui lòng nhập nội dung',
            'contact_message.max'      => 'Nội dung không được vượt quá 1000 ký tự',
        ]);

        Contact::create([
            'name'    => $request->contact_name,
            'email'   => $request->contact_email,
            'message' => $request->contact_message,
            'status'  => 'unread',
        ]);

        return redirect()->back()->with('contact_success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.');
    }

    public function adminIndex(Request $request)
    {
        $query = Contact::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $contacts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.contact.list', compact('contacts'));
    }

    public function adminReplyForm($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.contact.reply', compact('contact'));
    }

    public function adminReply(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ], [
            'reply_message.required' => 'Vui lòng nhập nội dung phản hồi',
        ]);

        $contact = Contact::findOrFail($id);

        try {
            Mail::to($contact->email)->send(new ContactReplyMail($contact, $request->reply_message));
        } catch (\Exception $e) {
            Log::error('Failed to send contact reply email', [
                'contact_id' => $contact->id,
                'email'      => $contact->email,
                'error'      => $e->getMessage(),
            ]);
        }

        $contact->update([
            'status'     => 'replied',
            'replied_at' => now(),
        ]);

        return redirect()->route('admin.contact.list')->with('success', 'Đã gửi phản hồi thành công!');
    }
}
