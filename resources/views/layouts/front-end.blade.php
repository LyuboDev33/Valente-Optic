<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @yield('SEO')

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/animate.min.css" />
    <link rel="stylesheet" href="/assets/css/custom-animate.css" />
    <link rel="stylesheet" href="/assets/css/swiper.min.css" />
    <link rel="stylesheet" href="/assets/css/font-awesome-all.css" />
    <link rel="stylesheet" href="/assets/css/jarallax.css" />
    {{-- <link rel="stylesheet" href="/assets/css/jquery.magnific-popup.css" /> --}}
    <link rel="stylesheet" href="/assets/css/flaticon.css" />
    <link rel="stylesheet" href="/assets/css/owl.carousel.min.css" />
    {{-- <link rel="stylesheet" href="/assets/css/odometer.min.css" />
    <link rel="stylesheet" href="/assets/css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="/assets/css/nice-select.css" /> --}}
    {{-- <link rel="stylesheet" href="/assets/css/jquery-ui.css" />
    <link rel="stylesheet" href="/assets/css/aos.css" /> --}}

    <link rel="stylesheet" href="/assets/css/module-css/slider.css" />
    <link rel="stylesheet" href="/assets/css/module-css/footer.css" />
    <link rel="stylesheet" href="/assets/css/module-css/counter.css" />
    <link rel="stylesheet" href="/assets/css/module-css/services.css" />
    <link rel="stylesheet" href="/assets/css/module-css/about.css" />
    <link rel="stylesheet" href="/assets/css/module-css/brand.css" />
    <link rel="stylesheet" href="/assets/css/module-css/gallery.css" />
    <link rel="stylesheet" href="/assets/css/module-css/faq.css" />
    <link rel="stylesheet" href="/assets/css/module-css/testimonial.css" />
    <link rel="stylesheet" href="/assets/css/module-css/team.css" />
    <link rel="stylesheet" href="/assets/css/module-css/contact.css" />
    <link rel="stylesheet" href="/assets/css/module-css/pricing.css" />
    <link rel="stylesheet" href="/assets/css/module-css/blog.css" />
    <link rel="stylesheet" href="/assets/css/module-css/sliding-text.css" />
    <link rel="stylesheet" href="/assets/css/module-css/cta.css" />
    <link rel="stylesheet" href="/assets/css/module-css/feature.css" />
    <link rel="stylesheet" href="/assets/css/module-css/banner.css" />

    <!-- template styles -->
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="/assets/css/shop.css" />

    <link rel="stylesheet" href="/assets/css/responsive.css" />

    <link rel="icon" type="image/png" href="/assets/images/favicons/favicon-96x96.png?v=<?php echo time(); ?>" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicons/transparent-image.png?v=<?php echo time(); ?>" />
    <link rel="shortcut icon" href="/assets/images/favicons/favicon-96x96.png?v=<?php echo time(); ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicons/apple-touch-icon.png?v=<?php echo time(); ?>" />
    <link rel="manifest" href="/assets/images/favicons/site.webmanifest?v=<?php echo time(); ?>" />

    <link rel="stylesheet" href="https://unpkg.com/lenis@1.3.23/dist/lenis.css">
    <script src="https://unpkg.com/lenis@1.3.23/dist/lenis.min.js"></script>

    <script>
        const lenis = new Lenis({
            duration: 1,
            smoothWheel: true,
            wheelMultiplier: 0.8,
            touchMultiplier: 0.8,
            lerp: 0.5
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);
    </script>


</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        @include('layouts.partials.frontend.header')

        <main class="page-wrapper">
            {{ $slot }}
        </main>

        @include('layouts.partials.frontend.footer')


    </div>
</body>




<script src="/assets/js/jquery-3.6.0.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/jarallax.min.js"></script>
<script src="/assets/js/jquery.ajaxchimp.min.js"></script>
<script src="/assets/js/jquery.appear.min.js"></script>
<script src="/assets/js/swiper.min.js"></script>
{{-- <script src="/assets/js/jquery.circle-progress.min.js"></script>
<script src="/assets/js/jquery.magnific-popup.min.js"></script>
<script src="/assets/js/isotope.js"></script>
<script src="/assets/js/jquery.validate.min.js"></script>
<script src="/assets/js/wNumb.min.js"></script> --}}
<script src="/assets/js/wow.js"></script>
<script src="/assets/js/owl.carousel.min.js"></script>
{{-- <script src="/assets/js/jquery-ui.js"></script>
<script src="/assets/js/odometer.min.js"></script> --}}
{{-- <script src="/assets/js/jquery.nice-select.min.js"></script> --}}
<script src="/assets/js/jquery-sidebar-content.js"></script>
{{-- <script src="/assets/js/marquee.min.js"></script> --}}
{{-- <script src="/assets/js/gsap/gsap.js"></script>
<script src="/assets/js/gsap/ScrollTrigger.js"></script>
<script src="/assets/js/gsap/SplitText.js"></script> --}}
<script src="/assets/js/aos.js"></script>

<!-- template js -->
<script src="/assets/js/script.js"></script>

</html>
