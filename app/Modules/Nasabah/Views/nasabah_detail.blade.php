@extends('layouts.app')

@section('page-css')
@endsection

@section('main')
<div class="page-heading">
    <div class="page-title">
        <div class="row mb-2">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <a href="{{ route('nasabah.index') }}" class="btn btn-sm icon icon-left btn-outline-secondary"><i class="fa fa-arrow-left"></i> Kembali </a>
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
                            <div class='col-lg-2'><p>Alamat</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $nasabah->alamat }}</p></div>
									<div class='col-lg-2'><p>Cif</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $nasabah->cif }}</p></div>
									<div class='col-lg-2'><p>Bank Gaji</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $nasabah->bankGaji->id }}</p></div>
									<div class='col-lg-2'><p>Unit</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $nasabah->unit->id }}</p></div>
									<div class='col-lg-2'><p>Keterangan</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $nasabah->keterangan }}</p></div>
									<div class='col-lg-2'><p>Nama Nasabah</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $nasabah->nama_nasabah }}</p></div>
									<div class='col-lg-2'><p>Nip</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $nasabah->nip }}</p></div>
									<div class='col-lg-2'><p>Tempat Lahir</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $nasabah->tempat_lahir }}</p></div>
									<div class='col-lg-2'><p>Tgl Lahir</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $nasabah->tgl_lahir }}</p></div>
									
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