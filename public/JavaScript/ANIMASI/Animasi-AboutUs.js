document.addEventListener('DOMContentLoaded', function () {
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.bottom >= 0
        );
    }

    const aboutDesc = document.querySelector('.about-desc');
    const fotoAbout = document.querySelector('.foto-about');

    let isDescVisible = false;
    let isFotoVisible = false;

    function triggerAnimation(element, direction) {
        element.classList.remove('show');

        // trigger reflow agar animasi bisa diputar ulang
        void element.offsetWidth;

        element.classList.add('show');
    }

    function checkAnimation() {
        const descNowVisible = isInViewport(aboutDesc);
        const fotoNowVisible = isInViewport(fotoAbout);

        // Hanya trigger animasi jika sebelumnya tidak terlihat, sekarang terlihat
        if (!isDescVisible && descNowVisible) {
            triggerAnimation(aboutDesc, 'left');
        }
        if (!isFotoVisible && fotoNowVisible) {
            triggerAnimation(fotoAbout, 'right');
        }

        // Update status visibility
        isDescVisible = descNowVisible;
        isFotoVisible = fotoNowVisible;
    }

    window.addEventListener('scroll', checkAnimation);
    window.addEventListener('load', checkAnimation);
})
