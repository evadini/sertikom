<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Dibatalkan</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto;">
        <tr>
            <td style="background-color: #ffffff; border-radius: 8px; padding: 30px;">
                <h2 style="color: #dc2626; margin-top: 0;">Event Dibatalkan</h2>

                <p>Halo <strong>{{ $buyer->name }}</strong>,</p>

                <p>Dengan berat hati, event <strong>{{ $event->name }}</strong> yang telah kamu beli tiketnya terpaksa dibatalkan.</p>

                <table style="background-color: #f9fafb; border-radius: 6px; padding: 15px; margin: 20px 0; width: 100%;">
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">Nama Event</td>
                        <td style="padding: 6px 0; font-weight: bold;">{{ $event->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">Tanggal</td>
                        <td style="padding: 6px 0; font-weight: bold;">{{ $event->date_range }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">Lokasi</td>
                        <td style="padding: 6px 0; font-weight: bold;">{{ $event->location }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">Alasan Pembatalan</td>
                        <td style="padding: 6px 0; font-weight: bold;">{{ $event->unpublish_reason }}</td>
                    </tr>
                </table>

                @if($event->refund_date || $event->refund_location || $event->refund_info)
                    <h3 style="color: #2563eb; margin-top: 25px;">Informasi Pengembalian Dana</h3>
                    <table style="background-color: #eff6ff; border-radius: 6px; padding: 15px; margin: 10px 0 20px 0; width: 100%;">
                        @if($event->refund_date)
                        <tr>
                            <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">Tanggal Refund</td>
                            <td style="padding: 6px 0; font-weight: bold;">{{ \Carbon\Carbon::parse($event->refund_date)->format('d F Y') }}</td>
                        </tr>
                        @endif
                        @if($event->refund_location)
                        <tr>
                            <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">Lokasi Refund</td>
                            <td style="padding: 6px 0; font-weight: bold;">{{ $event->refund_location }}</td>
                        </tr>
                        @endif
                        @if($event->refund_info)
                        <tr>
                            <td style="padding: 6px 0; color: #6b7280; font-size: 14px;">Informasi Tambahan</td>
                            <td style="padding: 6px 0; font-weight: bold;">{{ $event->refund_info }}</td>
                        </tr>
                        @endif
                    </table>
                @endif

                <p style="color: #6b7280; font-size: 14px;">Mohon maaf atas ketidaknyamanannya. Jika ada pertanyaan, silakan hubungi panitia event atau admin Ticketify.</p>

                <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">Salam,<br><strong>Tim Ticketify</strong></p>
            </td>
        </tr>
    </table>
</body>
</html>
