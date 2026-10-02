<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapor - {{ $rapor['siswa']['nama'] }} - {{ $rapor['semester']['jenis'] }} {{ str_replace('/', '-', $rapor['semester']['tahun_ajaran']) }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        @page { size: A4 portrait; margin: 14mm; }
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; background: #e2e8f0; }
        body { font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; }

        .toolbar {
            position: sticky; top: 0; z-index: 10;
            display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px;
            padding: 12px clamp(12px, 3vw, 28px);
            background: #fff; border-bottom: 1px solid rgba(15, 23, 42, .06);
        }
        .toolbar p { margin: 0; font-size: 13px; color: #64748b; }
        .toolbar strong { color: #0f172a; }
        .toolbar .actions { display: flex; gap: 8px; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 16px; border-radius: 12px; border: 1px solid #e2e8f0;
            font: 600 13px/1 'Figtree', ui-sans-serif, system-ui, sans-serif;
            color: #475569; background: #fff; text-decoration: none; cursor: pointer; transition: background .15s;
        }
        .btn:hover { background: #f8fafc; }
        .btn.primary { background: #2563eb; border-color: #2563eb; color: #fff; }
        .btn.primary:hover { background: #1d4ed8; }

        .page { padding: clamp(12px, 3vw, 28px); }
        .page .rapor { box-shadow: 0 10px 30px rgba(15, 23, 42, .08); border-radius: 4px; }

        @media print {
            html, body { background: #fff; }
            .toolbar { display: none; }
            .page { padding: 0; }
            .page .rapor { box-shadow: none; border-radius: 0; }
        }
    </style>
</head>

<body>
    <div class="toolbar">
        <p><strong>{{ $rapor['siswa']['nama'] }}</strong> · Semester {{ $rapor['semester']['jenis'] }} {{ $rapor['semester']['tahun_ajaran'] }}</p>
        <div class="actions">
            <a class="btn" href="{{ route('cetak-rapor.index', ['siswa_id' => $rapor['siswa']['id'], 'semester_id' => $rapor['semester']['id']]) }}">Kembali</a>
            <button type="button" class="btn primary" onclick="window.print()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <div class="page">
        @include('cetak-rapor._rapor', ['rapor' => $rapor])
    </div>

    <script>
        // Buka dialog cetak otomatis setelah font & logo selesai dimuat.
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 400);
        });
    </script>
</body>

</html>
