<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Verifikasi Akun Anda</title>
    <style>
        table,
        tr,
        td {
            border: solid 1px;
            border-collapse: collapse;
        }

        td {
            padding: 1rem;
        }
    </style>
</head>

<body>
    <div class="container border border-4">
        <div class="d-flex justify-content-center p-4" style="background-color: #16b842; color: white;">
            <center>
                <h1 style="color: white;">Ganti password anda</h2>
            </center>
        </div>

        <div class="text-start">
            <!-- Greetings -->
            <div class="mt-4" style="margin-left: 2rem;">
                <h2>Halo <strong>{{ $data['email'] }}</strong></h2>
            </div>

            <!-- What's going on? -->
            <div class="mt-4" style="margin-left: 2rem;">
                <p style="font-size: 20px;">
                    Anda telah melakukan Permintaan ganti password <strong>Atma Bakery</strong> menggunakan email ini. <br>
                    Berikut adalah data anda:
                </p>

                < </div>

                    <!-- Prompt -->
                    <div style="margin-left: 2rem; margin-top: 2rem;">
                        <h4>Klik link dibawah ini untuk melakukan verifikasi akun anda</h4>
                    </div>

                    <!-- Verify Button -->
                    <div class="d-flex justify-content-center" style="margin-left: 2rem;">
                        {{ $data['url'] }}
                    </div>
            </div>

            <footer style="margin-left: 2rem; margin-top: 4rem;">
                <h4>Terima Kasih telah melakukan registrasi</h4>
            </footer>
        </div>
</body>

</html>