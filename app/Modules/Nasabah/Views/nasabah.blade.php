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
                Tabel Data {{ $title }}
            </h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <form action="{{ route('nasabah.index') }}" method="get">
                            <div class="form-group col-md-3 has-icon-left position-relative">
                                <input type="text" class="form-control" value="{{ request()->get('search') }}" name="search" placeholder="Search">
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
                                <td>Alamat</td>
								<td>Cif</td>
								<td>Bank Gaji</td>
								<td>Unit</td>
								<td>Keterangan</td>
								<td>Nama Nasabah</td>
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
                                    <td>{{ $item->alamat }}</td>
									<td>{{ $item->cif }}</td>
									<td>{{ $item->bankGaji->nama_bank }}</td>
									<td>{{ $item->unit->nama_unit }}</td>
									<td>{{ $item->keterangan }}</td>
									<td>{{ $item->nama_nasabah }}</td>
									<td>{{ $item->nip }}</td>
									<td>{{ $item->tempat_lahir }}</td>
									<td>{{ \App\Helpers\Format::tanggal($item->tgl_lahir ) }}</td>

                                    <td>
										{!! button('nasabah.show','', $item->id) !!}
										{!! button('nasabah.edit', $title, $item->id) !!}
                                        {!! button('nasabah.destroy', $title, $item->id) !!}
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
