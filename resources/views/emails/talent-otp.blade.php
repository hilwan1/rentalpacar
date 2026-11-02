<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Kata Sandi - RentalPacar</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; -webkit-font-smoothing: antialiased;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 540px; background-color: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    <!-- Brand Header -->
                    <tr>
                        <td align="center" style="padding: 32px 24px 20px 24px; border-bottom: 1px solid #f1f5f9;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; width: 44px; height: 44px; line-height: 44px; border-radius: 14px; background: linear-gradient(135deg, #e11d48, #be123c); color: #ffffff; font-weight: 800; font-size: 20px; text-align: center;">
                                            RP
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 10px;">
                                        <span style="font-size: 18px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px;">
                                            Rental<span style="color: #e11d48;">Pacar</span>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 32px 32px 24px 32px;">
                            <h1 style="margin: 0 0 12px 0; font-size: 20px; font-weight: 800; color: #0f172a; line-height: 1.3;">
                                Reset Kata Sandi Akun Talent
                            </h1>
                            <p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                                Halo <strong style="color: #0f172a;">{{ $talent->name }}</strong>, kami menerima permintaan untuk mengatur ulang kata sandi akun Talent RentalPacar yang terdaftar pada email ini.
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                                Masukkan 6 digit kode OTP (One-Time Password) berikut pada halaman verifikasi di aplikasi untuk melanjutkan:
                            </p>

                            <!-- OTP Box -->
                            <div style="background-color: #fff1f2; border: 1.5px dashed #fecdd3; border-radius: 16px; padding: 20px; text-align: center; margin: 0 0 24px 0;">
                                <span style="font-size: 11px; font-weight: 700; color: #9f1239; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 6px;">
                                    Kode OTP Verifikasi
                                </span>
                                <span style="font-family: 'Courier New', Courier, monospace; font-size: 34px; font-weight: 800; color: #e11d48; letter-spacing: 8px; display: inline-block;">
                                    {{ $otp }}
                                </span>
                                <p style="margin: 8px 0 0 0; font-size: 12px; color: #e11d48; font-weight: 600;">
                                    ⏳ Berlaku selama 15 menit
                                </p>
                            </div>

                            <!-- Security Notice -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin: 0 0 24px 0;">
                                <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #64748b;">
                                    🔒 <strong>Peringatan Keamanan:</strong> Jangan bagikan kode OTP ini kepada siapa pun, termasuk pihak yang mengaku sebagai admin RentalPacar. Jika kamu tidak merasa meminta reset kata sandi, abaikan email ini dan akunmu tetap aman.
                                </p>
                            </div>

                            <p style="margin: 0; font-size: 13px; line-height: 1.6; color: #64748b;">
                                Butuh bantuan lain? Hubungi admin resmi RentalPacar melalui WhatsApp yang tertera di website.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 20px 32px; background-color: #f8fafc; border-top: 1px solid #f1f5f9; font-size: 11px; color: #94a3b8; line-height: 1.5;">
                            <p style="margin: 0 0 4px 0;">Email otomatis dari sistem otentikasi RentalPacar.</p>
                            <p style="margin: 0;">&copy; {{ date('Y') }} RentalPacar. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

