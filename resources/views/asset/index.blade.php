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
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Aset</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Aset</li>
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
                        <h3 class="card-title">Filter Asset </h3>
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
                                {{--<h3 class="card-title">--}}
                                    {{--<button type="button" class="btn btn-block btn-primary btn-sm" data-toggle="modal" data-target="#modal-default"><i class="fa fa-plus"></i> Aset</button>--}}
                                {{--</h3>--}}
                                <button href="#" class="btn btn-default btn-sm btn-export"
                                         style="float: right"><span class="info-box-icon bg-success"><i
                                                class="fa fa-file-excel"></i></span> Export Excel</button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="list-manage" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>OMSET</th>
                                        <th>Total Invoice</th>
                                        {{-- <th>Total Invoice Hari Ini</th> --}}
                                        <th>Total Pengeluaran</th>
                                        <th>Terakhir di-update</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align: right"></th>
                                            <th></th>
                                            <th></th>
                                            <th colspan="2"></th>
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
@endsection

@push('js')
    <script>
        var table;
        $(document).ready(function () {
            initTable();
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
                    url: '{{ url('/manage/asset/list') }}',
                    data : function(d){
                        d.dateStart = $('.dateStart').val(),
                        d.dateEnd = $('.dateEnd').val()
                    }
                },
                columns: [
                    {data: 'rownum',name: 'rownum'},
                    {data: 'asset_date',name: 'asset_date',
                        render: $.fn.dataTable.render.moment('DD-MM-YYYY' ),
                    },
                    {data: 'turnover',name: 'turnover',
                        render: $.fn.dataTable.render.number( '.', '', 0, 'Rp.' )
                    },
                    {data: 'total_invoice',name: 'total_invoice',
                        render: $.fn.dataTable.render.number( '.', '', 0, 'Rp.' )
                    },
                    // {data: 'total_invoice_current',name: 'total_invoice_current',
                    //     render: $.fn.dataTable.render.number( '.', '', 0, 'Rp.' )
                    // },
                    {data: 'spending_amount',name: 'spending_amount',
                        render: $.fn.dataTable.render.number( '.', '', 0, 'Rp.' )
                    },
                    {data: 'updated_at',name: 'updated_at'}
                ],
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
                        turnover = api
                            .column(2)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);
                        total_invoice = api
                            .column(3)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);
                        spending_amount = api
                            .column(4)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Update footer
                        $( api.column( 0 ).footer() ).html('TOTAL Per Page <br> TOTAL');
                        $( api.column( 2 ).footer() ).html(
                            $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(turnover)+'<br>'+$.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.turnover_sum)
                        )
                        $( api.column( 3 ).footer() ).html(
                            $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(total_invoice)+'<br>'+$.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.total_invoice_sum)
                        )
                        $( api.column( 4 ).footer() ).html(
                            $.fn.dataTable.render.number('.', '', 0, 'Rp.').display(spending_amount)+'<br>'+$.fn.dataTable.render.number('.', '', 0, 'Rp.').display(json.spending_amount_sum)
                        )
                },
            });
        }

        $(".btn-export").click(function (e) {
                e.preventDefault();

                var query = {
                    dateStart : $('.dateStart').val(),
                    dateEnd : $('.dateEnd').val()
                }

                var url = `{{ url("manage/asset/export") }}`+ '?' + $.param(query)
                console.log(url);
                window.location = url;

            });
    </script>
@endpush
