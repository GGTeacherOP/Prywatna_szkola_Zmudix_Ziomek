function showSection(sectionNumber) {
    var sections = document.querySelectorAll('.section');
  sections.forEach(function(section) {
      section.classList.remove('active');
  });
  var activeSection = document.querySelectorAll('.section')[sectionNumber - 1];
  if (activeSection) {
      activeSection.classList.add('active');
      window.scrollTo({
          top: activeSection.offsetTop - 100,
          behavior: 'smooth'
      });
  }
}

document.addEventListener('DOMContentLoaded', function() {

  const navItems = document.querySelectorAll('.left-section ul li');
  

  navItems.forEach((item, index) => {
      item.addEventListener('click', function() {

          navItems.forEach(i => i.classList.remove('active-nav'));
          this.classList.add('active-nav');
          showSection(index + 1);
      });
  });
  
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
          if (this.getAttribute('href') !== '#') {
              e.preventDefault();
              
              const targetId = this.getAttribute('href');
              const targetElement = document.querySelector(targetId);
              
              if (targetElement) {
                  window.scrollTo({
                      top: targetElement.offsetTop - 100,
                      behavior: 'smooth'
                  });
              }
          }
      });
  });
  
  if (navItems.length > 0) {
      navItems[0].classList.add('active-nav');
  }
  showSection(1);
});
        // dzialanie kalkulatora
        const calculatorBtn = document.getElementById('calculatorBtn');
        const calculatorPopup = document.getElementById('calculatorPopup');
        const closeCalculator = document.getElementById('closeCalculator');
        const calculatorForm = document.getElementById('calculatorForm');
        const calculatorResult = document.getElementById('calculatorResult');
        
        // Toggle popup visibility
        calculatorBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            calculatorPopup.classList.toggle('active');
            calculatorForm.reset();
            calculatorResult.style.display = 'none';
        });
        
        // Close popup
        closeCalculator.addEventListener('click', () => {
            calculatorPopup.classList.remove('active');
        });
        
        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!calculatorPopup.contains(e.target)) {
                calculatorPopup.classList.remove('active');
            }
        });
        
        // Calculate points
        calculatorForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const math = parseInt(document.getElementById('math').value) || 0;
            const polish = parseInt(document.getElementById('polish').value) || 0;
            const english = parseInt(document.getElementById('english').value) || 0;
            
            const totalPoints = Math.round(
                (math * 0.35) + (polish * 0.35) + (english * 0.3)
            );
            
            document.getElementById('totalPoints').textContent = totalPoints;
            
            // Determine admission chance
            let chance;
            if (totalPoints >= 90) {
                chance = "Bardzo wysoka";
            } else if (totalPoints >= 70) {
                chance = "Wysoka";
            } else if (totalPoints >= 50) {
                chance = "Średnia";
            } else {
                chance = "Niska";
            }
            
            document.getElementById('admissionChance').textContent = chance;
            calculatorResult.style.display = 'block';
        });

        // zmiana zdjec na stornie glownej
        let currentSlide = 0;
        const slides = document.querySelectorAll('.gallery-slide');
        const dots = document.querySelectorAll('.dot');
        
        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }
        
        function nextSlide() {
            showSlide(currentSlide + 1);
        }
        
        function prevSlide() {
            showSlide(currentSlide - 1);
        }
        
        function goToSlide(n) {
            showSlide(n);
        }
        
        // Auto slide change every 5 seconds
        setInterval(nextSlide, 5000);

        document.addEventListener('DOMContentLoaded', function() {
    const readMoreLinks = document.querySelectorAll('.read-more');
    let currentlyExpanded = null;
    
    // Funkcja zamykająca wszystkie rozwiniecia
    function collapseAll() {
        document.querySelectorAll('.news-content p.expanded').forEach(expandedPara => {
            expandedPara.classList.remove('expanded');
            const link = expandedPara.closest('.news-content').querySelector('.read-more');
            link.innerHTML = 'Czytaj więcej <i class="fas fa-arrow-right"></i>';
        });
        currentlyExpanded = null;
    }
    
    // Nasłuchiwanie kliknięć na linki "Czytaj więcej"
    readMoreLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const newsContent = this.closest('.news-content');
            const paragraph = newsContent.querySelector('p');
            
            // Jeśli kliknięto już rozwinięty element - zwiń
            if (paragraph === currentlyExpanded) {
                collapseAll();
                return;
            }
            
            // Zamknij poprzednie rozwiniecie
            collapseAll();
            
            // Rozwiń aktualne
            paragraph.classList.add('expanded');
            this.innerHTML = 'Zwiń <i class="fas fa-arrow-up"></i>';
            currentlyExpanded = paragraph;
        });
    });
    
    // Nasłuchiwanie zmiany zakładek
    const tabLinks = document.querySelectorAll('.left-section li');
    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            collapseAll();
        });
    });
});