@extends('layout.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="vue">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Detail Manifest</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">Detail Manifest</li>
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jenis Invoice</label>
                                                <select class="form-control" v-model="invForm.invoice_type" disabled
                                                    required="required" id="invoice_type">
                                                    <option value="">Pilih Jenis Invoice</option>
                                                    <option value="1">Invoice Laut</option>
                                                    <option value="2">Invoice Kendaraan</option>
                                                    <option value="3">Invoice Udara</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tanggal Manifest</label>
                                                <input type="date" required="required" class="form-control" disabled
                                                    v-model="invForm.date_manifest" placeholder="Tanggal Manifest">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Nama Marketing</label>
                                                <select v-model="invForm.sales_name" id="sales_name" class="form-control"
                                                    disabled required>
                                                    <option value="">Pilih Marketing</option>
                                                    <option value="IBU DYTA">IBU DYTA</option>
                                                    <option value="IBU AINUN">IBU AINUN</option>
                                                    <option value="IBU FIRA">IBU FIRA</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Nama Pengirim</label>
                                                {{-- <input type="text" required="required" class="form-control"
                                                v-model="invForm.shipping_name" placeholder="Nama Pengirim"
                                                style="text-transform:uppercase"> --}}
                                                <v-select :taggable="true" required="required" disabled
                                                    v-model="customer" :selectOnTab="true" placeholder="Nama Pengirim"
                                                    style="text-transform:uppercase" :options="customers"
                                                    @option:selected="onSelect" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>No Telepon</label>
                                                <input type="text" class="form-control" disabled
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    v-model="invForm.phone_number" placeholder="No Telepon">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Alamat Pengirim</label>
                                                <input type="text" required="required" class="form-control" disabled
                                                    v-model="invForm.shipping_address" placeholder="Alamat Pengirim">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Kota Pengirim</label>
                                                <input type="text" required="required" class="form-control" disabled
                                                    v-model="invForm.shipping_city" placeholder="Kota Pengirim"
                                                    style="text-transform:uppercase">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tujuan</label>
                                                <input type="text" required="required" class="form-control" disabled
                                                    v-model="invForm.destination" placeholder="Tujuan"
                                                    style="text-transform:uppercase">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>No STT / Resi</label>
                                                <input type="text" class="form-control" disabled
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    v-model="invForm.receipt_number" placeholder="No Telepon">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Drop / Pickup</label>
                                                <input type="text" class="form-control" v-model="invForm.drop_pickup"
                                                    disabled placeholder="Drop / Pickup">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Moda</label>
                                                <select class="form-control" v-model="invForm.moda" required="required"
                                                    disabled id="moda">
                                                    <option value="">Pilih Moda</option>
                                                    <option value="Darat">Darat</option>
                                                    <option value="Laut">Laut</option>
                                                    <option value="Udara">Udara</option>
                                                    <option value="Darat-Laut">Darat-Laut</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jenis Pembayaran</label>
                                                <select v-model="invForm.payment_type" id="payment_type"
                                                    class="form-control" disabled>
                                                    <option value="">Pilih Jenis Pembayaran</option>
                                                    <option value="1">Cash</option>
                                                    <option value="2">TOP</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label>Jenis Layanan</label>
                                            <select v-model="invForm.service_type" id="service_type" disabled
                                                class="form-control">
                                                <option value="">Pilih Jenis Layanan</option>
                                                <option value="REGULER">REGULER</option>
                                                <option value="OKE">OKE</option>
                                                <option value="YES">YES</option>
                                            </select>
                                        </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>No DO / Reff</label>
                                                <input type="text" class="form-control" disabled
                                                    v-model="invForm.do_number" placeholder="Supir">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Isi Menurut Pengirim</label>
                                                <textarea class="form-control" placeholder="Isi Menurut Pengirim" disabled v-model="invForm.recipient_detail"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                             <div class="form-group">
                                                <label>Instruksi Khusus</label>
                                                {{-- <select class="form-control" disabled
                                                    v-model="invForm.instruction_special">
                                                    <option value="">Pilih Instruksi</option>
                                                    <option value="TIDAK PAKAI">TIDAK PAKAI</option>
                                                    <option value="PAKING KAYU">PAKING KAYU</option>
                                                    <option value="WRAPPING">WRAPPING</option>
                                                    <option value="DUS + WRAPPING">DUS + WRAPPING</option>
                                                    <option value="DUS + BUBLE WRAP + WRAPPING">DUS + BUBLE WRAP +
                                                        WRAPPING</option>
                                                    <option value="KARUNG">KARUNG</option>
                                                    <option value="KARUNG + WRAPPING">KARUNG + WRAPPING</option>
                                                    <option value="KARUNG + PACKING KAYU">KARUNG + PACKING KAYU
                                                    </option>
                                                    <option value="MIXED PACKING">MIXED PACKING</option>
                                                </select> --}}
                                                <textarea class="form-control" placeholder="Instruksi Khusus" disabled v-model="invForm.instruction_special"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Foto Produk</label>
                                                <img src="{{ asset('storage/' . $manifest->photo_product) }}"
                                                    width="100px">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Surat Jalan</label>
                                                <img src="{{ asset('storage/' . $manifest->photo_travel_document) }}"
                                                    width="100px">
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Data Penerima</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Nama Perusahaan</label>
                                                <input type="text" class="form-control" placeholder="Nama Perusahaan"
                                                    disabled v-model="invForm.recipient_company">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Nama Penerima</label>
                                                <input type="text" class="form-control" placeholder="Nama Penerima"
                                                    disabled v-model="invForm.recipient_name">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Telp Penerima</label>
                                                <input type="text" class="form-control" disabled
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')"
                                                    placeholder="Telp Penerima" v-model="invForm.recipient_phone">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Kota</label>
                                                <input type="text" class="form-control" placeholder="Kota" disabled
                                                    v-model="invForm.recipient_city">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Kode Pos</label>
                                                <input type="text" class="form-control" placeholder="Kode Pos"
                                                    disabled
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')"
                                                    v-model="invForm.recipient_zip">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Alamat</label>
                                                <textarea class="form-control" placeholder="Alamat" disabled v-model="invForm.recipient_address"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex align-items-center justify-content-between py-4">
                                        <h4>Daftar Barang</h4>

                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center">#</th>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center">Nama Barang</th>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center">
                                                        Koli (Colly)</th>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center">
                                                        Kilo</th>
                                                    <th scope="col" colspan="3" class="text-center">Dimensi</th>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center"
                                                        v-if="['1','2'].includes(invForm.invoice_type)">
                                                        Volume (Darat Laut)</th>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center">
                                                        Volume M3</th>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center">
                                                        Actual</th>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center">
                                                        Chargeable Weight
                                                    </th>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center">Packaging</th>
                                                    <th scope="col" rowspan="2" style="vertical-align: middle;"
                                                        class="text-center">Charge Packaging</th>
                                                </tr>
                                                <tr>
                                                    <th scope="col" class="text-center">P</th>
                                                    <th scope="col" class="text-center">L</th>
                                                    <th scope="col" class="text-center">T</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in invForm.details">
                                                    {{-- no --}}
                                                    <td style="width: 45px;">@{{ index + 1 }}</td>
                                                    {{-- nama barang --}}
                                                    <td style="min-width:180px;">
                                                        <input type="text" required="required" class="form-control"
                                                            v-model="invForm.details[index].product_name" disabled
                                                            placeholder="Nama Barang">
                                                    </td>
                                                    {{-- koli --}}
                                                    <td style="min-width:100px;">
                                                        <input type="text" required="required" id="colly"
                                                            class="form-control colly" @change="calculateAmount(index)"
                                                            v-model="invForm.details[index].colly" placeholder="colly"
                                                            disabled
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    {{-- kilo --}}
                                                    <td style="min-width:140px;">
                                                        <div class="input-group mb-3">
                                                            <input type="text" required="required"
                                                                @change="calculateAmount(index)" class="form-control"
                                                                disabled v-model="invForm.details[index].weight"
                                                                placeholder="Kilo"
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Kg</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    {{-- panjang --}}
                                                    <td style="min-width:180px;">
                                                        <input type="text" required="required" id="unit" disabled
                                                            @change="calculateAmount(index)" class="form-control unit"
                                                            v-model="invForm.details[index].dimension_p" placeholder="P"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    {{-- lebar --}}
                                                    <td style="min-width:180px;">
                                                        <input type="text" required="required" id="unit" disabled
                                                            @change="calculateAmount(index)" class="form-control unit"
                                                            v-model="invForm.details[index].dimension_l" placeholder="L"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    {{-- tinggi --}}
                                                    <td style="min-width:180px;">
                                                        <input type="text" required="required" id="unit" disabled
                                                            @change="calculateAmount(index)" class="form-control unit"
                                                            v-model="invForm.details[index].dimension_t" placeholder="T"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    {{-- volume --}}
                                                    <td style="min-width:110px;"
                                                        v-if="['1','2'].includes(invForm.invoice_type)"><input
                                                            type="text" required="required" id="price" disabled
                                                            @change="calculateAmount(index)" class="form-control price"
                                                            v-model="invForm.details[index].volume" placeholder="Volume"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    {{-- volume m3 --}}
                                                    <td style="min-width:110px;"><input type="text" id="volume_m3"
                                                            class="form-control volume_m3" disabled
                                                            v-model="invForm.details[index].volume_m3"
                                                            @change="calculateAmount(index)" placeholder="Volume M3"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    {{-- actual --}}
                                                    <td style="min-width:110px;"><input type="text" id="actual"
                                                            class="form-control actual" disabled
                                                            v-model="invForm.details[index].actual" placeholder="Actual"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    {{-- chargeable weight --}}
                                                    <td style="min-width:110px;"><input type="text"
                                                            id="chargeable_weight" class="form-control chargeable_weight"
                                                            v-model="invForm.details[index].chargeable_weight"
                                                            placeholder="Chargeable Weight" disabled
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    {{-- packaging --}}
                                                    <td style="min-width:180px;">
                                                        <select class="form-control" @change="calculateAmount(index)"
                                                            disabled v-model="invForm.details[index].packaging"
                                                            required="required" id="packaging">
                                                            <option value="">Pilih Packaging</option>
                                                            <option value="PAKING KAYU">PAKING KAYU</option>
                                                            <option value="WRAPPING">WRAPPING</option>
                                                            <option value="DUS + WRAPPING">DUS + WRAPPING</option>
                                                            <option value="DUS + BUBLE WRAP + WRAPPING">DUS + BUBLE WRAP +
                                                                WRAPPING</option>
                                                            <option value="KARUNG">KARUNG</option>
                                                            <option value="KARUNG + WRAPPING">KARUNG + WRAPPING</option>
                                                            <option value="KARUNG + PACKING KAYU">KARUNG + PACKING KAYU
                                                            </option>
                                                        </select>
                                                    </td>
                                                    {{-- charge packaging  --}}
                                                    <td style="min-width:110px;">
                                                        @{{ formatPrice(invForm.details[index].charge_packaging) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td :colspan="2" class="text-center">Total</td>
                                                    {{-- koli --}}
                                                    <td class="text-center font-weight-bold">
                                                        @{{ invForm.total_colly ? invForm.total_colly.toFixed(2) : 0 }}</td>
                                                    {{-- kilo --}}
                                                    <td class="text-center font-weight-bold"></td>
                                                    {{-- dimensi --}}
                                                    <td class="text-center font-weight-bold">
                                                    </td>
                                                    <td class="text-center font-weight-bold">
                                                    </td>
                                                    <td class="text-center font-weight-bold">
                                                    </td>
                                                    {{-- volume --}}
                                                    <td class="text-center font-weight-bold"
                                                        v-if="['1','2'].includes(invForm.invoice_type)">
                                                        @{{ invForm.total_volume ? invForm.total_volume.toFixed(2) : 0 }}</td>
                                                    </td>
                                                    {{-- m3 --}}
                                                    <td class="text-center font-weight-bold">
                                                        @{{ invForm.total_volume_m3 ? invForm.total_volume_m3.toFixed(2) : 0 }}
                                                    </td>
                                                    {{-- actual --}}
                                                    <td class="text-center font-weight-bold">
                                                        @{{ invForm.total_actual }}
                                                    </td>
                                                    {{-- charge weight --}}
                                                    <td class="text-center font-weight-bold">
                                                        @{{ invForm.total_chargeable_weight }}</td>
                                                    <td class="text-center font-weight-bold"></td>
                                                    <td class="text-center font-weight-bold">
                                                        @{{ formatPrice(invForm.total_charge_packaging) }}
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('manifest-barang') }}" class="btn btn-default"
                                    data-dismiss="modal">Kembali</a>
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
                    manifest: @json($manifest),
                    invForm: {
                        invoice_type: "",
                        service_type: "",
                        payment_type: "",
                        do_number: "",
                        date_manifest: "",
                        sales_name: "",
                        vendor_id: "",
                        vendor_name: "",
                        shipping_name: "",
                        shipping_address: "",
                        shipping_city: "",
                        phone_number: "",
                        receipt_number: "",
                        destination: "",
                        receipt_number: "",
                        moda: "",
                        drop_pickup: "",
                        photo_product: "",
                        photo_travel_document: "",
                        total_colly: 0,
                        total_weight: 0,
                        total_volume: 0,
                        total_volume_m3: 0,
                        total_actual: 0,
                        total_chargeable_weight: 0,
                        total_charge_packaging: 0,
                        recipient_company: "",
                        recipient_address: "",
                        recipient_city: "",
                        recipient_zip: "",
                        recipient_name: "",
                        recipient_phone: "",
                        recipient_detail: "",
                        instruction_special: "",
                        details: [],
                    },
                    tfootSpan: 2,
                    lautDetails: [],
                    lautItems: {
                        product_name: "",
                        colly: 0,
                        weight: 0,
                        dimension_p: 0,
                        dimension_l: 0,
                        dimension_t: 0,
                        volume: 0,
                        volume_m3: 0,
                        actual: 0,
                        chargeable_weight: 0,
                        packaging: "",
                        charge_packaging: 0,
                    },
                    kendaraanItems: {
                        product_name: "",
                        colly: 0,
                        weight: "",
                        dimension_p: 0,
                        dimension_l: 0,
                        dimension_t: 0,
                        volume: 0,
                        volume_m3: 0,
                        actual: 0,
                        chargeable_weight: 0,
                        packaging: "",
                        charge_packaging: "",
                    },
                    udaraItems: {
                        product_name: "",
                        colly: 0,
                        weight: "",
                        dimension_p: 0,
                        dimension_l: 0,
                        dimension_t: 0,
                        volume: 0,
                        volume_m3: 0,
                        actual: 0,
                        chargeable_weight: 0,
                        packaging: "",
                        charge_packaging: "",
                    },
                    customers: [],
                    vendors: [],
                    details: @json($manifest->details),
                    customer: null,
                    vendor: null,
                }
            },
            mounted() {
                this.invForm = {
                    invoice_type: this.manifest.invoice_type.toString(),
                    date_manifest: this.manifest.date_manifest,
                    do_number: this.manifest.do_number,
                    payment_type: this.manifest.payment_type,
                    service_type: this.manifest.service_type,
                    sales_name: this.manifest.sales_name,
                    vendor_id: this.manifest.vendor_id,
                    vendor_name: this.manifest.vendor_name,
                    shipping_name: this.manifest.shipping_name,
                    shipping_address: this.manifest.shipping_address,
                    shipping_city: this.manifest.shipping_city,
                    phone_number: this.manifest.phone_number,
                    receipt_number: this.manifest.receipt_number,
                    destination: this.manifest.destination,
                    receipt_number: this.manifest.receipt_number,
                    moda: this.manifest.moda,
                    drop_pickup: this.manifest.drop_pickup,
                    photo_product: "",
                    photo_travel_document: "",
                    total_colly: this.manifest.total_colly,
                    total_weight: this.manifest.total_weight,
                    total_volume: this.manifest.total_volume,
                    total_volume_m3: this.manifest.total_volume_m3,
                    total_actual: this.manifest.total_actual,
                    total_chargeable_weight: this.manifest.total_chargeable_weight,
                    total_charge_packaging: this.manifest.total_charge_packaging,
                    recipient_company: this.manifest.recipient_company,
                    recipient_address: this.manifest.recipient_address,
                    recipient_city: this.manifest.recipient_city,
                    recipient_zip: this.manifest.recipient_zip,
                    recipient_name: this.manifest.recipient_name,
                    recipient_phone: this.manifest.recipient_phone,
                    recipient_detail: this.manifest.recipient_detail,
                    instruction_special: this.manifest.instruction_special,
                    details: this.details
                }
                this.fetchCustomers()
                this.fetchVendor()
            },
            methods: {
                onChangeFotoProduct(e) {
                    this.invForm.photo_product = e.target.files[0]
                },
                onChangeTravelDocument(e) {
                    this.invForm.photo_travel_document = e.target.files[0]
                },
                removeSelected(index) {
                    this.$delete(this.invForm.details, index)
                    this.calculateTotal()
                },
                addRowDetails(type) {
                    switch (type) {
                        case '1':
                            this.invForm.details.push({
                                ...this.lautItems
                            })
                            break;
                        case '2':
                            this.invForm.details.push({
                                ...this.kendaraanItems
                            })
                            break;
                        case '3':
                            this.invForm.details.push({
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
                calculateVolume(p, l, t, colly) {
                    let totalVolume = (parseFloat(p ?? 0) * parseFloat(l ?? 0) * parseFloat(t ?? 0) * parseFloat(
                        colly ?? 0)) / 4000
                    return totalVolume.toFixed(2)
                },
                calculateVolumeM3(p, l, t, colly) {
                    let totalVolume = (parseFloat(p ?? 0) * parseFloat(l ?? 0) * parseFloat(t ?? 0) * parseFloat(
                        colly ?? 0)) / 1000000
                    return totalVolume.toFixed(2)
                },
                calculateActual(colly, weight) {
                    let totalActual = parseFloat(colly ?? 0) * parseFloat(weight ?? 0)
                    return totalActual
                },
                roundUpMax(vol, actual, precision = 2) {
                    const maxValue = Math.max(parseFloat(vol ?? 0), parseFloat(actual ?? 0));
                    return Math.round(maxValue * precision) / precision;
                },
                calculateTotal() {
                    this.invForm.total_colly = this.invForm.details.reduce((a, b) => a + parseFloat(b.colly), 0)
                    this.invForm.total_weight = this.invForm.details.reduce((a, b) => a + parseFloat(b.weight), 0)
                    this.invForm.total_volume = this.invForm.details.reduce((a, b) => a + parseFloat(b.volume), 0)
                    this.invForm.total_volume_m3 = this.invForm.details.reduce((a, b) => a + parseFloat(b
                        .volume_m3), 0)
                    this.invForm.total_actual = this.invForm.details.reduce((a, b) => a + parseFloat(b.actual), 0)
                    this.invForm.total_chargeable_weight = this.invForm.details.reduce((a, b) => a + parseFloat(b
                        .chargeable_weight), 0)
                    this.invForm.total_charge_packaging = this.invForm.details.reduce((a, b) => a + parseFloat(b
                        .charge_packaging), 0)
                },
                calculatePackaging(index) {
                    switch (this.invForm.details[index].packaging) {
                        case 'PAKING KAYU':
                            this.invForm.details[index].charge_packaging = this.invForm.details[index].dimension_p *
                                this.invForm.details[index].dimension_l * this.invForm.details[index].dimension_t *
                                0.6
                            break;
                        case 'WRAPPING':
                            this.invForm.details[index].charge_packaging = this.invForm.details[index].dimension_p *
                                this.invForm.details[index].dimension_l * this.invForm.details[index].dimension_t *
                                0.2
                            break;
                        case 'DUS + WRAPPING':
                            this.invForm.details[index].charge_packaging = this.invForm.details[index].dimension_p *
                                this.invForm.details[index].dimension_l * this.invForm.details[index].dimension_t *
                                0.3
                            break;
                        case 'DUS + BUBLE WRAP + WRAPPING':
                            this.invForm.details[index].charge_packaging = this.invForm.details[index].dimension_p *
                                this.invForm.details[index].dimension_l * this.invForm.details[index].dimension_t *
                                0.3
                            break;
                        default:
                            break;
                    }
                },
                calculateAmount(index) {
                    if (this.invForm.invoice_type != '3') {
                        this.invForm.details[index].volume = this.calculateVolume(this.invForm.details[index]
                            .dimension_p, this.invForm.details[index].dimension_l, this.invForm.details[index]
                            .dimension_t, this.invForm.details[index].colly)
                        this.invForm.details[index].volume_m3 = this.calculateVolumeM3(this.invForm.details[index]
                            .dimension_p, this.invForm.details[index].dimension_l, this.invForm.details[index]
                            .dimension_t, this.invForm.details[index].colly)
                        this.invForm.details[index].actual = this.calculateActual(this.invForm.details[index].colly,
                            this.invForm.details[index].weight)
                        this.invForm.details[index].chargeable_weight = this.roundUpMax(this.invForm.details[index]
                            .volume, this.invForm.details[index].actual)
                        this.calculatePackaging(index)
                    } else {
                        this.invForm.details[index].volume_m3 = this.calculateVolumeM3(this.invForm.details[index]
                            .dimension_p, this.invForm.details[index].dimension_l, this.invForm.details[index]
                            .dimension_t, this.invForm.details[index].colly)
                        this.invForm.details[index].actual = this.calculateActual(this.invForm.details[index].colly,
                            this.invForm.details[index].weight)
                        this.invForm.details[index].chargeable_weight = this.roundUpMax(this.invForm.details[index]
                            .volume, this.invForm.details[index].actual)
                        this.calculatePackaging(index)

                    }
                    this.calculateTotal()
                },
                async fetchCustomers() {
                    const url = "{{ route('get-customer') }}"
                    await axios.get(url).then((resp) => {
                        if (resp.data.length > 0) {
                            resp.data.forEach((val) => {
                                this.customers.push({
                                    label: val.name,
                                    value: {
                                        name: val.name,
                                        phone: val.phone
                                    }
                                })
                            })

                            this.customer = {
                                label: this.manifest.shipping_name,
                                value: {
                                    name: this.manifest.shipping_name,
                                    phone: this.manifest.phone_number
                                }
                            }
                        }
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
                            this.vendor = {
                                label: this.manifest.vendor_name,
                                value: {
                                    id: this.manifest.vendor_id,
                                    name: this.manifest.vendor_name
                                }
                            }
                        }
                    })
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
                onSelectVendor(input) {
                    console.log(input);

                    if (input.value) {
                        this.invForm.vendor_id = input.value.id
                        this.invForm.vendor_name = input.value.name
                    }
                },
                onSubmitForm() {
                    let url = "{{ route('update-manifest', ':id') }}"
                    url = url.replace(':id', this.manifest.id)
                    const formData = new FormData()

                    Object.entries(this.invForm).forEach(([key, value]) => {
                        if (key == 'details') {
                            formData.append(key, JSON.stringify(value))
                        } else {
                            formData.append(key, value)
                        }
                    })

                    $('button').prop('disabled', true)
                    btnText = $('#btnSubmit').html()
                    $('#btnSubmit').html('<i class="fa fa-spinner fa-spin"></i> Loading')
                    axios.post(url, formData)
                        .then((response) => {
                            if (response.data.status == 200) {
                                window.location.href = "{{ route('manifest-barang') }}"
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
            watch: {
                'invForm.invoice_type': function(val) {
                    if (this.details.length == 0) {
                        this.invForm.details = []
                    }
                    switch (val) {
                        case '1':
                            this.tfootSpan = 10
                            break;
                        case '2':
                            this.tfootSpan = 10
                            break;
                        case '3':
                            this.tfootSpan = 13
                            break;
                        default:
                            this.tfootSpan = 3
                            break;
                    }
                },
            },

        });
    </script>
@endpush
