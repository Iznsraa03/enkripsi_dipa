<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transkrip Resmi Akademik - {{ $mahasiswa->nama }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 40px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #1a365d;
            padding: 40px;
            position: relative;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            border-bottom: 3px double #1a365d;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1a365d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            color: #4a5568;
            font-weight: 500;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #718096;
        }
        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .student-info table {
            width: 48%;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 4px 0;
            vertical-align: top;
        }
        .student-info td.label {
            width: 40%;
            font-weight: bold;
            color: #4a5568;
        }
        .student-info td.val {
            width: 60%;
        }
        .transcript-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 13px;
        }
        .transcript-table th {
            background-color: #1a365d;
            color: #fff;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        .transcript-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .transcript-table tr:nth-child(even) {
            background-color: #f7fafc;
        }
        .summary-card {
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 40px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        .summary-item .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #2b6cb0;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .summary-item .value {
            font-size: 24px;
            font-weight: 800;
            color: #2c5282;
            line-height: 1;
        }
        .footer-sig {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            font-size: 13px;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-box .title {
            margin-bottom: 60px;
            color: #4a5568;
        }
        .signature-box .name {
            font-weight: bold;
            text-decoration: underline;
            color: #1a365d;
        }
        .security-stamp {
            border: 2px dashed #319795;
            color: #319795;
            padding: 10px;
            font-size: 10px;
            text-align: center;
            border-radius: 4px;
            background-color: #e6fffa;
            max-width: 480px;
            margin: 0 auto;
        }
        .security-stamp strong {
            display: block;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-size: 11px;
        }
        @media print {
            body {
                padding: 0;
            }
            .container {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Sistem Informasi Akademik Resmi</h1>
        <h2>UNIVERSITAS TEKNOLOGI INFORMASI & KRIPTOGRAFI</h2>
        <p>Jl. Kampus Kripto No. 101, Gedung C, Jakarta · Telp: (021) 555-0199 · www.utik.ac.id</p>
    </div>

    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0; text-transform: uppercase; letter-spacing: 0.5px; color: #1a365d; font-size: 16px;">Transkrip Akademik Sementara (Resmi)</h3>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td class="label">Nama Mahasiswa</td>
                <td class="val">: <strong>{{ $mahasiswa->nama }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td class="val">: {{ $mahasiswa->user->nim }}</td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td class="val">: {{ $mahasiswa->program_studi }}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="label">Angkatan</td>
                <td class="val">: {{ $mahasiswa->angkatan }}</td>
            </tr>
            <tr>
                <td class="label">Semester Saat Ini</td>
                <td class="val">: {{ $mahasiswa->semester }}</td>
            </tr>
            <tr>
                <td class="label">Status Keamanan</td>
                <td class="val">: <span style="color: #319795; font-weight: bold;">✔ Terverifikasi PIN</span></td>
            </tr>
        </table>
    </div>

    <table class="transcript-table">
        <thead>
            <tr>
                <th style="width: 15%">Kode MK</th>
                <th style="width: 45%">Mata Kuliah</th>
                <th style="width: 10%; text-align: center;">SKS</th>
                <th style="width: 15%; text-align: center;">Nilai Angka</th>
                <th style="width: 15%; text-align: center;">Grade</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilais as $nilai)
                <tr>
                    <td style="font-family: monospace; font-weight: bold;">{{ $nilai->mataKuliah->kode_mk }}</td>
                    <td>{{ $nilai->mataKuliah->nama_mk }}</td>
                    <td style="text-align: center;">{{ $nilai->mataKuliah->sks }}</td>
                    <td style="text-align: center;">{{ $nilai->nilai_angka }}</td>
                    <td style="text-align: center; font-weight: bold; color: #1a365d;">{{ $nilai->grade }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #718096;">Belum ada data nilai akademik.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-card">
        <div class="summary-item">
            <div class="label">Total SKS Diambil</div>
            <div class="value">{{ $totalSks }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Indeks Prestasi Kumulatif (IPK)</div>
            <div class="value">{{ $ipk }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Predikat Kelulusan</div>
            <div class="value" style="font-size: 14px; margin-top: 6px; color: #319795; font-weight: bold;">
                {{ floatval($ipk) >= 3.51 ? 'Dengan Pujian (Cum Laude)' : (floatval($ipk) >= 3.00 ? 'Sangat Memuaskan' : 'Memuaskan') }}
            </div>
        </div>
    </div>

    <div class="security-stamp">
        <strong>Dokumen Terenkripsi Kriptografis</strong>
        Data transkrip akademik ini dilindungi dengan enkripsi simetris <strong>AES-256-GCM</strong> untuk menjaga kerahasiaan PII dan integritas nilai mahasiswa. Telah diverifikasi melalui modul otentikasi <strong>Argon2id</strong>.
    </div>

    <div class="footer-sig">
        <div class="signature-box" style="visibility: hidden;">
            <p>Ruang Kosong</p>
        </div>
        <div class="signature-box">
            <p class="title">Jakarta, {{ now()->translatedFormat('d F Y') }}<br>Kepala Biro Administrasi Akademik,</p>
            <p class="name" style="margin-top: 50px;">Dr. Eng. H. Rinaldi Munir, M.T.</p>
            <p style="font-size: 10px; color: #718096; margin: 2px 0 0 0;">NIP. 197003251996031002</p>
        </div>
    </div>
</div>

</body>
</html>
