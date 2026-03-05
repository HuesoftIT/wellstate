<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load((['guests.services.service', 'branchRoomType']));
    }

    public function build()
    {
        return $this->subject('Xác nhận đặt lịch thành công')
            ->view('theme::front-end.emails.booking_success');
    }
}
