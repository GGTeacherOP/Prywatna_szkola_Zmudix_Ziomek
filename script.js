function showSection(sectionNumber) {
    var sections = document.querySelectorAll('.section');
    sections.forEach(function(section) {
        section.classList.remove('active');
    });
    
    var activeSection = document.querySelectorAll('.section')[sectionNumber - 1];
    activeSection.classList.add('active');
}
document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.left-section ul li');
    
    navItems.forEach(item => {
      item.addEventListener('click', function() {
        navItems.forEach(i => i.classList.remove('active-nav'));
        this.classList.add('active-nav');
      });
    });
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 100,
            behavior: 'smooth'
          });
        }
      });
    });
  });