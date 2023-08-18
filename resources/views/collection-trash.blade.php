@section('title')
    Collection
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trash') }}
        </h2>
        <div class="breadcrumbs mt-4 mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li>
                        <a href="{{ route('collection') }}" class="hover:text-gray-700">Collection</a>
                    </li>
                    <li class="px-2">
                        <i class="fa fa-caret-right"></i>
                    </li>
                    <li class="font-semibold">
                        <span class="whitespace-nowrap">Trash Can</span>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-2">
                        <h1 class="text-2xl font-bold">Deleted Products</h1>
                    </div>
                    <table id="trashTable" class="w-full table-auto border-collapse border">
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
                                <th class="px-4 py-2 border">Added By</th>
                                {{-- <th class="px-4 py-2">Modified By</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mannequins as $mannequin)
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
                                            <a href="#" class="btn-view restore-button" onclick="confirmRestore('{{ route('collection.restore', ['id' => $mannequin->id]) }}')">
                                                <i class="fas fa-check"></i> Restore
                                            </a>
                                            <button class="btn-delete" data-id="{{ $mannequin->id }}" data-transfer-url="{{ route('collection.delete', ['id' => $mannequin->id]) }}"
                                                onclick="confirmDelete(this)">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 border">{{ $mannequin->company }}</td>
                                    <td class="px-4 py-2 border">{{ $mannequin->category }}</td>
                                    <td class="px-4 py-2 border">{{ $mannequin->type }}</td>
                                    <td class="px-4 py-2 border">{{ $mannequin->addedBy }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#trashTable').DataTable({
                lengthChange: false,
                searching: false,
            });
            // Handle "select all" checkbox
            $('#selectAllCheckbox').on('change', function() {
                var isChecked = this.checked;
                $('td input.row-checkbox').each(function() {
                    this.checked = isChecked;
                });
            });
        });
    </script>
    {{-- SweetAlert --}}
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

        function confirmRestore(restoreUrl) {
            Swal.fire({
                title: 'Restore Image',
                text: 'Are you sure you want to restore this image?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Restore',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = restoreUrl;
                }
            });

            return false; // Prevent the default link behavior
        }
        function confirmDelete(button) {
            var id = button.getAttribute('data-id');
            var deleteUrl = button.getAttribute('data-transfer-url');

            Swal.fire({
                title: 'Delete Product',
                text: 'Are you sure you want to delete this product?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d9534f',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete URL
                    window.location.href = deleteUrl;
                }
            });
        }
    </script>
</x-app-layout>
