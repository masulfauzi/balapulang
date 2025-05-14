@extends('layouts.app')

@section('page-css')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row mb-2">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <a href="{{ route('nasabah.index') }}" class="btn btn-sm icon icon-left btn-outline-secondary"><i
                            class="fa fa-arrow-left"></i> Kembali </a>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('nasabah.index') }}">{{ $title }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $nasabah->nama }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <h6 class="card-header">
                    Detail Data {{ $title }}: {{ $nasabah->nama }}
                </h6>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-2">
                            <div class="row">
                                <div class='col-lg-2'>
                                    <p>Nama Nasabah</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ $nasabah->nama_nasabah }}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Nip</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ $nasabah->nip }}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Alamat</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{!! $nasabah->alamat !!}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Cif</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ $nasabah->cif }}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Bank Gaji</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>
                                        @if ($nasabah->id_bank_gaji == null)
                                            <b>Belum ada data</b>
                                        @else
                                            {{ $nasabah->bankGaji->nama_bank }}
                                        @endif
                                    </p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Unit</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ $nasabah->unit->nama_unit }}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Keterangan</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{!! $nasabah->keterangan !!}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Tempat Lahir</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ $nasabah->tempat_lahir }}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Tgl Lahir</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ \App\Helpers\Format::tanggal($nasabah->tgl_lahir) }}</p>
                                </div>
                                {{-- <div class='col-lg-2'>
                                    <p>Aksi</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'><a
                                            href="{{ route('kunjungan.create', ['id_nasabah' => $nasabah->id]) }}"
                                            class="btn btn-primary">Tambah
                                            Kunjungan</a></p>
                                </div> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="section">
            <div class="card">
                <h6 class="card-header">
                    Tabel Data {{ $title }}
                </h6>
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <form action="{{ route('kunjungan.index') }}" method="get">
                                <div class="form-group col-md-3 has-icon-left position-relative">
                                    <input type="text" class="form-control" value="{{ request()->get('search') }}"
                                        name="search" placeholder="Search">
                                    <div class="form-control-icon"><i class="fa fa-search"></i></div>
                                </div>
                            </form>
                        </div>
                        <div class="col-3">
                            <a href="{{ route('kunjungan.create', ['id_nasabah' => $nasabah->id]) }}"
                                class="btn btn-primary">Tambah
                                Kunjungan</a>
                        </div>
                    </div>
                    @include('include.flash')
                    <div class="table-responsive-md col-12">
                        <table class="table" id="table1">
                            <thead>
                                <tr>
                                    <th width="15">No</th>
                                    <td>Nasabah</td>
                                    <td>Hasil Kunjungan</td>
                                    <td>Status Kunjungan</td>
                                    <td>Operator</td>
                                    <td>Tgl Kunjungan</td>

                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($kunjungan as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->nasabah->nama_nasabah }}</td>
                                        <td>{!! $item->hasil_kunjungan !!}</td>
                                        <td>{{ $item->statusKunjungan->status_kunjungan }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ \App\Helpers\Format::tanggal($item->tgl_kunjungan) }}</td>

                                        <td>
                                            {!! button('kunjungan.show', '', $item->id) !!}
                                            {{-- {!! button('kunjungan.edit', $title, $item->id) !!}
                                            {!! button('kunjungan.destroy', $title, $item->id) !!} --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center"><i>No data.</i></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@section('page-js')
@endsection

@section('inline-js')
@endsection