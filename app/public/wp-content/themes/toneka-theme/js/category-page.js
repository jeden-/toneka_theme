/**
 * Category Page JavaScript
 * Handles smooth scrolling and AJAX category filtering
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('TONEKA: Category page JS loaded');
    
    // Check if we have the required parameters
    if (typeof toneka_category_params === 'undefined') {
        console.error('TONEKA: Category params not loaded');
        return;
    }
    
    let currentCategoryId = toneka_category_params.current_category_id;
    let isLoading = false;
    
    // Smooth scroll for filter button
    const filterButton = document.querySelector('.toneka-filter-button');
    const productsSection = document.getElementById('products-section');
    
    if (filterButton && productsSection) {
        filterButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            console.log('TONEKA: Filter button clicked, scrolling to products');
            
            productsSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }
    
    // AJAX Category Filtering
    function filterCategory(categoryId, pushState = true) {
        if (isLoading || categoryId == currentCategoryId) {
            return;
        }
        
        isLoading = true;
        console.log('TONEKA: Filtering to category ID:', categoryId);
        
        // Special case: if category ID is 0 (WSZYSTKO), handle via AJAX
        if (categoryId == 0) {
            console.log('TONEKA: Filtering to WSZYSTKO (shop page) via AJAX');
            // Continue with AJAX request for category_id = 0
        }
        
        // AJAX request
        jQuery.ajax({
            url: toneka_category_params.ajax_url,
            type: 'POST',
            data: {
                action: 'toneka_filter_category',
                category_id: categoryId,
                nonce: toneka_category_params.nonce,
                paged: 1
            },
            success: function(response) {
                console.log('TONEKA: Filter success:', response);
                
                if (response.success) {
                    // Update filter section with animation
                    const filterContainer = document.querySelector('.toneka-category-filter-container');
                    if (filterContainer) {
                        filterContainer.classList.add('toneka-fade-out');
                        
                        setTimeout(() => {
                            filterContainer.innerHTML = response.data.filter_html;
                            filterContainer.classList.remove('toneka-fade-out');
                            filterContainer.classList.add('toneka-fade-in');
                            
                            // Re-attach event listeners
                            attachFilterListeners();
                            
                            // Remove fade-in class after animation
                            setTimeout(() => {
                                filterContainer.classList.remove('toneka-fade-in');
                            }, 300);
                        }, 200);
                    }
                    
                    // Update products grid
                    const productsGridContainer = document.querySelector('.toneka-products-grid, .toneka-no-products');
                    if (productsGridContainer) {
                        // Find the parent that contains the products
                        const productsParent = productsGridContainer.parentNode;
                        
                        // Replace products content
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = response.data.products_html;
                        
                        // Remove old products
                        const oldProducts = productsParent.querySelector('.toneka-products-grid, .toneka-no-products');
                        const oldPagination = productsParent.querySelector('.toneka-ajax-pagination, .woocommerce-pagination');
                        
                        if (oldProducts) oldProducts.remove();
                        if (oldPagination) oldPagination.remove();
                        
                        // Add new products with fade-in animation
                        while (tempDiv.firstChild) {
                            const element = tempDiv.firstChild;
                            productsParent.appendChild(element);
                            
                            // Add fade-in animation to product cards
                            if (element.classList && element.classList.contains('toneka-products-grid')) {
                                const productCards = element.querySelectorAll('.toneka-product-card');
                                productCards.forEach((card, index) => {
                                    // Mark as AJAX loaded to prevent CSS animation conflict
                                    card.classList.add('toneka-ajax-loaded');
                                    card.style.opacity = '0';
                                    
                                    setTimeout(() => {
                                        card.style.transition = 'opacity 0.3s ease';
                                        card.style.opacity = '1';
                                    }, index * 100 + 50);
                                });
                            }
                        }
                        
                        // Attach pagination listeners if present
                        attachPaginationListeners();
                        
                        // Trigger event for other scripts (like product card listeners)
                        document.dispatchEvent(new CustomEvent('toneka_products_updated'));
                    }
                    
                    // Update current category
                    currentCategoryId = response.data.category_id;
                    
                    // Update URL if requested
                    if (pushState && window.history && window.history.pushState) {
                        if (categoryId == 0) {
                            // Special case for WSZYSTKO - redirect to shop page
                            window.history.pushState(
                                {categoryId: 0}, 
                                'SKLEP', 
                                toneka_category_params.shop_url
                            );
                        } else {
                            const categoryLink = document.querySelector(`[data-category-id="${categoryId}"]`);
                            if (categoryLink && categoryLink.dataset.categoryUrl) {
                                window.history.pushState(
                                    {categoryId: categoryId}, 
                                    response.data.category_name, 
                                    categoryLink.dataset.categoryUrl
                                );
                            }
                        }
                    }
                    
                    // Update page title
                    document.title = response.data.category_name + ' - ' + document.title.split(' - ').slice(1).join(' - ');
                    
                } else {
                    console.error('TONEKA: Filter error:', response.data);
                    showErrorMessage('Błąd podczas filtrowania produktów.');
                }
            },
            error: function(xhr, status, error) {
                console.error('TONEKA: AJAX error:', error);
                showErrorMessage('Błąd połączenia. Spróbuj ponownie.');
            },
            complete: function() {
                isLoading = false;
            }
        });
    }
    
    // Show error message
    function showErrorMessage(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'toneka-filter-error';
        errorDiv.innerHTML = '<p>' + message + '</p>';
        
        const productsSection = document.getElementById('products-section');
        if (productsSection) {
            productsSection.appendChild(errorDiv);
            
            // Remove after 5 seconds
            setTimeout(function() {
                errorDiv.remove();
            }, 5000);
        }
    }
    
    // Attach filter listeners
    function attachFilterListeners() {
        const filterLinks = document.querySelectorAll('.toneka-category-filter-item a[data-category-id]');
        
        filterLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const categoryId = parseInt(this.dataset.categoryId);
                if (categoryId >= 0) {
                    filterCategory(categoryId, true);
                }
            });
        });
    }
    
    // Attach pagination listeners
    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('.toneka-ajax-pagination a[data-page]');
        
        paginationLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const page = parseInt(this.dataset.page);
                if (page && !isLoading) {
                    loadPage(page);
                }
            });
        });
    }
    
    // Load specific page
    function loadPage(page) {
        if (isLoading) return;
        
        isLoading = true;
        
        jQuery.ajax({
            url: toneka_category_params.ajax_url,
            type: 'POST',
            data: {
                action: 'toneka_filter_category',
                category_id: currentCategoryId,
                nonce: toneka_category_params.nonce,
                paged: page
            },
            success: function(response) {
                if (response.success) {
                    // Update only products, not the filter
                    const productsGridContainer = document.querySelector('.toneka-products-grid, .toneka-no-products');
                    if (productsGridContainer) {
                        const productsParent = productsGridContainer.parentNode;
                        
                        // Replace products content
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = response.data.products_html;
                        
                        // Remove old products and pagination
                        const oldProducts = productsParent.querySelector('.toneka-products-grid, .toneka-no-products');
                        const oldPagination = productsParent.querySelector('.toneka-ajax-pagination, .woocommerce-pagination');
                        
                        if (oldProducts) oldProducts.remove();
                        if (oldPagination) oldPagination.remove();
                        
                        // Add new products
                        while (tempDiv.firstChild) {
                            productsParent.appendChild(tempDiv.firstChild);
                        }
                        
                        attachPaginationListeners();
                    }
                } else {
                    showErrorMessage('Błąd podczas ładowania strony.');
                }
            },
            error: function() {
                showErrorMessage('Błąd połączenia. Spróbuj ponownie.');
            },
            complete: function() {
                isLoading = false;
            }
        });
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.categoryId) {
            filterCategory(event.state.categoryId, false);
        }
    });
    
    // Initial attachment of event listeners
    attachFilterListeners();
    attachPaginationListeners();
    
    console.log('TONEKA: Category page with AJAX filtering ready');
});
