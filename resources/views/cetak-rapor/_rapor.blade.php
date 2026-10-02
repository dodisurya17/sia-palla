{{--
    Lembar rapor. Dipakai bersama oleh halaman preview (index) dan halaman cetak (print),
    sehingga tampilan preview = hasil cetak.
    Variabel: $rapor (array dari CetakRaporController@buildRapor)
--}}
@php
    $s   = $rapor['siswa'];
    $k   = $rapor['kelas'];
    $sem = $rapor['semester'];
    $sk  = $rapor['sekolah'];
    $r   = $rapor['ringkasan'];
    $dots = '………………………………';
@endphp

<style>
    .rapor {
        --ink: #0f172a;
        --muted: #475569;
        --line: #cbd5e1;
        --head: #eff6ff;
        width: 100%;
        max-width: 210mm;
        margin: 0 auto;
        padding: clamp(16px, 4vw, 16mm);
        box-sizing: border-box;
        background: #fff;
        color: var(--ink);
        font-family: 'Figtree', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.5;
    }
    .rapor * { box-sizing: border-box; }

    /* Kop */
    .rapor-kop { display: flex; align-items: center; gap: 16px; padding-bottom: 12px; border-bottom: 3px double var(--ink); }
    .rapor-kop img { width: 64px; height: 64px; object-fit: cover; flex-shrink: 0; }
    .rapor-kop-text { flex: 1; text-align: center; }
    .rapor-kop-text .sekolah { font-size: 18px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .rapor-kop-text .alamat { margin: 2px 0 0; color: var(--muted); font-size: 11px; }
    .rapor-kop-spacer { width: 64px; flex-shrink: 0; }

    .rapor-title { text-align: center; margin: 18px 0 14px; }
    .rapor-title h2 { margin: 0; font-size: 15px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .rapor-title p { margin: 2px 0 0; color: var(--muted); font-size: 11px; }

    /* Identitas */
    .rapor-id { display: grid; grid-template-columns: 1fr 1fr; gap: 0 28px; margin-bottom: 16px; }
    .rapor-id dl { margin: 0; }
    .rapor-id .row { display: grid; grid-template-columns: 104px 10px 1fr; padding: 3px 0; }
    .rapor-id dt { color: var(--muted); margin: 0; }
    .rapor-id dd { margin: 0; font-weight: 600; word-break: break-word; }
    @media (max-width: 560px) { .rapor-id { grid-template-columns: 1fr; } }

    .rapor-section-title { margin: 16px 0 6px; font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: #1d4ed8; }

    /* Tabel */
    .rapor-scroll { overflow-x: auto; }
    .rapor-table { width: 100%; border-collapse: collapse; font-size: 11.5px; }
    .rapor-table th, .rapor-table td { border: 1px solid var(--line); padding: 5px 8px; vertical-align: middle; }
    .rapor-table thead th { background: var(--head); color: #1e3a8a; font-weight: 700; text-align: center; white-space: nowrap; }
    .rapor-table td.c { text-align: center; }
    .rapor-table td.n { text-align: center; font-variant-numeric: tabular-nums; }
    .rapor-table td.akhir { font-weight: 800; text-align: center; font-variant-numeric: tabular-nums; }
    .rapor-table tfoot td { background: #f8fafc; font-weight: 700; }
    .rapor-table tfoot .rata td { background: var(--head); color: #1e3a8a; font-size: 12px; }
    .rapor-table .kode { display: block; color: var(--muted); font-size: 10px; font-weight: 400; }
    .tag { display: inline-block; padding: 1px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; white-space: nowrap; }
    .tag.ok { background: #dcfce7; color: #166534; }
    .tag.no { background: #fee2e2; color: #991b1b; }

    .rapor-note { margin: 6px 0 0; color: var(--muted); font-size: 10.5px; }

    /* Tanda tangan */
    .rapor-sign { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 28px; text-align: center; break-inside: avoid; page-break-inside: avoid; }
    .rapor-sign .tgl { min-height: 18px; }
    .rapor-sign .jabatan { margin-top: 0; }
    .rapor-sign .ruang { height: 62px; }
    .rapor-sign .nama { font-weight: 700; text-decoration: underline; min-height: 18px; }
    .rapor-sign .nip { color: var(--muted); font-size: 10.5px; min-height: 16px; }
    @media (max-width: 560px) { .rapor-sign { grid-template-columns: 1fr; gap: 24px; } }

    .rapor .rapor-sign, .rapor .rapor-id { break-inside: avoid; }
    .rapor tr { break-inside: avoid; page-break-inside: avoid; }

    @media print {
        .rapor { max-width: none; padding: 0; box-shadow: none; font-size: 11.5px; }
        .rapor, .rapor * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .rapor-scroll { overflow: visible; }
    }
</style>

<article class="rapor">

    {{-- Kop sekolah --}}
    <header class="rapor-kop">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo sekolah">
        <div class="rapor-kop-text">
            <p class="sekolah">{{ $sk['nama'] }}</p>
            @if (!empty($sk['alamat']))
                <p class="alamat">{{ $sk['alamat'] }}</p>
            @endif
        </div>
        <div class="rapor-kop-spacer"></div>
    </header>

    <div class="rapor-title">
        <h2>Laporan Hasil Belajar</h2>
        <p>Semester {{ $sem['jenis'] }} · Tahun Ajaran {{ $sem['tahun_ajaran'] }}</p>
    </div>

    {{-- Identitas siswa --}}
    <section class="rapor-id">
        <dl>
            <div class="row"><dt>Nama Siswa</dt><dd>:</dd><dd>{{ $s['nama'] }}</dd></div>
            <div class="row"><dt>NISN</dt><dd>:</dd><dd>{{ $s['nisn'] }}</dd></div>
            <div class="row"><dt>Jenis Kelamin</dt><dd>:</dd><dd>{{ $s['jenis_kelamin'] }}</dd></div>
            <div class="row"><dt>TTL</dt><dd>:</dd><dd>{{ $s['ttl'] ?? '-' }}</dd></div>
        </dl>
        <dl>
            <div class="row"><dt>Kelas</dt><dd>:</dd><dd>{{ $k['nama'] ?? '-' }}</dd></div>
            <div class="row"><dt>Semester</dt><dd>:</dd><dd>{{ $sem['jenis'] }}</dd></div>
            <div class="row"><dt>Tahun Ajaran</dt><dd>:</dd><dd>{{ $sem['tahun_ajaran'] }}</dd></div>
            <div class="row"><dt>Orang Tua/Wali</dt><dd>:</dd><dd>{{ $s['orang_tua'] ?? '-' }}</dd></div>
        </dl>
    </section>

    {{-- Nilai akademik --}}
    <h3 class="rapor-section-title" style="margin-top:0">A. Nilai Akademik</h3>
    <div class="rapor-scroll">
        <table class="rapor-table">
            <thead>
                <tr>
                    <th style="width:34px">No</th>
                    <th style="text-align:left">Mata Pelajaran</th>
                    <th>KKM</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Nilai Akhir</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rapor['nilai'] as $row)
                    <tr>
                        <td class="c">{{ $row['no'] }}</td>
                        <td>{{ $row['nama'] }}</td>
                        <td class="n">{{ $row['kkm'] }}</td>
                        <td class="n">{{ $row['tugas'] }}</td>
                        <td class="n">{{ $row['uts'] }}</td>
                        <td class="n">{{ $row['uas'] }}</td>
                        <td class="akhir">{{ $row['akhir'] }}</td>
                        <td class="c">
                            <span class="tag {{ $row['tuntas'] ? 'ok' : 'no' }}">{{ $row['tuntas'] ? 'Tuntas' : 'Belum Tuntas' }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right">Jumlah Nilai Akhir</td>
                    <td class="n">{{ $r['jumlah_nilai'] }}</td>
                    <td></td>
                </tr>
                <tr class="rata">
                    <td colspan="6" style="text-align:right">Rata-rata Nilai Akhir</td>
                    <td class="n">{{ $r['rata_rata'] }}</td>
                    <td class="c" style="font-weight:600;font-size:10.5px">{{ $r['tuntas'] }}/{{ $r['jumlah_mapel'] }} tuntas</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <p class="rapor-note">KKM = Kriteria Ketuntasan Minimal. Siswa dinyatakan tuntas jika nilai akhir ≥ KKM mata pelajaran.</p>

    {{-- Ekstrakurikuler (hanya tampil bila ada datanya) --}}
    @if (count($rapor['ekskul']))
        <h3 class="rapor-section-title">B. Ekstrakurikuler</h3>
        <div class="rapor-scroll">
            <table class="rapor-table">
                <thead>
                    <tr>
                        <th style="width:34px">No</th>
                        <th style="text-align:left">Kegiatan</th>
                        <th>Nilai</th>
                        <th>Predikat</th>
                        <th style="text-align:left">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rapor['ekskul'] as $i => $e)
                        <tr>
                            <td class="c">{{ $i + 1 }}</td>
                            <td>{{ $e['nama'] }}</td>
                            <td class="akhir">{{ $e['nilai'] }}</td>
                            <td class="c">{{ $e['predikat'] }}</td>
                            <td>{{ $e['keterangan'] ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Tanda tangan --}}
    <section class="rapor-sign">
        <div>
            <div class="tgl">&nbsp;</div>
            <div class="jabatan">Orang Tua/Wali</div>
            <div class="ruang"></div>
            <div class="nama" style="text-decoration:none">{{ $dots }}</div>
            <div class="nip">&nbsp;</div>
        </div>
        <div>
            <div class="tgl">&nbsp;</div>
            <div class="jabatan">Wali Kelas</div>
            <div class="ruang"></div>
            @if (!empty($k['wali_kelas']))
                <div class="nama">{{ $k['wali_kelas'] }}</div>
            @else
                <div class="nama" style="text-decoration:none">{{ $dots }}</div>
            @endif
            <div class="nip">&nbsp;</div>
        </div>
        <div>
            <div class="tgl">{{ !empty($sk['kota']) ? $sk['kota'] . ', ' : '' }}{{ $rapor['tanggal_cetak'] }}</div>
            <div class="jabatan">Kepala Sekolah</div>
            <div class="ruang"></div>
            @if (!empty($sk['kepala_sekolah']))
                <div class="nama">{{ $sk['kepala_sekolah'] }}</div>
            @else
                <div class="nama" style="text-decoration:none">{{ $dots }}</div>
            @endif
            <div class="nip">{{ !empty($sk['nip_kepsek']) ? 'NIP. ' . $sk['nip_kepsek'] : '' }}</div>
        </div>
    </section>
</article>
