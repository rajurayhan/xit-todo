<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
        <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css"  href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">	
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script type="text/javascript">
        var table;
        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            table = $('.todo-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('todos.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'title', name: 'title'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: true, 
                        searchable: true
                    },
                ]
            });
            
        });

        $('#createBtn').click(function(){
            //console.log('Create');
            var title = $('#name-create').val();
            if(!title){
                showError('Title Field Is Required');
                return false;
            }

            $.ajax({
                data: $('#createForm').serialize(),
                url: "{{ route('todos.store') }}",
                type: "POST",
                dataType: 'json',
                
                success: function (response) {
                    //table.draw();
                    console.log(response);
                    $('#name-create').val('');
                    $('#addModal').modal('toggle');
                    showSuccess(response.message);
                    table.draw();
                },
                error: function (err) {
                    if(err.status){ // Validation Error
                        var response = JSON.parse(err.responseText);

                        $.each(response.errors, function( index, error ) {
                            showError(error.message, 'Error');
                        });
                        
                    }
                    
                    $('#addModal').modal('toggle');
                }
            });

        });

        function editTask(id){
            var url = "{{ route('todos.index') }}";
            url = url + '/' + id;
            getTask(id);
            $('#editModal').modal('toggle');
        }

        function updateTask(){
            var title = $('#name-update').val();
            var id = $('#task_id').val();
            if(!title){
                showError('Title Field Is Required');
                return false;
            }

            $.ajax({
                data: $('#updateForm').serialize(),
                url: "{{ route('todos.index') }}" + '/' + id,
                type: "PUT",
                dataType: 'json',
                
                success: function (response) {
                    //table.draw();
                    console.log(response);
                    $('#name-update').val('');
                    $('#task_id').val('');
                    $('#editModal').modal('toggle');
                    showSuccess(response.message);
                    table.draw();
                },
                error: function (err) {
                    if(err.status){ // Validation Error
                        var response = JSON.parse(err.responseText);

                        $.each(response.errors, function( index, error ) {
                            showError(error.message, 'Error');
                        });
                        
                    }
                    
                    $('#addModal').modal('toggle');
                }
            });
        }
        function deleteTask(id){
            var url = "{{ route('todos.index') }}";
            url = url + '/' + id;

            if(confirm("Are you sure to delete?")){
                $.ajax({
                    url: url,
                    type: "DELETE",
                    dataType: 'json',
                    
                    success: function (response) {
                        console.log(response);
                        showSuccess(response.message);
                        table.draw();
                    },
                    error: function (err) {
                        showError('Something went wrong', 'Error');
                    }
                });
            }
        }

        function getTask(id){
            var data;
            var url = "{{ route('todos.index') }}";
            url = url + '/' + id;
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                
                success: function (response) {
                    data = response.data
                    $('#name-update').val(data.title);
                    $('#task_id').val(data.id);
                },
                error: function (err) {
                    $('#name-update').val('');
                    $('#task_id').val('');
                }
            });
        }

        function showError($message){
            toastr.options = {
                "closeButton": true,
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
                "hideMethod": "fadeOut"
                }
            toastr.error($message, 'Error');
        }
        function showSuccess($message){
            toastr.options = {
                "closeButton": true,
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
                "hideMethod": "fadeOut"
                }
            toastr.success($message, 'Success');
        }
    </script>
</html>
