@extends('layouts.app')

@section('page-css')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row mb-2">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Data {{ $title }}</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <h6 class="card-header">
                    Filter Data
                </h6>
                <div class="card-body">
                    @include('include.flash')
                    <form class="form form-horizontal" action="" method="GET">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3 text-sm-start text-md-end pt-2">
                                    <label>Filter</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    {{ Form::select('filter', $filter, $filter_aktif, ['class' => 'form-control select2']) }}
                                    @error('filter')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3 text-sm-start text-md-end pt-2">
                                    <label>Dinas</label>
                                </div>
                                <div class="col-md-9 form-group">
                                    {{ Form::select('id_dinas', $dinas, $dinas_aktif, ['class' => 'form-control select2']) }}
                                    @error('filter')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="offset-md-3 ps-2">
                                <button class="btn btn-primary" type="submit">Simpan</button> &nbsp;
                                <a href="{{ route('nasabah.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </form>

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
                            <form action="{{ route('nasabah.index') }}" method="get">
                                <div class="form-group col-md-3 has-icon-left position-relative">
                                    <input type="hidden" name="filter" value="{{ $filter_aktif }}">
                                    <input type="text" class="form-control" value="{{ request()->get('search') }}"
                                        name="search" placeholder="Search">
                                    <div class="form-control-icon"><i class="fa fa-search"></i></div>
                                </div>
                            </form>
                        </div>
                        <div class="col-3">
                            {!! button('nasabah.create', $title) !!}
                        </div>
                    </div>
                    @include('include.flash')
                    <div class="table-responsive-md col-12">
                        <table class="table" id="table1">
                            <thead>
                                <tr>
                                    <th width="15">No</th>
                                    <td>Nama Nasabah</td>
                                    <td>Alamat</td>
                                    <td>Status</td>
                                    <td>Bank Gaji</td>
                                    <td>Unit</td>
                                    <td>Keterangan</td>
                                    <td>Nip</td>
                                    <td>Tempat Lahir</td>
                                    <td>Tgl Lahir</td>

                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = $data->firstItem(); @endphp
                                @forelse ($data as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->nama_nasabah }}</td>
                                        <td>{!! $item->alamat !!}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            @if ($item->id_bank_gaji != null)
                                                {{ $item->bankGaji->nama_bank }}
                                            @else
                                                <b>Belum ada data</b>
                                            @endif
                                        </td>
                                        <td>{{ $item->unit->nama_unit }}</td>
                                        <td>{!! $item->keterangan !!}</td>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->tempat_lahir }}</td>
                                        <td>{{ \App\Helpers\Format::tanggal($item->tgl_lahir) }}</td>

                                        <td>
                                            {!! button('nasabah.show', '', $item->id) !!}
                                            @if (session()->get('active_role')['role'] == 'Operator')
                                            @else
                                                {!! button('nasabah.edit', $title, $item->id) !!}
                                                {!! button('nasabah.destroy', $title, $item->id) !!}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center"><i>No data.</i></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $data->links() }}
                </div>
            </div>

        </section>
    </div>
@endsection

@section('page-js')
@endsection

@section('inline-js')
@endsection
