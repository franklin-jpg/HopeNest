<!DOCTYPE html>

<html lang="en">


<head>
    <meta charset="UTF-8">
    <base href="/public">

    <title>HOPENEST❤️</title><!-- mobile responsive meta -->
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link href="images/favicons/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180">
    <link href="images/favicons/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png">
    <link href="images/favicons/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png">

    <!-- TailwindCSS -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- iziToast -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
</head>

<body>

    <div class="boxed_wrapper">
        {{-- top bar --}}
        @include('partials.welcome.topbar')




        @yield('content')
        {{-- footer section --}}
        @include('partials.welcome.footer')


        <!-- Scroll Top  -->
        <button class="scroll-top tran3s color2_bg"><span class="fa fa-angle-up"></span></button> <!-- preloader  -->


        <div class="preloader">


        </div>

        {{-- Add this JavaScript at the bottom of the page or in your main JS file --}}
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars individually
    document.querySelectorAll('.animated-bar').forEach(function(bar) {
        const percent = bar.getAttribute('data-percent');
        setTimeout(function() {
            bar.style.width = percent;
        }, 200);
    });
});

 </script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    @if (session('success') || session('error') || session('info') || session('warning'))
<script>
        // iziToast Messages
        @if (session('success'))
            iziToast.success({
                title: 'Success',
                message: @json(session('success')),
                position: 'topRight',
                timeout: 5000
            });
        @elseif (session('error'))
            iziToast.error({
                title: 'Error',
                message: @json(session('error')),
                position: 'topRight',
                timeout: 5000
            });
        @elseif (session('info'))
            iziToast.info({
                title: 'Info',
                message: @json(session('info')),
                position: 'topRight',
                timeout: 5000
            });
        @elseif (session('warning'))
            iziToast.warning({
                title: 'Warning',
                message: @json(session('warning')),
                position: 'topRight',
                timeout: 5000
            });
        @endif

        </script>
    @endif
       
    </script>
        </script>
        @endpush

        <!-- jQuery -->
        <script src="js/jquery.js">
        </script>
        <script src="js/bootstrap.min.js">
        </script>
        <script src="js/menu.js">
        </script>
        <script src="js/owl.carousel.min.js">
        </script>
        <script src="js/jquery.mixitup.min.js">
        </script>
        <script src="js/jquery.fancybox.pack.js">
        </script>
        <script src="js/imagezoom.js">
        </script>
        <script src="js/jquery.magnific-popup.min.js">
        </script>
        <script src="js/jquery.polyglot.language.switcher.js">
        </script>
        <script src="js/SmoothScroll.js">
        </script>
        <script src="js/jquery.appear.js">
        </script>
        <script src="js/jquery.countTo.js">
        </script>
        <script src="js/validation.js">
        </script>
        <script src="js/wow.js">
        </script>
        <script src="js/jquery.fitvids.js">
        </script>
        <script src="js/nouislider.js">
        </script> <!-- revolution slider js -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js">
        </script>

        <script src="js/rev-slider/jquery.themepunch.tools.min.js">
        </script>
        <script src="js/rev-slider/jquery.themepunch.revolution.min.js">
        </script>
        <script src="js/rev-slider/revolution.extension.actions.min.js">
        </script>
        <script src="js/rev-slider/revolution.extension.carousel.min.js">
        </script>
        <script src="js/rev-slider/revolution.extension.kenburn.min.js">
        </script>
        <script src="js/rev-slider/revolution.extension.layeranimation.min.js">
        </script>
        <script src="js/rev-slider/revolution.extension.migration.min.js">
        </script>
        <script src="js/rev-slider/revolution.extension.navigation.min.js">
        </script>
        <script src="js/rev-slider/revolution.extension.parallax.min.js">
        </script>
        <script src="js/rev-slider/revolution.extension.slideanims.min.js">
        </script>
        <script src="js/rev-slider/revolution.extension.video.min.js">
        </script>
        <script src="js/custom.js">
        </script>
    </div>
</body>

</html>