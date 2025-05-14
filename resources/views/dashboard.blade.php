@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row mb-2">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        @include('include.flash')
        <section class="row">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Data Nasabah</h6>
                                        <h6 class="font-extrabold mb-0">{{ count($nasabah) }}</h6>
                                        <a href="/nasabah" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Nasabah Pensiun</h6>
                                        <h6 class="font-extrabold mb-0">{{ count($nasabah->where('is_pensiun', '1')) }}</h6>
                                        <a href="/nasabah?filter=pensiun" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Nasabah Aktif</h6>
                                        <h6 class="font-extrabold mb-0">{{ count($nasabah->where('is_pensiun', '0')) }}</h6>
                                        <a href="/nasabah?filter=aktif" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Pensiun Tahun Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ count(value: $pensiun_tahun_ini) }}</h6>
                                        <a href="/nasabah?filter=pensiun_tahun_ini" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Pensiun Bulan Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ count($pensiun_bulan_ini) }}</h6>
                                        <a href="/nasabah?filter=pensiun_bulan_ini" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Pensiun Bulan Depan</h6>
                                        <h6 class="font-extrabold mb-0">{{ count($pensiun_bulan_depan) }}</h6>
                                        <a href="/nasabah?filter=pensiun_bulan_depan" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

        </section>
    </div>
@endsection

@section('page-js')
    {{--
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chart-pensiun');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Pensiun'],
                datasets: [{
                    data: [{{ count($nasabah->where('is_pensiun', '0')) }},
                        {{ count($nasabah->where('is_pensiun', '1')) }}
                    ],
                    borderWidth: 1
                }]
            }
        });
    </script>
@endsection