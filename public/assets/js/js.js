//Au clique sur n'importe quel image
$('.monImage').on('click', function () {

    // On met temporairement le src de la grande image dans une variable
    var tmp = $('.grde-img').attr('src');

    //On utilise $(this).attr('src') pour recuperer le src sur lequel on a cliqué
    //Et on le met dans le src de la grande image
    $('.grde-img').attr('src', $(this).attr('src'));

    //Et on met l'ancien src qui se trouve dans tmp, dans le src de l'image sur laquelle on viens de cliquer
    $('this').attr('src', tmp);

});


//Permet l'affichage du nom de l'image dans l'input
$(".custom-file-input").on("change", function (event) {
    let inputFile = event.currentTarget;
    $(inputFile)
        .parent()
        .find(".custom-file-label")
        .html(inputFile.files[0].name);
});

//Champs imbriqués

var $categories = $('#categories');
// When sport gets selected ...
$categories.change(function () {
    // ... retrieve the corresponding form.
    var $form = $(this).closest('form');
    // Simulate form data, but only include the selected sport value.
    var data = {};
    data[$categories.attr('name')] = $categories.val();
    // Submit data via AJAX to the form's action path.
    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: data,
        success: function (html) {
            // Replace current position field ...
            $('#services').replaceWith(
                // ... with the returned one from the AJAX response.
                $(html).find('#services')
            );
            // Position field now displays the appropriate positions.
        }
    });
});