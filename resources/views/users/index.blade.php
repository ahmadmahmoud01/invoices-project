@extends('layouts.master')
@section('css')
@section('title', 'إدارة المستخدمين')

<!-- DataTable CSS -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<!-- Notify CSS -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة المستخدمين</span>
        </div>
    </div>
</div>
@endsection

@section('content')
<!-- Notification -->
@if (session('success'))
    <script>
        notif({
            msg: "<b>{{ session('success') }}</b>",
            type: "success"
        });
    </script>
@endif

<!-- User Management Table -->
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card shadow-sm">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <div>
                        <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}">إضافة مستخدم</a>
                    </div>
                    <div class="col-md-3">
                        <select id="filterStatus" class="form-control nice-select" onchange="filterTable()">
                            <option value="">عرض الكل</option>
                            <option value="active">فعال</option>
                            <option value="inactive">غير فعال</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-center" id="userTable" data-page-length="10">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>اسم المستخدم</th>
                                <th>البريد الإلكتروني</th>
                                <th>حالة المستخدم</th>
                                <th>نوع المستخدم</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $user)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="label d-flex">
                                            <div class="dot-label ml-1 {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}"></div>
                                            {{ $user->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @foreach ($user->getRoleNames() as $v)
                                            <label class="badge badge-success">{{ $v }}</label>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-info" title="تعديل">
                                            <i class="las la-pen"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-user_id="{{ $user->id }}" data-username="{{ $user->name }}" title="حذف">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-danger">
                <h6 class="modal-title">حذف المستخدم <i class="las la-exclamation-circle"></i></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('users.destroy', 'test') }}" method="post">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <p>هل أنت متأكد من عملية الحذف؟</p>
                    <input type="hidden" name="user_id" id="user_id">
                    <input class="form-control" name="username" id="username" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- DataTable and Notify JS -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "pageLength": 10,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Arabic.json"
            }
        });
    });

    function filterTable() {
        var filter = $('#filterStatus').val();
        var rows = $('#userTable tbody tr');
        rows.show();
        if (filter) {
            rows.filter(function() {
                return $(this).find('td:nth-child(4)').text().trim() !== filter;
            }).hide();
        }
    }

    $('#deleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user_id');
        var username = button.data('username');
        var modal = $(this);
        modal.find('.modal-body #user_id').val(userId);
        modal.find('.modal-body #username').val(username);
    });
</script>
@endsection
    