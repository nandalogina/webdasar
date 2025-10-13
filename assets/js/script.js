 
// Form validation and interactive features
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                if(alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 300);
        }, 5000);
    });

    // Password confirmation validation
    const passwordForm = document.querySelector('form');
    if(passwordForm) {
        const passwordInput = passwordForm.querySelector('input[name="password"]');
        const confirmPasswordInput = passwordForm.querySelector('input[name="confirm_password"]');
        
        if(passwordInput && confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                if(passwordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity('Passwords do not match');
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            });
        }
    }

    // Add loading state to buttons
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if(submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                submitBtn.disabled = true;
            }
        });
    });
});

// Confirm delete function
function confirmDelete() {
    return confirm('Are you sure you want to delete this item? This action cannot be undone.');
}