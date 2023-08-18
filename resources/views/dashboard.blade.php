@section('title')
    Home
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Magic Line') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-6">
            <div class="flex flex-wrap">

                <div class="w-1/5 p-4">
                    <a href="#" class="block text-center relative overflow-hidden group">
                        <!-- Content for the first square -->
                        <div class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800 rounded-md">
                            <div class="relative">
                                <!-- Overflowing box with picture/icon -->
                                <div class="absolute left-0 top-2 transform -translate-x-1/2 -translate-y-1/2 ml-7 w-14 h-14 bg-white rounded-md flex justify-center items-center">
                                    <i class="fa fa-camera-retro fa-2x text-black"></i>
                                </div>

                                <!-- Content container -->
                                <div class="p-6 items-end flex justify-end">
                                    <div class="text-sm text-gray-500">
                                        <div class="text-right">
                                            <div class="text-2xl font-semibold text-white-800">
                                                <span class="text-5xl text-800 text-white">14</span>
                                            </div>
                                            <div class="text-sm text-white-500">
                                                <p class="text-white">CLEO's Products</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Pseudo-element for hover effect -->
                        <div class="absolute left-0 bottom-0 h-0 w-full bg-gradient-to-t from-gray-400 to-transparent transition-all duration-300 ease-in-out group-hover:h-full"></div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
