@extends('layout.public')

@section('title', 'Tracking Nomor Resi')

@push('css')
    <style>
        .starrating>input {
            display: none;
        }

        .starrating>label:before {
            content: "\f005";
            /* Star */
            margin: 2px;
            font-size: 2em;
            font-family: FontAwesome;
            display: inline-block;
        }

        .starrating>label {
            color: #222222;
        }

        .starrating>input:checked~label {
            color: #ffca08;
        }

        .starrating>input:hover~label {
            color: #ffca08;
        }

        .is-valid {
            border-color: #28a745;
        }

        .is-invalid {
            border-color: #dc3545;
        }
    </style>
@endpush

@section('content')


    <div class="d-flex justify-content-center p-10">
        <div class="text-center w-100">
            <img src="{{ asset('AdminLogo.png') }}" class="img-fluid" style="max-width: 230px;" alt="">
            <h1 style="color: #dc0b0c;font-weight: 800;"><span style="color: #104e97;">Form </span>Keluhan</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            <form action="{{ route('complain-post') }}" method="POST">
                @csrf
                <div class="card p-5 mx-auto" style="border-radius: 35px;width: 800px;text-align: left;">
                    <div class="card-body">
                        <h2>Personal Information</h2>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="full_name">Nama Lengkap*</label>
                                    <input class="form-control" type="text" required name="full_name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="email">Email*</label>
                                <input class="form-control" type="email" required name="email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="phone">Contact number / Whatsapp*</label>
                                <input class="form-control" type="tel" required name="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" >
                            </div>
                        </div>
                        <h2>Order & Informasi Keluhan</h2>
                        <div class="row">
                            <div class="form-group">
                                <label for="orderid">Jenis Keluhan*</label>
                                <select required name="complaint_type" class="form-control" id="complaint_type">
                                    <option value="" selected>Pilih Jenis Keluhan</option>
                                    @foreach ($complaintType as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="orderid">No Resi*</label>
                                <div class="input-group mb-3">
                                    <input  id="receipt_number" class="form-control" type="text" required
                                    name="receipt_number"
                                        aria-label="Receipt number" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" id="btn-search" style="height: 38px" type="button">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label for="review">Rating*</label>
                            <div class="starrating risingstar d-flex justify-content-end flex-row-reverse">
                                <input type="radio" id="star5" name="rating" value="5" /><label for="star5"
                                    title="5 star"></label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4"
                                    title="4 star"></label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3"
                                    title="3 star"></label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2"
                                    title="2 star"></label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1"
                                    title="1 star"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="date">Keluhan / Kendala*</label>
                                <textarea class="form-control" required rows="5" name="complaint_description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-grid">
                        <button type="submit" class="btn btn-primary" disabled="true" id="btn-submit" name="submit"
                            value="Submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $("#btn-search").click(function() {
                searchResi();
            });


            function searchResi() {
                var receipt_number = $('#receipt_number').val();
                $.ajax({
                    url: "{{ route('search-resi') }}",
                    type: "POST",
                    data: {
                        receipt_number: receipt_number
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 200) {
                            $('#receipt_number').addClass('is-valid');
                            $('#receipt_number').removeClass('is-invalid');
                            $("#btn-submit").prop('disabled', false);
                        } else {
                            // set invalid input receiptnumber
                            $("#btn-submit").prop('disabled', true);
                            $('#receipt_number').addClass('is-invalid');
                            $('#receipt_number').removeClass('is-valid');
                            // set invalid input receiptnumber
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        var err = JSON.parse(xhr.responseText);
                        showError(err.messages);
                    }
                });
            }

        });
    </script>
@endpush
