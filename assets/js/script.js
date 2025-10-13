// Form validation and interactive features
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            const password = this.value;
            const strengthIndicator = this.parentNode.querySelector('.password-strength');
            
            if (!strengthIndicator) {
                const indicator = document.createElement('div');
                indicator.className = 'password-strength mt-1';
                this.parentNode.appendChild(indicator);
            }
            
            const strength = checkPasswordStrength(password);
            updateStrengthIndicator(strengthIndicator, strength);
        });
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    return strength;
}

function updateStrengthIndicator(indicator, strength) {
    const colors = ['danger', 'danger', 'warning', 'warning', 'success', 'success'];
    const texts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    
    indicator.innerHTML = `
        <div class="progress" style="height: 5px;">
            <div class="progress-bar bg-${colors[strength]}" 
                 style="width: ${(strength / 5) * 100}%"></div>
        </div>
        <small class="text-${colors[strength]}">${texts[strength]}</small>
    `;
}