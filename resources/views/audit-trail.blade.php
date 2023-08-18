@section('title')
    Audit Trail
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Trail') }}
        </h2>
    </x-slot>

    <div class="container mx-auto p-4">

        <div class="bg-white shadow-md rounded-lg overflow-x-auto px-4 py-6">
            <table id="auditTrailTable" class="w-full table-auto border-collapse border">
                <thead class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold uppercase tracking-wider">User ID</th>
                        <th class="px-5 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold uppercase tracking-wider">User Status</th>
                        <th class="px-5 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold uppercase tracking-wider">Activity</th>
                        <th class="px-5 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold uppercase tracking-wider">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $audit)
                    <tr>
                        <td class="px-4 py-2 border">{{ $audit->user->name }}</td>
                        <td class="px-4 py-2 border">{{ $audit->user->status }}</td>
                        <td class="px-4 py-2 border">{{ $audit->activity }}</td>
                        <td class="px-4 py-2 border">{{ $audit->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- datables --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#auditTrailTable').DataTable({
                lengthChange: false,
                order: [[3, "asc"]] // Set default sorting order for timestamp column (index 2) to ascending
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
