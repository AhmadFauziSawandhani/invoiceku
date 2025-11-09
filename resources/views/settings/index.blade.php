@extends('layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1>Pengaturan Markup Harga</h1>
        </div>
    </section>

    <section class="content">
        <div class="card mt-3">
            <div class="card-body">
                {{-- Pesan sukses / error --}}
                <div id="alert-success" class="alert alert-success d-none"></div>
                <div id="alert-error" class="alert alert-danger d-none"></div>

                <form id="settingForm">
                    @csrf
                    @foreach ($settings as $setting)
                        <div class="form-group d-flex align-items-center mb-2" style="gap: 10px;">
                            {{-- Label --}}
                            <label class="mb-0 font-weight-bold" style="width: 180px;">
                                Mark Up Harga Product
                            </label>

                            {{-- Input + Unit --}}
                            <div class="input-group" style="width: 160px;">
                                <input type="number" 
                                       name="value" 
                                       class="form-control" 
                                       value="{{ $setting->value }}" 
                                       step="0.01">
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ $setting->unit }}</span>
                                </div>
                            </div>

                            {{-- Tombol Simpan --}}
                            <button type="button" 
                                    class="btn btn-primary btn-sm btn-update" 
                                    data-id="{{ $setting->id }}">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('.btn-update').click(function() {
        const id = $(this).data('id');
        const group = $(this).closest('.form-group');
        const value = group.find('input[name="value"]').val();

        $.ajax({
            url: `/settings/${id}`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                name: "{{ $settings->first()->name ?? 'markup_harga' }}",
                value: value,
                unit: '{{ $settings->first()->unit ?? '%' }}'
            },
            success: function(res) {
                $('#alert-success')
                    .removeClass('d-none')
                    .text(res.msg)
                    .fadeIn()
                    .delay(2000)
                    .fadeOut();
            },
            error: function() {
                $('#alert-error')
                    .removeClass('d-none')
                    .text('Gagal memperbarui data.')
                    .fadeIn()
                    .delay(2000)
                    .fadeOut();
            }
        });
    });
});
</script>
@endpush
