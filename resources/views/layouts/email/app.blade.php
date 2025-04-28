<!DOCTYPE html>
<html lang="en-US">
    <head>
        @include('layouts.email.partials.header')
    </head>
    <style>
        a:hover {
            text-decoration: underline !important;
        }
    </style>

    <body marginheight="0" topmargin="0" marginwidth="0" style="box-sizing: border-box; font-family: 'Open Sans', sans-serif; position: relative; -webkit-text-size-adjust: none; color: #718096; height: 100%; line-height: 1.4; padding: 0; width: 100% !important; margin: 0px; background-color: #f2f3f8;" leftmargin="0">
        <table
            cellspacing="0"
            border="0"
            cellpadding="0"
            width="100%"
            bgcolor="#f2f3f8"
            style="@import url(https://fonts.googleapis.com/css?family=Rubik:300, 400, 500, 700|Open + Sans:300, 400, 600, 700); font-family: 'Open Sans', sans-serif;"
        >
            <tr>
                <td>
                    <table style="background-color: #f2f3f8; max-width: 670px; margin: 0 auto;" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="height: 50px;">&nbsp;</td>
                        </tr>
                        <!-- Logo -->
                        <tr>
                            <td style="text-align: center;">
                                <a href="{{ config('app.url') }}" title="logo" target="_blank">
                                    {{-- <img width="120" src="{{ asset('Logo/blueorange.png') }}" title="logo" alt="logo" /> --}}
                                    <img width="120" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path(config('app.logo')))) }}" alt="{{ config('app.name') }} Logo">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 20px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <table
                                    width="95%"
                                    border="0"
                                    align="center"
                                    cellpadding="0"
                                    cellspacing="0"
                                    style="
                                        max-width: 670px;
                                        background: #fff;
                                        border-radius: 3px;
                                        -webkit-box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06);
                                        -moz-box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06);
                                        box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06);
                                        padding: 0 40px;
                                    "
                                >
                                    <tr>
                                        <td style="height: 40px;">&nbsp;</td>
                                    </tr>
                                    <!-- Title -->
                                    <tr>
                                        <td style="padding: 0 15px; text-align: center;">
                                            <h1 style="text-align: center; color: #1e1e2d; font-weight: 400; margin: 0; font-size: 32px; font-family: 'Rubik', sans-serif;">
                                                @yield('email_title')
                                            </h1>
                                            <span style="display: inline-block; vertical-align: middle; margin: 15px 0 30px; border-bottom: 1px solid #cecece; width: 80%;"></span>
                                        </td>
                                    </tr>

                                    <!-- Details Table -->
                                    <tr>
                                        <td>
                                            {{-- Email Content Starts --}}
                                            @yield('content')
                                            {{-- Email Content Ends --}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="height: 40px;">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        @include('layouts.email.partials.footer')
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
