<script>
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('nav.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    (function($) {
        $(window).scroll(function() {
            if ($(document).scrollTop() > 300) {
                // Navigation Bar
                $('.navbar').removeClass('fadeIn');
                $('body').addClass('shrink');
                $('.navbar').addClass('fixed-top animated fadeInDown');
            } else {
                $('.navbar').removeClass('fadeInDown');
                $('.navbar').removeClass('fixed-top');
                $('body').removeClass('shrink');
                $('.navbar').addClass('animated fadeIn');
            }
        });
    })(jQuery);
</script>
