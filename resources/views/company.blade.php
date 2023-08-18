@section('title')
    Company
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add company') }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
    <div class="max-w-auto mx-auto sm:px-3 lg:px-8">
    <div class="flex space-x-4">
        <div class="bg-white shadow-md rounded-lg px-8 py-6 w-full">
            <form action="{{ route('company.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST') <!-- Add this line to specify the method -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label for="company" class="block font-bold mb-2">Company</label>
                        <input type="text" name="company" id="company" class="w-full border rounded-md py-2 px-3" placeholder="Enter company name">
                    </div>
                    {{-- images --}}
                    <div class="col-span-2">
                        <label for="images" class="block font-bold mb-2">Images</label>
                        <input type="file" name="images" id="images" class="w-full border rounded-md py-2 px-3">
                    </div>
                </div>
                <button type="submit" class="mt-4 mb-10 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Add Company
                </button>
            </form>
        </div>
        <div class="bg-white shadow-md rounded-lg px-8 py-6 w-full">
            <table id="categoriesTable" class="w-full table-auto border-collapse border rounded-lg px-8 py-6">
                <thead class>
                    <tr>
                        <th class="px-4 py-2 border">Logo</th>
                        <th class="px-4 py-2 border">Name</th>
                        <th class="px-4 py-2 border">Added By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($company as $company)
                        <tr class="border">
                            <td class="px-4 py-2 border">
                                <img src="{{ asset('storage/' . $company->images) }}" alt="Company logo" width="100">
                            </td>
                            <td class="px-4 py-2 border">{{ $company->name }}</td>
                            <td class="px-4 py-2 border">{{ $company->addedBy }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>

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
    </script>
</x-app-layout>
