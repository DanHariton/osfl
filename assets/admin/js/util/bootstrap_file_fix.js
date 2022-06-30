(function () {
    $('.custom-file-input').on('change', function(event) {
        let inputFile = event.currentTarget;
        let names = Array.from(inputFile.files).map((f) => f.name).join(', ');
        $(inputFile).parent().find('.custom-file-label').html(names);
    });
})();