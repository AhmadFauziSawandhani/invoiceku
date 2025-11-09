@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Detail Laporan Keluhan Customer</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Detail Laporan Keluhan Customer</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h4>Detail Laporan Keluhan</h4>
                                <span class="badge badge-danger ml-auto">Baru</span>
                            </div>
                            <div class="card-body d-flex flex-wrap p-3">
                                <div class="col-md-3">
                                    <h6>Nama Customer :</h6>
                                    <h5>Luthfy Wiratama</h5>
                                </div>
                                <div class="col-md-3">
                                    <h6>Email Custoemr :</h6>
                                    <h5>example@email.com</h5>
                                </div>
                                <div class="col-md-3">
                                    <h6>No Telepon :</h6>
                                    <h5>12313123</h5>
                                </div>
                                <div class="col-md-3">
                                    <h6>Jenis Keluhan :</h6>
                                    <h5>Order</h5>
                                </div>
                                <div class="col-md-3">
                                    <h6>No Resi :</h6>
                                    <h5>165-1312312</h5>
                                </div>
                                <div class="col-md-3">
                                    <h6>Tujuan :</h6>
                                    <h5>Papua Barat</h5>
                                </div>
                                <div class="col-md-3">
                                    <h6>Tujuan :</h6>
                                    <h5>Papua</h5>
                                </div>
                                <div class="col-md-3">
                                    <h6>Provinsi :</h6>
                                    <h5>Papua</h5>
                                </div>
                                <div class="col-md-3">
                                    <h6>Kode POS :</h6>
                                    <h5>54424324</h5>
                                </div>
                                <div class="col-md-12">
                                    <h6>Keluhan :</h6>
                                    <h5>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem delectus, aliquid error
                                        odio, quam iste porro, nulla ipsum qui soluta eligendi aliquam sed sapiente. Nisi
                                        debitis beatae temporibus deleniti exercitationem.</h5>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Response Laporan</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <h6>Response Sebelumnya:</h6>
                                    <div>
                                        <div>
                                            <span class="badge badge-info mr-4">Customer Service 1</span> <span>2024-09-01 13:23 WIB</span>
                                        </div>
                                        <h5>Terima kasih atas laporan anda, kami akan segera memproses permasalahan anda
                                            mohon tunggu info selanjutnya melalui email dan whatsapp, pastikan nomor yang
                                            anda masukkan benar</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <textarea name="response" id="response" class="form-control" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end" style="gap: 10px">
                                <button class="btn btn-outline-danger">Kembali</button>
                                <button class="btn btn-success">Selesaikan Laporan</button>
                                <button class="btn btn-primary">Submit Tanggapan</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    </div>
@endsection
