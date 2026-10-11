<?php
$errors = [];
$success = false;
$old = ["fullname" => "", "email" => "", "phone" => "", "address" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($old as $key => $v) {
        $old[$key] = trim($_POST[$key] ?? "");
    }
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirmPassword"] ?? "";

    if ($old["fullname"] === "") {
        $errors["fullname"] = "This field is required.";
    } elseif (!preg_match("/^[A-Za-z]+(?:[ .'\-][A-Za-z]+)*$/", $old["fullname"])) {
        $errors["fullname"] = "Only letters and spaces allowed";
    }

    if ($old["email"] === "") {
        $errors["email"] = "This field is required.";
    } elseif (!preg_match("/^[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/", $old["email"])) {
        $errors["email"] = "Please enter a valid email address.";
    }

    if ($old["phone"] === "") {
        $errors["phone"] = "Please enter your phone number";
    } elseif (!preg_match("/^\d+$/", $old["phone"])) {
        $errors["phone"] = "Please put your correct phone number";
    }

    if ($old["address"] === "") {
        $errors["address"] = "This field is required.";
    }

    if ($password === "") {
        $errors["password"] = "Please enter your password";
    } elseif (!preg_match("/^.{8,}$/", $password)) {
        $errors["password"] = "Password must be at least 8 characters";
    } elseif (!preg_match("/^.{1,20}$/", $password)) {
        $errors["password"] = "Password must be at most 20 characters";
    }

    if ($confirmPassword === "") {
        $errors["confirmPassword"] = "Please confirm your password";
    } elseif (!preg_match("/^.{8,}$/", $confirmPassword)) {
        $errors["confirmPassword"] = "Password must be at least 8 characters";
    } elseif (!preg_match("/^.{1,20}$/", $confirmPassword)) {
        $errors["confirmPassword"] = "Password must be at most 20 characters";
    } elseif ($confirmPassword !== $password) {
        $errors["confirmPassword"] = "Try the password again";
    }

    if (empty($_POST["termsCheck"])) {
        $errors["termsCheck"] = "This field is required.";
    }

    if (!$errors) {
        $success = true;
        $old = ["fullname" => "", "email" => "", "phone" => "", "address" => ""];
    }
}

function showError($errors, $key)
{
    if (isset($errors[$key])) {
        echo '<label class="error">' . htmlspecialchars($errors[$key]) . '</label>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
</head>

<body class="register-page">

    <div class="card shadow border-0">
        <div class="card-body p-4 p-md-5">
            <h3 class="card-title text-center mb-2 fw-bold">Create an Account</h3>
            <p class="text-muted text-center mb-4">Register now to access.</p>

            <?php if ($success) { ?>
                <div class="alert alert-success" role="alert">Registration successful! You can now <a href="login.php">log in</a>.</div>
            <?php } ?>

            <form id="registerForm" method="post" novalidate>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-semibold" for="fullname">Full name</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($old["fullname"]); ?>">
                        <?php showError($errors, "fullname"); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-semibold" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($old["email"]); ?>">
                    <?php showError($errors, "email"); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-semibold" for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($old["phone"]); ?>">
                    <?php showError($errors, "phone"); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-semibold" for="address">Delivery Address</label>
                    <input type="text" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($old["address"]); ?>">
                    <?php showError($errors, "address"); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-semibold" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                    <?php showError($errors, "password"); ?>
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary small fw-semibold" for="confirmPassword">Confirm
                        Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-control"
                        placeholder="Repeat password">
                    <?php showError($errors, "confirmPassword"); ?>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="termsCheck" name="termsCheck" required>
                    <label class="form-check-label small" for="termsCheck">
                        I agree to the <a href="#" class="text-decoration-none">Terms &amp; Conditions</a>
                    </label>
                    <?php showError($errors, "termsCheck"); ?>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold py-2 mb-3">Register</button>

                <p class="text-center small text-muted mb-0">
                    Already have an account? <a href="login.php" class="text-decoration-none fw-semibold">Log In</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            $("#registerForm").validate({

                rules: {

                    phone: {
                        required: true,
                        digits: true
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 20
                    },
                    confirmPassword: {
                        required: true,
                        minlength: 8,
                        maxlength: 20,
                        equalTo: "#password"
                    }

                },

                messages: {

                    phone: {
                        required: "Please enter your phone number",
                        digits: "Please put your correct phone number"
                    },
                    password: {
                        required: "Please enter your password",
                        minlength: "Password must be at least 8 characters",
                        maxlength: "Password must be at most 20 characters"
                    },
                    confirmPassword: {
                        required: "Please confirm your password",
                        minlength: "Password must be at least 8 characters",
                        maxlength: "Password must be at most 20 characters",
                        equalTo: "Try the password again"
                    }

                }

            });

        });
    </script>

</body>

</html>
