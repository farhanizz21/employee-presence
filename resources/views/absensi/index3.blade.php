@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Absensi Pegawaiaa</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Absensi Pegawai
                    </li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->

<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h4 class="mb-3">Absensi Pegawai</h4>

            {{-- Search Box --}}
            <form action="" method="GET" class="mb-2">
                <div class="input-group input-group-lg mx-auto" style="max-width: 600px;">
                    <input type="text" name="q" class="form-control rounded-pill" placeholder="Cari nama pegawai..."
                        style="border-radius: 50px; padding-left: 20px;">
                    <button type="submit" class="btn btn-warning rounded-pill ms-2 px-3 py-1 fs-6">
                        <i class="fas fa-plus"></i> Tambah ke daftar antrian
                    </button>
                </div>

            </form>

            <!--begin::Latest Order Widget-->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Latest Orders</h3>
                    <div class="card-tools"> <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i> <i data-lte-icon="collapse"
                                class="bi bi-dash-lg"></i> </button> <button type="button" class="btn btn-tool"
                            data-lte-toggle="card-remove"> <i class="bi bi-x-lg"></i> </button> </div>
                </div> <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Item</th>
                                    <th>Status</th>
                                    <th>Popularity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> <a href="pages/examples/invoice.html"
                                            class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR9842</a>
                                    </td>
                                    <td>Call of Duty IV</td>
                                    <td> <span class="badge text-bg-success">
                                            Shipped
                                        </span> </td>
                                    <td>
                                        <div id="table-sparkline-1"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <a href="pages/examples/invoice.html"
                                            class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR1848</a>
                                    </td>
                                    <td>Samsung Smart TV</td>
                                    <td> <span class="badge text-bg-warning">Pending</span> </td>
                                    <td>
                                        <div id="table-sparkline-2"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <a href="pages/examples/invoice.html"
                                            class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR7429</a>
                                    </td>
                                    <td>iPhone 6 Plus</td>
                                    <td> <span class="badge text-bg-danger">
                                            Delivered
                                        </span> </td>
                                    <td>
                                        <div id="table-sparkline-3"></div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div> <!-- /.table-responsive -->
                </div> <!-- /.card-body -->
                <div class="card-footer clearfix"> <a href="javascript:void(0)"
                        class="btn btn-sm btn-primary float-start">
                        Place New Order
                    </a> <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-end">
                        View All Orders
                    </a> </div> <!-- /.card-footer -->
            </div> <!-- /.card -->

            {{-- Tombol Status --}}
            <div class="mt-3">
                <button class="btn btn-success rounded-pill px-4 mx-1">
                    <i class="fas fa-check-circle"></i> Hadir
                </button>
                <button class="btn btn-warning rounded-pill px-4 mx-1">
                    <i class="fas fa-exclamation-circle"></i> Izin
                </button>
                <button class="btn btn-danger rounded-pill px-4 mx-1">
                    <i class="fas fa-times-circle"></i> Tidak Hadir
                </button>
            </div>

        </div>
    </div>
</div>

@endsection