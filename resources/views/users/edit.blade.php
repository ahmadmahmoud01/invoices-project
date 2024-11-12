@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/parsleyjs/parsley.css') }}" rel="stylesheet" />
@section('title', 'تعديل مستخدم')
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل مستخدم</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<!-- row -->
<div class="row">
    <div class="col-lg-12 col-md-12">

        @if ($errors->any())
            <div class="alert alert-danger">
                <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>خطأ</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a class="btn btn-main-primary mb-3" href="{{ route('users.index') }}">رجوع</a>
                    <h4 class="mb-4">تعديل بيانات المستخدم</h4>
                </div>

                {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id], 'data-parsley-validate']) !!}
                <div class="form-group">
                    <label>اسم المستخدم: <span class="tx-danger">*</span></label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'required', 'data-parsley-required-message' => 'يرجى إدخال اسم المستخدم']) !!}
                </div>

                <div class="form-group">
                    <label>البريد الالكتروني: <span class="tx-danger">*</span></label>
                    {!! Form::email('email', null, ['class' => 'form-control', 'required', 'data-parsley-required-message' => 'يرجى إدخال البريد الإلكتروني']) !!}
                </div>

                <div class="form-group">
                    <label>كلمة المرور:</label>
                    {!! Form::password('password', ['class' => 'form-control', 'data-parsley-minlength' => '6', 'data-parsley-minlength-message' => 'يجب أن تكون كلمة المرور 6 أحرف على الأقل', 'data-parsley-equalto' => '#confirm-password', 'data-parsley-equalto-message' => 'كلمتا المرور غير متطابقتين']) !!}
                </div>

                <div class="form-group">
                    <label>تأكيد كلمة المرور:</label>
                    {!! Form::password('confirm-password', ['class' => 'form-control', 'id' => 'confirm-password']) !!}
                </div>

                <div class="form-group">
                    <label>حالة المستخدم:</label>
                    <select name="status" class="form-control nice-select custom-select">
                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>مفعل</option>
                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>غير مفعل</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>نوع المستخدم:</label>
                    {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-control nice-select', 'multiple', 'data-parsley-required' => 'true', 'data-parsley-required-message' => 'يرجى تحديد نوع المستخدم']) !!}
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-main-primary">تحديث</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Internal Nice-select and Parsley js-->
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.nice-select').niceSelect();
        $('form').parsley();
    });
</script>
@endsection
