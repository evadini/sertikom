<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; }
        th { background-color: #1d4ed8; color: #ffffff; padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        td { padding: 6px 12px; border: 1px solid #ccc; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Data Peserta: {{ $event->name }}</h2>
    <p>Tanggal Export: {{ now()->format('d M Y, H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Kategori</th>
                <th>Unique Code</th>
                <th>Waktu Beli</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendees as $index => $ticket)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ticket->user->name ?? 'User Terhapus' }}</td>
                    <td>{{ $ticket->user->email ?? '-' }}</td>
                    <td>{{ $ticket->user->phone_number ?? '-' }}</td>
                    <td>{{ strtoupper($ticket->ticket_type ?? 'REGULER') }}</td>
                    <td>{{ $ticket->qr_code ?? '-' }}</td>
                    <td>{{ $ticket->created_at ? $ticket->created_at->format('d M Y, H:i') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>