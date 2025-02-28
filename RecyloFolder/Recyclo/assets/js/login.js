const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".login-container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

// Add input event listeners for glowing icons
document.querySelectorAll('.input-field input').forEach(input => {
    input.addEventListener('input', function() {
        const icon = this.previousElementSibling;
        if (this.value.length > 0) {
            icon.classList.add('glow');
        } else {
            icon.classList.remove('glow');
        }
    });
});

// Password validation
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirm-password');
const errorMessage = document.querySelector('.password-error');
const signUpForm = document.querySelector('.sign-up-form');

function validatePassword() {
    if(password.value !== confirmPassword.value) {
        errorMessage.textContent = "Passwords do not match";
        errorMessage.classList.add('visible');
        return false;
    } else {
        errorMessage.classList.remove('visible');
        return true;
    }
}

// Remove the timeout/debounce event listeners
confirmPassword.addEventListener('input', validatePassword);
password.addEventListener('input', validatePassword);

// Update form submission with both error and success popups
signUpForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Check if all fields are filled
    const inputs = this.querySelectorAll('input[required]');
    let allFilled = true;
    
    inputs.forEach(input => {
        if (!input.value) {
            allFilled = false;
        }
    });

    if (!allFilled) {
        Swal.fire({
            title: 'Missing Information!',
            text: 'Please fill in all required fields',
            icon: 'warning',
            confirmButtonColor: '#3C6255',
            background: '#fff'
        });
        return;
    }

    if (!validatePassword()) {
        Swal.fire({
            title: 'Passwords Do Not Match!',
            text: 'Please make sure your passwords match',
            icon: 'error',
            confirmButtonColor: '#3C6255',
            background: '#fff'
        });
    } else {
        // Success popup
        Swal.fire({
            title: 'Successfully Signed Up!',
            text: 'You can now login to your account',
            icon: 'success',
            confirmButtonColor: '#3C6255',
            background: '#fff',
            customClass: {
                title: 'swal-title',
                content: 'swal-text'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Remove sign-up mode to show login form
                container.classList.remove("sign-up-mode");
                // Optional: Reset the form
                this.reset();
            }
        });
    }
});
