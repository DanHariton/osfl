AOS.init({
    // Global settings:
    disable: false, // false // accepts following values: 'phone', 'tablet', 'mobile', boolean, expression or function
    startEvent: 'DOMContentLoaded', // 'DOMContentLoaded' // name of the event dispatched on the document, that AOS should initialize on
    initClassName: 'aos-init', // 'aos-init' // class applied after initialization
    animatedClassName: 'aos-animate', // 'aos-animate' // class applied on animation
    useClassNames: false, // false // if true, will add content of `data-aos` as classes on scroll
    disableMutationObserver: false, // false // disables automatic mutations' detections (advanced)
    debounceDelay: 50, // 50 // the delay on debounce used while resizing window (advanced)
    throttleDelay: 99, // 99 // the delay on throttle used while scrolling the page (advanced)

    // Settings that can be overridden on per-element basis, by `data-aos-*` attributes:
    offset: 120, // 120 // offset (in px) from the original trigger point
    delay: 100, // 0 // values from 0 to 3000, with step 50ms
    duration: 600, // 400 // values from 0 to 3000, with step 50ms
    easing: 'ease', // 'ease' // default easing for AOS animations
    once: false, // false // whether animation should happen only once - while scrolling down
    mirror: false, // false // whether elements should animate out while scrolling past them
    anchorPlacement: 'top-bottom', // 'top-bottom' // defines which position of the element regarding to window should trigger the animation

});

new Movinwords({
    el: '.movinwords-sentence',
    duration: 375,
    delay: 45,
    transition: 'slideInBottom',
});