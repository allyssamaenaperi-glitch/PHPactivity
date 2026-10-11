<?php
$errors = [];
$formError = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "") {
        $errors["email"] = "This field is required.";
    } elseif (!preg_match("/^[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/", $email)) {
        $errors["email"] = "Please enter your correct email";
    }

    if ($password === "") {
        $errors["password"] = "This field is required.";
    } elseif (!preg_match("/^.{8,}$/", $password)) {
        $errors["password"] = "Please enter at least 8 characters.";
    } elseif (!preg_match("/^.{1,20}$/", $password)) {
        $errors["password"] = "Please enter no more than 20 characters.";
    }

    if (!$errors) {
        if ($email === "allyssa@gmail.com" && $password === "maemae2980") {
            header("Location: index.php");
            exit;
        }
        $formError = "Incorrect email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.22.1/jquery.validate.min.js"
        integrity="sha512-qu7dMuIm2f0KcKZ3BOoP4c+Hn+r4E8PbD2Ro4rmKsOyheCxcwhzQpf6SojA76dn+owqfANzfTFUTkGA+HpHjOA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="login-page">

    <form id="loginForm" method="post" novalidate>
        <div id="card">

            <div class="card-title"><img src="assets/logo.jpg" alt="logo"></div>

            <div class="mb-3">
                <label class="form-label text-secondary small fw-semibold" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                <?php if (isset($errors["email"])) { echo '<label class="error">' . htmlspecialchars($errors["email"]) . '</label>'; } ?>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary small fw-semibold" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control">
                <?php if (isset($errors["password"])) { echo '<label class="error">' . htmlspecialchars($errors["password"]) . '</label>'; } ?>
            </div>

            <div id="error-msg" role="alert"><?php echo htmlspecialchars($formError); ?></div>

            <button type="submit" class="btn btn-primary w-100" id="loginBtn">Login</button>

            <div>
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>

            <p>
                NOTE:<br>
                Email = allyssa@gmail.com<br>
                Password = maemae2980
            </p>
        </div>


    </form>



    <script>

        const validEmail = "allyssa@gmail.com";
        const validPassword = "maemae2980";

        const MAX_ATTEMPTS = 5;
        const LOCKOUT_TIME_MS = 15000;

        let attempts = 0;
        let lockedUntil = 0;

        function startLockoutCountdown() {
            $("#loginBtn").prop("disabled", true);

            const tick = () => {
                const remainingSec = Math.ceil((lockedUntil - Date.now()) / 1000);

                if (remainingSec <= 0) {
                    $("#loginBtn").prop("disabled", false);
                    attempts = 0;
                    $("#error-msg").text("");
                    return;
                }

                $("#error-msg").text(`Too many attempts. Try again in ${remainingSec}s.`);
                setTimeout(tick, 1000);
            };

            tick();
        }

        $(document).ready(function () {

            $("#loginForm").validate({

                rules: {

                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 20
                    }

                },

                messages: {

                    email: {
                        email: "Please enter your correct email"
                    },
                    password: {
                        password: "Please enter your correct password"
                    }

                },

                submitHandler: function (form) {

                    $("#error-msg").text("");

                    if (Date.now() < lockedUntil) {
                        return false;
                    }

                    const email = $("#email").val().trim();
                    const password = $("#password").val();

                    if (email === validEmail && password === validPassword) {
                        form.submit(); 
                        return false;
                    }

                    attempts++;
                    const remainingTries = MAX_ATTEMPTS - attempts;

                    if (remainingTries <= 0) {
                        lockedUntil = Date.now() + LOCKOUT_TIME_MS;
                        startLockoutCountdown();
                    } else {
                        $("#error-msg").text(`Incorrect email or password.`);
                    }

                    return false;
                }

            });

        });
    </script>

</body>

</html>
