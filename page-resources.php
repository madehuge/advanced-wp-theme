<?php
/**
 * Template Name: Resources Listing
 * 
 * This template displays the resources listing page with filtering capabilities
 */

get_header(); 

get_template_part('template-parts/components/breadcrumb');
?>
<section class="resources_list_sec position-relative">
        <div class="container w-100 position-relative px-4 px-sm-3 h-100 comn_sec_py comn_sec_py_half">
            <div class="divider_line_holder position-absolute z-2">
                <div class="divider_line"></div>
                <div class="divider_line"></div>
                <div class="divider_line"></div>
                <div class="divider_line"></div>
            </div>
            
            <div class="container_inr position-relative z-2 h-100">  
                <!-- Navigation Tabs -->
                <nav class="blog-cat-tabs">
                    <ul class="blog-cat-items">
                        <li>
                            <a class="blog-cat active" data-filter="all">All</a>
                        </li>
                        <li>
                            <a class="blog-cat" data-filter="blogs">Blogs</a>
                        </li>                  
                        <li>
                            <a class="blog-cat" data-filter="videos">Videos</a>
                        </li>
                        <li>
                             <a class="blog-cat" data-filter="webinars">Webinars</a>
                        </li>
                    </ul>
                      
                      <!-- Search Bar -->
                      <div class="search-container">
                          <input type="text" placeholder="Search" class="search-input">
                          <button class="search-btn">
                              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                  <circle cx="11" cy="11" r="8"></circle>
                                  <path d="m21 21-4.35-4.35"></path>
                              </svg>
                          </button>
                      </div>
                </nav>

                <div id="blogListWrap">
                    <!-- Resources will be loaded dynamically via AJAX -->
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading resources...</p>
                    </div>
                    
                    <div class="row g-4 rowRecords"></div>
                </div>

                <div class="text-center mt-5">
                    <button class="btn btn_deep_blue" type="button">
                        <span class="bnt_text_wrap"><span>Load More</span></span>
                        <span class="arrow"></span>
                    </button>
                </div>
            </div>  
        </div>
    </section>
<?php 
get_template_part('template-parts/components/cta');
get_footer(); ?>
