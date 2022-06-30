(function () {
    if (!Boolean(document.querySelector('.contact-tab-link'))) {
        return;
    }

    let mobilePointer = 991;
    let getInnerWidth = () => window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    let contactUsLink = document.querySelector('.contact-tab-link[href="#tab-contact-us"]');
    let contactDistLink = document.querySelector('.contact-tab-link[href="#tab-contact-distributors"]');
    let changeOrder = () => {
        let isContactUsSelected = contactUsLink.classList.contains('selected');
        let isContactDistLinkSelected = contactDistLink.classList.contains('selected');

        if (getInnerWidth() <= mobilePointer) {
            // Mobile
            contactUsLink.style.display = isContactDistLinkSelected ? 'none' : 'block';
            contactDistLink.style.display = isContactUsSelected ? 'none' : 'block';
        } else {
            // Desktop
            contactUsLink.style.display = 'block';
            contactDistLink.style.display = 'block';
        }
    }

    window.addEventListener('resize', changeOrder);

    document.querySelectorAll('.contact-tab-link').forEach(link => link.addEventListener('click', (e) => {
        e.preventDefault();
        let currentState = link.classList.contains('selected');

        document.querySelectorAll('.contact-tab-link').forEach(l => l.classList.remove('selected'));
        document.querySelectorAll('.contact-tabs-content .item').forEach(item => item.classList.remove('show'));

        link.classList.toggle('selected', !currentState);
        document.querySelector(link.getAttribute('href')).classList.toggle('show', !currentState);

        changeOrder();
    }));
})();