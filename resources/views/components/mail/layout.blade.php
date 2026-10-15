<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width"></head>
<body style="margin:0;padding:24px;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;color:#111;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#fff;">
        <tr><td style="background:#262424;padding:20px 28px;"><span style="color:#e8391c;font-size:24px;font-weight:bold;letter-spacing:1px;">KODU</span></td></tr>
        <tr><td style="padding:28px;font-size:14px;line-height:1.5;">
            {{ $slot }}
        </td></tr>
        <tr><td style="padding:16px 28px;background:#293239;color:#dbd7d7;font-size:12px;">
            {{ config('kodu.contact.email') }} · {{ config('kodu.contact.phone') }} · {{ config('kodu.contact.address') }}
        </td></tr>
    </table>
</body>
</html>
