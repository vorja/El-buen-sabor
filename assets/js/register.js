document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('customer-form');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('contraseña');
    const successMessage = document.getElementById('success-message');
    const registrationForm = document.getElementById('registration-form');
    const newRegistrationBtn = document.getElementById('new-registration');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        clearErrors();
        
        if (!validateForm()) {
            return;
        }

        const formData = new FormData(this);
        
        Swal.fire({
            title: 'Registrando...',
            text: 'Por favor espere un momento',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('../Controllers/registro.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Error parsing JSON:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Registro Exitoso!',
                    text: 'Gracias por registrarte en El Buen Sabor',
                    confirmButtonColor: '#6F4E37',
                    confirmButtonText: 'Continuar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = data.redirect;
                    }
                });
            } else {
                showErrors(data.errors || { general: 'Error en el registro' });
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el registro',
                    text: 'Por favor corrige los errores indicados',
                    confirmButtonColor: '#6F4E37'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al procesar tu registro. Por favor, intenta nuevamente.',
                confirmButtonColor: '#6F4E37'
            });
        });
    });

    newRegistrationBtn.addEventListener('click', resetForm);

    if (!localStorage.getItem('cafeteria_registros')) {
        localStorage.setItem('cafeteria_registros', JSON.stringify([]));
    }

    function validateForm() {
        let isValid = true;
        const email = document.getElementById('email').value;
        const fullname = document.getElementById('fullname').value;
        const phone = document.getElementById('phone').value;
        const cedula = document.getElementById('cedula').value;
        const password = document.getElementById('contraseña').value;

        if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            showError('email', 'Por favor ingresa un correo electrónico válido');
            isValid = false;
        }

        if (!fullname.match(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,100}$/)) {
            showError('fullname', 'El nombre debe contener solo letras y espacios');
            isValid = false;
        }

        if (!phone.match(/^[0-9]{10}$/)) {
            showError('phone', 'El teléfono debe tener 10 dígitos');
            isValid = false;
        }

        if (!cedula.match(/^[0-9]{8,10}$/)) {
            showError('cedula', 'La cédula debe tener entre 8 y 10 dígitos');
            isValid = false;
        }

        if (password.length < 8) {
            showError('contraseña', 'La contraseña debe tener al menos 8 caracteres');
            isValid = false;
        }

        return isValid;
    }

    function showError(field, message) {
        const errorElement = document.getElementById(`${field}-error`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    function showErrors(errors) {
        Object.keys(errors).forEach(field => {
            showError(field, errors[field]);
        });
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
        });
    }

    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            const field = this.id;
            const value = this.value;

            switch(field) {
                case 'email':
                    if (!value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                        showError(field, 'Por favor ingresa un correo electrónico válido');
                    }
                    break;
                case 'fullname':
                    if (!value.match(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,100}$/)) {
                        showError(field, 'El nombre debe contener solo letras y espacios');
                    }
                    break;
                case 'phone':
                    if (!value.match(/^[0-9]{10}$/)) {
                        showError(field, 'El teléfono debe tener 10 dígitos');
                    }
                    break;
                case 'cedula':
                    if (!value.match(/^[0-9]{8,10}$/)) {
                        showError(field, 'La cédula debe tener entre 8 y 10 dígitos');
                    }
                    break;
                case 'contraseña':
                    if (value.length < 8) {
                        showError(field, 'La contraseña debe tener al menos 8 caracteres');
                    }
                    break;
            }
        });

        input.addEventListener('input', function() {
            const errorElement = document.getElementById(`${this.id}-error`);
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
            }
        });
    });

    function resetForm() {
        form.reset();
        clearErrors();
        successMessage.classList.add('hidden');
        registrationForm.classList.remove('hidden');
    }
});