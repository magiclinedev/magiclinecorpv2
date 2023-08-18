//image
$(document).ready(function() {
    const imageThumbnails = $('.zoomable-image');
    const mainImage = $('#mainImage'); // Update the selector to target the main image by its id

    imageThumbnails.on('click', function() {
        const imageIndex = $(this).data('image-index');
        const imagePath = $(this).find('img').attr('src');
        mainImage.attr('src', imagePath);
    });
});

$(document).ready(function() {
    const imageThumbnails = $('.zoomable-image');
    const mainImage = $('#mainImage');

    // Initialize the Magnifier.js instance
    const magnifier = new Magnifier({
        // The container element for the magnifier
        container: '.magnifier-container',
        // The zoomed image source will be set dynamically on thumbnail click
        src: '',
        zoomFactor: 2, // You can adjust the zoom factor as needed
    });

    imageThumbnails.on('click', function() {
        const imageIndex = $(this).data('image-index');
        const imagePath = $(this).find('img').attr('src');
        mainImage.attr('src', imagePath);

        // Update the source of the magnifier with the selected image
        magnifier.src = imagePath;
    });
});

//magnifier



