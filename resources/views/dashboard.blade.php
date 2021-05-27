<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Todo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addModal">
                        Add
                    </button>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table table-bordered todo-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Task</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form class="form-horizontal" method="" id="createForm" action="">
                    <div class="form-group">
                        <label class="control-label" >Title:</label>
                            {{-- @csrf --}}
                            <input type="text" class="form-control"  placeholder="Enter task title" id="name-create" name="title" required>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="createBtn" >Submit</button>
            </div>

            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Task</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form class="form-horizontal" method="" id="updateForm" action="">
                    <div class="form-group">
                        <label class="control-label" >Title:</label>
                            {{-- @csrf --}}
                            <input type="text" class="form-control"  placeholder="Enter task title" id="name-update" name="title" required>
                            <input type="hidden" name="task_id" id="task_id">
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="updateTask()" >Submit</button>
            </div>

            </div>
        </div>
    </div>
</x-app-layout>
