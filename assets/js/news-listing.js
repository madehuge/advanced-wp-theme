/**
 * News Listing Custom JavaScript
 * 
 * This file contains custom JavaScript logic for the news listing page
 * including filtering, AJAX functionality, and user interactions.
 */

(function() {
    'use strict';

    // Configuration object
    const CONFIG = {
        selectors: {
            yearSelect: '#sortByYear',
            categorySelect: '#sortByCategory',
            loadMoreBtn: '.load-more-news',
            newsContainer: '#news-grid-container',
            loadingIndicator: '#news-loading'
        },
        choices: {
            year: {
                searchEnabled: false,
                itemSelectText: '',
                shouldSort: false,
                placeholder: true,
                placeholderValue: 'Sort by Year'
            },
            category: {
                searchEnabled: false,
                itemSelectText: '',
                shouldSort: false,
                placeholder: true,
                placeholderValue: 'All Categories'
            }
        },
        ajax: {
            action: 'load_more_news',
            method: 'POST'
        }
    };

    // Cache DOM elements
    const elements = {
        yearSelect: null,
        categorySelect: null,
        loadMoreBtn: null,
        newsContainer: null,
        loadingIndicator: null
    };

    // Initialize variables
    let yearChoices = null;
    let categoryChoices = null;
    let urlParams = null;

    /**
     * Initialize the news listing functionality
     */
    function init() {
        cacheElements();
        initializeURLParams();
        initializeChoices();
        initializeLoadMore();
    }

    /**
     * Cache DOM elements for better performance
     */
    function cacheElements() {
        elements.yearSelect = document.querySelector(CONFIG.selectors.yearSelect);
        elements.categorySelect = document.querySelector(CONFIG.selectors.categorySelect);
        elements.loadMoreBtn = document.querySelector(CONFIG.selectors.loadMoreBtn);
        elements.newsContainer = document.querySelector(CONFIG.selectors.newsContainer);
        elements.loadingIndicator = document.querySelector(CONFIG.selectors.loadingIndicator);
    }

    /**
     * Initialize URL parameters
     */
    function initializeURLParams() {
        urlParams = new URLSearchParams(window.location.search);
    }

    /**
     * Initialize Choices.js for both select boxes
     */
    function initializeChoices() {
        initializeYearChoices();
        initializeCategoryChoices();
    }

    /**
     * Initialize year select choices
     */
    function initializeYearChoices() {
        if (!elements.yearSelect) return;

        yearChoices = new Choices(elements.yearSelect, CONFIG.choices.year);
        
        // Set initial value from URL parameters
        const yearParam = urlParams.get('year');
        if (yearParam) {
            yearChoices.setChoiceByValue(yearParam);
        }

        // Add change event listener
        yearChoices.passedElement.element.addEventListener('change', filterNewsWithURL);
    }

    /**
     * Initialize category select choices
     */
    function initializeCategoryChoices() {
        if (!elements.categorySelect) return;

        categoryChoices = new Choices(elements.categorySelect, CONFIG.choices.category);
        
        // Set initial value from URL parameters
        const categoryParam = urlParams.get('category');
        if (categoryParam) {
            categoryChoices.setChoiceByValue(categoryParam);
        }

        // Add change event listener
        categoryChoices.passedElement.element.addEventListener('change', filterNewsWithURL);
    }

    /**
     * URL-based filter function
     */
    function filterNewsWithURL() {
        const selectedYear = elements.yearSelect ? elements.yearSelect.value : '';
        const selectedCategory = elements.categorySelect ? elements.categorySelect.value : '';
        
        // Build URL with query parameters
        const url = new URL(window.location);
        
        // Update or remove parameters
        updateURLParameter(url, 'year', selectedYear);
        updateURLParameter(url, 'category', selectedCategory);
        
        // Navigate to the new URL
        window.location.href = url.toString();
    }

    /**
     * Update or remove URL parameter
     * @param {URL} url - URL object
     * @param {string} param - Parameter name
     * @param {string} value - Parameter value
     */
    function updateURLParameter(url, param, value) {
        if (value) {
            url.searchParams.set(param, value);
        } else {
            url.searchParams.delete(param);
        }
    }

    /**
     * Initialize load more functionality
     */
    function initializeLoadMore() {
        if (!elements.loadMoreBtn) return;

        elements.loadMoreBtn.addEventListener('click', handleLoadMore);
    }

    /**
     * Handle load more button click
     */
    function handleLoadMore() {
        const currentPage = parseInt(this.dataset.page);
        const maxPages = parseInt(this.dataset.maxPages);
        const selectedYear = this.dataset.year || '';
        const selectedCategory = this.dataset.category || '';
        
        if (currentPage > maxPages || !elements.newsContainer) {
            return;
        }

        showLoadingState(this);
        loadMoreNews(currentPage, selectedYear, selectedCategory, this);
    }

    /**
     * Show loading state
     * @param {HTMLElement} button - Load more button
     */
    function showLoadingState(button) {
        if (elements.loadingIndicator) {
            elements.loadingIndicator.style.display = 'block';
        }
        button.disabled = true;
    }

    /**
     * Hide loading state
     * @param {HTMLElement} button - Load more button
     */
    function hideLoadingState(button) {
        if (elements.loadingIndicator) {
            elements.loadingIndicator.style.display = 'none';
        }
        button.disabled = false;
    }

    /**
     * Load more news via AJAX
     * @param {number} page - Current page number
     * @param {string} year - Selected year
     * @param {string} category - Selected category
     * @param {HTMLElement} button - Load more button
     */
    function loadMoreNews(page, year, category, button) {
        const formData = new FormData();
        formData.append('action', CONFIG.ajax.action);
        formData.append('page', page);
        formData.append('year', year);
        formData.append('category', category);
        formData.append('nonce', newsListingAjax.nonce);

        fetch(newsListingAjax.ajaxurl, {
            method: CONFIG.ajax.method,
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                handleLoadMoreSuccess(data, button);
            } else {
                console.error('Load more failed:', data.data);
            }
        })
        .catch(error => {
            console.error('Error loading more news:', error);
        })
        .finally(() => {
            hideLoadingState(button);
        });
    }

    /**
     * Handle successful load more response
     * @param {Object} data - Response data
     * @param {HTMLElement} button - Load more button
     */
    function handleLoadMoreSuccess(data, button) {
        // Append new content
        elements.newsContainer.insertAdjacentHTML('beforeend', data.data.html);
        
        // Update page number
        const currentPage = parseInt(button.dataset.page);
        button.dataset.page = currentPage + 1;
        
        // Hide load more button if no more pages
        const maxPages = parseInt(button.dataset.maxPages);
        if (currentPage + 1 > maxPages) {
            button.style.display = 'none';
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
