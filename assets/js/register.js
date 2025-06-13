document.addEventListener('DOMContentLoaded', function() {
      const loginForm = document.getElementById('loginForm');
      const cedulaInput = document.getElementById('Cedula');
      const nameInput = document.getElementById('name');
      const emailInput = document.getElementById('email');
      const phoneInput = document.getElementById('phone');
      const passwordInput = document.getElementById('password');
      const submitBtn = document.getElementById('submitBtn');
      const loginCard = document.getElementById('loginCard');
      const welcomeMessage = document.getElementById('welcomeMessage');
      const loginError = document.getElementById('loginError');
      const userName = document.getElementById('userName');

      // Create floating coffee beans
      createCoffeeBeans();
      createCoffeeGrains();
      
      // Validaciones
      function validateCedula() {
        const isValid = cedulaInput.value.length > 0;
        cedulaInput.classList.toggle('is-invalid', !isValid);
        return isValid;
      }

      function validateName() {
        const isValid = nameInput.value.length > 0;
        nameInput.classList.toggle('is-invalid', !isValid);
        return isValid;
      }

      function validatePhone() {
        const phoneRegex = /^[0-9]{10}$/;
        const isValid = phoneRegex.test(phoneInput.value);
        phoneInput.classList.toggle('is-invalid', !isValid);
        return isValid;
      }

      function validateEmail() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(emailInput.value);
        emailInput.classList.toggle('is-invalid', !isValid);
        return isValid;
      }

      function validatePassword() {
        const isValid = passwordInput.value.length >= 6;
        passwordInput.classList.toggle('is-invalid', !isValid);
        return isValid;
      }

      // Event listeners para validación en tiempo real
      cedulaInput.addEventListener('input', validateCedula);
      nameInput.addEventListener('input', validateName);
      emailInput.addEventListener('input', validateEmail);
      phoneInput.addEventListener('input', validatePhone);
      passwordInput.addEventListener('input', validatePassword);

      // Manejo del formulario
      loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const validations = [
          validateCedula(),
          validateName(),
          validateEmail(),
          validatePhone(),
          validatePassword()
        ];
        
        if (validations.every(Boolean)) {
          submitBtn.classList.add('loading');
          
          // Simulación de envío
          setTimeout(function() {
            submitBtn.classList.remove('loading');
            
            // Simulación de registro exitoso
            loginCard.style.opacity = '0';
            setTimeout(() => {
              loginCard.style.display = 'none';
              userName.textContent = nameInput.value.split(' ')[0];
              welcomeMessage.style.display = 'block';
              welcomeMessage.classList.add('animate__fadeIn');
            }, 300);
            
          }, 1500);
        } else {
          loginCard.classList.add('animate__animated', 'animate__shakeX');
          setTimeout(() => {
            loginCard.classList.remove('animate__animated', 'animate__shakeX');
          }, 1000);
        }
      });

      // Create animated coffee beans in the background
      function createCoffeeBeans() {
        const particles = document.getElementById('particles');
        const isSmallScreen = window.innerWidth < 768;
        const numberOfBeans = isSmallScreen ? 8 : 15;
        
        // Clear existing beans
        particles.innerHTML = '';
        
        for (let i = 0; i < numberOfBeans; i++) {
          const bean = document.createElement('div');
          bean.classList.add('coffee-bean');
          
          // Random size between 20 and 60px
          const size = Math.random() * 40 + 20;
          bean.style.width = `${size}px`;
          bean.style.height = `${size}px`;
          
          // Random position
          bean.style.top = `${Math.random() * 100}%`;
          bean.style.left = `${Math.random() * 100}%`;
          
          // Random rotation
          const rotation = Math.random() * 360;
          bean.style.transform = `rotate(${rotation}deg)`;
          
          // Random animation duration
          const duration = Math.random() * 20 + 10;
          bean.style.animation = `float ${duration}s linear infinite`;
          
          // Random delay
          bean.style.animationDelay = `${Math.random() * 5}s`;
          
          particles.appendChild(bean);
        }
      }

      // Function to create coffee grains
      function createCoffeeGrains() {
        const grainsContainer = document.getElementById('coffeeGrains');
        const isSmallScreen = window.innerWidth < 768;
        const numberOfGrains = isSmallScreen ? 10 : 20;
        
        // Clear existing grains
        grainsContainer.innerHTML = '';
        
        for (let i = 0; i < numberOfGrains; i++) {
          const grain = document.createElement('div');
          grain.classList.add('coffee-bean');
          const size = Math.random() * 20 + 10;
          grain.style.width = `${size}px`;
          grain.style.height = `${size}px`;
          grain.style.top = `${Math.random() * 100}%`;
          grain.style.left = `${Math.random() * 100}%`;
          const rotation = Math.random() * 360;
          grain.style.transform = `rotate(${rotation}deg)`;
          const duration = Math.random() * 20 + 10;
          grain.style.animation = `float ${duration}s linear infinite`;
          grain.style.animationDelay = `${Math.random() * 5}s`;
          grainsContainer.appendChild(grain);
        }
      }
    });