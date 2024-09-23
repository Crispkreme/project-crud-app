<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                let perPage = $('#records-per-page').val();
                let searchQuery = '';
        
                fetchEmployee(1, perPage);
        
                function fetchEmployee(page = 1) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('fetch.employee') }}",
                        data: {
                            page: page,
                            per_page: perPage,
                            search: searchQuery
                        },
                        dataType: "json",
                        success: (response) => {
                            let employees = response.employees;
                            let html = '';
        
                            // Generate the employee rows
                            $.each(employees, function (key, item) {
                                let autoIncrementId = key + 1 + (response.per_page * (response.current_page - 1));
                                html += `<tr class="border-b hover:bg-gray-100">
                                            <td class="py-2 px-4 text-sm">${autoIncrementId}</td>
                                            <td class="py-2 px-4 text-sm">${item.name}</td>
                                            <td class="py-2 px-4 text-sm">${item.age}</td>
                                            <td class="py-2 px-4 text-sm">${item.position}</td>
                                            <td class="py-2 px-4 text-sm">${item.address}</td>
                                            <td class="py-2 px-4">
                                                <button type="button" value="${item.id}" class="edit_btn bg-blue-500 text-white rounded px-2 py-1 hover:bg-blue-600">Edit</button>
                                                <button type="button" value="${item.id}" class="delete_btn bg-red-500 text-white rounded px-2 py-1 hover:bg-red-600">Delete</button>
                                            </td>
                                        </tr>`;
                            });
        
                            $('tbody').html(html);
                            setupPagination(response);
                        },
                        error: function(response) {
                            console.log("errors:", response);
                        },
                    });
                }
        
                function setupPagination(response) {
                    let totalPages = Math.ceil(response.total / response.per_page);
                    let currentPage = response.current_page;
                    let paginationHtml = '';
        
                    // Previous button
                    paginationHtml += currentPage > 1 
                        ? `<li class="page-item" style="list-style: none;"><a href="#" class="page-link prev" data-page="${currentPage - 1}">&laquo; Previous</a></li>` 
                        : `<li class="page-item disabled" style="list-style: none;"><span class="page-link">&laquo; Previous</span></li>`;
        
                    // Page numbers
                    for (let i = 1; i <= totalPages; i++) {
                        paginationHtml += i === currentPage 
                            ? `<li class="page-item active" style="list-style: none;"><span class="page-link">${i}</span></li>` 
                            : `<li class="page-item" style="list-style: none;"><a href="#" class="page-link" data-page="${i}">${i}</a></li>`;
                    }
        
                    // Next button
                    paginationHtml += currentPage < totalPages 
                        ? `<li class="page-item" style="list-style: none;"><a href="#" class="page-link next" data-page="${currentPage + 1}">Next &raquo;</a></li>` 
                        : `<li class="page-item disabled" style="list-style: none;"><span class="page-link">Next &raquo;</span></li>`;
        
                    $('#pagination-links').html(paginationHtml);
                }
        
                $(document).on('click', '#pagination-links a.page-link', function(e) {
                    e.preventDefault();
                    let page = $(this).data('page');
                    fetchEmployee(page);
                });
        
                $(document).on('change', '#records-per-page', function() {
                    perPage = $(this).val();
                    fetchEmployee(1);
                });
        
                $(document).on('input', '#search-input', function() {
                    searchQuery = $(this).val();
                    fetchEmployee(1);
                });
        
                $("#EmployeeForm").submit(function(e) {
                    e.preventDefault();
        
                    $("#employeeModalLabel").html("Add Employee");
                    let formData = new FormData(this);
        
                    $.ajax({
                        type: "POST",
                        url: "{{ route('store.employee') }}",
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
                            $('#errorlist').html('').addClass('p-2 alert-danger');
                            $.each(response.responseJSON.errors, function(key, err_values) {
                                $('#errorlist').append(`<li class="mx-4">${err_values}</li>`);
                            });
                        }
                    });
                });
        
                $(document).on('click', '.edit_btn', function() {
                    let csrfToken = $('meta[name="csrf-token"]').attr('content');
                    let employeeId = $(this).val();
                    let editUrl = "{{ route('edit.employee', ':id') }}".replace(':id', employeeId);
        
                    $.ajax({
                        type: "POST",
                        url: editUrl,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: { 'X-CSRF-TOKEN': csrfToken },
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
                    let deleteUrl = "{{ route('delete.employee', ':id') }}".replace(':id', employeeId);
        
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
                                headers: { 'X-CSRF-TOKEN': csrfToken },
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
        
                function formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString();
                }
        
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
