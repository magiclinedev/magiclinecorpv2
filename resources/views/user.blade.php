@section('title')
    Users
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-auto mx-auto sm:px-3 lg:px-8">
            <div class="flex space-x-4">
                <form method="POST" action="{{ route('users.add') }}" class="bg-white shadow-md rounded-lg px-8 py-6 w-1/2">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 p-2 border rounded-md w-full" required>
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" class="mt-1 p-2 border rounded-md w-full" required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="mt-1 p-2 border rounded-md w-full" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" class="mt-1 p-2 border rounded-md w-full" required>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 p-2 border rounded-md w-full" required>
                    </div>

                    {{-- ROLE --}}
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 p-2 border rounded-md w-full" required>
                            <option value="" disabled selected>Select a status</option>
                            <option value="1">Admin 1</option>
                            <option value="2">Admin 2</option>
                            <option value="3">Viewer</option>
                        </select>
                    </div>

                    {{-- COMPANY ACCESS --}}
                    <div class="mb-4">
                        <div class="mt-1">
                            <div class="inline-flex items-center">
                                <label class="block text-sm font-medium text-gray-700 mr-2">Companies</label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="select-all-companies" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="block text-sm font-medium text-gray-700 ml-2">Select All</span>
                                </label>
                            </div>

                            <div class="flex flex-wrap -mx-2">
                                @foreach ($companies as $company)
                                    <div class="w-1/2 px-2 sm:w-1/4 md:w-1/6">
                                        <label class="inline-flex">
                                            <input type="checkbox" name="company_ids[]" value="{{ $company->id }}" class="company-checkbox rounded border-gray-300 font-medium text-indigo-600 shadow-sm focus:ring-indigo-500 mx-1">
                                            <span class="block text-sm font-medium text-gray-700 mx-2">{{ $company->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Price Acces --}}
                    <div class="mb-4">
                        <div class="mt-1">
                            <label class="block text-sm font-medium text-gray-700">Price Access</label>
                            <div id="selected-companies" class="flex flex-wrap">
                                <!-- Selected companies will be listed here dynamically -->
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="mt-4 mb-10 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Register
                        </button>
                    </div>
                </form>

                <br>

                {{-- USer TABLE --}}
                <div class="bg-white shadow-md rounded-lg px-8 py-6 w-auto">
                    <table id="usersTable" class="w-full border-collapse pt-6">
                        <thead class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 border">Name</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Company</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border">
                                    <td class="px-4 py-2 border">{{ $user->name }}</td>
                                    <td class="px-4 py-2 border">
                                        @if ($user->status == 1)
                                            Super Admin / Admin 1
                                        @elseif ($user->status == 2)
                                            Admin 2
                                        @else
                                            Viewer
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">
                                        @if ($user->status == 1)
                                            All
                                        @else
                                            @foreach ($user->companies as $company)
                                                {{ $company->name }}
                                                @unless($loop->last)
                                                    , {{-- Add a comma unless it's the last company --}}
                                                @endunless
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">{{ $user->addedBy }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- DATA TABLES --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                lengthChange: false,
                // ... other DataTables options you want to set
            });
        });
    </script>

    {{-- script for company checkbox and price access --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('select-all-companies');
            const companyCheckboxes = document.querySelectorAll('.company-checkbox');
            const selectedCompaniesDiv = document.getElementById('selected-companies');

            selectAllCheckbox.addEventListener('change', function () {
                const isChecked = selectAllCheckbox.checked;

                companyCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    handleSelectedCompany(checkbox);
                });
            });

            companyCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    handleSelectedCompany(checkbox);
                });
            });

            function handleSelectedCompany(checkbox) {
                const companyName = checkbox.nextElementSibling.textContent.trim();
                const selectedCompany = Array.from(selectedCompaniesDiv.children).find(el => el.querySelector('.text-gray-700').textContent === companyName);

                if (checkbox.checked) {
                    if (!selectedCompany) {
                        const newSelectedCompany = document.createElement('div');
                        newSelectedCompany.classList.add('inline-flex', 'mb-1');
                        newSelectedCompany.innerHTML = `
                            <input type="checkbox" name="selected_company_ids[]" value="1" class="selected-company-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" checked>
                            <span class="block text-sm font-medium text-gray-700 ml-2">${companyName}</span>
                        `;
                        selectedCompaniesDiv.appendChild(newSelectedCompany);
                    }
                } else {
                    if (selectedCompany) {
                        selectedCompaniesDiv.removeChild(selectedCompany);
                    }
                }
            }
        });
    </script>
</x-app-layout>
