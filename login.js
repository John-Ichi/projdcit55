document.addEventListener('DOMContentLoaded', function() {
    // Animate the login card on load
    const card = document.querySelector('.animate-login-card');
    if (card) {
        card.classList.add('show');
    }

    // Add a click effect to the login button
    const loginBtn = document.querySelector('.animate-login-btn');
    if (loginBtn) {
        loginBtn.addEventListener('mousedown', function() {
            loginBtn.classList.add('pressed');
        });
        loginBtn.addEventListener('mouseup', function() {
            loginBtn.classList.remove('pressed');
        });
        loginBtn.addEventListener('mouseleave', function() {
            loginBtn.classList.remove('pressed');
        });
    }
});
