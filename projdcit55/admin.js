// admin.js - Header and Form functionality

document.addEventListener('DOMContentLoaded', function() {
    // Set active nav item
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    // Header scroll effect
    const header = document.querySelector('header');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
        } else {
            header.style.boxShadow = 'var(--box-shadow)';
        }
    });

    // Form validation (existing code remains the same)
    const loginForm = document.querySelector('form');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            
            // Validate username
            if (username.value.trim() === '') {
                showError(username, 'Username is required');
                isValid = false;
            }
            
            // Validate password
            if (password.value.trim() === '') {
                showError(password, 'Password is required');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    function showError(input, message) {
        const error = document.createElement('div');
        error.className = 'error-message text-danger mt-1';
        error.textContent = message;
        input.parentNode.insertBefore(error, input.nextSibling);
        input.classList.add('is-invalid');
        
        input.addEventListener('input', function() {
            error.remove();
            input.classList.remove('is-invalid');
        });
    }
});