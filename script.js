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
        const calculatorModal = document.getElementById('calculatorModal');
        const closeCalculator = document.getElementById('closeCalculator');
        const calculatorForm = document.getElementById('calculatorForm');
        const calculatorResult = document.getElementById('calculatorResult');
        
        calculatorBtn.addEventListener('click', () => {
            calculatorForm.reset();
            calculatorResult.style.display = 'none';
            calculatorModal.style.display = 'flex';
        });
        closeCalculator.addEventListener('click', () => {
            calculatorModal.style.display = 'none';
        });
        
        window.addEventListener('click', (e) => {
            if (e.target === calculatorModal) {
                calculatorModal.style.display = 'none';
            }
        });
        
        calculatorForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const math = parseInt(document.getElementById('math').value);
            const polish = parseInt(document.getElementById('polish').value);
            const english = parseInt(document.getElementById('english').value);
            const subject = parseInt(document.getElementById('subject').value);
            
            const totalPoints = Math.round(
                (math * 0.35) + (polish * 0.35) + (english * 0.15) + (subject * 0.15)
            );
            
            document.getElementById('totalPoints').textContent = totalPoints;
            
            let chance;
            if (totalPoints >= 90) {
                chance = "Bardzo wysoka (elitarne licea)";
            } else if (totalPoints >= 75) {
                chance = "Wysoka (dobre licea/technika)";
            } else if (totalPoints >= 60) {
                chance = "Średnia (technika/mało oblegane licea)";
            } else if (totalPoints >= 40) {
                chance = "Niska (zawodówki/technika z miejscami)";
            } else {
                chance = "Bardzo niska";
            }
            
            document.getElementById('admissionChance').textContent = chance;
            calculatorResult.style.display = 'block';
        });