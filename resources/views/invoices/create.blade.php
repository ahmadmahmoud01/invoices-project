@extends('layouts.master')
@section('css')
    <!-- Select2 CSS -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- Fileupload CSS -->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!-- Fancy uploader CSS -->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!-- Additional CSS -->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection

@section('title', 'برنامج الفواتير - إضافة فاتورة')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إضافة فاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success" id="success-alert">
    {{ session('success') }}
</div>
@endif
{{--  vakidation error  --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        <div class="row">
                            <div class="col">
                                <label for="invoice-number">رقم الفاتورة</label>
                                <input type="text" class="form-control" id="invoice-number" name="number" required>
                            </div>
                            <div class="col">
                                <label for="invoice-date">تاريخ الفاتورة</label>
                                <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col">
                                <label for="due-date">تاريخ الاستحقاق</label>
                                <input type="date" class="form-control" name="due_date" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="section">القسم</label>
                                <select name="section_id" class="form-control select2" id="section">
                                    <option value="" selected disabled>حدد القسم</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="product">المنتج</label>
                                <select id="product" name="product" class="form-control"></select>
                            </div>
                            <div class="col">
                                <label for="collection_amount">مبلغ التحصيل</label>
                                <input type="number" class="form-control" name="collection_amount" id="collection_amount">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="commission_amount">مبلغ العمولة</label>
                                <input type="number" class="form-control" id="commission_amount" name="commission_amount" required>
                            </div>
                            <div class="col">
                                <label for="discount">الخصم</label>
                                <input type="number" class="form-control" id="discount" name="discount" value="0" required>
                            </div>
                            <div class="col">
                                <label for="vat_rate">نسبة ضريبة القيمة المضافة</label>
                                <select name="vat_rate" id="vat_rate" class="form-control">
                                    <option value="" selected disabled>حدد نسبة الضريبة</option>
                                    <option value="5">5%</option>
                                    <option value="10">10%</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="vat_value">قيمة ضريبة القيمة المضافة</label>
                                <input type="text" class="form-control" id="vat_value" name="vat_value" readonly>
                            </div>
                            <div class="col">
                                <label for="total">الاجمالي شامل الضريبة</label>
                                <input type="text" class="form-control" id="total" name="total" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="note">ملاحظات</label>
                                <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 mt-3">
                            <input type="file" name="pic" class="dropify" accept=".pdf,.jpg,.png" />
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- External JavaScript Files -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#section').on('change', function() {
                var sectionId = $(this).val();
                if (sectionId) {
                    $.ajax({
                        url: "/section/" + sectionId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#product').empty().append('<option disabled selected>حدد المنتج</option>');
                            $.each(data, function(key, value) {
                                $('#product').append('<option value="' + value + '">' + value + '</option>');
                            });
                        },
                        error: function() {
                            alert('Error loading products. Please try again.');
                        }
                    });
                } else {
                    $('#product').empty().append('<option disabled selected>حدد المنتج</option>');
                }
            });
        });



        // Calculate VAT and Total
        $('#vat_rate').change(function() {
            calculateVat();
        });

        function calculateVat() {
            let amountCommission = parseFloat($('#commission_amount').val()) || 0;
            let discount = parseFloat($('#discount').val()) || 0;
            let vatRate = parseFloat($('#vat_rate').val()) || 0;
            let amountAfterDiscount = amountCommission - discount;

            if (amountAfterDiscount > 0 && vatRate > 0) {
                let vatValue = (amountAfterDiscount * vatRate / 100).toFixed(2);
                let total = (parseFloat(amountAfterDiscount) + parseFloat(vatValue)).toFixed(2);
                $('#vat_value').val(vatValue);
                $('#total').val(total);
            }
        }
    </script>
@endsection
