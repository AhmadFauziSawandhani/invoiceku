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
                <div class="row">
                    <div class="col-12">
                        <form id="form-add-invoice" @submit.prevent="onSubmitForm">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Form Ubah Invoice
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
                                                    required="required" id="invoice_type" disabled>
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
                                                <input type="date" required="required" class="form-control" disabled
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
                                                <input type="text" required="required" class="form-control"
                                                    v-model="invForm.shipping_name" placeholder="Nama Pengirim"
                                                    style="text-transform:uppercase">
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
                                                <v-select :taggable="false" required="required" autocomplete resetOnOptionsChange
                                                    :reduce="bank => bank.value" :selectOnTab="true" multiple :value="invForm.banks"
                                                    placeholder="Rekening Bank" v-model="invForm.banks"
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
                                                <select v-model="pickupDooring" id="pickupDooring"
                                                    class="form-control" required>
                                                    <option value="pickup">Pickup</option>
                                                    <option value="dooring">Dooring</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" v-if="invForm.invoice_type == 3">
                                            <div class="form-group">
                                                <label>Shipdex / Acc Xray</label>
                                                <select v-model="shipXray" id="shipXray"
                                                    class="form-control" required>
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
                                                    <th scope="col"
                                                        v-if="['1','2','3'].includes(invForm.invoice_type)">
                                                        Repacking</th>
                                                    <th scope="col"
                                                        v-if="['1','2','3'].includes(invForm.invoice_type)">
                                                        Insurance</th>
                                                    <th scope="col" v-if="invForm.invoice_type == '1'">FORKLIFT</th>
                                                    <th scope="col" v-if="['1','2'].includes(invForm.invoice_type)">Lalamove/Grab
                                                    </th>
                                                    <th scope="col" v-if="invForm.invoice_type == '3'">Minimum Hdl</th>
                                                    <th scope="col" v-if="invForm.invoice_type == '3' && shipXray == 'shipdex'">Shipdex</th>
                                                    <th scope="col" v-if="invForm.invoice_type == '3'">Dus Un</th>
                                                    <th scope="col" v-if="invForm.invoice_type == '3' && shipXray == 'accxray'" >Acc Xray</th>
                                                    <th scope="col" v-if="invForm.invoice_type == '3'">Adm Smu</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Remarks</th>
                                                    <th scope="col">#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in invForm.details">
                                                    <td>1</td>
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
                                                    <td style="min-width:140px;">
                                                        <input type="text" required="required" class="form-control"
                                                            id="receipt_number"
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
                                                    <td style="min-width:140px;" v-if="invForm.invoice_type == '2'"><input
                                                            type="text" required="required" id="unit"
                                                            @change="calculateAmount(index)" class="form-control unit"
                                                            v-model="invForm.details[index].unit" placeholder="unit"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:140px;" v-if="invForm.invoice_type == '2'"><input
                                                            type="text" required="required" id="product"
                                                            class="form-control product"
                                                            v-model="invForm.details[index].product"
                                                            placeholder="Product">
                                                    </td>
                                                    <td style="min-width:140px;"><input type="text"
                                                            required="required" id="destination" class="form-control"
                                                            v-model="invForm.details[index].destination"
                                                            placeholder="Destinasi" style="text-transform:uppercase">
                                                    </td>
                                                    <td style="min-width:120px;"><input type="text"
                                                            required="required" id="price"
                                                            @change="calculateAmount(index)" class="form-control price"
                                                            v-model="invForm.details[index].price" placeholder="Price"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;"
                                                        v-if="pickupDooring == 'pickup'"><input
                                                            type="text" id="pickup" class="form-control pickup"
                                                            v-model="invForm.details[index].pickup"
                                                            @change="calculateAmount(index)" placeholder="Pickup"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;"
                                                        v-if="pickupDooring == 'dooring'"><input
                                                            type="text" id="dooring" class="form-control dooring"
                                                            v-model="invForm.details[index].dooring"
                                                            @change="calculateAmount(index)" placeholder="Dooring"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:120px;"
                                                        v-if="['1','2','3'].includes(invForm.invoice_type)"><input
                                                            type="text" id="repacking" class="form-control repacking"
                                                            v-model="invForm.details[index].repacking"
                                                            placeholder="Repacking" @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:120px;"
                                                        v-if="['1','2','3'].includes(invForm.invoice_type)"><input
                                                            type="text" id="insurance" class="form-control insurance"
                                                            v-model="invForm.details[index].insurance"
                                                            placeholder="Insurance" @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:120px;" v-if="invForm.invoice_type == '1'"><input
                                                            type="text" id="forklift" class="form-control forklift"
                                                            v-model="invForm.details[index].forklift"
                                                            @change="calculateAmount(index)" placeholder="Forklift"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:120px;" v-if="['1','2'].includes(invForm.invoice_type)"><input
                                                            type="text" id="lalamove_grab"
                                                            class="form-control lalamove_grab"
                                                            v-model="invForm.details[index].lalamove_grab"
                                                            @change="calculateAmount(index)" placeholder="Lalamove/Grab"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:120px;" v-if="invForm.invoice_type == '3'"><input
                                                            type="text" id="minimum_hdl"
                                                            class="form-control minimum_hdl"
                                                            v-model="invForm.details[index].minimum_hdl"
                                                            placeholder="minimum_hdl" @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;" v-if="invForm.invoice_type == '3' && shipXray == 'shipdex'"><input
                                                            type="text" id="shipdex" class="form-control shipdex"
                                                            v-model="invForm.details[index].shipdex" placeholder="shipdex"
                                                            @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:120px;" v-if="invForm.invoice_type == '3'"><input
                                                            type="text" id="dus_un" class="form-control dus_un"
                                                            v-model="invForm.details[index].dus_un" placeholder="Dus Un"
                                                            @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:110px;" v-if="invForm.invoice_type == '3' && shipXray == 'accxray'"><input
                                                            type="text" id="acc_xray" class="form-control acc_xray"
                                                            v-model="invForm.details[index].acc_xray"
                                                            placeholder="Acc Xray" @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <td style="min-width:120px;" v-if="invForm.invoice_type == '3'"><input
                                                            type="text" id="adm_smu" class="form-control adm_smu"
                                                            v-model="invForm.details[index].adm_smu" placeholder="Adm Smu"
                                                            @change="calculateAmount(index)"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    </td>
                                                    <input type="hidden" id="detail_id" class="form-control detail_id"
                                                        v-model="invForm.details[index].detail_id">
                                                    <td>@{{ formatPrice(invForm.details[index].amount) }}</td>
                                                    <td style="min-width:100px;">
                                                        <input type="text" class="form-control"
                                                            @change="calculateAmount(index)" name="remarks"
                                                            placeholder="Remarks">
                                                    </td>
                                                    <td>
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
                    data: @json($payload),
                    details: [],
                    invForm: {
                        invoice_type: "",
                        invoice_date: "",
                        shipping_name: "",
                        sales_name: "",
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
                        detail_id: 0,
                        manifest_id:null,
                        colly: 0,
                        chargeable_weight: 0,
                        price: 0,
                        starting_price: 0,
                        moda: "",
                        destination: "",
                        receipt_number: "",
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
                        detail_id: 0,
                        manifest_id:null,
                        unit: 0,
                        product: "",
                        price: 0,
                        pickup: 0,
                        dooring: 0,
                        starting_price: 0,
                        moda: "",
                        destination: "",
                        receipt_number: "",
                        repacking: 0,
                        insurance: 0,
                        amount: 0,
                        remarks: "",
                    },
                    udaraItems: {
                        detail_id: 0,
                        manifest_id:null,
                        colly: 0,
                        chargeable_weight: 0,
                        price: 0,
                        starting_price: 0,
                        pickup: 0,
                        dooring: 0,
                        repacking: 0,
                        insurance: 0,
                        moda: "",
                        destination: "",
                        receipt_number: "",
                        minimum_hdl: 0,
                        shipdex: 0,
                        dus_un: 0,
                        acc_xray: 0,
                        adm_smu: 0,
                        amount: 0,
                        remarks: "",
                    },
                    pickupDooring:"pickup",
                    shipXray:"shipdex",
                    refresh: 0,
                    banks: []
                }
            },
            computed: {
                subtotal() {
                    this.refresh

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
                    let tax = parseInt(this.subtotal) * (1.1 / 100)
                    this.invForm.ppn = parseInt(tax)
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
                        let dp = parseInt(this.invForm.down_payment)
                        if (dp > (parseInt(this.subtotal) + parseInt(tax))) {
                            this.invForm.down_payment = (parseInt(this.subtotal) + parseInt(tax))
                        }
                        return (parseInt(this.subtotal) + parseInt(tax)) - dp
                    }
                }
            },
            async mounted() {
                await this.fetchBanks()
                this.mapDataDetails()
            },
            methods: {
                mapDataDetails() {
                    this.invForm = {
                        invoice_type: this.data.data.invoice_type,
                        invoice_date: this.data.data.invoice_date,
                        sales_name: this.data.data.sales_name,
                        shipping_name: this.data.data.shipping_name,
                        shipping_address: this.data.data.shipping_address,
                        shipping_city: this.data.data.shipping_city,
                        phone_number: this.data.data.phone_number,
                        receipt_number: this.data.data.receipt_number,
                        destination: this.data.data.destination,
                        payment_type: this.data.data.payment_type,
                        payment_due_date: this.data.data.payment_due_date,
                        total: this.data.data.total ? this.data.data.total : 0,
                        sub_total: this.data.data.sub_total ? this.data.data.sub_total : 0,
                        include_pph: this.data.data.include_pph,
                        ppn: this.data.data.ppn ? this.data.data.ppn : 0,
                        pph: this.data.data.pph ? this.data.data.pph : 0,
                        down_payment: this.data.data.down_payment ? this.data.data.down_payment : 0,
                        discount: this.data.data.discount ? this.data.data.discount : 0,
                        details: [],
                        banks: []
                    }
                    this.data.data.details.forEach((val) => {
                        if (this.data.data.invoice_type == '1') {
                            this.invForm.details.push({
                                detail_id: val.id,
                                manifest_id: val.manifest_id,
                                colly: val.colly,
                                chargeable_weight: val.chargeable_weight,
                                moda: val.moda,
                                receipt_number: val.receipt_number,
                                destination: val.destination,
                                price: val.price ? val.price : 0,
                                pickup: val.price_addons_pickup ? val.price_addons_pickup : 0,
                                dooring: val.price_addons_dooring ? val.price_addons_dooring : 0,
                                repacking: val.price_addons_packing ? val.price_addons_packing : 0,
                                insurance: val.price_addons_insurance ? val.price_addons_insurance :
                                    0,
                                amount: val.amount ? val.amount : 0,
                                starting_price: val.starting_price ? val.starting_price : 0,
                                forklift: val.forklift ? val.forklift : 0,
                                lalamove_grab: val.lalamove_grab ? val.lalamove_grab : 0,
                                remarks: val.remarks,
                            })
                        } else if (this.data.data.invoice_type == '2') {
                            this.invForm.details.push({
                                detail_id: val.id,
                                manifest_id: val.manifest_id,
                                unit: val.unit,
                                product: val.product,
                                moda: val.moda,
                                receipt_number: val.receipt_number,
                                destination: val.destination,
                                price: val.price ? val.price : 0,
                                pickup: val.price_addons_pickup ? val.price_addons_pickup : 0,
                                dooring: val.price_addons_dooring ? val.price_addons_dooring : 0,
                                starting_price: val.starting_price ? val.starting_price : 0,
                                repacking: val.price_addons_packing ? val.price_addons_packing : 0,
                                insurance: val.price_addons_insurance ? val.price_addons_insurance :
                                    0,
                                amount: val.amount ? val.amount : 0,
                                remarks: val.remarks,
                            })
                        } else if (this.data.data.invoice_type == '3') {
                            this.invForm.details.push({
                                detail_id: val.id,
                                manifest_id: val.manifest_id,
                                colly: val.colly,
                                chargeable_weight: val.chargeable_weight,
                                moda: val.moda,
                                receipt_number: val.receipt_number,
                                destination: val.destination,
                                price: val.price ? val.price : 0,
                                pickup: val.price_addons_pickup ? val.price_addons_pickup : 0,
                                dooring: val.price_addons_dooring ? val.price_addons_dooring : 0,
                                repacking: val.price_addons_packing ? val.price_addons_packing : 0,
                                insurance: val.price_addons_insurance ? val.price_addons_insurance :
                                    0,
                                starting_price: val.starting_price ? val.starting_price : 0,
                                minimum_hdl: val.minimum_hdl ? val.minimum_hdl : 0,
                                shipdex: val.shipdex ? val.shipdex : 0,
                                dus_un: val.dus_un ? val.dus_un : 0,
                                acc_xray: val.acc_xray ? val.acc_xray : 0,
                                adm_smu: val.adm_smu ? val.adm_smu : 0,
                                amount: val.amount ? val.amount : 0,
                                remarks: val.remarks,
                            })
                        }
                    })
                    this.data.data.shipping_banks.forEach((val) => {
                        const item = this.banks.find((value) => value.value.number_account == val.number_account)
                        this.invForm.banks.push(item.value)
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
                onSelectBank(input) {
                    console.log(input);
                },
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

                    let pickupDooring = this.pickupDooring == "pickup" ? this.invForm.details[index].pickup : this.invForm.details[index].dooring
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

                    let chargeValue = 0
                    let productValue = 0
                    if (this.invForm.include_pph == '1') {
                        let price_up = parseFloat(this.invForm.details[index].price * 0.02 ?? 0) + parseFloat(this
                            .invForm.details[index].price ?? 0)
                        chargeValue = parseFloat(this.invForm.details[index].chargeable_weight ?? 0) * price_up
                        productValue = parseFloat(this.invForm.details[index].unit ?? 0) * price_up
                        console.log(this.invForm.details[index].price);
                        this.invForm.details[index].price = price_up
                    } else {
                        chargeValue = parseFloat(this.invForm.details[index].chargeable_weight ?? 0) * parseFloat(
                            this
                            .invForm.details[index].price ?? 0);
                        productValue = parseFloat(this.invForm.details[index].unit ?? 0) * parseFloat(this.invForm
                            .details[index].price ?? 0);
                    }

                    if (this.invForm.invoice_type === '1') {
                        this.invForm.details[index].amount = addsLaut + chargeValue + productValue
                    } else if (this.invForm.invoice_type === '2') {
                        this.invForm.details[index].amount = addsUdara + chargeValue + productValue + pickupDooring
                    }
                    else {
                        this.invForm.details[index].amount = addsUdara + chargeValue + productValue
                    }

                },
                calculateAmountChange(index) {
                    this.invForm.details[index].starting_price = this.invForm.details[index].starting_price ??
                        parseFloat(this.invForm.details[index].price ?? 0)
                    this.refresh++
                    let pickupDooring = this.pickupDooring == "pickup" ? this.invForm.details[index].pickup : this.invForm.details[index].dooring

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

                    let chargeValue = 0
                    let productValue = 0
                    if (this.invForm.include_pph == '1') {
                        let price_up = parseFloat(this.invForm.details[index].starting_price * 0.02 ?? 0) +
                            parseFloat(this
                                .invForm.details[index].starting_price ?? 0)
                        chargeValue = parseFloat(this.invForm.details[index].chargeable_weight ?? 0) * price_up
                        productValue = parseFloat(this.invForm.details[index].unit ?? 0) * price_up
                        this.invForm.details[index].price = price_up
                    } else {
                        // let price_up = parseFloat(this.invForm.details[index].price ?? 0) - parseFloat(this.invForm.details[index].starting_price * 0.02 ?? 0)
                        let price_up = parseFloat(this.invForm.details[index].price ?? 0)

                        chargeValue = parseFloat(this.invForm.details[index].chargeable_weight ?? 0) * parseFloat(
                            this
                            .invForm.details[index].starting_price ?? 0)
                        productValue = parseFloat(this.invForm.details[index].unit ?? 0) * parseFloat(this.invForm
                            .details[index].starting_price ?? 0)
                        this.invForm.details[index].price = price_up
                    }

                    if (this.invForm.invoice_type === '1') {
                        this.invForm.details[index].amount = addsLaut + chargeValue + productValue
                    } else if (this.invForm.invoice_type === '2') {
                        this.invForm.details[index].amount = addsUdara + chargeValue + productValue + pickupDooring
                    }
                    else {
                        this.invForm.details[index].amount = addsUdara + chargeValue + productValue
                    }
                    // this.invForm.details[index].amount = addsLaut + addsUdara + chargeValue + productValue
                },
                onSubmitForm() {
                    this.invForm.sub_total = this.subtotal
                    this.invForm.total = this.total

                    $('button').prop('disabled', true)
                    btnText = $('#btnSubmit').html()
                    $('#btnSubmit').html('<i class="fa fa-spinner fa-spin"></i> Loading')
                    axios.put('{{ route('shipping.shipping-update', '') }}/' + this.data.data.id, {
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
                    // this.invForm.details = []
                    switch (val) {
                        case '1':
                            this.tfootSpan = 10
                            break;
                        case '2':
                            this.tfootSpan = 10
                            break;
                        case '3':
                            this.tfootSpan = 12
                            break;
                        default:
                            this.tfootSpan = 2
                            break;
                    }
                },
                'invForm.include_pph': function(val) {

                    if (val == 1) {
                        if (this.data.data.pph) {
                            this.invForm.pph = this.data.data.pph
                        }

                    } else {
                        this.invForm.pph = 0
                    }
                    for (let i = 0; i < this.invForm.details.length; i++) {
                        this.calculateAmountChange(i)
                    }
                },
                subtotal: function(val) {
                    let tax = this.subtotal * (1.1 / 100)
                    this.invForm.ppn = tax
                    let dp = parseInt(this.invForm.down_payment)
                    if (dp > (this.subtotal + tax)) {
                        this.invForm.down_payment = (this.subtotal + tax)
                    }
                    this.invForm.total = (this.subtotal + tax) - dp
                }
            }

        });
    </script>
@endpush
