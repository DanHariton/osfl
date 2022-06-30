(function () {
    if (Boolean(document.querySelector('.splide-4'))) {
        let slider = new Splide('.splide-4', {
            type: 'loop',
            perPage: 4,
            perMove: 1,
            gap: '1em',
        })

        slider.mount();

        let imageContainer = document.querySelector('#slider-modal-image-container');
        let updateImageContainer = (image) => {
            imageContainer.innerHTML = '';
            imageContainer.appendChild(slider.Components.Elements.slides[slider.Components.Controller.getIndex()].querySelector('img').cloneNode());
        }

        slider.on( 'click', function (slide, e) {
            slider.Components.Controller.go(slide.index);
            updateImageContainer();
            $('#slider-modal').modal();
        });

        document.querySelector('#slider-modal-prev').addEventListener('click', () => {
            slider.Components.Controller.go('<');
            updateImageContainer();
        });
        document.querySelector('#slider-modal-next').addEventListener('click', () => {
            slider.Components.Controller.go('>');
            updateImageContainer();
        });
    }
})();