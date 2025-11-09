<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rohim
 * Date: 8/15/2023
 * Time: 11:12 PM
 */
?>
@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Pengeluaran Office</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Pengeluaran Office</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Filter Pengeluaran Office</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal awal:</label>
                                    <div class="input-group date">
                                        <input type="date" class="form-control dateStart" id="dateStart" />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Akhir:</label>
                                    <div class="input-group date">
                                        <input type="date" class="form-control dateEnd" id="dateEnd" />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="input-group date" style="margin-top: 12px">
                                    <button class="btn btn-primary btn-md btn-filter"><i class="fa fa-search"></i>
                                        &nbsp;&nbsp;Filter&nbsp;&nbsp;&nbsp; </button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="input-group date" style="margin-top: 12px">
                                    <button class="btn btn-default btn-md btn-reset"><i class="fa fa-trash"></i>
                                        &nbsp;&nbsp;Reset&nbsp; </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <button type="button" class="btn btn-block btn-primary btn-sm" data-toggle="modal" data-target="#modal-default"><i class="fa fa-plus"></i> Pengeluaran</button>
                                </h3>
                                <button href="#" class="btn btn-default btn-sm btn-export"
                                         style="float: right"><span class="info-box-icon bg-success"><i
                                                class="fa fa-file-excel"></i></span> Export Excel</button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        {{--<th>No</th>--}}
                                        <th>Nama Pengeluaran</th>
                                        <th>Tanggal Pengeluaran</th>
                                        <th>Nominal</th>
                                        <th>Dibuat</th>
                                        <th>Jenis Pengeluaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2"style="text-align:right"></th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Pengeluaran Office</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nama Pengeluaran</label>
                                    <input type="text" class="form-control" name="spending_name" placeholder="Nama Pengeluaran" style="text-transform:uppercase">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Tanggal</label>
                                    <input type="date" class="form-control" name="spending_date" placeholder="Tanggal">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Jumlah</label>
                                    <input type="text" class="form-control" name="amount" placeholder="jumlah"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                </div>
                                <div class="form-group">
                                    <label>Jenis Transaksi</label>
                                    <select name="spending_type" class="form-control">
                                        <option name="">Pilih Jenis Transaksi</option>
                                        <option name="Oprasional">Oprasional</option>
                                        <option name="Omset">Omset</option>
                                        <option name="Vendor">Vendor</option>
                                        <option name="Gaji">Gaji</option>
                                        <option name="Tabungan">Tabungan</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-submit">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@push('js')
    <script>
        var table;
        $(document).ready(function () {
            initTable();

            $('body').on('click', '.btn-submit', function(){
                saveItem();
            });

            $('body').on('click', '.btn-approve-item', function(){
                let id = $(this).data('id');
                approveItem(id);
            });

            $(".btn-filter").click(function() {
                table.draw();
            });

            $(".btn-reset").click(function() {
                $("input[type=date]").val("")
                document.getElementById("dateStart").value = "";
                document.getElementById("dateEnd").value = "";
                table.draw();
            });
        });

        const IDR = (number)=>{
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number);
        }

        async function initTable() {
            table = await $('#list-manage').DataTable({
                language: {
                    "paginate": {
                        'previous':'Prev',
                        'next':'Next'
                    }
                },
                searching: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ url('/manage/office-spending/list') }}',
                    data : function(d){
                        d.dateStart = $('.dateStart').val(),
                        d.dateEnd = $('.dateEnd').val()
                    }
                },
                columns: [
                    // {data: 'rownum',name: 'rownum'},
                    {data: 'spending_name',name: 'spending_name'},
                    {data: 'spending_date',name: 'spending_date',
                        render: $.fn.dataTable.render.moment('DD-MM-YYYY' ),
                    },
                    {data: 'amount',name: 'amount',
                        render: $.fn.dataTable.render.number( '.', '', 0, 'Rp.' )
                    },
                    {data: 'created_name',name: 'created_name'},
                    {data: 'spending_type',name: 'spending_type'},
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                "order": [[ 1, "asc" ]],
                "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                "iDisplayLength": 25,
                footerCallback: function (row, data, start, end, display) {
                        let api = this.api();
                        var json = table.ajax.json();


                        // Remove the formatting to get integer data for summation
                        let intVal = function (i) {
                            return typeof i === 'string'
                                ? i.replace(/[\$,]/g, '') * 1
                                : typeof i === 'number'
                                ? i
                                : 0;
                        };
                        total = api
                            .column(2)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Update footer
                        $( api.column( 0 ).footer() ).html('TOTAL Per Page <br> TOTAL');
                        $( api.column( 2 ).footer() ).html(
                            $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(total)+'<br>'+$.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.nominal_sum)
                        )
                },
            });
        }

        function refreshTable() {
            table.ajax.reload();
        }

        function saveItem(){
            const data = {
                '_token':'{{csrf_token()}}',
                '_method':'post',
                'spending_name': $('input[name=spending_name]').val(),
                'spending_date': $('input[name=spending_date]').val(),
                'amount': $('input[name=amount]').val(),
                'spending_type': $('select[name=spending_type]').val(),
            };
            $.post('{{url("manage/office-spending/store")}}', data, res=>{
                if(res.status == 200){
                    $('#modal-default form')[0].reset();
                    $('#modal-default').modal('hide');
                    showSuccess(res.msg);
                    refreshTable();
                }else{
                    showError(res.msg);
                }
            })
        }

        $(".btn-export").click(function (e) {
                e.preventDefault();

                var query = {
                    dateStart : $('.dateStart').val(),
                    dateEnd : $('.dateEnd').val()
                }

                var url = `{{ url("manage/office-spending/export") }}`+ '?' + $.param(query)
                console.log(url);
                window.location = url;

            });

            $('body').delegate('.btnDelSpending', 'click', function(e) {
            let shipping_id = $(this).data('id')

            Swal.fire({
                title: 'Yakin akan menghapus pengeluaran ini?',
                text: 'Anda akan menghapus pengeluaran ini. Lanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Tutup',
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'post',
                        'office_spending_id': shipping_id
                    };
                    $.post('{{ url('manage/office-spending/delete') }}', data, res => {
                        if (res.status == 200) {
                            showSuccess(res.msg);
                            refreshTable();
                        } else {
                            showError(res.msg);
                        }
                    })
                }
            })
        })
    </script>
@endpush
