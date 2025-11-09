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
    <div class="content-wrapper" id="vue">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Input Invoice</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">create Invoice</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="['1','3'].includes(invForm.invoice_type)">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>Perhatian</strong> Pastikan Detail berat sesuai dengan minimum berat pada jenis invoice.
                    <strong>Moda Udara : Minimum Berat 20KG</strong>
                    <strong>& Moda Laut : Minimum Berat 100KG</strong>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form id="form-add-invoice" @submit.prevent="onSubmitForm">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Form Tambah Invoice
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jenis Invoice</label>
                                                <select class="form-control" v-model="invForm.invoice_type"
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
                                                <label>Tanggal Invoice</label>
                                                <input type="date" required="required" class="form-control"
                                                    v-model="invForm.invoice_date" placeholder="Tanggal Invoice">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Nama Marketing</label>
                                                <select v-model="invForm.sales_name" id="sales_name" class="form-control"
                                                    required>
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
                                                <v-select :taggable="true" required="required" autocomplete
                                                    v-model="customer" :selectOnTab="true" placeholder="Nama Pengirim"
                                                    style="text-transform:uppercase" :options="customers"
                                                    @option:selected="onSelect" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Alamat Pengirim</label>
                                                <input type="text" required="required" class="form-control"
                                                    v-model="invForm.shipping_address" placeholder="Alamat Pengirim">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Kota Pengirim</label>
                                                <input type="text" required="required" class="form-control"
                                                    v-model="invForm.shipping_city" placeholder="Kota Pengirim"
                                                    style="text-transform:uppercase">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>No Telepon</label>
                                                <input type="text" class="form-control"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    v-model="invForm.phone_number" placeholder="No Telepon">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jenis Pembayaran</label>
                                                <select v-model="invForm.payment_type" id="payment_type"
                                                    class="form-control" required>
                                                    <option value="">Pilih Jenis Pembayaran</option>
                                                    <option value="1">Cash</option>
                                                    <option value="2">TOP</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" v-if="invForm.payment_type == 2">
                                            <div class="form-group" id="top">
                                                <label>Tanggal Batas Bayar</label>
                                                <input type="date" class="form-control"
                                                    v-model="invForm.payment_due_date" placeholder="Tanggal Batas Bayar">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Rekening Bank</label>
                                                <v-select :taggable="false" required="required" autocomplete
                                                    :reduce="bank => bank.value" :selectOnTab="true" multiple
                                                    placeholder="Rekening Bank" label="label" v-model="invForm.banks"
                                                    style="text-transform:uppercase" :options="banks" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tambah Pph</label>
                                                <select v-model="invForm.include_pph" id="include_pph"
                                                    class="form-control" required>
                                                    <option value="">Tambah Pph</option>
                                                    <option value="1">Ya</option>
                                                    <option value="2">Tidak</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Pickup / Dooring</label>
                                                <select v-model="pickupDooring" id="pickupDooring" class="form-control"
                                                    required>
                                                    <option value="pickup">Pickup</option>
                                                    <option value="dooring">Dooring</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" v-if="invForm.invoice_type == 3">
                                            <div class="form-group">
                                                <label>Shipdex / Acc Xray</label>
                                                <select v-model="shipXray" id="shipXray" class="form-control" required>
                                                    <option value="shipdex">Shipdex</option>
                                                    <option value="accxray">Acc Xray</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="button"
                                            @click.stop="addRowDetails(invForm.invoice_type)">Tambah</button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Moda</th>
                                                    <th scope="col">No Resi</th>
                                                    <th scope="col" v-if="['1','3'].includes(invForm.invoice_type)">
                                                        Colly</th>
                                                    <th scope="col" v-if="['1','3'].includes(invForm.invoice_type)">
                                                        Chargeable Weight</th>
                                                    <th scope="col" v-if="invForm.invoice_type == '2'">Unit</th>
                                                    <th scope="col" v-if="invForm.invoice_type == '2'">Product</th>
                                                    <th scope="col">Destination</th>
                                                    <th scope="col">Price</th>
                                                    <th scope="col" v-if="pickupDooring == 'pickup'">
                                                        Pickup</th>
                                                    <th scope="col" v-if="pickupDooring == 'dooring'">
                                                        Dooring</th>
                                                    <th scope="col">
                                                        Repacking</th>
                                                    <th scope="col">
                                                        Insurance</th>
                                                    <th scope="col" v-if="invForm.invoice_type == '1'">FORKLIFT</th>
                                                    <th scope="col" v-if="['1','2'].includes(invForm.invoice_type)">
                                                        Lalamove/Grab
                                                    </th>
                                                    <th scope="col" v-if="invForm.invoice_type == '3'">Minimum Hdl</th>
                                                    <th scope="col"
                                                        v-if="invForm.invoice_type == '3' && shipXray == 'shipdex'">Shipdex
                                                    </th>
                                                    <th scope="col" v-if="invForm.invoice_type == '3'">Dus Un</th>
                                                    <th scope="col"
                                                        v-if="invForm.invoice_type == '3' && shipXray == 'accxray'">Acc
                                                        Xray</th>
                                                    <th scope="col" v-if="invForm.invoice_type == '3'">Adm Smu</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Remarks</th>
                                                    <th scope="col">#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in invForm.details">
                                                    <td style="width: 45px;">@{{ index + 1 }}</td>
                                                    <td style="min-width:140px;">
                                                        <select class="form-control" v-model="invForm.details[index].moda"
                                                            required="required" id="moda">
                                                            <option value="">Pilih Moda</option>
                                                            <option value="Darat">Darat</option>
                                                            <option value="Laut">Laut</option>
                                                            <option value="Udara">Udara</option>
                                                            <option value="Darat-Laut">Darat-Laut</option>
                                                        </select>
                                                    </td>
                                                    <td style="min-width:180px;">
                                                        <input type="text" required="required" class="form-control"
                                                            v-model="invForm.details[index].receipt_number"
                                                            placeholder="No Resi">
                                                    </td>
                                                    <td style="min-width:100px;"
                                                        v-if="['1','3'].includes(invForm.invoice_type)"><input
                                                            type="text" required="required" id="colly"
                                                            class="form-control colly"
                                                            v-model="invForm.details[index].colly" placeholder="colly"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:140px;"
                                                        v-if="['1','3'].includes(invForm.invoice_type)">
                                                        <div class="input-group mb-3">
                                                            <input type="text" required="required"
                                                                @change="calculateAmount(index)" class="form-control"
                                                                v-model="invForm.details[index].chargeable_weight"
                                                                placeholder="Chargeable Weight"
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Kg</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="min-width:180px;" v-if="invForm.invoice_type == '2'"><input
                                                            type="text" required="required" id="unit"
                                                            @change="calculateAmount(index)" class="form-control unit"
                                                            v-model="invForm.details[index].unit" placeholder="unit"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:180px;" v-if="invForm.invoice_type == '2'"><input
                                                            type="text" required="required" id="product"
                                                            class="form-control product"
                                                            v-model="invForm.details[index].product"
                                                            placeholder="Product">
                                                    </td>
                                                    <td style="min-width:180px;"><input type="text"
                                                            required="required" class="form-control"
                                                            v-model="invForm.details[index].destination"
                                                            placeholder="Destinasi" style="text-transform:uppercase">
                                                    </td>
                                                    <td style="min-width:110px;"><input type="text"
                                                            required="required" id="price"
                                                            @change="calculateAmount(index)" class="form-control price"
                                                            v-model="invForm.details[index].price" placeholder="Price"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;" v-if="pickupDooring == 'pickup'"><input
                                                            type="text" id="pickup" class="form-control pickup"
                                                            v-model="invForm.details[index].pickup"
                                                            @change="calculateAmount(index)" placeholder="Pickup"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;" v-if="pickupDooring == 'dooring'"><input
                                                            type="text" id="dooring" class="form-control dooring"
                                                            v-model="invForm.details[index].dooring"
                                                            @change="calculateAmount(index)" placeholder="Dooring"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;"
                                                        v-if="['1','2','3'].includes(invForm.invoice_type)"><input
                                                            type="text" id="repacking" class="form-control repacking"
                                                            v-model="invForm.details[index].repacking"
                                                            placeholder="Repacking" @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;"
                                                        v-if="['1','2','3'].includes(invForm.invoice_type)"><input
                                                            type="text" id="insurance" class="form-control insurance"
                                                            v-model="invForm.details[index].insurance"
                                                            placeholder="Insurance" @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;" v-if="invForm.invoice_type == '1'"><input
                                                            type="text" id="forklift" class="form-control forklift"
                                                            v-model="invForm.details[index].forklift"
                                                            @change="calculateAmount(index)" placeholder="Forklift"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;"
                                                        v-if="['1','2'].includes(invForm.invoice_type)"><input
                                                            type="text" id="lalamove_grab"
                                                            class="form-control lalamove_grab"
                                                            v-model="invForm.details[index].lalamove_grab"
                                                            @change="calculateAmount(index)" placeholder="Lalamove/Grab"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;" v-if="invForm.invoice_type == '3'"><input
                                                            type="text" id="minimum_hdl"
                                                            class="form-control minimum_hdl"
                                                            v-model="invForm.details[index].minimum_hdl"
                                                            placeholder="minimum_hdl" @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;"
                                                        v-if="invForm.invoice_type == '3' && shipXray == 'shipdex'"><input
                                                            type="text" id="shipdex" class="form-control shipdex"
                                                            v-model="invForm.details[index].shipdex" placeholder="shipdex"
                                                            @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;" v-if="invForm.invoice_type == '3'"><input
                                                            type="text" id="dus_un" class="form-control dus_un"
                                                            v-model="invForm.details[index].dus_un" placeholder="Dus Un"
                                                            @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;"
                                                        v-if="invForm.invoice_type == '3' && shipXray == 'accxray'"><input
                                                            type="text" id="acc_xray" class="form-control acc_xray"
                                                            v-model="invForm.details[index].acc_xray"
                                                            placeholder="Acc Xray" @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;" v-if="invForm.invoice_type == '3'"><input
                                                            type="text" id="adm_smu" class="form-control adm_smu"
                                                            v-model="invForm.details[index].adm_smu" placeholder="Adm Smu"
                                                            @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:180px;">@{{ formatPrice(invForm.details[index].amount) }}</td>
                                                    <td style="min-width:100px;">
                                                        <input type="text" class="form-control"
                                                            @change="calculateAmount(index)" name="remarks"
                                                            placeholder="Remarks">
                                                    </td>
                                                    <td style="min-width:18px;">
                                                        <button type="button" class="btn btn-light text-danger"
                                                            @click="removeSelected(index)"><i
                                                                class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td :colspan="tfootSpan" class="text-right">Subtotal</td>
                                                    <td colspan="3" class="text-right font-weight-bold">
                                                        @{{ formatPrice(subtotal) }}</td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td :colspan="tfootSpan" class="text-right">Discount</td>
                                                    <td colspan="3"><input type="text" id="discount"
                                                            class="form-control discount" v-model="invForm.discount"
                                                            placeholder="Discount"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td :colspan="tfootSpan" class="text-right">PPN</td>
                                                    <td colspan="3" class="text-right font-weight-bold">
                                                        @{{ formatPrice(invForm.ppn) }}</td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td :colspan="tfootSpan" class="text-right">Pph</td>
                                                    <td colspan="3" class="text-right font-weight-bold">
                                                        @{{ formatPrice(invForm.pph) }}</td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td :colspan="tfootSpan" class="text-right">Down Payment</td>
                                                    <td colspan="3"><input type="text" id="down_payment"
                                                            class="form-control down_payment"
                                                            v-model="invForm.down_payment" placeholder="down_payment"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td :colspan="tfootSpan" class="text-right">Total</td>
                                                    <td colspan="3" class="text-right font-weight-bold">
                                                        @{{ formatPrice(total) }}</td>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('shipping.index') }}" class="btn btn-default"
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
                    customer: null,
                    invForm: {
                        invoice_type: "",
                        invoice_date: "",
                        sales_name: "",
                        shipping_name: "",
                        shipping_address: "",
                        shipping_city: "",
                        phone_number: "",
                        receipt_number: "",
                        destination: "",
                        payment_type: "",
                        payment_due_date: "",
                        include_pph: "",
                        total: 0,
                        sub_total: 0,
                        ppn: 0,
                        pph: 0,
                        down_payment: 0,
                        discount: 0,
                        details: [],
                        banks: []
                    },
                    tfootSpan: 2,
                    lautDetails: [],
                    lautItems: {
                        manifest_id: null,
                        colly: 0,
                        chargeable_weight: 0,
                        destination: "",
                        price: 0,
                        pickup: 0,
                        dooring: 0,
                        repacking: 0,
                        insurance: 0,
                        forklift: 0,
                        lalamove_grab: 0,
                        amount: 0,
                        remarks: "",
                    },
                    kendaraanItems: {
                        manifest_id: null,
                        unit: 0,
                        product: "",
                        destination: "",
                        price: 0,
                        pickup: 0,
                        dooring: 0,
                        repacking: 0,
                        insurance: 0,
                        amount: 0,
                        remarks: "",
                    },
                    udaraItems: {
                        manifest_id: null,
                        colly: 0,
                        chargeable_weight: 0,
                        destination: "",
                        price: 0,
                        pickup: 0,
                        dooring: 0,
                        repacking: 0,
                        insurance: 0,
                        minimum_hdl: 0,
                        shipdex: 0,
                        dus_un: 0,
                        acc_xray: 0,
                        adm_smu: 0,
                        amount: 0,
                        remarks: "",
                    },
                    pickupDooring: "pickup",
                    shipXray: "shipdex",
                    customers: [],
                    banks: []
                }
            },
            computed: {
                subtotal() {
                    let sum = 0
                    for (let i = 0; i < this.invForm.details.length; i++) {
                        sum += parseInt(this.invForm.details[i].amount)
                    }

                    let discount = parseInt(this.invForm.discount)
                    if (discount > 0) {
                        return (sum - discount)
                    } else {
                        return sum
                    }
                },

                total() {
                    let tax = this.subtotal * (1.1 / 100)
                    this.invForm.ppn = tax

                    if (this.invForm.include_pph === '1') {
                        let tax_pph = this.subtotal * (2 / 100)
                        this.invForm.pph = tax_pph
                        let dp = parseInt(this.invForm.down_payment)
                        // if (dp > (this.subtotal + tax + tax_pph)) {
                        if (dp > (this.subtotal + tax)) {
                            // this.invForm.down_payment = (this.subtotal + tax + tax_pph)
                            this.invForm.down_payment = (this.subtotal + tax)
                        }
                        // return (this.subtotal + tax + tax_pph) - dp
                        return (this.subtotal + tax) - dp
                    } else {
                        this.invForm.pph = 0
                        let dp = parseInt(this.invForm.down_payment)
                        if (dp > (this.subtotal + tax)) {
                            this.invForm.down_payment = (this.subtotal + tax)
                        }
                        return (this.subtotal + tax) - dp
                    }
                }
            },
            created() {},
            mounted() {
                if (this.manifest.length > 0) {
                    this.invForm = {
                        invoice_type: this.manifest[0].invoice_type.toString(),
                        invoice_date: "",
                        sales_name: this.manifest[0].sales_name,
                        shipping_name: this.manifest[0].shipping_name,
                        shipping_address: this.manifest[0].shipping_address,
                        shipping_city: this.manifest[0].shipping_city,
                        phone_number: this.manifest[0].phone_number,
                        receipt_number: this.manifest[0].receipt_number,
                        destination: this.manifest[0].destination,
                        manifest_id: this.manifest[0].id,
                        payment_type: this.manifest[0].payment_type,
                        payment_due_date: "",
                        include_pph: "",
                        total: 0,
                        sub_total: 0,
                        ppn: 0,
                        pph: 0,
                        down_payment: 0,
                        discount: 0,
                        details: [],
                        banks: []
                    }
                }
                this.fetchCustomers()
                this.fetchBanks()
            },
            methods: {
                removeSelected(index) {
                    this.$delete(this.invForm.details, index)
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
                calculateAmount(index) {
                    this.invForm.details[index].starting_price = parseFloat(this.invForm.details[index].price ?? 0)
                    let pickupDooring = this.pickupDooring == "pickup" ? this.invForm.details[index].pickup : this
                        .invForm.details[index].dooring
                    let addsLaut = parseInt(pickupDooring ?? 0) + parseInt(this.invForm
                            .details[index].repacking ?? 0) + parseInt(this.invForm.details[index].insurance ?? 0) +
                        parseInt(this.invForm.details[index].forklift ?? 0) + parseInt(this.invForm.details[index]
                            .lalamove_grab ?? 0)

                    let addsUdara = parseInt(this.invForm.details[index].minimum_hdl ?? 0) + parseInt(this.invForm
                            .details[index].shipdex ?? 0) + parseInt(this.invForm.details[index].dus_un ?? 0) +
                        parseInt(this.invForm.details[index].acc_xray ?? 0) + parseInt(this.invForm.details[index]
                            .adm_smu ?? 0) + parseInt(pickupDooring ?? 0) + parseInt(this
                            .invForm
                            .details[index].repacking ?? 0) + parseInt(this.invForm.details[index].insurance ?? 0)
                    // let addsKKendaraan = parseInt(this.invForm.details[index].repacking ?? 0) + parseInt(this.invForm.details[index].insurance ?? 0)

                    let chargeValue = 0;
                    let productValue = 0;
                    if (this.invForm.include_pph === '1') {
                        let price_up = parseFloat(this.invForm.details[index].price * 0.02 ?? 0) + parseFloat(this
                            .invForm.details[index].price ?? 0)
                        chargeValue = parseFloat(this.invForm.details[index].chargeable_weight ?? 0) * price_up
                        productValue = parseFloat(this.invForm.details[index].unit ?? 0) * price_up

                        this.invForm.details[index].price = price_up
                    } else {
                        chargeValue = parseFloat(this.invForm.details[index].chargeable_weight ?? 0) * parseFloat(
                            this
                            .invForm.details[index].price ?? 0)
                        productValue = parseFloat(this.invForm.details[index].unit ?? 0) * parseFloat(this.invForm
                            .details[index].price ?? 0)
                    }

                    if (this.invForm.invoice_type === '1') {
                        this.invForm.details[index].amount = addsLaut + chargeValue + productValue
                    } else if (this.invForm.invoice_type === '2') {
                        this.invForm.details[index].amount = addsUdara + chargeValue + productValue + pickupDooring
                    } else {
                        this.invForm.details[index].amount = addsUdara + chargeValue + productValue
                    }

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
                            if (this.manifest.length > 0) {

                                if (this.manifest[0].invoice_type == 1) {
                                    this.manifest.forEach((val) => {
                                        this.invForm.details.push({
                                            manifest_id: val.id,
                                            moda: val.moda,
                                            receipt_number: val.receipt_number,
                                            colly: val.total_colly,
                                            chargeable_weight: val
                                                .total_chargeable_weight,
                                            destination: val.destination,
                                            price: 0,
                                            pickup: 0,
                                            dooring: 0,
                                            repacking: val.total_charge_packaging,
                                            insurance: 0,
                                            forklift: 0,
                                            lalamove_grab: 0,
                                            amount: 0,
                                            remarks: "",
                                        })
                                    })
                                    this.tfootSpan = 10
                                } else if (this.manifest[0].invoice_type == 2) {
                                    this.manifest.forEach((val) => {
                                        this.invForm.details.push({
                                            manifest_id: val.id,
                                            moda: val.moda,
                                            receipt_number: val.receipt_number,
                                            unit: 0,
                                            product: "",
                                            destination: "",
                                            price: val.total_charge_packaging,
                                            pickup: 0,
                                            dooring: 0,
                                            repacking: 0,
                                            insurance: 0,
                                            amount: 0,
                                            remarks: "",
                                        })
                                    })
                                    this.tfootSpan = 10
                                } else if (this.manifest[0].invoice_type == 3) {
                                    this.manifest.forEach((val) => {
                                        this.invForm.details.push({
                                            manifest_id: val.id,
                                            moda: val.moda,
                                            receipt_number: val.receipt_number,
                                            colly: val.total_colly,
                                            chargeable_weight: val
                                                .total_chargeable_weight,
                                            destination: val.destination,
                                            price: 0,
                                            pickup: 0,
                                            dooring: 0,
                                            repacking: val.total_charge_packaging,
                                            insurance: 0,
                                            minimum_hdl: 0,
                                            shipdex: 0,
                                            dus_un: 0,
                                            acc_xray: 0,
                                            adm_smu: 0,
                                            amount: 0,
                                            remarks: "",
                                        })
                                    })
                                    this.tfootSpan = 13
                                }
                                this.customer = {
                                    label: this.manifest[0].shipping_name,
                                    value: {
                                        name: this.manifest[0].shipping_name,
                                        phone: this.manifest[0].phone_number
                                    }
                                }
                            }
                        }
                    })
                },
                async fetchBanks() {
                    const url = "{{ route('get-bank') }}"
                    await axios.get(url).then((resp) => {
                        if (resp.data.length > 0) {
                            resp.data.forEach((val) => {
                                this.banks.push({
                                    label: `${val.name_bank} - ${val.number_account}`,
                                    value: {
                                        id: val.id,
                                        name_bank: val.name_bank,
                                        name_account: val.name_account,
                                        number_account: val.number_account
                                    }
                                })
                            })
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
                onSubmitForm() {
                    this.invForm.sub_total = this.subtotal
                    this.invForm.total = this.total

                    $('button').prop('disabled', true)
                    btnText = $('#btnSubmit').html()
                    $('#btnSubmit').html('<i class="fa fa-spinner fa-spin"></i> Loading')
                    axios.post('{{ route('shipping.shipping-store') }}', {
                            ...this.invForm
                        })
                        .then((response) => {
                            console.log(response)
                            if (response.data.status == 200) {
                                window.location.href = "{{ route('shipping.index') }}"
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
                    this.invForm.details = []
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
                }
            },

        });
    </script>
@endpush
