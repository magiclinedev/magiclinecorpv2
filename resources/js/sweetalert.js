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
