document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.querySelector('.toneka-toggle-description');
    const fullDescription = document.querySelector('.toneka-full-description');
    const toggleText = document.querySelector('.toggle-text');
    
    if (toggleButton && fullDescription && toggleText) {
        toggleButton.addEventListener('click', function() {
            const currentState = toggleButton.getAttribute('data-state');
            
            if (currentState === 'collapsed') {
                // Rozwiń opis
                fullDescription.style.display = 'block';
                setTimeout(() => {
                    fullDescription.classList.add('expanded');
                }, 10);
                
                toggleButton.setAttribute('data-state', 'expanded');
                toggleText.textContent = 'ZWIŃ';
                
            } else {
                // Zwiń opis
                fullDescription.classList.remove('expanded');
                
                setTimeout(() => {
                    fullDescription.style.display = 'none';
                }, 300); // Czas trwania animacji
                
                toggleButton.setAttribute('data-state', 'collapsed');
                toggleText.textContent = 'WIĘCEJ';
            }
        });
    }
});
























