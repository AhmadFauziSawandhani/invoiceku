@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="vue">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Input Manifest outbond</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">create Manifest Outbond</li>
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
                        <form id="form-add-invoice" @submit.prevent="onSubmitForm">

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Form Buat Manifest Outbond
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tanggal Outbond</label>
                                                <input type="date" required="required" class="form-control"
                                                    v-model="invForm.date_outbond" placeholder="Tanggal Outbond">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Supir COD</label>
                                                <input type="text" required="required" class="form-control"
                                                    v-model="invForm.driver" placeholder="Supir COD">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Mengetahui</label>
                                                <input type="text" required="required" class="form-control"
                                                    v-model="invForm.acknowledge" placeholder="Mengetahui">
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select v-model="invForm.status" id="sales_name" class="form-control"
                                                    required>
                                                    <option value="">Pilih Status</option>
                                                    <option value="PACKING">PACKING</option>
                                                    <option value="PICKUP">PICKUP</option>
                                                    <option value="DIKIRIM">DIKIRIM</option>
                                                    <option value="SELESAI">SELESAI</option>
                                                </select>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tgl Keluar Gudang</label>
                                                <input type="date" required="required" class="form-control" required
                                                    v-model="invForm.warehouse_exit_date" placeholder="Tanggal Invoice">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Catatan</label>
                                                <textarea required="required" class="form-control" v-model="invForm.note" placeholder="Catatan"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="button" @click.stop="onShowModal">Tambah
                                            Manifest</button>
                                    </div>
                                    <h4>Daftar Manifest</h4>
                                    <div class="table-responsive" style="min-height: 500px">
                                        <table class="table table-bordered table-hover" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">No</th>
                                                    <th scope="col" class="text-center">No STT</th>
                                                    <th scope="col" class="text-center">Marketing</th>
                                                    <th scope="col" class="text-center">Tanggal</th>
                                                    <th scope="col" class="text-center">Nama Pengirim</th>
                                                    <th scope="col" class="text-center">Destinasi</th>
                                                    <th scope="col" class="text-center">Moda</th>
                                                    <th scope="col" class="text-center">Koli</th>
                                                    <th scope="col" class="text-center">Kilo</th>
                                                    <th scope="col" class="text-center">Volume</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" class="text-center">Jenis Vendor</th>
                                                    <th scope="col" class="text-center">Nama Vendor</th>
                                                    <th scope="col" class="text-center">Resi Vendor</th>
                                                    <th scope="col" class="text-center">Note</th>
                                                    <th scope="col" class="text-center">#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(detail, index) in invForm.manifest">
                                                    <td>@{{ index + 1 }}</td>
                                                    <td>@{{ detail.receipt_number }}</td>
                                                    <td>@{{ detail.sales_name }}</td>
                                                    <td>@{{ detail.date_manifest }}</td>
                                                    <td>@{{ detail.shipping_name }}</td>
                                                    <td>@{{ detail.destination }}</td>
                                                    <td>@{{ detail.moda }}</td>
                                                    <td>@{{ detail.total_colly }}</td>
                                                    <td>@{{ detail.total_actual }}</td>
                                                    <td>@{{ detail.total_volume }}</td>
                                                    <td>
                                                        <select v-model="detail.status" id="sales_name" class="form-control"
                                                    required>
                                                    <option value="">Pilih Status</option>
                                                    <option v-for="status in statuses" :value="status.name">@{{ status.name }}</option>
                                                </select>
                                                    </td>
                                                    <td>
                                                        <select v-model="detail.vendor_type" id="sales_name"
                                                            class="form-control" required>
                                                            <option value="">Pilih Jenis Vendor</option>
                                                            <option value="Trucking">Trucking</option>
                                                            <option value="Vendor">Vendor</option>
                                                            <option value="Armada Sendiri">Armada Sendiri</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <v-select required="required" autocomplete style="min-width: 100px;"
                                                            :selectOnTab="true" placeholder="Vendor" :reduce="country => country.value.id"
                                                            style="text-transform:uppercase" :options="vendors" v-model="detail.vendor_id" />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" v-model="detail.resi_vendor"
                                                            placeholder="Resi Vendor" style="min-width: 60px;">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" v-model="detail.note"
                                                            placeholder="Note" style="min-width: 100px;">
                                                    </td>
                                                    <td><button class="btn btn-danger"
                                                            @click="deleteEmployee(detail,index)" type="button"><i
                                                                class="fa fa-trash"></i></button></td </tr>
                                                <tr v-if="invForm.manifest.length == 0">
                                                    <td class="text-center" colspan="16">No Data</td>
                                                </tr>
                                            </tbody>
                                            <tfoot v-if="invForm.manifest.length > 0">
                                                <tr>
                                                    <th colspan="8" class="text-center">Total</th>
                                                    <th>@{{ invForm.total_colly.toFixed(2) }}</th>
                                                    <th>@{{ invForm.total_weight.toFixed(2) }}</th>
                                                    <th>@{{ invForm.total_volume.toFixed(2) }}</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('tracking.index') }}" class="btn btn-default"
                                    data-dismiss="modal">Close</a>
                                <button type="submit" id="btnSubmit" class="btn btn-primary btn-submit">Save</button>
                            </div>
                        </form>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <div class="modal fade" id="modalAddEmployee" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Tambah Manifest</h5>
                    </div>
                    <div class="modal-body">
                        <div class="align-items-center" :class="{ 'd-flex': loading }" v-show="loading">
                            <strong>Loading...</strong>
                            <div class="spinner-border ms-auto" style="width: 24px; height: 24px" role="status"
                                aria-hidden="true"></div>
                        </div>
                        <div class="table-responsive" v-show="!loading">
                            <table class="table table-striped table-bordered" id="employeeTable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">No</th>
                                        <th scope="col" class="text-center">No STT</th>
                                        <th scope="col" class="text-center">Marketing</th>
                                        <th scope="col" class="text-center">Tanggal</th>
                                        <th scope="col" class="text-center">Nama Pengirim</th>
                                        <th scope="col" class="text-center">No Telepon</th>
                                        <th scope="col" class="text-center">Destinasi</th>
                                        <th scope="col" class="text-center">Moda</th>
                                        <th scope="col" class="text-center">Koli</th>
                                        <th scope="col" class="text-center">Kilo</th>
                                        <th scope="col" class="text-center">Volume</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, index) in manifests">
                                        <td><input type="checkbox" v-model="manifests[index].selected"
                                                @input="onSelectChange($event,user,index)"></td>
                                        <td>@{{ user.receipt_number }}</td>
                                        <td>@{{ user.sales_name }}</td>
                                        <td>@{{ user.date_manifest }}</td>
                                        <td>@{{ user.shipping_name }}</td>
                                        <td>@{{ user.phone_number }}</td>
                                        <td>@{{ user.destination }}</td>
                                        <td>@{{ user.moda }}</td>
                                        <td>@{{ user.total_colly }}</td>
                                        <td>@{{ user.total_actual }}</td>
                                        <td>@{{ user.total_volume }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
    </div>
@endsection

@push('js')
    <script src="https://unpkg.com/vue-select@latest"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
    <script>
        new Vue({
            el: '#vue',
            components: {
                'v-select': VueSelect.VueSelect
            },
            data() {
                return {
                    invForm: {
                        date_outbond: "",
                        status: "Keluar Dari Gudang",
                        warehouse_exit_date: "",
                        note: "",
                        acknowledge: "",
                        driver: "",
                        total_colly: 0,
                        total_weight: 0,
                        total_volume: 0,
                        total_volume_m3: 0,
                        manifest: [],
                    },
                    modalInvoice: null,
                    loading: false,
                    vendors: [],
                    statuses: @json($statuses),
                    vendor: null,
                    manifests: [],
                    selectedCustomer: null,
                }
            },
            mounted() {
                this.modalInvoice = $('#modalAddEmployee')
                this.fetchManifests()
                this.fetchVendor()
            },
            methods: {
                formatPrice(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value)
                },
                async fetchManifests() {
                    this.loading = true
                    const url = "{{ route('outbond.get-manifest') }}"
                    await axios.get(url).then((resp) => {
                        if (resp.data.length > 0) {
                            resp.data.forEach((val) => {
                                val.selected = false
                                val.note = null
                                val.vendor_type = null
                                val.vendor_id = null
                                val.vendor_name = null
                                val.resi_vendor = null
                            })

                            this.manifests = resp.data
                        }
                    }).finally(() => {
                        this.loading = false
                    })
                },
                async fetchVendor() {
                    const url = "{{ route('get-vendor') }}"
                    await axios.get(url).then((resp) => {
                        if (resp.data.data.length > 0) {
                            resp.data.data.forEach((val) => {
                                this.vendors.push({
                                    label: `${val.name}`,
                                    value: {
                                        id: val.id,
                                        name: val.name,
                                    }
                                })
                            })
                        }
                    })
                },
                onSelectVendor(input, index) {
                    if (input.value) {
                        this.invForm.manifest[index].vendor_id = input.value.id
                        this.invForm.manifest[index].vendor_name = input.value.name
                    }
                },
                onSelectChange(e, items, index) {
                    const isChecked = e.target.checked
                    if (isChecked) {
                        items.selected = true
                        this.invForm.manifest.push(items)
                        this.invForm.total_colly += parseFloat(items.total_colly)
                        this.invForm.total_weight += parseFloat(items.total_weight)
                        this.invForm.total_volume += parseFloat(items.total_volume)
                        this.invForm.total_volume_m3 += parseFloat(items.total_volume_m3)
                    } else {
                        items.selected = false
                        this.invForm.manifest.splice(index, 1)
                        this.invForm.total_colly -= parseFloat(items.total_colly)
                        this.invForm.total_weight -= parseFloat(items.total_weight)
                        this.invForm.total_volume -= parseFloat(items.total_volume)
                        this.invForm.total_volume_m3 -= parseFloat(items.total_volume_m3)
                    }
                },
                onShowModal() {
                    this.modalInvoice.modal('show')
                    $("#employeeTable").dataTable()
                },
                closeModal() {
                    this.modalInvoice.modal('hide')
                },
                deleteEmployee(item, index) {
                    this.manifests.find(val => val.id == item.id).selected = false
                    this.invForm.manifest.splice(index, 1)
                },
                onSubmitForm() {
                    $('button').prop('disabled', true)
                    btnText = $('#btnSubmit').html()
                    $('#btnSubmit').html('<i class="fa fa-spinner fa-spin"></i> Loading')
                    axios.post('{{ route('outbond.store') }}', {
                            ...this.invForm
                        })
                        .then((response) => {
                            if (response.data.status == 200) {
                                window.location.href = "{{ route('outbond.index') }}"
                            } else {
                                showError(response.data.msg);
                            }
                        })
                        .catch((error) => {
                            console.log(error)
                        }).finally(() => {
                            $('#btnSubmit').html(btnText)
                            $('button').prop('disabled', false)
                        })
                }
            },
        });
    </script>
@endpush
