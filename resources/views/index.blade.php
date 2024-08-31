<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Laravel 10 Ajax Datatables CRUD (Create Read Update and Delete)</h2>
                </div>
                <div class="pull-right mb-2">
                    <button type="button" id="employeeModalBtn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Create Employee
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Position</th>
                        <th>Address</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <div id="pagination-links"></div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form name="EmployeeForm" action="javascript:void(0)" id="EmployeeForm" method="POST" enctype="multipart/form-data" class="form-horizontal">

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="employeeModalLabel">Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <ul id="errorlist" class="m-2"></ul>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="employee_id">

                        <div class="form-group mb-2">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Employee Name">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label for="age" class="col-sm-2 control-label">Age</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="age" name="age" placeholder="Employee Age">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label for="position" class="col-sm-2 control-label">Position</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="position" name="position" placeholder="Employee Position">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label for="address" class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-12">
                                <textarea name="address" id="address" id="address" cols="30" class="form-control" placeholder="Employee Address" rows="10"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save" class="btn btn-primary">Save changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function() {

            fetchEmployee();

            function fetchEmployee(page = 1) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('fetch-employee') }}",
                    data: { page: page },
                    dataType: "json",
                    success: (response) => {
                        let employees = response.employees;
                        let html = '';

                        $.each(employees, function (key, item) {
                            let autoIncrementId = key + 1 + (response.per_page * (response.current_page - 1));
                            html += '<tr>\
                                        <td>' + autoIncrementId + '</td>\
                                        <td>' + item.name + '</td>\
                                        <td>' + item.age + '</td>\
                                        <td>' + item.position + '</td>\
                                        <td>' + item.address + '</td>\
                                        <td>' + formatDate(item.created_at) + '</td>\
                                        <td>\
                                            <button type="button" value="' + item.id + '" class="edit_btn btn btn-primary btn-sm">Edit</button>\
                                            <button type="button" value="' + item.id + '" class="delete_btn btn btn-danger btn-sm">Delete</button>\
                                        </td>\
                                    </tr>';
                        });

                        $('tbody').html(html);

                        let totalPages = Math.ceil(response.total / response.per_page);
                        let paginationHtml = '';
                        for (let i = 1; i <= totalPages; i++) {
                            paginationHtml += `<a href="#" class="page-link" data-page="${i}">${i}</a> `;
                        }

                        $('#pagination-links').html(paginationHtml);
                    },
                    error: function(response) {
                        console.log("errors:", response);
                    },
                });
            }

            $("#EmployeeForm").submit(function(e) {
                e.preventDefault();

                $("#employeeModalLabel").html("Add Employee");
                var formData = new FormData(this);
                console.log(formData);

                $.ajax({
                    type: "POST",
                    url: "{{ route('store') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (response) => {

                        $('#exampleModal').modal('hide');
                        $("#EmployeeForm").trigger("reset");
                        $("#btn-save").attr("disabled", false);

                        toastr["success"]("Successfully Created!", "Employee");
                        fetchEmployee();
                    },
                    error: function(response) {

                        $('#errorlist').html('');
                        $('#errorlist').addClass('p-2 alert-danger');
                        $.each(response.responseJSON.errors, function(key, err_values) {
                            $('#errorlist').append('<li class="mx-4">'+err_values+'</li>');
                        });
                    }
                });

            });

            $(document).on('click', '.edit_btn', function() {

                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                let employeeId = $(this).val();
                let editUrl = "{{ route('edit', ':id') }}";
                editUrl = editUrl.replace(':id', employeeId);

                $.ajax({
                    type: "POST",
                    url: editUrl,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: (response) => {

                        $('#exampleModal').modal('show');
                        $("#employeeModalLabel").html("Edit Employee");
                        $("#btn-save").html("Update");

                        $('#employee_id').val(response.employee.id);
                        $('#name').val(response.employee.name);
                        $('#address').val(response.employee.address);
                        $('#age').val(response.employee.age);
                        $('#position').val(response.employee.position);
                    },
                    error: function(response) {

                        console.log(response);
                    }
                });
            });

            $(document).on('click', '.delete_btn', function() {

                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                let employeeId = $(this).val();
                let deleteUrl = "{{ route('delete', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', employeeId);

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            type: "DELETE",
                            url: deleteUrl,
                            dataType: 'json',
                            cache: false,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: (response) => {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.message,
                                    icon: "success"
                                });

                                fetchEmployee();
                            },
                            error: function(response) {

                                console.log(response);
                            }
                        });
                    }
                });
            });

            $('#employeeModalBtn').on('click', function() {
                $("#employeeModalLabel").html("Add New Employee");
            });

            $(document).on('click', '.page-link', function(event) {
                event.preventDefault();

                let page = $(this).data('page');
                fetchEmployee(page);
            });

            function formatDate(dateString) {
                var date = new Date(dateString);
                return date.toLocaleDateString();
            };

            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
            };
        });

    </script>
</body>

</html>
