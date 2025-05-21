function showSection(sectionNumber) {
    // Ukryj wszystkie sekcje
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.classList.remove('active');
    });

    // Pokaż wybraną sekcję
    const selectedSection = document.querySelector(`.section:nth-child(${sectionNumber})`);
    if (selectedSection) {
        selectedSection.classList.add('active');
    }

    // Zaktualizuj aktywną nawigację
    const navItems = document.querySelectorAll('.left-section li');
    navItems.forEach((item, index) => {
        if (index + 1 === sectionNumber) {
            item.classList.add('active-nav');
        } else {
            item.classList.remove('active-nav');
        }
    });

    // Przewiń do góry sekcji
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
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
        // Calculator functionality
document.addEventListener('DOMContentLoaded', function() {
    const calculatorBtn = document.getElementById('calculatorBtn');
    const calculatorPopup = document.getElementById('calculatorPopup');
    const closeCalculator = document.getElementById('closeCalculator');
    const calculatorForm = document.getElementById('calculatorForm');

    // Show calculator popup
    calculatorBtn.addEventListener('click', function() {
        calculatorPopup.classList.toggle('active');
        calculatorForm.reset();
        document.getElementById('calculatorResult').style.display = 'none';
    });

    // Close calculator popup
    closeCalculator.addEventListener('click', function() {
        calculatorPopup.classList.remove('active');
    });

    // Close popup when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target === calculatorPopup) {
            calculatorPopup.classList.remove('active');
        }
    });

    // Handle form submission
    calculatorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get subject scores
        const math = parseInt(document.getElementById('math').value) || 0;
        const polish = parseInt(document.getElementById('polish').value) || 0;
        const english = parseInt(document.getElementById('english').value) || 0;
        
        // Get checkbox values
        const volunteer = document.getElementById('volunteer').checked;
        const contest = document.getElementById('contest').checked;
        
        // Calculate points
        let totalPoints = math * 0.35 + polish * 0.35 + english * 0.3;
        
        // Add points for additional achievements
        if (volunteer) totalPoints += 5;
        if (contest) totalPoints += 10;
        
        // Display result
        document.getElementById('totalPoints').textContent = totalPoints.toFixed(1);
        
        // Determine admission chance
        let chance = 'Niska';
        if (totalPoints >= 95) chance = 'Bardzo wysoka';
        else if (totalPoints >= 80) chance = 'Wysoka';
        else if (totalPoints >= 65) chance = 'Średnia';
        else if (totalPoints >= 40) chance = 'Niska';
        
        document.getElementById('admissionChance').textContent = chance;
        document.getElementById('calculatorResult').style.display = 'block';
    });
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