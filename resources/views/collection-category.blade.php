@section('title')
    Collection
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Category') }}
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
                        <span class="whitespace-nowrap">Category</span>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <form action="{{ route('collection.category') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg px-8 py-6">
            @csrf
            @method('POST') <!-- Add this line to specify the method -->
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="category" class="block font-bold mb-2">Category</label>
                    <input type="text" name="category" id="category" class="w-full border rounded-md py-2 px-3" placeholder="Enter Category">
                </div>
            </div>
            <button type="submit" class="mt-4 mb-10 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Add category
            </button>

            <div class="overflow-x-auto">
                <table id="categoriesTable" class="w-full table-auto border-collapse border">
                    <thead class>
                        <tr>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Added By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr class="border">
                                <td class="px-4 py-2 border">{{ $category->name }}</td>
                                <td class="px-4 py-2 border">{{ $category->addedBy }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

    </div>

    <script>
        $(document).ready(function() {
            // Add hover effect to item reference cell
            $('.item-ref-cell').hover(
                function() {
                    // When mouse enters, show the action buttons
                    $(this).find('.action-buttons').removeClass('hidden');
                },
                function() {
                    // When mouse leaves, hide the action buttons
                    $(this).find('.action-buttons').addClass('hidden');
                }
            );
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
                title: 'Invalid Input',
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
</x-app-layout>
