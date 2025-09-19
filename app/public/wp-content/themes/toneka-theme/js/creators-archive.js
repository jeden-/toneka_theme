/**
 * Creators Archive Page - Infinity Scroll
 */

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for "ZOBACZ" button
    const scrollButton = document.querySelector('.toneka-scroll-button');
    if (scrollButton) {
        scrollButton.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    }

    // Infinity Scroll Implementation
    const creatorsContainer = document.getElementById('creators-list-container');
    const loadingIndicator = document.getElementById('creators-loading');
    const endMarker = document.getElementById('creators-end');
    
    if (!creatorsContainer || !window.toneka_creators_data) {
        return;
    }
    
    let currentPage = window.toneka_creators_data.current_page;
    let maxPages = window.toneka_creators_data.max_pages;
    let isLoading = false;
    let hasMoreContent = currentPage < maxPages;
    
    // Intersection Observer for infinity scroll
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting && hasMoreContent && !isLoading) {
                loadMoreCreators();
            }
        });
    }, {
        root: null,
        rootMargin: '100px',
        threshold: 0.1
    });
    
    // Create sentinel element for intersection observer
    const sentinel = document.createElement('div');
    sentinel.style.height = '1px';
    sentinel.classList.add('toneka-scroll-sentinel');
    creatorsContainer.parentNode.insertBefore(sentinel, loadingIndicator);
    observer.observe(sentinel);
    
    function loadMoreCreators() {
        if (isLoading || !hasMoreContent) {
            return;
        }
        
        isLoading = true;
        const nextPage = currentPage + 1;
        
        // Show loading indicator
        loadingIndicator.style.display = 'block';
        
        // AJAX request
        jQuery.ajax({
            url: window.toneka_creators_data.ajax_url,
            type: 'POST',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                action: 'toneka_load_more_creators',
                page: nextPage,
                posts_per_page: window.toneka_creators_data.posts_per_page,
                nonce: window.toneka_creators_data.nonce
            },
            success: function(response) {
                if (response.success && response.data.html) {
                    // Create temporary container
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = response.data.html;
                    
                    // Append new creators with fade-in animation
                    const newCreators = tempDiv.querySelectorAll('.toneka-creator-item');
                    newCreators.forEach(function(creator, index) {
                        creator.style.opacity = '0';
                        creator.style.transform = 'translateY(20px)';
                        creatorsContainer.appendChild(creator);
                        
                        // Animate in with delay
                        setTimeout(function() {
                            creator.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                            creator.style.opacity = '1';
                            creator.style.transform = 'translateY(0)';
                        }, index * 100 + 200);
                    });
                    
                    // Update pagination data
                    currentPage = response.data.current_page;
                    maxPages = response.data.max_pages;
                    hasMoreContent = response.data.has_more;
                    
                    console.log('Creators loaded:', {
                        current_page: currentPage,
                        max_pages: maxPages,
                        has_more: hasMoreContent
                    });
                    
                } else {
                    hasMoreContent = false;
                }
                
                // Hide loading indicator
                loadingIndicator.style.display = 'none';
                
                // Show end marker if no more content
                if (!hasMoreContent) {
                    endMarker.style.display = 'block';
                    observer.unobserve(sentinel);
                }
                
                isLoading = false;
            },
            error: function(xhr, status, error) {
                console.error('Error loading creators:', error);
                loadingIndicator.style.display = 'none';
                isLoading = false;
            }
        });
    }
    
    // Initial fade-in animation for existing creators
    const initialCreators = document.querySelectorAll('.toneka-creator-item:not(.toneka-fade-in)');
    initialCreators.forEach(function(creator, index) {
        creator.style.opacity = '0';
        creator.style.transform = 'translateY(20px)';
        
        setTimeout(function() {
            creator.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            creator.style.opacity = '1';
            creator.style.transform = 'translateY(0)';
        }, index * 100 + 300);
    });
});
