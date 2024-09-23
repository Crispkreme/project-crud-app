<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if(auth()->check() && auth()->user()->role === 'employee')

        @php
            $employee = \App\Models\Employee::where('user_id', auth()->user()->id)->first();
        @endphp

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Welcome, {{ $employee->name ?? 'Employee' }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="container mx-auto mt-5">
                            <div class="flex justify-between mb-4">
                                <div>
                                    <h2 class="text-2xl font-bold">Laravel 10 Ajax Datatables CRUD (Create Read Update and Delete)</h2>
                                </div>
                                <div>
                                    <button type="button" id="employeeModalBtn" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Create Employee
                                    </button>
                                </div>
                            </div>
                        
                            <div class="card-body">
                                <table class="min-w-full border border-gray-300">
                                    <div class="flex mb-3">
                                        <div class="w-1/2 pr-2">
                                            <input type="text" id="search-input" placeholder="Search employees..." class="border border-gray-300 rounded px-3 py-2 w-full" />
                                        </div>
                                        <div class="w-1/2 flex items-center">
                                            <label for="records-per-page" class="mr-2">Show</label>
                                            <select id="records-per-page" class="border border-gray-300 rounded w-auto py-2 px-2">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                                <option value="40">40</option>
                                                <option value="50">50</option>
                                            </select>
                                            <span class="ml-2">entries</span>
                                        </div>
                                    </div>
                        
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="w-12 py-2 px-4 text-left text-sm font-medium text-gray-500">#</th>
                                            <th class="w-1/4 py-2 px-4 text-left text-sm font-medium text-gray-500">Name</th>
                                            <th class="w-1/12 py-2 px-4 text-left text-sm font-medium text-gray-500">Age</th>
                                            <th class="w-1/4 py-2 px-4 text-left text-sm font-medium text-gray-500">Position</th>
                                            <th class="w-1/4 py-2 px-4 text-left text-sm font-medium text-gray-500">Address</th>
                                            <th class="w-1/6 py-2 px-4 text-left text-sm font-medium text-gray-500">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                        
                                <div id="pagination-links" class="flex justify-center mt-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-white rounded-lg shadow-md">
    
                <form name="EmployeeForm" action="javascript:void(0)" id="EmployeeForm" method="POST" enctype="multipart/form-data" class="p-6">
    
                    @csrf
    
                    <div class="modal-header flex justify-between items-center border-b mb-4">
                        <h5 class="modal-title text-lg font-semibold" id="employeeModalLabel">Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
    
                    <ul id="errorlist" class="m-2 text-red-600"></ul>
    
                    <div class="modal-body space-y-4">
                        <input type="hidden" name="id" id="employee_id">
    
                        <div class="form-group">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="name" name="name" placeholder="Employee Name">
                        </div>
    
                        <div class="form-group">
                            <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                            <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="age" name="age" placeholder="Employee Age">
                        </div>
    
                        <div class="form-group">
                            <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                            <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="position" name="position" placeholder="Employee Position">
                        </div>
    
                        <div class="form-group">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" cols="30" class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="Employee Address" rows="3"></textarea>
                        </div>
                    </div>
    
                    <div class="modal-footer flex justify-between">
                        <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Save changes</button>
                    </div>
    
                </form>
            </div>
        </div>
    </div>    

</x-app-layout>
