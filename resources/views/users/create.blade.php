@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@section('title', 'إضافة مستخدم')
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة
                مستخدم</span>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>خطا</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">إضافة مستخدم جديد</h5>
                    <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}">رجوع</a>
                </div>

                <form class="parsley-style-1" id="selectForm2" autocomplete="off" action="{{ route('users.store') }}"
                    method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>اسم المستخدم: <span class="tx-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm"
                                placeholder="أدخل اسم المستخدم" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>البريد الالكتروني: <span class="tx-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-sm"
                                placeholder="example@domain.com" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>كلمة المرور: <span class="tx-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-sm"
                                placeholder="أدخل كلمة المرور" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>تأكيد كلمة المرور: <span class="tx-danger">*</span></label>
                            <input type="password" name="confirm-password" class="form-control form-control-sm"
                                placeholder="أعد إدخال كلمة المرور" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حالة المستخدم</label>
                            <select name="status" class="form-control nice-select custom-select">
                                <option value="active">active</option>
                                <option value="inactive">inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roles" class="form-label">صلاحية المستخدم <span class="tx-danger">*</span></label>
                            {!! Form::select('roles[]', $roles, null, [
                                'class' => 'form-control nice-select custom-select',
                                'multiple' => 'multiple',
                                'required' => 'required',
                                'id' => 'roles',
                                'placeholder' => 'اختر صلاحيات المستخدم'
                            ]) !!}
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success pd-x-20">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Internal Nice-select js-->
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js') }}"></script>
<!--Internal  Parsley.min js -->
<script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<!-- Internal Form-validation js -->
<script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        $('select').niceSelect();
    });
</script>
@endsection
