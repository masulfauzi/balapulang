@extends('layouts.app')

@section('page-css')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row mb-2">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <a href="{{ route('kunjungan.index') }}" class="btn btn-sm icon icon-left btn-outline-secondary"><i
                            class="fa fa-arrow-left"></i> Kembali </a>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kunjungan.index') }}">{{ $title }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $kunjungan->nama }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <h6 class="card-header">
                    Detail Data {{ $title }}: {{ $kunjungan->nama }}
                </h6>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-2">
                            <div class="row">
                                <div class='col-lg-2'>
                                    <p>Nasabah</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ $kunjungan->nasabah->nama_nasabah }}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Hasil Kunjungan</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{!! $kunjungan->hasil_kunjungan !!}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Foto</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'><img width="100%" src="{{ url('uploads/' . $kunjungan->foto) }}"
                                            alt=""></p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Status Kunjungan</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ $kunjungan->statusKunjungan->status_kunjungan }}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Operator</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ $kunjungan->user->name }}</p>
                                </div>
                                <div class='col-lg-2'>
                                    <p>Tgl Kunjungan</p>
                                </div>
                                <div class='col-lg-10'>
                                    <p class='fw-bold'>{{ \App\Helpers\Format::tanggal($kunjungan->tgl_kunjungan) }}</p>
                                </div>

                                @if ($kunjungan->statusKunjungan->status_kunjungan != 'Valid')
                                    @if (session()->get('active_role')['role'] == 'Supervisor')
                                        <div class='col-lg-10'>
                                            <button
                                                onclick="validasiConfirm('{{ route('kunjungan.validasi.update', $kunjungan->id) }}')"
                                                class="btn btn-danger">Validasi</button>
                                        </div>
                                    @endif
                                @endif



                            </div>
                        </div>
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