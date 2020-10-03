<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="">

        td.email-content p,h2.greeting{
            font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
            box-sizing: border-box;
            color: #3d4852;
            font-size: 16px;
            line-height: 1.5em;
            margin-top: 0;
            text-align: left;
        }

        h2.greeting{
            font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
            box-sizing: border-box;
            color: #3d4852;
            font-size: 16px;
            line-height: 1.5em;
            text-align: left;
        }

        div.mail-body{
            margin-top: 30px !important;
            margin-bottom: 30px !important;
        }

        .email-footer{
            background-color: {{env('APP_EMAIL_BACKGROUND')}} !important;
            display: flex;
            justify-content: center !important;
            padding: 8px !important;
        }

        .email-footer  p{
            margin: 0 !important;
            font-size: 12px;
            color: #fff !important;
            text-align: center !important;
        }

        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }


    </style>
    <title></title>

</head>
<body>


<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0">

                <!-- Email Header -->
                <tr>
                    <td class="header" style="background-color: {{env('APP_EMAIL_BACKGROUND')}} !important;">
                        <p style="color: #fff !important; text-align: center !important;">
                            <img src="{{env('APP_LOGO')}}" alt="">
                        </p>
                    </td>
                </tr>

                <!-- Email Body -->
                <tr>
                    <td class="body" width="100%">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell email-content">
                                    <div class="mail-body">
                                        <h2 class="greeting">Hello {{ $render_data['recipient'] }} </h2>

                                        @yield('email-content')

                                        <p>Regards,</p>
                                        <p>
                                            <strong>{{config('app.name')}}</strong>
                                        </p>

                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Email Footer -->
                <tr class="email-footer">
                    <td style="width: 100%; text-align: center; color: #fff;">
                        &copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
