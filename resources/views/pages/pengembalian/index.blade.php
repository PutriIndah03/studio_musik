@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Pengembalian</h2>

    @if (session('success'))
    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        setTimeout(function () {
            let alert = document.getElementById('success-alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000); // Hilang dalam 5 detik
    </script>
    @endif

    <table class="table table-bordered table-sm small">
        <thead>
            <tr class="bg-primary text-white">
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Studio Musik</th>
                <th style="background-color: #0d6efd; color: white;">Alat Musik</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Kembali</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Ket. Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Kondisi</th>
                <th style="background-color: #0d6efd; color: white;">Catatan Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengembalian as $index => $data)
            <tr>
                <td>{{ $loop->iteration + ($pengembalian->currentPage() - 1) * $pengembalian->perPage() }}</td>
                
                <!-- Studio Musik -->
                <td>{{ optional($data->studio_musik)->nama ?? '-' }}</td>
                
                <!-- Alat Musik -->
                <td style="text-align: left">
                    @if($data->alat_musik instanceof Illuminate\Support\Collection)
                        @if($data->alat_musik->count() > 1)
                            @foreach($data->alat_musik as $loopIndex => $alat)
                                {{ $loop->iteration }}. {{ $alat->nama }} <br>
                            @endforeach
                        @elseif($data->alat_musik->count() == 1)
                            @php $alat = $data->alat_musik->first(); @endphp
                            {{ $alat->nama }}
                        @endif
                    @elseif($data->alat_musik)
                        {{ $data->alat_musik->nama }}
                    @else
                        -
                    @endif
                </td>
                
                
                <!-- Tanggal & Waktu Kembali -->
                <td>{{ \Carbon\Carbon::parse($data->tanggal_kembali)->format('d-m-Y H:i') }}</td>

                <!-- Tanggal & Waktu Pengembalian -->
                <td>{{ optional($data->pengembalian)->tanggal_pengembalian ? \Carbon\Carbon::parse($data->pengembalian->tanggal_pengembalian)->format('d-m-Y H:i') : '-' }}</td>

                <!-- Keterangan Pengembalian -->
                <td>
                    @if(optional($data->pengembalian)->keterangan_pengembalian == 'Terlambat')
                        <bold class="text-danger">{{ optional($data->pengembalian)->keterangan_pengembalian }}</bold>
                    @elseif(optional($data->pengembalian)->keterangan_pengembalian == 'Tepat Waktu')
                        <bold class="text-success">{{ optional($data->pengembalian)->keterangan_pengembalian }}</bold>
                    @else
                        <span class="text-muted">{{ optional($data->pengembalian)->keterangan_pengembalian ?? '-' }}</span>
                    @endif
                </td>
                

                <!-- Kondisi Alat Musik -->
                <td style="text-align: left">
                    @if($data->pengembalian && $data->pengembalian->kondisi)
                        @php
                            // Mendekode kondisi pengembalian
                            $kondisiAlat = json_decode($data->pengembalian->kondisi, true);
                            $detailExist = !empty($data->pengembalian->detail);  // Mengecek apakah detail pengembalian ada
                        @endphp
                
                        @if(is_array($kondisiAlat) && count($kondisiAlat) > 1)
                            @php $nomor = 1; @endphp
                            @foreach($kondisiAlat as $alat_id => $kondisi)
                                @php
                                    $alat = $data->alat_musik->firstWhere('id', $alat_id) ?? null;
                                    $namaAlat = $alat ? $alat->nama : 'Alat tidak ditemukan';
                                    // Mengecek apakah kondisi alat sudah diubah berdasarkan detail
                                    $isChanged = $detailExist && strtolower($kondisi) !== 'baik';
                                    
                                    // Menentukan warna berdasarkan kondisi hanya jika diubah
                                    $warnaKondisi = '';
                                    if ($isChanged) {
                                        switch(strtolower($kondisi)) {
                                            case 'baik':
                                                $warnaKondisi = 'text-success';  // Hijau
                                                break;
                                            case 'rusak ringan':
                                                $warnaKondisi = 'text-warning';  // Kuning
                                                break;
                                            case 'rusak':
                                                $warnaKondisi = 'text-danger';   // Merah
                                                break;
                                            default:
                                                $warnaKondisi = 'text-dark';     // Hitam untuk kondisi lainnya
                                        }
                                    } else {
                                        $warnaKondisi = 'text-dark';  // Tetap hitam jika tidak ada perubahan
                                    }
                                @endphp
                                {{ $nomor++ }}. 
                                <span 
                                    class="{{ $warnaKondisi }}{{ $isChanged ? ' fw-semibold' : '' }}" 
                                    @if($isChanged) title="Kondisi dikembalikan berbeda" @endif
                                >
                                    {{ $kondisi ?? '-' }}
                                </span><br>
                            @endforeach
                        @elseif(is_array($kondisiAlat) && count($kondisiAlat) == 1)
                            @php
                                $alat_id = array_key_first($kondisiAlat);
                                $alat = $data->alat_musik->firstWhere('id', $alat_id) ?? null;
                                $kondisi = reset($kondisiAlat);
                                $namaAlat = $alat ? $alat->nama : 'Alat tidak ditemukan';
                                // Mengecek apakah kondisi alat sudah diubah berdasarkan detail
                                $isChanged = $detailExist && strtolower($kondisi) !== 'baik';
                                
                                // Menentukan warna berdasarkan kondisi hanya jika diubah
                                $warnaKondisi = '';
                                if ($isChanged) {
                                    switch(strtolower($kondisi)) {
                                        case 'baik':
                                            $warnaKondisi = 'text-success';  // Hijau
                                            break;
                                        case 'rusak ringan':
                                            $warnaKondisi = 'text-warning';  // Kuning
                                            break;
                                        case 'rusak':
                                            $warnaKondisi = 'text-danger';   // Merah
                                            break;
                                        default:
                                            $warnaKondisi = 'text-dark';     // Hitam untuk kondisi lainnya
                                    }
                                } else {
                                    $warnaKondisi = 'text-dark';  // Tetap hitam jika tidak ada perubahan
                                }
                            @endphp
                            
                            <span 
                                class="{{ $warnaKondisi }}{{ $isChanged ? ' fw-semibold' : '' }}" 
                                @if($isChanged) title="Kondisi dikembalikan berbeda" @endif
                            >
                                {{ $kondisi ?? '-' }}
                            </span>
                        @else
                            {{ $data->pengembalian->kondisi ?? '-' }}
                        @endif
                
                        {{-- Tombol Detail hanya muncul jika kolom detail tidak kosong --}}
                        @if($detailExist)
                            <div class="mt-1">
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#alasanModal{{ $data->id }}">
                                    Detail
                                </button>
                
                                <!-- Modal -->
                                <div class="modal fade" id="alasanModal{{ $data->id }}" tabindex="-1" aria-labelledby="alasanModalLabel{{ $data->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="alasanModalLabel{{ $data->id }}">Detail Pengembalian</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $data->pengembalian->detail }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        -
                    @endif
                </td>
                
                
                
                <!-- Alasan -->
                <td>{{ optional($data->pengembalian)->alasan ?? '-' }}</td>

                <!-- Status Pengembalian -->
                <td>
                    @if(optional($data->pengembalian)->status == 'Menunggu')
                        <span class="badge bg-warning">Menunggu</span>
                    @elseif(optional($data->pengembalian)->status == 'Diterima')
                        <span class="badge bg-success">Diterima</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
                
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data pengembalian</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $pengembalian->links() }}
    </div>
</div>
@endsection
