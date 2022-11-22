let previewPhoto = document.querySelectorAll('.preview-photo, .preview-photo-small, .preview-photo-small-offer, preview-photo-big');

previewPhoto.forEach(function(el) {
    el.addEventListener('click', function (e) {
        let zoom = document.querySelectorAll('.lb-container');
        zoom.forEach(function(el) {
            let div = document.createElement('div');
            div.className = 'image-zoom-block';
            el.append(div);
            el.addEventListener('click', function(e) {
                const target = e.target.closest('.lb-container'),
                    rect = target.getBoundingClientRect();
                target.classList.toggle('-active');
                target.style.setProperty('--image', `url(${target.querySelector('img').getAttribute('src')})`);
                target.style.setProperty('--x', Math.floor(((e.clientX - rect.left) / rect.width * 100) * 100) / 100+'%');
                target.style.setProperty('--y', Math.floor(((e.clientY - rect.top) / rect.height * 100) * 100) / 100+'%');
                target.classList.toggle('-enter');
            });

            el.addEventListener('mouseenter', function(e) {
                const target = e.target.closest('.lb-container');
                if(target.classList.contains('-active')) {
                    target.classList.add('-enter');
                }
            });

            el.addEventListener('mousemove', function(e) {
                const target = e.target.closest('.lb-container');
                if(target.classList.contains('-active')) {
                    const rect = target.getBoundingClientRect();
                    target.style.setProperty('--x', Math.floor(((e.clientX - rect.left) / rect.width * 100) * 100) / 100+'%');
                    target.style.setProperty('--y', Math.floor(((e.clientY - rect.top) / rect.height * 100) * 100) / 100+'%');
                }
            });

            el.addEventListener('mouseleave', function(e) {
                let target = e.target.closest('.lb-container');
                if(target.classList.contains('-active')) {
                    target.classList.remove('-enter');
                }
            });
        });
    })
})