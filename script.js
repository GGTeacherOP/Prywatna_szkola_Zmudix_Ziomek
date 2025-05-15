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