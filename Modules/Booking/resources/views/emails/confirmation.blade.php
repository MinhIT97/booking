<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Confirmation</title>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; }
        .header { background: linear-gradient(135deg, #ff385c, #d90b2e); padding: 40px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.025em; }
        .content { padding: 40px; }
        .booking-info { background-color: #f3f4f6; border-radius: 8px; padding: 20px; margin-bottom: 30px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .label { color: #6b7280; font-weight: 500; }
        .value { color: #111827; font-weight: 700; }
        .property-card { border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-top: 20px; }
        .property-image { width: 100%; height: 200px; object-fit: cover; }
        .property-details { padding: 20px; }
        .property-title { font-size: 18px; font-weight: 800; color: #111827; margin: 0 0 8px 0; }
        .property-address { font-size: 14px; color: #6b7280; margin: 0; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; }
        .btn { display: inline-block; background-color: #ff385c; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmed!</h1>
            <p style="margin-top: 10px; opacity: 0.9;">Pack your bags, {{ $booking->user->name }}!</p>
        </div>
        <div class="content">
            <p style="color: #374151; font-size: 16px; line-height: 1.5;">Your reservation at <strong>{{ $booking->property->title }}</strong> is confirmed. We've sent the details to the host, and they're looking forward to your stay.</p>
            
            <div class="booking-info">
                <div class="info-row">
                    <span class="label">Reservation ID</span>
                    <span class="value">#{{ strtoupper(substr($booking->id, 0, 8)) }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Check-in</span>
                    <span class="value">{{ $booking->check_in_date->format('M d, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Checkout</span>
                    <span class="value">{{ $booking->check_out_date->format('M d, Y') }}</span>
                </div>
                <div class="info-row" style="margin-top: 15px; border-top: 1px solid #e5e7eb; pt: 15px;">
                    <span class="label">Total Paid</span>
                    <span class="value" style="color: #ff385c; font-size: 18px;">${{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>

            <div class="property-card">
                @if($booking->property->primaryImage)
                    <img src="{{ $booking->property->primaryImage->url }}" class="property-image" alt="Property">
                @endif
                <div class="property-details">
                    <h3 class="property-title">{{ $booking->property->title }}</h3>
                    <p class="property-address">{{ $booking->property->address }}, {{ $booking->property->city }}, {{ $booking->property->country }}</p>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">View Reservation</a>
            </div>
        </div>
        <div class="footer">
            &copy; 2026 StayNest Inc. All rights reserved.<br>
            If you have any questions, contact us at support@staynest.com
        </div>
    </div>
</body>
</html>
