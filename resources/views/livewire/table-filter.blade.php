<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('collection.add') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Add Product
        </a>
        <a href="{{ route('collection.category') }}" class="inline-flex items-center ml-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Add Category
        </a><a href="{{ route('collection.type') }}" class="inline-flex items-center ml-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Add Type
        </a>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
            <div class="p-6 text-gray-900">
                <div class="flex items-center mb-2">
                    <h1 class="text-2xl font-bold">Products</h1>
                </div>
                <select wire:model="categoryFilter" class="block appearance-none w-full border rounded-md py-2 px-3">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                {{-- Add this line to include the CSRF token for DataTables AJAX requests --}}
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <div class="overflow-x-auto">
                    <table id="mannequinsTable" class="w-full table-auto border-collapse border">
                        <thead class>
                            <tr>
                                <th class="px-4 py-2 border">
                                    <input type="checkbox" id="selectAllCheckbox">
                                </th>  
                                <th class="px-4 py-2 border">Image</th>
                                <th class="px-4 py-2 border">Item Reference</th>
                                <th class="px-4 py-2 border">Company</th>
                                <th class="px-4 py-2 border">Category</th>
                                <th class="px-4 py-2 border">Type</th>
                                <th class="px-4 py-2 border">Added By</th>
                                {{-- <th class="px-4 py-2">Modified By</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            {{-- DataTable content will be loaded dynamically --}}
                        </tbody>
                    </table>
                    {{ $mannequins->links() }}
                </div>               
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css">
{{-- datatables --}}
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<!-- DataTables Checkbox Extension -->
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#mannequinsTable').DataTable({
            lengthChange: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('collection') }}",
                data: function (d) {
                    d.categoryFilter = categoryFilterValue;
                },
            },
            columns: [
                // Define your table columns here
                // For example:
                { data: 'image', name: 'image', render: function(data, type, full, meta) {
                        return '<img src="{{ asset("storage") }}/' + data + '" alt="Mannequin Photo" width="100">';
                    }
                },
                { data: 'itemref', name: 'itemref' },
                { data: 'company', name: 'company' },
                { data: 'category', name: 'category' },
                { data: 'type', name: 'type' },
                { data: 'addedBy', name: 'addedBy' },
            ],
            // Add these options for paging
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10,
        });

        // Handle input in the categoryFilter field
        $('#categoryFilter').on('input', function () {
            Livewire.emit('categoryFilterUpdated', $(this).val());
        });
    });
</script>

{{-- sweet alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $.ajax({
        type: "GET",
        url: "{{ config('app.root_domain') . config('app.user_details_slug') . \Crypt::encryptString(Auth::user()->id) }}",
        dataType: 'json',
        success: function(response){
            document.getElementById('fullname').innerHTML = response['first_name'] + " " + response['last_name'];
            document.getElementById('email').innerHTML = response['email'];
        }
    });
</script>


