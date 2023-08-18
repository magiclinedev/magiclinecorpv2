@section('title')
    Collection
@endsection

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product') }}
            </h2>
            {{-- BUTTONS --}}
            <div class="flex items-center space-x-2">
                @can('add-product', Auth::user())
                    {{-- buttons for add --}}
                    <a href="{{ route('collection.add') }}" class="text-gray-800 hover:text-gray-600">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </a>
                    <a href="{{ route('collection.category') }}" class="text-gray-800 hover:text-gray-600">
                        <i class="fas fa-folder-plus"></i> Add Category
                    </a>
                    <a href="{{ route('collection.type') }}" class="text-gray-800 hover:text-gray-600">
                        <i class="fas fa-tags"></i> Add Type
                    </a>
                @endcan
                @can('edit-delete', Auth::user())
                    {{-- trashcan button --}}
                    @if ($mannequins->contains('activeStatus', 0))
                        <div class="ml-2">
                            <a href="{{ route('collection.trashcan') }}" class="text-gray-800 hover:text-gray-600">
                                <i class="fas fa-trash-alt"></i> Trash
                                <span class="badge">{{ $mannequins->where('activeStatus', 0)->count() }}</span>
                            </a>
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </x-slot>
    {{-- START content --}}
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-8">
            {{-- START main --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-2">
                        <h1 class="text-2xl font-bold"><i class="fas fa-list-alt"></i> Product List</h1>
                    </div>
                    {{-- Add this line to include the CSRF token for DataTables AJAX requests --}}
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <div class="overflow-x-auto">
                        {{-- FILTER --}}
                        <div class="flex space-x-4 mt-4">
                            {{-- category --}}
                            <div class="filter-dropdown">
                                <select id="categoryFilter" class="block w-52 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Filter by Category">
                                    <option value="">Categories </option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}"> {{ $category->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- company --}}
                            <div class="filter-dropdown">
                                <select id="companyFilter" class="block w-52 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Filter by Company">
                                    <option value="">Companies </option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->name }}"> {{ $company->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- TABLE --}}
                        <table id="mannequinsTable" class="w-full table-auto border-collapse border">
                            <thead class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-2 border">
                                        <input type="checkbox" id="selectAllCheckbox">
                                    </th>
                                    <th class="px-4 py-2 border">Image</th>
                                    <th class="px-4 py-2 border">Item Reference</th>
                                    <th class="px-4 py-2 border">Company</th>
                                    <th class="px-4 py-2 border">Category</th>
                                    <th class="px-4 py-2 border">Type</th>
                                    <th class="px-4 py-2 border">Action By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mannequins as $mannequin)
                                    @if ($mannequin->activeStatus != 0)
                                        <tr class="border">
                                            <td class="px-4 py-2 border">
                                                <!-- Add the checkbox input here -->
                                                <input type="checkbox" class=" row-checkbox center pb-4">
                                            </td>
                                            <td class="px-4 py-2 border">
                                                @php
                                                    // Split the image paths string into an array
                                                    $imagePaths = explode(',', $mannequin->images);
                                                    // Get the first image path from the array
                                                    $firstImagePath = $imagePaths[0] ?? null;
                                                @endphp
                                                @if ($firstImagePath)
                                                    <img src="{{ asset('storage/' . $firstImagePath) }}" alt="Mannequin Photo" width="100">
                                                @else
                                                    No Image
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border itemref-cell">
                                                <span class="itemref-text">{{ $mannequin->itemref }}</span>
                                                {{-- HOVER to show read, update, and delete --}}
                                                <div class="action-buttons">

                                                    <a href="{{ route('collection.view_prod', ['encryptedId' => Crypt::encrypt($mannequin->id)]) }}" class="btn-view">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    {{-- Admin --}}
                                                    @can('edit-product', $mannequin)
                                                    <a href="{{ route('collection.edit', ['id' => $mannequin->id]) }}" class="btn-view">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    @endcan
                                                    @can('edit-delete', $mannequin)
                                                    <button class="btn-delete" data-id="{{ $mannequin->id }}" data-transfer-url="{{ route('collection.trash', ['id' => $mannequin->id]) }}">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                    @endcan
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 border">{{ $mannequin->company }}</td>
                                            <td class="px-4 py-2 border">{{ $mannequin->category }}</td>
                                            <td class="px-4 py-2 border">{{ $mannequin->type }}</td>
                                            <td class="px-4 py-2 border">{{ $mannequin->addedBy }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        {{-- END TABLE --}}
                    </div>
                </div>
            </div>
            {{-- END main --}}
        </div>
    </div>
    {{-- END content --}}

    {{--START scripts --}}
    {{-- datables --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#mannequinsTable').DataTable({
                lengthChange: false,
            });
            // Handle "select all" checkbox
            $('#selectAllCheckbox').on('change', function() {
                var isChecked = this.checked;
                $('td input.row-checkbox').each(function() {
                    this.checked = isChecked;
                });
            });
            // Handle category filter change
            $('#categoryFilter').on('change', function() {
                var category = $(this).val();
                table.column(4) // Category column index (0-based)
                    .search(category)
                    .draw();
            });
            // Handle company filter change
            $('#companyFilter').on('change', function() {
                var company = $(this).val();
                table.column(3) // company column index (0-based)
                    .search(company)
                    .draw();
            });
        });
    </script>

    {{-- sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        @if(session('success_message'))
            Swal.fire({
                title: 'Done!',
                text: '{{ session('success_message') }}',
                icon: 'success',
                timer: 3000,
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Close'
            });
        @elseif(session('danger_message'))
            Swal.fire({
                title: 'Done!',
                text: '{{session('danger_message') }}',
                icon: 'error',
                timer: 3000,
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            });
        @endif

        @if(session('cancel_message'))
            Swal.fire({
                title: 'Action Cancelled!',
                text: '',
                icon: 'error',
                timer: 3000,
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            });
        @endif
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const recordId = this.getAttribute('data-id');
                    const transferUrl = this.getAttribute('data-transfer-url');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You won\'t be able to revert this!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Perform AJAX request to delete the record
                            axios.post(transferUrl)
                                 .then(response => {
                                     if (response.data.success) {
                                         Swal.fire(
                                             'Deleted!',
                                             'Your record has been deleted.',
                                             'success'
                                         ).then(() => {
                                             // Refresh the page after successful deletion
                                             window.location.reload();
                                         });
                                     }
                                 })
                            .catch(error => {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting the record.',
                                    'error'
                                );
                            });
                        }
                    });
                });
            });
        });
    </script>
    {{--END scripts--}}
</x-app-layout>

