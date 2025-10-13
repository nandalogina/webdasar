// Basic form validation
document.addEventListener('DOMContentLoaded', function () {
  // Password confirmation validation for registration
  const registerForm = document.querySelector('form[action*="register"]')
  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      const password = document.getElementById('password').value
      const confirmPassword = document.getElementById('confirm_password').value

      if (password !== confirmPassword) {
        alert('Passwords do not match!')
        e.preventDefault()
        return false
      }

      if (password.length < 6) {
        alert('Password must be at least 6 characters long!')
        e.preventDefault()
        return false
      }
    })
  }

  // Password change validation for profile
  const profileForm = document.querySelector('form[action*="profile"]')
  if (profileForm) {
    profileForm.addEventListener('submit', function (e) {
      const newPassword = document.getElementById('new_password').value
      const confirmPassword = document.getElementById('confirm_password').value

      if (newPassword && newPassword !== confirmPassword) {
        alert('New passwords do not match!')
        e.preventDefault()
        return false
      }

      if (newPassword && newPassword.length < 6) {
        alert('New password must be at least 6 characters long!')
        e.preventDefault()
        return false
      }
    })
  }

  // Confirm delete action
  const deleteLinks = document.querySelectorAll('a[href*="delete"]')
  deleteLinks.forEach((link) => {
    link.addEventListener('click', function (e) {
      if (!confirm('Are you sure you want to delete this user?')) {
        e.preventDefault()
        return false
      }
    })
  })

  // Simple email validation
  const emailInputs = document.querySelectorAll('input[type="email"]')
  emailInputs.forEach((input) => {
    input.addEventListener('blur', function () {
      const email = this.value
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (email && !emailRegex.test(email)) {
        alert('Please enter a valid email address.')
        this.focus()
      }
    })
  })
})
