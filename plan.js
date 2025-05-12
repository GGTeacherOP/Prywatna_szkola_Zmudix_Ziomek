document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
      row.style.opacity = '0';
      row.style.transform = 'translateY(20px)';
      row.style.transition = 'all 0.5s ease ' + (index * 0.1) + 's';
      
      setTimeout(() => {
        row.style.opacity = '1';
        row.style.transform = 'translateY(0)';
      }, 100);
    });
    
    const backBtn = document.createElement('button');
    backBtn.textContent = 'Powrót';
    backBtn.id = 'cofnij';
    backBtn.addEventListener('click', () => {
      window.history.back();
    });
    
    const header = document.querySelector('header');
    header.appendChild(backBtn);
  });