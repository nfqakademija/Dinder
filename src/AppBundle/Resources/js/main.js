// setup an "add image" link
var $addImageLink = $('<a href="#" class="add_image_link">Add image</a>');
var $newLinkLi = $('<div class="ImageCollection-add"></div>').append($addImageLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of images
    var $collectionHolder = $('.ImageCollection-list');

    // add the "add image" anchor and li to the images ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new input (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addImageLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new image form (see code block below)
        addImagesForm($collectionHolder, $newLinkLi);
    });
});

function addImagesForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add image" link li
    var $newFormLi = $('<div class="ImageCollection-item"></div>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<div class="ImageCollection-remove"><a href="#" class="remove-image">x</a></div>');
    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-image').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}
