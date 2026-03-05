<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt lịch - WELLSTATE</title>
</head>

<body style="margin:0; padding:0; background-color:#f5f5f5; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; padding:40px 0;">
        <tr>
            <td align="center">

                <!-- Container -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 5px 20px rgba(0,0,0,0.05);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#1f2937; padding:30px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:24px; letter-spacing:1px;">
                                WELLSTATE SPA
                            </h1>
                            <p style="color:#d1d5db; margin-top:8px; font-size:14px;">
                                Xác nhận đặt lịch thành công
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px;">

                            <h2 style="margin-top:0; color:#111827;">
                                Đặt lịch thành công 🎉
                            </h2>

                            <p style="color:#4b5563; font-size:14px;">
                                Xin chào, <br>
                                Cảm ơn bạn đã tin tưởng sử dụng dịch vụ của chúng tôi.
                                Dưới đây là thông tin chi tiết đặt lịch của bạn:
                            </p>

                            <!-- Booking Info -->
                            <table width="100%" cellpadding="10" cellspacing="0"
                                style="margin-top:20px; border-collapse:collapse; font-size:14px;">

                                <tr style="background:#f9fafb;">
                                    <td width="40%" style="color:#6b7280;">Mã booking</td>
                                    <td style="font-weight:bold; color:#111827;">
                                        {{ $booking->booking_code }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="color:#6b7280;">Ngày</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                    </td>
                                </tr>

                                <tr style="background:#f9fafb;">
                                    <td style="color:#6b7280;">Thời gian</td>
                                    <td>
                                        {{ $booking->start_time }} - {{ $booking->end_time }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="color:#6b7280;">Số khách</td>
                                    <td>{{ $booking->total_guests }}</td>
                                </tr>

                                <!-- Giá gốc -->
                                <tr style="background:#f9fafb;">
                                    <td style="color:#6b7280;">Tạm tính</td>
                                    <td>
                                        {{ number_format($booking->subtotal_amount) }} VNĐ
                                    </td>
                                </tr>

                                <!-- Nếu có giảm giá -->
                                @if ($booking->discount_amount > 0)
                                    <tr>
                                        <td style="color:#6b7280;">Giảm giá</td>
                                        <td style="color:#23a651; font-weight:bold;">
                                            - {{ number_format($booking->discount_amount) }} VNĐ
                                        </td>
                                    </tr>
                                @endif

                                <tr style="background:#f9fafb;">
                                    <td>Phí phòng</td>
                                    <td>
                                        @if (optional($booking->branchRoomType)->price)
                                            <strong class="text-danger">
                                                {{ number_format($booking->branchRoomType->price, 0, ',', '.') }} ₫
                                            </strong>
                                        @else
                                            <span class="text-muted">0 ₫</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Tổng tiền -->
                                <tr style="background:#fef3c7;">
                                    <td style="color:#111827; font-weight:bold;">Tổng thanh toán</td>
                                    <td style="font-weight:bold; font-size:16px; color:#b45309;">
                                        {{ number_format($booking->total_amount) }} VNĐ
                                    </td>
                                </tr>

                                <!-- Trạng thái -->
                                <tr>
                                    <td style="color:#6b7280;">Thanh toán</td>
                                    <td>
                                        @if ($booking->payment_status === 'paid')
                                            <span style="color:#16a34a; font-weight:bold;">Đã thanh toán</span>
                                        @else
                                            <span style="color:#dc2626; font-weight:bold;">Chưa thanh toán</span>
                                        @endif
                                    </td>
                                </tr>

                            </table>

                            @if ($booking->guests->count())
                                <h3 style="margin-top:30px; color:#111827; font-size:16px;">
                                    Danh sách dịch vụ đã đặt
                                </h3>

                                <table width="100%" cellpadding="8" cellspacing="0"
                                    style="margin-top:10px; border-collapse:collapse; font-size:13px; border:1px solid #e5e7eb;">

                                    <thead>
                                        <tr style="background:#f3f4f6; text-align:left;">
                                            <th style="border:1px solid #e5e7eb;">Khách</th>
                                            <th style="border:1px solid #e5e7eb;">Dịch vụ</th>
                                            <th style="border:1px solid #e5e7eb;">Thời lượng</th>
                                            <th style="border:1px solid #e5e7eb;">Giá</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($booking->guests as $guest)
                                            @foreach ($guest->services as $service)
                                                <tr>
                                                    <td style="border:1px solid #e5e7eb;">
                                                        {{ $guest->guest_name ?? 'Khách' }}
                                                    </td>
                                                    <td style="border:1px solid #e5e7eb;">
                                                        {{ optional($service->service)->title ?? '-' }}
                                                    </td>
                                                    <td style="border:1px solid #e5e7eb;">
                                                        {{ $service->duration }} phút
                                                    </td>
                                                    <td style="border:1px solid #e5e7eb; font-weight:bold;">
                                                        {{ number_format($service->price) }} VNĐ
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <!-- CTA Button -->
                            {{-- <div style="text-align:center; margin-top:30px;">
                                <a href="{{ url('/') }}"
                                    style="display:inline-block; padding:12px 24px; background:#1f2937; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px;">
                                    Xem chi tiết đặt lịch
                                </a>
                            </div> --}}

                            <p style="margin-top:30px; font-size:13px; color:#6b7280;">
                                Nếu bạn cần hỗ trợ, vui lòng liên hệ hotline hoặc phản hồi email này.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f9fafb; padding:20px; text-align:center; font-size:12px; color:#9ca3af;">
                            © {{ date('Y') }} WELLSTATE SPA. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
