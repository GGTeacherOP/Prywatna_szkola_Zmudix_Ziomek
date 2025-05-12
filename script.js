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