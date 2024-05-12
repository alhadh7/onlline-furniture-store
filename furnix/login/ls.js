document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("login-form");
    const signupForm = document.getElementById("signup-form");
    const loginLink = document.getElementById("login-link");
    const signupLink = document.getElementById("signup-link");

    loginForm.classList.add("active");

    loginLink.addEventListener("click", function (event) {
        event.preventDefault();
        loginForm.classList.add("active");
        signupForm.classList.remove("active");
    });

    loginForm.addEventListener("submit", function (event) {
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
        signupForm.classList.add("active");
        loginForm.classList.remove("active");
    });

    signupForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const username = signupForm.querySelector('input[name="username"]').value;
        const email = signupForm.querySelector('input[name="email"]').value;
        const password = signupForm.querySelector('input[name="password"]').value;

        if (!username || !email || !password) {
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