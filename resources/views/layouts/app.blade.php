<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>{{ env('APP_NAME') }} - @yield('title')</title>

    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    @yield('css')
    @vite(['resources/sass/main.scss', 'resources/js/codebase/app.js'])
</head>
<body>
    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-modern main-content-boxed remember-theme">
        @include('layouts.includes.sidebar')
        @include('layouts.includes.header')
        <main id="main-container">
            @yield('content')
        </main>
    </div>
</body>
<script src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script>
    $(function() {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @if (session()->has('success'))
            Codebase.helpers('jq-notify', {
                align: 'right',
                from: 'top',
                type: 'success',
                icon: 'fa fa-check me-1',
                message: '{{ session()->get('success') }}'
            });
        @endif

        @if (session()->has('error'))
            Codebase.helpers('jq-notify', {
                align: 'right',
                from: 'top',
                type: 'danger',
                icon: 'fa fa-times me-1',
                message: '{{ session()->get('error') }}'
            });
        @endif

        @if (session()->has('error_message'))
            Codebase.helpers('jq-notify', {
                align: 'right',
                from: 'top',
                type: 'danger',
                icon: 'fa fa-times me-1',
                message: '{{ session()->get('error_message') }}'
            });
        @endif

        @if (session()->has('info'))
            Codebase.helpers('jq-notify', {
                align: 'right',
                from: 'top',
                type: 'info',
                icon: 'fa fa-info-circle me-1',
                message: '{{ session()->get('info') }}'
            });
        @endif

        @if (session()->has('warning'))
            Codebase.helpers('jq-notify', {
                align: 'right',
                from: 'top',
                type: 'warning',
                icon: 'fa fa-exclamation-triangle me-1',
                message: '{{ session()->get('warning') }}'
            });
        @endif

        let html = '';
        $(".nav-main-link").each(function () {
            if($(this).attr('href') !== 'javascript:void(0);') {
                html += `<option value="${$(this).find('span').text()}" data-url="${$(this).attr('href')}">${$(this).find('span').text()}</option>`;
            }
        });
        $('#sidebar-search-list').html(html);

        $(document).on("input keydown", "#page-header-search-input", function(event) {
            if(event.which === 13) {
                let val = $(this).val();
                $('#sidebar-search-list option').each(function() {
                    if($(this).val().toUpperCase() === val.toUpperCase()) {
                        window.location = $(this).data('url');
                    }
                });
            }
        });

        $(document).on("click", "#sidebar-search-submit", function(event) {
            let val = $("#page-header-search-input").val();
            $('#sidebar-search-list option').each(function() {
                if($(this).val().toUpperCase() === val.toUpperCase()) {
                    window.location = $(this).data('url');
                }
            });
        });
    });
</script>
@yield('js')
</html>
