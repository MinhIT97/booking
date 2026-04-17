<?php

namespace Modules\Booking\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Booking\Events\BookingCreated;
use Modules\Booking\Mail\BookingConfirmation;

class SendBookingConfirmation
{
    /**
     * Handle the event.
     *
     * @param  BookingCreated  $event
     * @return void
     */
    public function handle(BookingCreated $event)
    {
        Mail::to($event->booking->user->email)->send(new BookingConfirmation($event->booking));
    }
}
