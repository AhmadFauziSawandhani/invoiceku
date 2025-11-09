@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="vue">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Input Tracking Manifest</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">create Manifest</li>
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
                                        Form Tambah Manifest Barang
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="card-body">
                                    <div class="row">
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jenis Vendor</label>
                                                <select v-model="invForm.vendor_type" id="sales_name" class="form-control"
                                                    required>
                                                    <option value="">Pilih Jenis Vendor</option>
                                                    <option value="Trucking">Trucking</option>
                                                    <option value="Vendor">Vendor</option>
                                                    <option value="Armada Sendiri">Armada Sendiri</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div v-if="invForm.vendor_type != 'Armada Sendiri' && invForm.vendor_type"
                                            class="col-md-3">
                                            <div class="form-group">
                                                <label>Nama Vendor</label>
                                                <v-select required="required" autocomplete v-model="vendor"
                                                    :selectOnTab="true" placeholder="Vendor"
                                                    style="text-transform:uppercase" :options="vendors"
                                                    @option:selected="onSelectVendor" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>No Resi Vendor</label>
                                                <input type="text" required="required" class="form-control"
                                                    v-model="invForm.resi_vendor" placeholder="No Resi Vendor">
                                            </div>
                                        </div> --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select v-model="invForm.status" id="sales_name" class="form-control"
                                                    required>
                                                    <option value="">Pilih Status</option>
                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status->name }}">{{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tanggal</label>
                                                <input type="date" required="required" class="form-control"
                                                    v-model="invForm.tracking_date" placeholder="Tanggal Invoice">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>ETD</label>
                                                <input type="date" required="required" class="form-control"
                                                    v-model="invForm.etd" placeholder="Tanggal ETD">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>ETA</label>
                                                <input type="date" required="required" class="form-control"
                                                    v-model="invForm.eta" placeholder="Tanggal ETA">
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tgl Keluar Gudang</label>
                                                <input type="date" required="required" class="form-control" required
                                                    v-model="invForm.warehouse_exit_date" placeholder="Tanggal Invoice">
                                            </div>
                                        </div> --}}
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
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" style="width: 100%">
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
                                                    <th scope="col" class="text-center">#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(detail, index) in invForm.manifest_id">
                                                    <td>@{{ index + 1 }}</td>
                                                    <td>@{{ detail.receipt_number }}</td>
                                                    <td>@{{ detail.sales_name }}</td>
                                                    <td>@{{ detail.date_manifest }}</td>
                                                    <td>@{{ detail.shipping_name }}</td>
                                                    <td>@{{ detail.phone_number }}</td>
                                                    <td>@{{ detail.destination }}</td>
                                                    <td>@{{ detail.moda }}</td>
                                                    <td>@{{ detail.total_colly }}</td>
                                                    <td>@{{ detail.total_actual }}</td>
                                                    <td>@{{ detail.total_volume }}</td>
                                                    <td><button class="btn btn-danger"
                                                            @click="deleteEmployee(detail,index)" type="button"><i
                                                                class="fa fa-trash"></i></button></td </tr>
                                                <tr v-if="invForm.manifest_id.length == 0">
                                                    <td class="text-center" colspan="12">No Data</td>
                                                </tr>
                                            </tbody>
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
                                    <tr v-for="(user, index) in customers">
                                        <td><input type="checkbox" v-model="customers[index].selected"
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
                        vendor_type: "",
                        vendor_id: "",
                        resi_vendor: "",
                        status: "",
                        tracking_date: "",
                        warehouse_exit_date: "",
                        note: "",
                        manifest_id: [],
                    },
                    modalInvoice: null,
                    loading: false,
                    vendors: [],
                    vendor: null,
                    customers: [],
                    selectedCustomer: null,
                }
            },
            mounted() {
                this.modalInvoice = $('#modalAddEmployee')
                this.fetchVendor()
                this.fetchManifests()
            },
            methods: {
                removeSelected(index) {
                    this.$delete(this.invForm.manifest_id, index)
                },
                addRowDetails(type) {
                    switch (type) {
                        case '1':
                            this.invForm.manifest_id.push({
                                ...this.lautItems
                            })
                            break;
                        case '2':
                            this.invForm.manifest_id.push({
                                ...this.kendaraanItems
                            })
                            break;
                        case '3':
                            this.invForm.manifest_id.push({
                                ...this.udaraItems
                            })
                            break;
                        default:
                            break;
                    }
                },
                formatPrice(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value)
                },
                async fetchManifests() {
                    this.loading = true
                    const url = "{{ route('tracking.get-manifest') }}"
                    await axios.get(url).then((resp) => {
                        if (resp.data.length > 0) {
                            resp.data.forEach((val) => {
                                val.selected = false
                            })

                            this.customers = resp.data
                        }
                    }).finally(() => {
                        this.loading = false
                    })
                },
                onSelectChange(e, items, index) {
                    const isChecked = e.target.checked
                    if (isChecked) {
                        items.selected = true
                        this.invForm.manifest_id.push(items)
                    } else {
                        items.selected = false
                        this.invForm.manifest_id.splice(index, 1)
                    }
                },
                onShowModal() {
                    this.modalInvoice.modal('show')
                    $("#employeeTable").dataTable()
                },
                closeModal() {
                    this.modalInvoice.modal('hide')
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
                onSelectVendor(input) {
                    if (input.value) {
                        this.invForm.vendor_id = input.value.id
                        this.invForm.vendor_name = input.value.name
                    }
                },
                deleteEmployee(item, index) {
                    this.customers.find(val => val.id == item.id).selected = false
                    this.invForm.manifest_id.splice(index, 1)
                },
                onSelect(input) {
                    if (input.value) {
                        this.invForm.shipping_name = input.value.name.toUpperCase()
                        this.invForm.phone_number = input.value.phone
                    } else {
                        this.invForm.shipping_name = input.label.toUpperCase()
                        this.invForm.phone_number = null
                    }
                },
                onSubmitForm() {
                    $('button').prop('disabled', true)
                    btnText = $('#btnSubmit').html()
                    $('#btnSubmit').html('<i class="fa fa-spinner fa-spin"></i> Loading')
                    axios.post('{{ route('tracking.store') }}', {
                            ...this.invForm
                        })
                        .then((response) => {
                            if (response.data.status == 200) {
                                window.location.href = "{{ route('tracking.index') }}"
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
