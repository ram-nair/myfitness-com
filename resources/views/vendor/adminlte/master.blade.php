<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
            @yield('title', config('adminlte.title', 'AdminLTE 3'))
            @yield('title_postfix', config('adminlte.title_postfix', ''))</title>
        @if(! config('adminlte.enabled_laravel_mix'))
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        @include('adminlte::plugins', ['type' => 'css'])

        @yield('adminlte_css_pre')

        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

        @yield('adminlte_css')

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        @else
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @endif
        <link rel="stylesheet" href="{{ asset('css/Myadmin.css') }}">

        @yield('meta_tags')

        @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicon.png?ver=1.12') }}" />
        @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
        @endif
    </head>
    <body class="@yield('classes_body')" @yield('body_data')>

          @yield('body')

          @if(! config('adminlte.enabled_laravel_mix'))
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
        <script src="{{ asset('js/provis.js') }}"></script>

        @include('adminlte::plugins', ['type' => 'js'])
        @include('sweet::alert')
        <script>
$(function () {
    $(document).on('click', '.table .destroy', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var thisTr = $(this).closest('tr');
        swal({
            title: "{{ trans('myadmin.confirm-delete') }}",
            text: "{{ trans('myadmin.confirm-delete-msg') }}",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
                .then((willDelete) => {
                    if (willDelete) {
                        Pace.restart();
                        Pace.track(function () {
                            $.ajax({
                                url: href,
                                method: 'DELETE',
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                success: function (response) {
                                    if (response.status) {
                                        thisTr.remove();
                                        swal("Done!", response.message, "success");
                                    } else {
                                        swal("Failed!", response.message, "error");
                                    }
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    swal("Error deleting!", "Please try again", "error");
                                }
                            });
                        });
                    } else {
                        return true;
                    }
                });
    });
});

$(function () {
    $(document).on('click', '.table .no-delete', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var thisTr = $(this).closest('tr');
        swal({
            title: "{{ trans('myadmin.no-delete') }}",
            text: "{{ trans('myadmin.no-delete-msg') }}",
            icon: "info",
            buttons: false,
            dangerMode: false,
            timer: 3000,
        })
    });
});

// $(function () {
//     $(document).on('click', '.table .mark-attendance', function (e) {
//         e.preventDefault();
//         var href = $(this).attr('href');
//         var thisTr = $(this).closest('tr');
//         swal({
//             title: "{{ trans('myadmin.mark-attendance') }}",
//             text: "{{ trans('myadmin.mark-attendance-msg') }}",
//             icon: "success",
//             buttons: true,
//             dangerMode: false,
//             timer: 3000,
//         })
//         .then((willDelete) => {
//             if (willDelete) {
//                 Pace.restart();
//                 Pace.track(function () {
//                     $.ajax({
//                         url: href,
//                         method: 'GET',
//                         headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
//                         success: function (response) {
//                             if (response.status) {
//                                 swal("Done!", response.message, "success");
//                             } else {
//                                 swal("Failed!", response.message, "error");
//                             }
//                             location.reload(true);
//                         },
//                         error: function (xhr, ajaxOptions, thrownError) {
//                             swal("Error deleting!", "Please try again", "error");
//                         }
//                     });
//                 });
//             } else {
//                 return true;
//             }
//         });
//     });
// });

        </script>
        @yield('adminlte_js')
        @else
        <script src="{{ mix('js/app.js') }}"></script>
        @endif

    </body>
</html>
