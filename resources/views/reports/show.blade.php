<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Seleksi PSB MA - Sesi #{{ str_pad($run->id, 4, '0', STR_PAD_LEFT) }} - Pesantren Ell Firdaus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                font-size: 11pt !important;
                padding: 0 !important;
            }
            .page-break {
                page-break-after: always;
            }
            .print-table {
                border-collapse: collapse !important;
                width: 100% !important;
            }
            .print-table th, .print-table td {
                border: 1px solid #333 !important;
                padding: 4px 6px !important;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm 1cm 1.5cm 1cm;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 font-sans min-h-screen py-6 px-4">
    <!-- Tombol Aksi di Atas (Disembunyikan saat cetak) -->
    <div class="max-w-4xl mx-auto mb-5 no-print flex items-center justify-between bg-white p-3.5 rounded-xl shadow-sm border border-slate-200">
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 hover:text-emerald-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Laporan
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('selection-results.show', $run) }}" class="px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-lg transition">
                Lihat di Dashboard
            </a>
            <button type="button" onclick="window.print()" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium rounded-lg shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Lembar Dokumen Resmi Kertas Cetak -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-sm rounded-xl print:shadow-none print:p-0 print:rounded-none border border-slate-200 print:border-none">
        <!-- KOP SURAT RESMI (Tersinkronkan dengan Pengaturan) -->
        <div class="border-b-4 border-double border-slate-900 pb-4 mb-6 text-center">
            <div class="flex items-center justify-center gap-4 mb-2">
                @if (setting_logo_url())
                    <img src="{{ setting_logo_url() }}" alt="Logo" class="w-16 h-16 object-contain shrink-0">
                @else
                    <div class="w-14 h-14 rounded-lg bg-emerald-900 text-white flex items-center justify-center font-bold text-xl print:bg-black shrink-0">
                        {{ strtoupper(substr(setting('institution_name', 'EF'), 0, 2)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-base sm:text-lg font-bold tracking-wide uppercase text-slate-900 font-serif">
                        {{ setting('institution_letterhead_title', 'YAYASAN PONDOK PESANTREN ELL FIRDAUS') }}
                    </h1>
                    <h2 class="text-lg sm:text-xl font-extrabold uppercase text-emerald-950 print:text-black tracking-wider">
                        {{ setting('institution_letterhead_subtitle', 'PANITIA PENERIMAAN SANTRI BARU (PSB)') }}
                    </h2>
                    <p class="text-[11px] sm:text-xs text-gray-600 mt-0.5">
                        {{ setting('institution_address', 'Kecamatan Kotapinang, Kabupaten Labuhanbatu Selatan, Sumatera Utara') }}
                    </p>
                    <p class="text-[10px] text-gray-500">
                        Telp: {{ setting('institution_phone', '-') }} &bull; Email: {{ setting('institution_email', '-') }} &bull; Website: {{ setting('institution_website', '-') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- JUDUL SURAT KEPUTUSAN -->
        <div class="text-center mb-6">
            <h3 class="text-sm sm:text-base font-bold uppercase underline tracking-wider text-gray-900">
                SURAT KEPUTUSAN KELULUSAN PENERIMAAN SANTRI BARU (PSB)
            </h3>
            <p class="text-xs font-semibold text-gray-700 mt-1">
                Tahun Ajaran: {{ $run->period->academic_year ?? '-' }} &bull; Gelombang: {{ $run->period->name ?? '-' }}
            </p>
            <p class="text-[11px] font-mono text-gray-500">
                Nomor: SK-PSB/MA-EF/{{ $run->run_at ? $run->run_at->format('Ym') : date('Ym') }}/{{ str_pad($run->id, 4, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <!-- METADATA & KETERANGAN METODOLOGI -->
        <div class="mb-4 text-xs grid grid-cols-2 gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 print:border-gray-400 print:bg-transparent">
            <div>
                <p><span class="font-semibold text-gray-700">Tanggal Sesi Seleksi:</span> {{ $run->run_at ? $run->run_at->translatedFormat('d F Y H:i') : '-' }}</p>
                <p><span class="font-semibold text-gray-700">Batas Kelulusan (Passing Grade):</span> {{ $run->config_snapshot['passing_grade'] ?? $run->period->passing_grade ?? 70.0 }}</p>
                <p><span class="font-semibold text-gray-700">Kuota Penerimaan:</span> {{ $run->period->quota ?? 'Tidak Dibatasi' }}</p>
            </div>
            <div>
                <p><span class="font-semibold text-gray-700">Metode Komputasi SPK:</span> Fuzzy Associative Memory (FAM)</p>
                <p><span class="font-semibold text-gray-700">Encoding / Defuzzifikasi:</span> {{ $run->encoding_method }} / {{ $run->defuzzification_method }}</p>
                <p><span class="font-semibold text-gray-700">Operator Panitia:</span> {{ $run->user->name ?? 'Administrator' }}</p>
            </div>
        </div>

        <!-- TABEL HASIL PEMERINGKATAN RESMI -->
        <div class="mb-6 overflow-x-auto">
            <table class="w-full text-left text-xs border border-gray-300 print-table">
                <thead class="bg-gray-100 text-gray-800 font-bold border-b border-gray-300 print:bg-gray-200">
                    <tr>
                        <th class="py-2 px-2 text-center w-10">No</th>
                        <th class="py-2 px-3">No. Pendaftaran</th>
                        <th class="py-2 px-3">Nama Calon Santri</th>
                        <th class="py-2 px-2 text-center">JK</th>
                        <th class="py-2 px-2 text-center">Akad.</th>
                        <th class="py-2 px-2 text-center">BTQ</th>
                        <th class="py-2 px-2 text-center">Tahf.</th>
                        <th class="py-2 px-2 text-center">Wwc.</th>
                        <th class="py-2 px-3 text-center">Skor FAM (z*)</th>
                        <th class="py-2 px-3 text-center">Status Kelulusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($details as $detail)
                        <tr class="{{ $loop->iteration % 2 === 0 ? 'bg-gray-50/50 print:bg-transparent' : '' }}">
                            <td class="py-2 px-2 text-center font-bold">{{ $detail->rank }}</td>
                            <td class="py-2 px-3 font-mono font-medium">{{ $detail->candidate->registration_number }}</td>
                            <td class="py-2 px-3 font-semibold text-gray-900">{{ $detail->candidate->name }}</td>
                            <td class="py-2 px-2 text-center">{{ $detail->candidate->gender === 'L' ? 'L' : 'P' }}</td>
                            <td class="py-2 px-2 text-center font-mono">{{ number_format($detail->candidate->score->academic_score ?? 0, 1) }}</td>
                            <td class="py-2 px-2 text-center font-mono">{{ number_format($detail->candidate->score->btq_score ?? 0, 1) }}</td>
                            <td class="py-2 px-2 text-center font-mono">{{ number_format($detail->candidate->score->tahfidz_score ?? 0, 1) }}</td>
                            <td class="py-2 px-2 text-center font-mono">{{ number_format($detail->candidate->score->interview_score ?? 0, 1) }}</td>
                            <td class="py-2 px-3 text-center font-mono font-bold">{{ number_format($detail->final_score, 2) }}</td>
                            <td class="py-2 px-3 text-center font-bold">
                                @if ($detail->decision === 'DITERIMA')
                                    <span class="text-emerald-700 font-extrabold print:text-black">DITERIMA</span>
                                @else
                                    <span class="text-rose-700 font-normal print:text-black">TIDAK DITERIMA</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-4 text-center text-gray-500">
                                Tidak ada data hasil seleksi pada sesi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- REKAPITULASI JUMLAH -->
        <div class="mb-8 p-3 rounded-xl border border-gray-300 bg-gray-50 text-xs flex justify-between items-center print:bg-transparent">
            <div>
                <span class="font-bold">Total Calon Santri:</span> {{ $run->total_candidates }} Orang
            </div>
            <div>
                <span class="font-bold text-emerald-800 print:text-black">Dinyatakan DITERIMA:</span> {{ $run->passed_candidates }} Orang
            </div>
            <div>
                <span class="font-bold text-rose-800 print:text-black">TIDAK DITERIMA:</span> {{ $run->failed_candidates }} Orang
            </div>
        </div>

        <!-- TANDA TANGAN & PENGESAHAN (Tersinkronkan dengan Pengaturan) -->
        <div class="grid grid-cols-2 gap-8 text-xs text-center mt-12 pt-4">
            <div>
                <p class="text-gray-600 print:text-black">Mengetahui,</p>
                <p class="font-bold text-gray-900 mt-0.5">{{ setting('leader_title', 'Pimpinan Pondok Pesantren') }}</p>
                <div class="h-20"></div>
                <p class="font-bold underline text-gray-900">( {{ setting('leader_name', 'H. Ahmad Fauzi, Lc., M.Pd.') }} )</p>
                <p class="text-[10px] text-gray-500 print:text-black">{{ setting('leader_nip', 'NIP. 19780512 200604 1 003') }}</p>
            </div>
            <div>
                <p class="text-gray-600 print:text-black">Kotapinang, {{ $run->run_at ? $run->run_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold text-gray-900 mt-0.5">{{ setting('committee_chairman_title', 'Ketua Panitia Seleksi PSB') }}</p>
                <div class="h-20"></div>
                <p class="font-bold underline text-gray-900">( {{ setting('committee_chairman_name', $run->user->name ?? 'Ust. Muhammad Ridwan, S.Pd.I.') }} )</p>
                <p class="text-[10px] text-gray-500 print:text-black">Panitia Penerimaan Santri Baru</p>
            </div>
        </div>
    </div>
</body>
</html>
