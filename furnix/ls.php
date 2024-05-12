<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ls.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <title>Login and Sign Up</title>
    <style>
        .bg-image-vertical {
            position: relative;
            overflow: hidden;
            background-repeat: no-repeat;
            background-position: right center;
            background-size: auto 100%;
        }

        @media (min-width: 1025px) {
            .h-custom-2 {
                height: 100%;
            }
        }
    </style>
</head>

<body>

    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 text-black">

                    <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

                        <form id="login-form" action="login.php" method="post" style="width: 23rem;" >

                            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Log in</h3>

                            <div class="form-outline mb-4">
                                <input type="email" id="loginEmail" name="email" class="form-control form-control-lg" />
                                <label class="form-label" for="loginEmail">Email address</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" id="loginPassword" name="password" class="form-control form-control-lg" />
                                <label class="form-label" for="loginPassword">Password</label>
                            </div>

                            <div class="pt-1 mb-4">
                                <button class="btn btn-info btn-lg btn-block" type="submit" id="loginButton">Login</button>
                            </div>

                            <p>Don't have an account? <a href="#!" id="signup-link" class="link-info">Register here</a></p>

                        </form>

                        <form id="signup-form" action="signup.php" method="post" style="width: 23rem; display: none;">

                            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign Up</h3>
                            
                            <div class="form-outline mb-4">
                                <input type="text" id="signupname" name="username" class="form-control form-control-lg" />
                                <label class="form-label" for="signupname">Name</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="email" id="signupEmail" name="email" class="form-control form-control-lg" />
                                <label class="form-label" for="signupEmail">Email address</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" id="signupPassword" name="password" class="form-control form-control-lg" />
                                <label class="form-label" for="signupPassword">Password</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" id="signupPhone" name="phone" class="form-control form-control-lg" />
                                <label class="form-label" for="signupPhone">Phone Number</label>
                            </div>
                           <div class="form-outline mb-4">
                            		<textarea id="signupAddress" name="address" rows="4" cols="50"></textarea>
                                <label class="form-label" for="signupAddress">address</label>
                            </div>
                            <div class="form-outline mb-4">
                            		<textarea id="signuppincode" name="pincode" rows="4" cols="50"></textarea>
                                <label class="form-label" for="signupAddress">pincode</label>
                            </div>
                            <div class="form-outline mb-4">
                            		<textarea id="signupstate" name="state" rows="4" cols="50"></textarea>
                                <label class="form-label" for="signupAddress">state</label>
                            </div>

                            <div class="pt-1 mb-4">
                                <button class="btn btn-info btn-lg btn-block" type="submit" id="signupButton">Signup</button>
                            </div>

                            <p>Already have an account? <a href="#!" id="login-link" class="link-info">login here</a></p>

                        </form>

                    </div>

                </div>
                <div class="col-sm-6 px-0 d-none d-sm-block">
                    <img src="images/login.jpg"
                        alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loginForm = document.getElementById("login-form");
            const signupForm = document.getElementById("signup-form");
            const loginLink = document.getElementById("login-link");
            const signupLink = document.getElementById("signup-link");

            loginForm.classList.add("active");

            loginLink.addEventListener("click", function (event) {
                event.preventDefault();
                loginForm.style.display = "block";
                signupForm.style.display = "none";
            });

            loginButton.addEventListener("click", function (event) {
                event.preventDefault();
                const email = loginForm.querySelector('input[name="email"]').value;
                const password = loginForm.querySelector('input[name="password"]').value;

                if (!email || !password) {
                    alert("Please fill in all fields.");
                    return;
                }
                if (!isValidEmail(email)) {
            alert("Please enter a valid email address.");
            return;
        }
            loginForm.submit();
            });

            signupLink.addEventListener("click", function (event) {
                event.preventDefault();
                signupForm.style.display = "block";
                loginForm.style.display = "none";
            });

            signupButton.addEventListener("click", function (event) {
                event.preventDefault();
            const username = signupForm.querySelector('input[name="username"]').value;
            const email = signupForm.querySelector('input[name="email"]').value;
            const password = signupForm.querySelector('input[name="password"]').value;
            const phone = signupForm.querySelector('input[name="phone"]').value;
                if (!email || !password || !phone) {
                    alert("Please fill in all fields.");
                    return;
                }
                if (!isValidEmail(email)) {
            alert("Please enter a valid email address.");
            return;
        }
            signupForm.submit();
            });
            function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
        });
    </script>
</body>

</html>
