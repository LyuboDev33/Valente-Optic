<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Потвърждение на поръчка</title>
</head>

<body style="margin:0; padding:0; background:#f4f6f9; font-family: Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                   style="background:#ffffff; border-radius:10px; padding:40px; box-shadow:0 5px 15px rgba(0,0,0,0.05);">

                <!-- IMAGE -->
                <tr>
                    <td align="center" style="padding-bottom:25px;">
                        <img src="{{ url('/') }}/assets/imgs/success-checkout.png"
                             alt="Success"
                             style="height:120px;">
                    </td>
                </tr>

                <!-- TITLE -->
                <tr>
                    <td style="text-align:center; padding-bottom:20px;">
                        <h2 style="margin:0; color:#222;">
                            Благодарим ви за направената поръчка!
                        </h2>
                    </td>
                </tr>

                <!-- TEXT -->
                <tr>
                    <td style="color:#555; font-size:15px; line-height:1.6; text-align:center;">
                        Здравейте <strong>{{ $order->first_name }} {{ $order->last_name }}</strong>,<br><br>

                        Получихме вашата поръчка и тя се обработва.<br>
                        Ще се свържем с вас при нужда от допълнителна информация.
                    </td>
                </tr>

                <!-- ORDER INFO -->
                <tr>
                    <td style="padding-top:25px; font-size:15px; color:#333; text-align:center;">
                        <p style="margin:5px 0;">
                            <strong>Номер на поръчка:</strong> {{ $order->order_number }}
                        </p>

                    </td>
                </tr>

                <!-- FOOTER NOTE -->
                <tr>
                    <td style="padding-top:30px; font-size:13px; color:#888; text-align:center;">
                        Ако имате въпроси, отговорете на този имейл.
                    </td>
                </tr>

                <tr>
                    <td style="padding-top:20px; font-size:12px; color:#aaa; text-align:center; border-top:1px solid #eee;">
                        © {{ date('Y') }} Вашият магазин. Всички права запазени.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
