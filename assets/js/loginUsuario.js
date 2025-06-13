document.addEventListener('DOMContentLoaded', function() {
      const loginForm = document.getElementById('loginForm');
      const nombreInput = document.getElementById('nombre');
      const submitBtn = document.getElementById('submitBtn');
      const loginCard = document.getElementById('loginCard');
      const welcomeMessage = document.getElementById('welcomeMessage');
      const userName = document.getElementById('userName');

      // Create floating coffee beans
      createCoffeeBeans();
      createCoffeeGrains();
      
      // Form submission
      loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (nombreInput.value.trim()) {
            submitBtn.classList.add('loading');
            
            setTimeout(() => {
                loginCard.style.opacity = '0';
                setTimeout(() => {
                    loginCard.style.display = 'none';
                    userName.textContent = nombreInput.value;
                    welcomeMessage.style.display = 'block';
                    welcomeMessage.classList.add('animate__fadeIn');
                }, 300);
                submitBtn.classList.remove('loading');
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