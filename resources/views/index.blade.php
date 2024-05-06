<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Reset Password</title>
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1523294587484-bae6cc870010?q=80&w=1902&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            background-position: center;
        }

        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-box {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <h3>Atma Bakery Reset Password Verification</h3>
    <p>{{$data['body']}}</p>
    <div class="container center-container">
        <div class="col-md-6 register-box">
            <h2 class="text-center"><b>Change Password</b></h2>
            <hr>

            @if(session('message'))
            <div class="alert alert-success">
                {{session('message')}}
            </div>
            @endif

            <form id="resetPasswordForm" method="POST" enctype="application/x-www-form-urlencoded>
                @csrf
                <div class="form-group ">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Email" required id="emailchange" value="{{$data['email']}}" disabled>
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password" required id="passwordinput">
                </div>
                <button type="button" class="btn btn-primary btn-block" id="btn-sub" onclick="change()">
                    <i class="fa fa-user"></i> Verify
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        async function change() {
            var passwordInput = document.getElementById("passwordinput").value;
            var emailInput = document.getElementById("emailchange").value;

            try {
                let response = await fetch('http://127.0.0.1:8000/api/new-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: emailInput,
                        password: passwordInput
                    })
                });

                if (response.ok) {
                    alert('Password has been changed');
                } else {
                    throw new Error('Network response was not ok');
                }
            } catch (error) {
                console.error('Error:', error);
                // Handle error response here
            }
        }
    </script>
</body>

</html>