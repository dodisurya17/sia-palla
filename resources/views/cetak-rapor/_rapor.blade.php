{{--
    Lembar rapor (desain mengikuti rapor fisik sekolah). Dipakai bersama oleh halaman
    preview (index) dan halaman cetak (print), sehingga tampilan preview = hasil cetak.
    Variabel: $rapor (array dari CetakRaporController@buildRapor)
--}}
@php
$s = $rapor['siswa'];
$k = $rapor['kelas'];
$sem = $rapor['semester'];
$sk = $rapor['sekolah'];
$dots = '……';
$tahunPelajaran = str_replace('/', ' / ', $sem['tahun_ajaran']);
$noEkskul = count($rapor['ekskul']);
@endphp

<style>
    .rapor {
        --ink: #000;
        width: 100%;
        max-width: 210mm;
        margin: 0 auto;
        padding: clamp(16px, 4vw, 14mm);
        box-sizing: border-box;
        background: #fff;
        color: var(--ink);
        font-family: 'Figtree', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.45;
    }

    .rapor * {
        box-sizing: border-box;
    }

    /* Judul */
    .rapor-title {
        text-align: center;
        margin: 0 0 18px;
    }

    .rapor-title h2 {
        margin: 0;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    /* Identitas */
    .rapor-id {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 0 24px;
        margin-bottom: 14px;
    }

    .rapor-id dl {
        margin: 0;
    }

    .rapor-id .row {
        display: grid;
        grid-template-columns: 112px 10px 1fr;
        padding: 1px 0;
    }

    .rapor-id dt,
    .rapor-id dd {
        margin: 0;
    }

    .rapor-id dd.v {
        word-break: break-word;
    }

    @media (max-width: 560px) {
        .rapor-id {
            grid-template-columns: 1fr;
        }
    }

    /* Tabel */
    .rapor-scroll {
        overflow-x: auto;
    }

    .rp-table {
        width: 100%;
        border-collapse: collapse;
    }

    .rp-table th,
    .rp-table td {
        border: 1px solid var(--ink);
        padding: 5px 8px;
        vertical-align: middle;
    }

    .rp-table thead th {
        font-weight: 800;
        text-align: center;
    }

    .rp-table td.c {
        text-align: center;
    }

    .rp-table td.nilai {
        text-align: center;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    .rp-table td.mapel {
        text-transform: uppercase;
        font-weight: 500;
    }

    .rp-table td.cap {
        vertical-align: top;
        min-height: 34px;
        height: 34px;
    }

    .rp-table .w-no {
        width: 34px;
    }

    .rp-table .w-nilai {
        width: 58px;
    }

    .rapor tbody,
    .rapor tr {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .rapor thead {
        display: table-header-group;
    }

    .rp-gap {
        margin-top: 18px;
    }

    /* Ekstrakurikuler & catatan */
    .rp-table.ekskul td.nama {
        font-weight: 800;
        text-transform: uppercase;
    }

    .rp-table.ekskul td.catatan {
        height: 54px;
        vertical-align: top;
        font-weight: 700;
    }

    /* Ketidakhadiran & keputusan */
    .rp-bottom {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 24px;
        margin-top: 18px;
        align-items: start;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .rp-hadir th {
        font-weight: 800;
        text-align: center;
    }

    .rp-hadir td.num {
        text-align: center;
        width: 54px;
    }

    .rp-hadir td.unit {
        width: 44px;
    }

    .rp-keputusan {
        border: 1px solid var(--ink);
        padding: 6px 10px;
        font-size: 12px;
    }

    .rp-keputusan p {
        margin: 0 0 4px;
    }

    .rp-keputusan .judul {
        font-weight: 800;
    }

    @media (max-width: 560px) {
        .rp-bottom {
            grid-template-columns: 1fr;
        }
    }

    /* Tempat tanda tangan (kosong, untuk diisi tangan) */
    .rp-sign {
        margin-top: 22px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .rp-sign-top {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .rp-sign-top>div {
        text-align: center;
    }

    .rp-sign-top .kanan {
        grid-column: 2;
    }

    .rp-sign .tgl {
        min-height: 18px;
    }

    .rp-sign .ruang {
        height: 66px;
    }

    .rp-sign .nama {
        font-weight: 700;
    }

    .rp-sign .nama.garis {
        text-decoration: underline;
    }

    .rp-sign .nip {
        min-height: 18px;
    }

    .rp-sign-kepsek {
        display: flex;
        justify-content: center;
        margin-top: 4px;
        text-align: center;
    }

    @media (max-width: 560px) {
        .rp-sign-top {
            grid-template-columns: 1fr;
        }

        .rp-sign-top .kanan {
            grid-column: 1;
        }
    }

    @media print {
        .rapor {
            max-width: none;
            padding: 0;
            box-shadow: none;
            font-size: 11.5px;
        }

        .rapor,
        .rapor * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .rapor-scroll {
            overflow: visible;
        }
    }
</style>

<article class="rapor">

    <div class="rapor-title">
        <h2>Laporan Hasil Belajar</h2>
        <h2>(Rapor)</h2>
    </div>

    {{-- Identitas --}}
    <section class="rapor-id">
        <dl>
            <div class="row">
                <dt>Nama Peserta Didik</dt>
                <dd>:</dd>
                <dd class="v">{{ $s['nama'] }}</dd>
            </div>
            <div class="row">
                <dt>NISN</dt>
                <dd>:</dd>
                <dd class="v">{{ $s['nisn'] }}</dd>
            </div>
            <div class="row">
                <dt>Sekolah</dt>
                <dd>:</dd>
                <dd class="v">{{ $sk['nama'] }}</dd>
            </div>
            <div class="row">
                <dt>Alamat</dt>
                <dd>:</dd>
                <dd class="v">{{ $s['alamat'] ?: '-' }}</dd>
            </div>
        </dl>
        <dl>
            <div class="row">
                <dt>Kelas</dt>
                <dd>:</dd>
                <dd class="v">{{ $k['nama'] ?? '-' }}</dd>
            </div>
            <div class="row">
                <dt>Fase</dt>
                <dd>:</dd>
                <dd class="v">{{ $k['fase'] }}</dd>
            </div>
            <div class="row">
                <dt>Semester</dt>
                <dd>:</dd>
                <dd class="v">{{ $sem['jenis'] }}</dd>
            </div>
            <div class="row">
                <dt>Tahun Pelajaran</dt>
                <dd>:</dd>
                <dd class="v">{{ $tahunPelajaran }}</dd>
            </div>
        </dl>
    </section>

    {{-- Nilai & capaian kompetensi --}}
    <div class="rapor-scroll">
        <table class="rp-table">
            <thead>
                <tr>
                    <th class="w-no">No</th>
                    <th style="width:26%">Muatan Pelajaran</th>
                    <th class="w-nilai">Nilai Akhir</th>
                    <th>Capaian Kompetensi</th>
                </tr>
            </thead>
            @foreach ($rapor['nilai'] as $row)
            <tbody>
                <tr>
                    <td class="c" rowspan="2">{{ $row['no'] }}</td>
                    <td class="mapel" rowspan="2">{{ $row['nama'] }}</td>
                    <td class="nilai" rowspan="2">{{ $row['akhir'] }}</td>
                    <td class="cap">{{ $row['paham'] }}</td>
                </tr>
                <tr>
                    <td class="cap">{{ $row['bimbingan'] }}</td>
                </tr>
            </tbody>
            @endforeach
        </table>
    </div>

    {{-- Ekstrakurikuler + catatan wali kelas --}}
    <div class="rapor-scroll rp-gap">
        <table class="rp-table ekskul">
            <thead>
                <tr>
                    <th class="w-no">No</th>
                    <th style="width:26%">Ekstrakurikuler</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rapor['ekskul'] as $i => $e)
                <tr>
                    <td class="c">{{ $i + 1 }}</td>
                    <td class="nama">{{ $e['nama'] }}</td>
                    <td>{{ $e['keterangan'] ?: ($e['predikat'] ? 'Predikat ' . $e['predikat'] : '-') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="c">{{ $noEkskul + 1 }}</td>
                    <td class="nama">Catatan Wali Kelas</td>
                    <td class="catatan"></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Ketidakhadiran & keputusan --}}
    <section class="rp-bottom" @if (!$sem['genap']) style="grid-template-columns: 200px;" @endif>
        <table class="rp-table rp-hadir">
            <thead>
                <tr>
                    <th colspan="3">Ketidakhadiran</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sakit</td>
                    <td class="num">{{ $dots }}</td>
                    <td class="unit">hari</td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td class="num">{{ $dots }}</td>
                    <td class="unit">hari</td>
                </tr>
                <tr>
                    <td>Tanpa Keterangan</td>
                    <td class="num">{{ $dots }}</td>
                    <td class="unit">hari</td>
                </tr>
            </tbody>
        </table>

        @if ($sem['genap'])
        <div class="rp-keputusan">
            <p class="judul">Keputusan:</p>
            <p>Berdasarkan Pencapaian Kompetensi pada Semester Ke-1 dan Ke-2, siswa ditetapkan:</p>
            <p>Naik ke kelas {{ $dots }}{{ $dots }} / tinggal di kelas {{ $dots }}{{ $dots }}</p>
        </div>
        @endif
    </section>

    {{-- Tanda tangan (ruang kosong untuk tanda tangan basah) --}}
    @php
    $kota = !empty($sk['kota']) ? $sk['kota'] : 'Puu Potto';
    $kepsek = !empty($sk['kepala_sekolah']) ? $sk['kepala_sekolah'] : 'Frumensius M. E. Adolf, S.Pd';
    $nipKepsek = !empty($sk['nip_kepsek']) ? $sk['nip_kepsek'] : '-';
    @endphp
    <section class="rp-sign">
        <div class="rp-sign-top">
            <div>
                <div class="tgl">&nbsp;</div>
                <div>Orang Tua,</div>
                <div class="ruang"></div>
                <div class="nama">{{ $dots }}{{ $dots }}{{ $dots }}</div>
                <div class="nip">&nbsp;</div>
            </div>
            <div class="kanan">
                <div class="tgl">{{ $kota }}, {{ $rapor['tanggal_cetak'] }}</div>
                <div>Wali Kelas</div>
                <div class="ruang"></div>
                @if (!empty($k['wali_kelas']))
                <div class="nama garis">{{ $k['wali_kelas'] }}</div>
                @else
                <div class="nama">{{ $dots }}{{ $dots }}{{ $dots }}</div>
                @endif
                <div class="nip">NIP. -</div>
            </div>
        </div>

        <div class="rp-sign-kepsek">
            <div>
                <div>Mengetahui,</div>
                <div>Kepala Sekolah</div>
                <div class="ruang"></div>
                <div class="nama garis">{{ $kepsek }}</div>
                <div class="nip">NIP. {{ $nipKepsek }}</div>
            </div>
        </div>
    </section>
</article>