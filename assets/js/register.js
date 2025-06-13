 document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('customer-form');
        const successMessage = document.getElementById('success-message');
        const registrationForm = document.getElementById('registration-form');
        const newRegistrationBtn = document.getElementById('new-registration');
        
        // Set up event listeners
        form.addEventListener('submit', handleFormSubmit);
        newRegistrationBtn.addEventListener('click', resetForm);
        
        // Initialize localStorage if needed
        if (!localStorage.getItem('cafeteria_registros')) {
          localStorage.setItem('cafeteria_registros', JSON.stringify([]));
        }
        
        function handleFormSubmit(event) {
          event.preventDefault();
          
          // Reset error messages
          clearErrors();
          
          // Get form values
          const email = document.getElementById('email').value.trim();
          const fullname = document.getElementById('fullname').value.trim();
          const phone = document.getElementById('phone').value.trim();
          const cedula = document.getElementById('cedula').value.trim();
          
          // Validate form
          let isValid = true;
          
          // Email validation using regex
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!email) {
            showError('email', 'El correo electrónico es obligatorio');
            isValid = false;
          } else if (!emailRegex.test(email)) {
            showError('email', 'Por favor, ingresa un correo electrónico válido');
            isValid = false;
          }
          
          // Full name validation
          if (!fullname) {
            showError('fullname', 'El nombre completo es obligatorio');
            isValid = false;
          }
          
          // Phone validation (digits only, 7-10 characters)
          const phoneRegex = /^\d+$/;
          if (!phone) {
            showError('phone', 'El teléfono es obligatorio');
            isValid = false;
          } else if (!phoneRegex.test(phone)) {
            showError('phone', 'El teléfono debe contener solo dígitos');
            isValid = false;
          } else if (phone.length < 7 || phone.length > 10) {
            showError('phone', 'El teléfono debe tener entre 7 y 10 dígitos');
            isValid = false;
          }
          
          // Cedula validation (Colombian ID: 6-10 digits)
          const cedulaRegex = /^\d+$/;
          if (!cedula) {
            showError('cedula', 'La cédula es obligatoria');
            isValid = false;
          } else if (!cedulaRegex.test(cedula)) {
            showError('cedula', 'La cédula debe contener solo dígitos');
            isValid = false;
          } else if (cedula.length < 6 || cedula.length > 10) {
            showError('cedula', 'La cédula debe tener entre 6 y 10 dígitos');
            isValid = false;
          }
          
          // If all validations pass
          if (isValid) {
            // Create customer object
            const customer = {
              email,
              fullname,
              phone,
              cedula,
              registrationDate: new Date().toISOString()
            };
            
            // Save to localStorage
            const registros = JSON.parse(localStorage.getItem('cafeteria_registros'));
            registros.push(customer);
            localStorage.setItem('cafeteria_registros', JSON.stringify(registros));
            
            // Show success message
            registrationForm.classList.add('hidden');
            successMessage.classList.remove('hidden');
          }
        }
        
        function showError(fieldId, message) {
          const errorElement = document.getElementById(`${fieldId}-error`);
          errorElement.textContent = message;
          errorElement.classList.remove('hidden');
          document.getElementById(fieldId).classList.add('border-red-500');
        }
        
        function clearErrors() {
          const errorElements = document.querySelectorAll('.error-message');
          errorElements.forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
          });
          
          const inputFields = document.querySelectorAll('.input-field');
          inputFields.forEach(field => {
            field.classList.remove('border-red-500');
          });
        }
        
        function resetForm() {
          form.reset();
          clearErrors();
          successMessage.classList.add('hidden');
          registrationForm.classList.remove('hidden');
        }
      });