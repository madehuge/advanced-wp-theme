/**
 * Resource Filter JavaScript
 * Handles dynamic filtering of resources via AJAX
 */

(function($) {
    'use strict';

    class ResourceFilter {
        constructor() {
            this.currentType = 'all';
            this.currentSearch = '';
            this.currentPage = 1;
            this.isLoading = false;
            
            this.init();
        }

        init() {
            this.bindEvents();
            this.loadInitialResources();
        }

        bindEvents() {
            // Filter tabs
            $(document).on('click', '.blog-cat', (e) => {
                e.preventDefault();
                const type = $(e.currentTarget).data('filter');
                this.filterByType(type);
            });

            // Search input
            $(document).on('input', '.search-input', (e) => {
                const search = $(e.currentTarget).val();
                this.searchResources(search);
            });

            // Load more button
            $(document).on('click', '.btn_deep_blue', (e) => {
                e.preventDefault();
                this.loadMore();
            });
        }

        filterByType(type) {
            if (this.isLoading) return;
            
            this.currentType = type;
            this.currentPage = 1;
            this.currentSearch = '';
            
            // Update active tab
            $('.blog-cat').removeClass('active');
            $(`.blog-cat[data-filter="${type}"]`).addClass('active');
            
            // Clear search input
            $('.search-input').val('');
            
            // Hide rowRecords container while loading
            $('#blogListWrap .rowRecords').hide();
            
            this.loadResources();
        }

        searchResources(search) {
            if (this.isLoading) return;
            
            this.currentSearch = search;
            this.currentPage = 1;
            
            // Clear timeout if user is still typing
            clearTimeout(this.searchTimeout);
            
            // Debounce search
            this.searchTimeout = setTimeout(() => {
                // Hide rowRecords container while loading
                $('#blogListWrap .rowRecords').hide();
                this.loadResources();
            }, 500);
        }

        loadInitialResources() {
            this.loadResources();
        }

        loadResources() {
            if (this.isLoading) return;
            
            this.isLoading = true;
            this.showLoading();
            
            const data = {
                action: 'filter_resources',
                nonce: custom_ajax.nonce,
                type: this.currentType,
                search: this.currentSearch,
                page: this.currentPage
            };
            
            $.ajax({
                url: custom_ajax.ajax_url,
                type: 'POST',
                data: data,
                success: (response) => {
                    this.hideLoading();
                    
                    if (response.success && response.data && Array.isArray(response.data)) {
                        this.renderResources(response.data);
                        this.updateLoadMoreButton(response.has_more);
                    } else {
                        this.showError(response.data || 'Failed to load resources');
                    }
                },
                error: (xhr, status, error) => {
                    this.hideLoading();
                    this.showError('An error occurred while loading resources: ' + error);
                },
                complete: () => {
                    this.isLoading = false;
                }
            });
        }

        loadMore() {
            if (this.isLoading || !this.hasMore) return;
            
            this.currentPage++;
            this.loadResources();
        }

        renderResources(resources) {
            const container = $('#blogListWrap .rowRecords');
            
            if (this.currentPage === 1) {
                // Clear existing content
                container.find('.col-md-12, .col-md-6').remove();
                // Hide loading spinner
                $('#blogListWrap .col-12.text-center.py-5').hide();
            }
            
            if (!resources || resources.length === 0) {
                this.showNoResults();
                return;
            }
            
            // Render featured resources in slider (first 4) - only on first page
            if (this.currentPage === 1) {
                const featuredResources = resources.slice(0, 4);
                if (featuredResources.length > 0) {
                    this.renderFeaturedSlider(featuredResources);
                }
                
                // Render remaining resources in grid
                const remainingResources = resources.slice(4);
                if (remainingResources.length > 0) {
                    this.renderResourceGrid(remainingResources);
                }
            } else {
                // For load more, render all resources in grid
                this.renderResourceGrid(resources);
            }
            
            // Show rowRecords container after content is rendered
            container.show();
        }


        renderFeaturedSlider(resources) {
            const sliderContainer = `
                <div class="col-md-12 col-lg-8">
                    <div class="hero_banner_slider_all">
                        <div class="splide" id="bloglistSlider">
                            <div class="splide__track">
                                <div class="splide__list">
                                    ${this.generateSliderCards(resources)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#blogListWrap .rowRecords').prepend(sliderContainer);
            
            // Trigger existing slider initialization from init.js
            setTimeout(() => {
                this.initializeExistingSlider();
            }, 100);
        }

        renderResourceGrid(resources) {
            const gridHtml = resources.map(resource => this.generateListCard(resource)).join('');
            $('#blogListWrap .rowRecords').append(gridHtml);
            
            // Re-bind Fancybox for newly loaded video elements
            setTimeout(() => {
                this.initializeVideoFancybox();
            }, 100);
        }

        initializeExistingSlider() {
            // Re-run the blog slider initialization from init.js
            const blogListSlider_el = document.getElementById("bloglistSlider");
            
            if (blogListSlider_el) {
                // Destroy existing slider if it exists
                if (blogListSlider_el.splide) {
                    blogListSlider_el.splide.destroy();
                }
                
                let totalSlides = blogListSlider_el.querySelectorAll(".splide__slide").length;
                
                const blogListSlider = new Splide(blogListSlider_el, {
                    type: "fade",
                    perPage: 1,
                    perMove: 1,
                    gap: "20px",
                    pagination: totalSlides > 1,
                    drag: totalSlides > 1,
                    arrows: false,
                    speed: 1200,
                    mediaQuery: "min",
                    rewind: true,
                    autoplay: true,
                    classes: {
                        pagination: "splide__pagination comn_splide__pagination bt_minus_30",
                    },
                });
                blogListSlider.mount();
            }
        }

        initializeVideoFancybox() {
            // Re-bind Fancybox for newly loaded video elements - using exact same config as init.js
            if (window.Fancybox && document.querySelectorAll("#blogListWrap [data-fancybox='blogListVideo']").length > 0) {
                // Unbind existing to avoid duplicates
                Fancybox.unbind("#blogListWrap [data-fancybox='blogListVideo']");
                // Re-bind with the exact same configuration as init.js
                Fancybox.bind("#blogListWrap [data-fancybox='blogListVideo']", {
                    toolbar: false,
                    nav: false,
                });
            }
        }


        generateSliderCards(resources) {
            return resources.map(resource => {
                return `
                    <div class="splide__slide">
                        <div class="resources_blog_slide_card has_link">
                            <a href="${resource.permalink}" class="total_link"></a>
                            <div class="resource-cat">
                                <span>${resource.type_name}</span>
                            </div>
                            <div class="resources_main_image">
                                <div class="ratio">
                                    <img src="${resource.featured_image || custom_ajax.theme_uri + '/assets/images/resource/blog-slider-img.jpg'}" alt="${resource.title}">
                                </div>
                            </div>
                            <div class="resources_main_overlay">
                                <div class="resources_main_content">
                                    <h3 class="resources_main_title heading_text_24 multi-3-line-ellipsis">
                                        ${resource.title}
                                    </h3>
                                    <div class="resources_main_meta">
                                        <span class="resources_date">${resource.date}</span>
                                        <span class="resources_read_time">${resource.duration}</span>
                                    </div>
                                    <span class="read_more_arrow_link color_blue" aria-label="Learn More">Read more</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        generateListCard(resource) {
            const videoOverlay = resource.has_video ? `
                <div class="video-overlay">
                    <a href="${resource.video_url}" 
                       data-caption="${resource.title}" 
                       data-fancybox="blogListVideo" 
                       class="play-video-btn" 
                       aria-label="Video">
                        <i>
                            <svg xmlns="http://www.w3.org/2000/svg" width="71" height="77" viewBox="0 0 71 77" fill="none">
                                <path d="M13.3787 1.69779C6.43671 -2.28423 0.808594 0.977889 0.808594 8.97812V68.0163C0.808594 76.0245 6.43671 79.2824 13.3787 75.3041L64.981 45.7105C71.9253 41.7271 71.9253 35.2734 64.981 31.2909L13.3787 1.69779Z" fill="#4BC4D6"/>
                            </svg>
                        </i>
                    </a>
                </div>
            ` : '';

            return `
                <div class="col-md-6 col-lg-4">
                    <div class="resources_blog_list_card has_link fade-in">
                        <a href="${resource.permalink}" class="total_link"></a>
                        <div class="resource-cat">
                            <span>${resource.type_name}</span>
                        </div>
                        <div class="resources_side_card_body d-flex flex-column">
                            <div class="resources_side_image">
                                <div class="ratio">
                                    <img src="${resource.featured_image || '${custom_ajax.theme_uri}/assets/images/resource/blog-list-img-1.jpg'}" alt="${resource.title}">
                                </div>
                                ${videoOverlay}
                            </div>
                            <div class="resources_side_content">
                                <h4 class="resources_side_title multi-3-line-ellipsis">
                                    ${resource.title}
                                </h4>
                                <div class="resources_side_meta">
                                    <span class="resources_date">${resource.date}</span>
                                    <span class="resources_read_time">${resource.duration}</span>
                                </div>
                                <span class="read_more_arrow_link" aria-label="Learn More">Read more</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }


        updateLoadMoreButton(hasMore) {
            this.hasMore = hasMore;
            const loadMoreBtn = $('.btn_deep_blue');
            
            if (hasMore) {
                loadMoreBtn.show();
            } else {
                loadMoreBtn.hide();
            }
        }

        showLoading() {
            $('#blogListWrap').addClass('loading');
            // Show the loading spinner
            $('#blogListWrap .col-12.text-center.py-5').show();
            // Hide rowRecords while loading
            $('#blogListWrap .rowRecords').hide();
        }

        hideLoading() {
            $('#blogListWrap').removeClass('loading');
            // Hide the loading spinner
            $('#blogListWrap .col-12.text-center.py-5').hide();
        }

        showNoResults() {
            const noResultsHtml = `
                <div class="col-12 text-center py-5">
                    <h3>No resources found</h3>
                    <p>Try adjusting your search or filter criteria.</p>
                </div>
            `;
            $('#blogListWrap .rowRecords').html(noResultsHtml).show();
        }

        showError(message) {
            // Show error message to user
            const errorHtml = `
                <div class="col-12 text-center py-5">
                    <h3>Error Loading Resources</h3>
                    <p>${message}</p>
                    <button class="btn btn-primary" onclick="location.reload()">Retry</button>
                </div>
            `;
            $('#blogListWrap .rowRecords').html(errorHtml).show();
        }

    }

    // Initialize when document is ready
    $(document).ready(function() {
        // Check if custom_ajax is available
        if (typeof custom_ajax === 'undefined') {
            return;
        }
        
        // Check if we're on the resources page
        if (custom_ajax.is_resources_page) {
            new ResourceFilter();
        }
    });

})(jQuery);
