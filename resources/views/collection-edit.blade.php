@section('title')
    Collection
@endsection

<x-app-layout>

    @php
        // Split the image paths string into an array
        $imagePaths = explode(',', $mannequin->images);
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
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
                        <span class="whitespace-nowrap">Product Edit</span>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg flex flex-col md:flex-row px-8 py-6">
            {{-- IMAGES --}}
            <div class="grid grid-cols-3 gap-4">
                @foreach ($imagePaths as $imagePath)
                    <div class="w-full">
                        <img src="{{ asset('storage/' . $imagePath) }}" alt="Photo" class="max-w-full h-auto">
                    </div>
                @endforeach
            </div>

            <div class="md:w-1/2">
                <div class="p-4 leading-normal">
                    <form method="POST" action="{{ route('collection.update', ['id' => $mannequin->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="flex">
                            <div class="w-1/3 font-bold font-bold">Purchase Order:</div>
                            <div class="w-2/3">
                                <input type="text" name="po" value="{{ $mannequin->po }}" class="border rounded p-1 w-full">
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-1/3 font-bold font-bold">Item Reference:</div>
                            <div class="w-2/3">
                                <input type="text" name="itemref" value="{{ $mannequin->itemref }}" class="border rounded p-1 w-full">
                            </div>
                        </div>
                        {{-- Company --}}
                        <div class="col-span-2 sm:col-span-1">
                            <label for="company" class="block font-bold mb-2">Company:</label>
                            <div class="col-span-2 sm:col-span-1 flex items-center">
                                <select name="company" id="company" class="w-full border rounded-md py-2 px-3">
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->name }}" {{ $company->name == $mannequin->company ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- CATEGORY --}}
                        <div class="col-span-2 sm:col-span-1">
                            <label for="category" class="block font-bold mb-2">Category:</label>
                            <div class="col-span-2 sm:col-span-1 flex items-center">
                                <select name="category" id="category" class="w-full border rounded-md py-2 px-3">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}" {{ $category->name == $mannequin->category ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- TYPE --}}
                        <div class="col-span-2 sm:col-span-1">
                            <label for="type" class="block font-bold mb-2">Type:</label>
                            <div class="col-span-2 sm:col-span-1 flex items-center">
                                <select name="type" id="type" class="w-full border rounded-md py-2 px-3">
                                    @foreach ($types as $type)
                                        <option value="{{ $type->name }}" {{ $type->name == $mannequin->type ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- PRICE --}}
                        <div class="col-span-2 sm:col-span-1">
                            <div class="w-1/3 font-bold font-bold">Price:</div>
                            <div class="col-span-2 sm:col-span-1 flex items-center">
                                <input type="text" name="price" value="{{ $mannequin->price }}" class="w-full border rounded-md py-2 px-3">
                            </div>
                        </div>

                        {{-- listed items wont save dots, number, or etc. --}}
                        {{-- DESCRIPTION --}}
                        <div class="col-span-2">
                            <label for="description" class="block font-bold mb-2">Description</label>
                            <div class="relative w-full border rounded-md py-2 px-3">
                                <div id="quill-editor" class="editor-style">{!! $mannequin->description !!}</div>
                            </div>
                            <textarea name="description" id="description" class="hidden">{!! $mannequin->description !!}</textarea>
                        </div>

                        {{-- UPLOAD FILES --}}

                        {{-- IMAGES --}}
                        <div class="w-full">
                            <label class="block font-bold mb-2">Images</label>
                            <div class="mt-2 items-center">
                                <input type="file" name="images[]" class="border rounded-lg p-2" multiple>
                            </div>
                        </div>

                        {{-- File --}}
                        <div class="mt-4">
                            <label class="block font-bold mb-2">Costing</label>
                            <div class="mt-2 flex items-center space-x-4">
                                <input type="file" name="file" class="border rounded-lg p-2">
                                @if ($mannequin->file)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500">Current File:</span>
                                        <span class="text-sm text-blue-600">{{ $mannequin->file }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500">Current File:</span>
                                        <span class="text-sm text-blue-600">No Current file</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- PDF --}}
                        <div class="mt-4">
                            <label class="block font-bold mb-2">PDF</label>
                            <div class="mt-2 flex items-center space-x-4">
                                <input type="file" name="pdf" class="border rounded-lg p-2">
                                @if ($mannequin->pdf)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500">Current PDF:</span>
                                        <span class="text-sm text-blue-600">{{ $mannequin->pdf }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500">Current PDF:</span>
                                        <span class="text-sm text-blue-600">No PDF</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $('form').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var form = this;

        // Get the current value of the description textarea
        var descriptionValue = $('#description').val();

        Swal.fire({
            title: 'Confirm Edit',
            text: 'Are you sure you want to save the changes?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save changes!',
        }).then((result) => {
            if (result.isConfirmed) {
                // Restore the original description value before submitting the form
                $('#description').val(descriptionValue);

                // Proceed with the form submission
                form.submit();
            }
        });
    });
    </script>
    {{-- <script src="{{ asset('js/main.js') }}"></script> --}}
    <script>
        //magnifier should disappear when outside border
        $(document).ready(function() {
            const imageThumbnails = $('.zoomable-image');
            const mainImage = $('#mainImage');
            let magnifierActive = false; // Keep track of magnifier statee

            imageThumbnails.on('click', function() {
                const imageIndex = $(this).data('image-index');
                const imagePath = $(this).find('img').attr('src');
                mainImage.attr('src', imagePath);

                // Disable the magnifier when switching to a new image
                disableMagnifier();
            });

            function disableMagnifier() {
                magnifierActive = false;
                const glass = $('.img-magnifier-glass'); // Store the magnifier glass element
                glass.hide();
            }

            // Add click handler for the main image to toggle the magnifier
            mainImage.on('click', function() {
                if (magnifierActive) {
                    disableMagnifier();
                } else {
                    magnifierActive = true;
                    setupMagnifier("mainImage", 2);
                }
            });

            // Add mouseleave event on the container of the main image to hide the magnifier
            mainImage.parent().on('mouseleave', function() {
                if (magnifierActive) {
                    disableMagnifier();
                }
            });
        });
    </script>
    {{-- Description Quill --}}
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#quill-editor', {
            theme: 'snow', // Snow is a prebuilt theme with a clean interface
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'], // Include the formatting options you want
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Enter Description'
        });

        // Sync the content of Quill editor with the hidden textarea
        quill.on('text-change', function() {
            var editorContent = document.querySelector('#quill-editor .ql-editor').innerHTML;
            document.querySelector('#description').value = editorContent;
        });
    </script>
</x-app-layout>
