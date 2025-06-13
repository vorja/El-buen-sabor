document.addEventListener('DOMContentLoaded', function() {
      const loginForm = document.getElementById('loginForm');
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');
      const submitBtn = document.getElementById('submitBtn');
      const loginCard = document.getElementById('loginCard');
      const welcomeMessage = document.getElementById('welcomeMessage');
      const loginError = document.getElementById('loginError');
      const userName = document.getElementById('userName');
      const rememberMe = document.getElementById('rememberMe');
      const recoverBtn = document.getElementById('recoverBtn');

      // Create floating coffee beans
      createCoffeeBeans();
      createCoffeeGrains();
      
      // Email validation on input
      emailInput.addEventListener('input', function() {
        validateEmail();
      });

      // Password validation on input
      passwordInput.addEventListener('input', function() {
        validatePassword();
      });

      function validateEmail() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(emailInput.value);
        
        if (!isValid && emailInput.value !== '') {
          emailInput.classList.add('is-invalid');
          return false;
        } else {
          emailInput.classList.remove('is-invalid');
          return true;
        }
      }

      function validatePassword() {
        const isValid = passwordInput.value.length > 0;
        
        if (!isValid) {
          passwordInput.classList.add('is-invalid');
          return false;
        } else {
          passwordInput.classList.remove('is-invalid');
          return true;
        }
      }

      // Form submission
      loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        
        if (isEmailValid && isPasswordValid) {
          // Show loading state
          submitBtn.classList.add('loading');
          
          // Simulate API call
          setTimeout(function() {
            submitBtn.classList.remove('loading');
            
            // Simulate login success (in a real app, this would be based on API response)
            if (emailInput.value === 'demo@cafe.com' && passwordInput.value === 'password') {
              // Store in localStorage if "Remember me" is checked
              if (rememberMe.checked) {
                localStorage.setItem('cafeToken', 'dummy-auth-token');
                localStorage.setItem('cafeUser', emailInput.value);
              }
              
              // Show success message
              loginCard.style.opacity = '0';
              setTimeout(() => {
                loginCard.style.display = 'none';
                userName.textContent = emailInput.value.split('@')[0];
                welcomeMessage.style.display = 'block';
                welcomeMessage.classList.add('animate__fadeIn');
              }, 300);
            } else {
              // Show error message
              loginError.style.display = 'block';
              loginCard.classList.add('animate__animated', 'animate__shakeX');
              setTimeout(() => {
                loginCard.classList.remove('animate__animated', 'animate__shakeX');
              }, 1000);
            }
          }, 1500);
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

      // Check if we have a stored token
      if (localStorage.getItem('cafeToken')) {
        emailInput.value = localStorage.getItem('cafeUser') || '';
        rememberMe.checked = true;
      }

      // Acción del botón recuperar contraseña
      recoverBtn.addEventListener('click', function() {
        alert('Funcionalidad de recuperación de contraseña no implementada.');
      });
    });