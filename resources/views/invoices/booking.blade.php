<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $booking->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: 130mm 185mm;
            margin: 4mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8.5px;
            line-height: 1.3;
            color: #1f2937;
            background: #ffffff;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 0;
        }

        .paper {
            background: #ffffff;
            border-radius: 0;
            padding: 8px 18px 12px;
            box-shadow: none;
            page-break-inside: avoid;
        }

        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 10px;
            padding: 10px 14px;
        }

        .header-left {
            max-width: 60%;
        }

        .company-name {
            font-size: 15px;
            font-weight: 700;
            color: #1d4ed8;
            margin-bottom: 4px;
        }

        .tagline {
            font-size: 11px;
            color: #6b7280;
        }

        .invoice-card {
            text-align: right;
        }

        .invoice-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            letter-spacing: 0.2em;
        }

        .invoice-meta {
            margin-top: 6px;
            font-size: 11px;
            color: #374151;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 700;
            margin-top: 4px;
            letter-spacing: 0.4px;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .section {
            margin-bottom: 8px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 9.5px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }

        .section-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px 12px;
            background: #f9fbff;
        }

        .section-card.dense {
            padding: 6px 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
        }

        .info-table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 110px;
            font-size: 8px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 0;
            vertical-align: top;
        }

        .info-value {
            font-size: 9.5px;
            color: #111827;
            font-weight: 600;
            padding: 3px 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 4px 10px;
        }

        .info-pair {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .info-pair-label {
            font-size: 8px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-pair-value {
            font-size: 9.5px;
            color: #111827;
            font-weight: 600;
            word-break: break-word;
        }

        .price-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
        }

        .price-table th,
        .price-table td {
            padding: 2px 2px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .price-table th {
            text-transform: uppercase;
            font-size: 8px;
            color: #6b7280;
            letter-spacing: 0.05em;
        }

        .price-table thead {
            background: #f0f4ff;
        }

        .text-right {
            text-align: right;
            white-space: nowrap;
        }

        .total-row td {
            border-bottom: none;
            font-weight: 700;
            color: #1d4ed8;
            font-size: 10px;
        }

        .total-highlight {
            margin-top: 6px;
            padding: 8px 12px;
            border-radius: 10px;
            background: #eef2ff;
            border: 1px solid #cfd8ff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            color: #1d4ed8;
        }

        .notes-box {
            border-radius: 10px;
            padding: 8px 12px;
            border: 1px solid #e0e7ff;
            background: #f6f7ff;
            margin-bottom: 6px;
        }

        .notes-box.warning {
            border-color: #fcd34d;
            background: #fffbeb;
        }

        .notes-content {
            font-size: 8.5px;
            line-height: 1.5;
            color: #374151;
        }

        .footer {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 8px;
            color: #6b7280;
            text-align: center;
            page-break-inside: avoid;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .container {
                padding: 0;
            }

            .paper {
                box-shadow: none;
                border-radius: 0;
                padding: 4mm;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="paper">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <div class="company-name">{{ $setting->site_name ?? 'Travel Agency' }}</div>
                    <div class="tagline">Invoice Booking • {{ $booking->route->origin }} ke
                        {{ $booking->route->destination }}</div>
                </div>
                <div class="invoice-card">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-meta">Kode: {{ $booking->booking_code }}</div>
                    <div>
                        @php
                            $statusClass = match ($booking->status) {
                                'pending' => 'status-pending',
                                'confirmed' => 'status-confirmed',
                                'completed' => 'status-completed',
                                'cancelled' => 'status-cancelled',
                                default => 'status-pending',
                            };
                            $statusText = match ($booking->status) {
                                'pending' => 'MENUNGGU KONFIRMASI',
                                'confirmed' => 'DIKONFIRMASI',
                                'completed' => 'SELESAI',
                                'cancelled' => 'DIBATALKAN',
                                default => strtoupper($booking->status),
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="section">
                <div class="section-title">Data Pemesan</div>
                <div class="section-card">
                    <div class="info-grid">
                        <div class="info-pair">
                            <div class="info-pair-label">Nama</div>
                            <div class="info-pair-value">{{ $booking->customer->name }}</div>
                        </div>
                        <div class="info-pair">
                            <div class="info-pair-label">No. Telepon</div>
                            <div class="info-pair-value">{{ $booking->customer->phone }}</div>
                        </div>
                        <div class="info-pair">
                            <div class="info-pair-label">Email</div>
                            <div class="info-pair-value">{{ $booking->customer->email ?? '-' }}</div>
                        </div>
                        <div class="info-pair">
                            <div class="info-pair-label">Tanggal Booking</div>
                            <div class="info-pair-value">{{ $booking->created_at->format('d M Y H:i') }} WIB</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trip Details -->
            <div class="section">
                <div class="section-title">Detail Perjalanan</div>
                <div class="section-card dense">
                    <table class="info-table">
                        <tr>
                            <td class="info-label">Rute</td>
                            <td class="info-value">{{ $booking->route->origin }} → {{ $booking->route->destination }}
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Kode Rute</td>
                            <td class="info-value">{{ $booking->route->route_code }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Tanggal Keberangkatan</td>
                            <td class="info-value">{{ $booking->travel_date->format('d M Y') }}</td>
                        </tr>
                        @if ($booking->travel_time)
                            <tr>
                                <td class="info-label">Jam Keberangkatan</td>
                                <td class="info-value">{{ $booking->travel_time->format('H:i') }} WIB</td>
                            </tr>
                        @endif
                        @if ($booking->pickup_location)
                            <tr>
                                <td class="info-label">Lokasi Penjemputan</td>
                                <td class="info-value">{{ $booking->pickup_location }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="info-label">Armada</td>
                            <td class="info-value">{{ $booking->armada->name }} ({{ $booking->armada->plate_number }})
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Jenis Kendaraan</td>
                            <td class="info-value">{{ $booking->armada->vehicle_type }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Kategori</td>
                            <td class="info-value">{{ $booking->category->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Price Details -->
            <div class="section">
                <div class="section-title">Rincian Harga</div>
                <div class="section-card dense">
                    <table class="price-table">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th class="text-right">Jumlah</th>
                                <th class="text-right">Harga Satuan</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tiket Perjalanan<br><small style="color: #6b7280;">{{ $booking->route->origin }} -
                                        {{ $booking->route->destination }}</small></td>
                                <td class="text-right">{{ $booking->total_passengers }} orang</td>
                                <td class="text-right">Rp {{ number_format($booking->price_per_person, 0, ',', '.') }}
                                </td>
                                <td class="text-right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="3" class="text-right">TOTAL PEMBAYARAN</td>
                                <td class="text-right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="total-highlight">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if ($booking->notes)
                <div class="section">
                    <div class="section-title">Catatan</div>
                    <div class="notes-box">
                        <div class="notes-content">{{ $booking->notes }}</div>
                    </div>
                </div>
            @endif

            @if ($booking->status === 'pending')
                <div class="section">
                    <div class="section-title">Informasi Penting</div>
                    <div class="notes-box warning">
                        <div class="notes-content">
                            Booking Anda sedang menunggu konfirmasi. Jam keberangkatan dan lokasi penjemputan akan
                            dikonfirmasi oleh tim kami segera.
                        </div>
                    </div>
                </div>
            @endif

            <!-- Footer -->
            <div class="footer">
                <p style="margin-top: 5px;">Terima kasih telah menggunakan layanan
                    {{ $setting->site_name ?? 'Travel Agency' }}</p>
            </div>
        </div>
    </div>
</body>

</html>
