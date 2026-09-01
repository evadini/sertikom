# Certificate in Fullstack Developer Associate (CFDA)
# Ticketify (Event & Ticketing Management System)

Ticketify adalah sistem manajemen event dan tiket berbasis web yang dikembangkan untuk mengatasi masalah pengelolaan event kampus (UKM) yang selama ini masih dilakukan secara manual menggunakan Ms. Excel, Google Form, WhatsApp, dan tiket fisik.

## Latar Belakang

Proses penyelenggaraan event oleh UKM (Unit Kegiatan Mahasiswa) selama ini menghadapi berbagai kendala karena masih mengandalkan alat bantu yang terpisah-pisah:

- **Ms. Excel** untuk pencatatan data pendaftar
- **Google Form** untuk pendaftaran peserta
- **WhatsApp** untuk koordinasi dan konfirmasi
- **Tiket fisik** yang harus dicetak dan didistribusikan manual

Pendekatan ini terbukti **tidak efektif dan tidak efisien**: data mudah tercecer, proses pelacakan (tracking) peserta sulit dilakukan, dan panitia kewalahan menangani volume pendaftaran serta pembayaran secara manual.

Ticketify hadir sebagai solusi terintegrasi untuk pengelolaan event dan tiket secara digital, mulai dari pengajuan event, penjualan tiket, pembayaran online, hingga validasi kehadiran peserta menggunakan QR Code.

## Teknologi yang Digunakan

- **Laravel** 
- **JavaScript** 
- **PHP**

## Aktor Sistem

| Aktor | Peran Utama |
|---|---|
| **Pembeli** | Melihat katalog event, membeli tiket, menerima e-ticket (QR code), melihat riwayat tiket/transaksi, mengajukan diri menjadi panitia |
| **Panitia** | Mengelola data event & jenis tiket, memverifikasi kehadiran peserta via QR, melihat data penjualan/pendaftar |
| **Admin** | Mengelola kategori event, memverifikasi pengajuan event, mengaktifkan/menonaktifkan akun panitia, mengelola data pengguna, meninjau pengajuan menjadi panitia |

## Arsitektur Sistem (Ringkasan Alur)

1. **Panitia** mengelola detail event (informasi lengkap termasuk tiket) dan mengajukannya untuk dipublikasikan.
2. **Admin** meninjau daftar event yang diajukan, melakukan validasi (approve/reject), serta mengelola kategori event dan data pengguna agar sistem tetap terorganisir.
3. **Pembeli** login, memilih event, memesan, dan melakukan pembayaran tiket sesuai kategori yang dipilih.
4. **Website** menampilkan rincian pembayaran yang selanjutnya digunakan sebagai tiket digital, serta menyimpan seluruh data event dan tiket ke dalam **basis data**.

## Use Case Utama

**Pembeli:**
- Melihat katalog event
- Membeli tiket event
- Mendapatkan kode unik sebagai tiket digital
- Melihat riwayat tiket dan riwayat transaksi
- Mengajukan diri menjadi panitia

**Admin:**
- Login
- Kelola kategori event
- Verifikasi pengajuan event
- Menonaktifkan akun panitia
- Kelola data pengguna
- Pengaktifan akun customer menjadi panitia

**Panitia:**
- Kelola data event
- Verifikasi kehadiran (scan QR)
- Mengetahui detail daftar pembeli tiket
- Kelola data jenis tiket
- Melihat data penjualan event

## Entity Relationship Diagram (Ringkasan)

Entitas utama dalam basis data:

- **PENGGUNA** (id_pengguna, nama_lengkap, email, password, phone, role) — bisa berperan sebagai Admin/Pembeli, dan dapat terlibat sebagai **PANITIA** dalam pengelolaan event
- **KATEGORI_EVENT** (id_kategori_event, nama_kategori) — mengkategorikan **EVENT**
- **EVENT** (id_event, kategori_id, judul, lokasi, tanggal_mulai, tanggal_selesai, deskripsi) — dikelola oleh panitia, menyediakan berbagai **TICKET**
- **TICKET** (id_ticket, id_event, id_tipe_ticket, tipe_ticket) — menjadi dasar tipe untuk tiket yang dijual, berisi **PURCHASED_TICKET**
- **TARANSAKSI** (id_transaksi, id_pengguna, total_transaksi, tanggal_transaksi, status_pembayaran) — dilakukan oleh pengguna (pembeli), satu transaksi bisa berisi beberapa tiket
- **PURCHASED_TICKET** (id_purchased_ticket, id_transaksi, id_ticket, qr_code, quota, harga, status_kehadiran [unused/used])

## Functional Requirements

| ID | Fitur | Deskripsi | Aktor |
|---|---|---|---|
| FR-01 | User Authentication | Login, registrasi, logout dengan keamanan (Bcrypt hashing) | Umum |
| FR-02 | Profile Management | Mengelola informasi akun dan perubahan password | Umum |
| FR-03 | Event Management | Membuat, mengedit, menghapus detail event | Panitia |
| FR-04 | Ticket Tiering | Mengelola jenis tiket (Reguler & VIP) beserta kuota dan harga | Panitia |
| FR-05 | Category Management | Mengelola kategori event | Admin |
| FR-06 | Event Approval System | Verifikasi (approve/reject) event yang diajukan panitia | Admin |
| FR-07 | Committee Control | Mengaktifkan/menonaktifkan (suspend) akun panitia | Admin |
| FR-08 | Event Catalog | Menampilkan galeri event terpublikasi dengan pencarian & filter | Pembeli |
| FR-09 | Payment Integration | Integrasi pembayaran otomatis via Midtrans (Snap/Redirect) | Pembeli |
| FR-10 | Payment Callback | Menerima notifikasi otomatis dari Midtrans untuk update status transaksi | Sistem |
| FR-11 | E-Ticket Issuance | Generate e-ticket dengan QR Code unik otomatis setelah pembayaran sukses | Sistem |
| FR-12 | My Tickets & History | Menampilkan daftar tiket dan riwayat transaksi | Pembeli |
| FR-13 | E-Ticket Validator | Scanner QR Code untuk validasi kehadiran peserta | Panitia |
| FR-14 | Dashboard & Reporting | Visualisasi jumlah pendaftar, statistik penjualan, laporan pendapatan | Panitia |
| FR-15 | Committee Request | Pengajuan permohonan menjadi panitia | Pembeli |
| FR-16 | Request Review System | Meninjau, menyetujui, atau menolak pengajuan akun menjadi panitia | Admin |

## Non-Functional Requirements

| ID | Parameter | Deskripsi |
|---|---|---|
| NFR-01 | Availability | Sistem dapat diakses 24/7, terutama saat masa krusial penjualan tiket |
| NFR-02 | Security | Enkripsi password, perlindungan dari SQL Injection, keamanan token Midtrans |
| NFR-03 | Usability | Antarmuka responsif (mobile friendly) karena QR Code ditunjukkan via HP |
| NFR-04 | Performance | Load time maksimal 3 detik pada koneksi internet standar kampus |
| NFR-05 | Integrity | Data transaksi sinkron antara sistem Ticketify dengan status di Midtrans |
| NFR-06 | Scalability | Mampu menangani lonjakan trafik saat banyak pengguna checkout bersamaan |

---
Project ini dikembangkan untuk mengikuti Sertifikasi CFDA
