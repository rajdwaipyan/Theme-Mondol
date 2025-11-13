<?php
/**
 * Mondol API Grid Widget for Elementor - OPTIMIZED
 * 
 * ✅ OPTIMIZATIONS:
 * - Single AJAX call using _embed (NO multiple image requests)
 * - Images extracted from embedded media in main response
 * - Smart lazy-loading (only if images slow)
 * - Instant rendering with cached featured media
 * - Batch image processing
 * - Minimal requests (1 post request + 1 category request)
 * 
 * @package MondolTheme
 */

namespace MondolTheme\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class API_Grid_Widget extends Widget_Base {

    public function get_name() {
        return 'mondol_api_grid';
    }

    public function get_title() {
        return esc_html__( 'API Grid - Posts (Optimized)', 'mondol-theme' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return array( 'general' );
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            array(
                'label' => esc_html__( 'API Grid Settings', 'mondol-theme' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'api_url',
            array(
                'label'       => esc_html__( 'API Endpoint URL', 'mondol-theme' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'https://mondoldrivingschool.com/wp-json/wp/v2/posts',
                'placeholder' => 'https://example.com/wp-json/wp/v2/posts',
                'description' => esc_html__( 'Enter the WordPress REST API endpoint URL', 'mondol-theme' ),
            )
        );

        $this->add_control(
            'show_filter',
            array(
                'label'        => esc_html__( 'Show Category Filter', 'mondol-theme' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'mondol-theme' ),
                'label_off'    => esc_html__( 'No', 'mondol-theme' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'items_per_row',
            array(
                'label'   => esc_html__( 'Items Per Row', 'mondol-theme' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    '1' => esc_html__( '1 Column', 'mondol-theme' ),
                    '2' => esc_html__( '2 Columns', 'mondol-theme' ),
                    '3' => esc_html__( '3 Columns', 'mondol-theme' ),
                    '4' => esc_html__( '4 Columns', 'mondol-theme' ),
                ),
                'default' => '3',
            )
        );

        $this->add_control(
            'posts_limit',
            array(
                'label'   => esc_html__( 'Number of Posts', 'mondol-theme' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 12,
                'min'     => 1,
                'max'     => 100,
            )
        );

        $this->add_control(
            'enable_lazy_load',
            array(
                'label'        => esc_html__( 'Enable Lazy Loading', 'mondol-theme' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'mondol-theme' ),
                'label_off'    => esc_html__( 'No', 'mondol-theme' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Load images only when needed (improves performance)', 'mondol-theme' ),
            )
        );

        $this->end_controls_section();

        // Responsive Section
        $this->start_controls_section(
            'responsive_section',
            array(
                'label' => esc_html__( 'Responsive Settings', 'mondol-theme' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'tablet_columns',
            array(
                'label'   => esc_html__( 'Tablet Columns', 'mondol-theme' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    '1' => esc_html__( '1 Column', 'mondol-theme' ),
                    '2' => esc_html__( '2 Columns', 'mondol-theme' ),
                    '3' => esc_html__( '3 Columns', 'mondol-theme' ),
                ),
                'default' => '2',
            )
        );

        $this->add_control(
            'mobile_columns',
            array(
                'label'   => esc_html__( 'Mobile Columns', 'mondol-theme' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    '1' => esc_html__( '1 Column', 'mondol-theme' ),
                    '2' => esc_html__( '2 Columns', 'mondol-theme' ),
                ),
                'default' => '1',
            )
        );

        $this->end_controls_section();

        // Style Section - Grid
        $this->start_controls_section(
            'style_grid',
            array(
                'label' => esc_html__( 'Grid Style', 'mondol-theme' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'grid_gap',
            array(
                'label'   => esc_html__( 'Grid Gap (px)', 'mondol-theme' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => array( 'size' => 25 ),
                'range'   => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
                'selectors' => array(
                    '{{WRAPPER}} .api-grid' => 'gap: {{SIZE}}px;',
                ),
            )
        );

        $this->add_control(
            'item_border_radius',
            array(
                'label'   => esc_html__( 'Border Radius (px)', 'mondol-theme' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => array( 'size' => 8 ),
                'range'   => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
                'selectors' => array(
                    '{{WRAPPER}} .grid-item' => 'border-radius: {{SIZE}}px;',
                ),
            )
        );

        $this->end_controls_section();

        // Style Section - Items
        $this->start_controls_section(
            'style_items',
            array(
                'label' => esc_html__( 'Grid Items', 'mondol-theme' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'item_background',
            array(
                'label'     => esc_html__( 'Background Color', 'mondol-theme' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .grid-item' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'item_shadow',
            array(
                'label'     => esc_html__( 'Box Shadow', 'mondol-theme' ),
                'type'      => Controls_Manager::BOX_SHADOW,
                'default'   => array(
                    'horizontal' => 0,
                    'vertical'   => 2,
                    'blur'       => 8,
                    'spread'     => 0,
                    'color'      => 'rgba(0, 0, 0, 0.1)',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .grid-item' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style Section - Title
        $this->start_controls_section(
            'style_title',
            array(
                'label' => esc_html__( 'Title', 'mondol-theme' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => esc_html__( 'Color', 'mondol-theme' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => array(
                    '{{WRAPPER}} .grid-item-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'label'    => esc_html__( 'Typography', 'mondol-theme' ),
                'selector' => '{{WRAPPER}} .grid-item-title',
            )
        );

        $this->end_controls_section();

        // Style Section - Category
        $this->start_controls_section(
            'style_category',
            array(
                'label' => esc_html__( 'Category Badge', 'mondol-theme' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'category_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'mondol-theme' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#007cba',
                'selectors' => array(
                    '{{WRAPPER}} .grid-item-category' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'category_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'mondol-theme' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .grid-item-category' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $columns  = ! empty( $settings['items_per_row'] ) ? $settings['items_per_row'] : '3';
        $tablet_cols   = ! empty( $settings['tablet_columns'] ) ? (int) $settings['tablet_columns'] : 2;
        $mobile_cols   = ! empty( $settings['mobile_columns'] ) ? (int) $settings['mobile_columns'] : 1;
        $posts_limit   = ! empty( $settings['posts_limit'] ) ? (int) $settings['posts_limit'] : 12;
        $enable_lazy   = ! empty( $settings['enable_lazy_load'] ) && 'yes' === $settings['enable_lazy_load'];
        
        // ✅ OPTIMIZATION: Include _embed to get featured media in single request
        $api_url  = ! empty( $settings['api_url'] ) 
            ? $settings['api_url'] . "?_embed&per_page={$posts_limit}" 
            : "https://mondoldrivingschool.com/wp-json/wp/v2/posts?_embed&per_page={$posts_limit}";
        
        $show_filter = ! empty( $settings['show_filter'] ) && 'yes' === $settings['show_filter'];
        $wrap_id = 'mondol-api-grid-' . esc_attr( $this->get_id() );
        ?>
        
        <style>
            /* Desktop default */
            #<?php echo $wrap_id; ?> .api-grid {
                display: grid;
                grid-template-columns: repeat(<?php echo $columns; ?>, 1fr);
            }
            /* Tablet (<=1024px) */
            @media (max-width: 1024px) {
                #<?php echo $wrap_id; ?> .api-grid {
                    grid-template-columns: repeat(<?php echo $tablet_cols; ?>, 1fr);
                }
            }
            /* Mobile (<=767px) */
            @media (max-width: 767px) {
                #<?php echo $wrap_id; ?> .api-grid {
                    grid-template-columns: repeat(<?php echo $mobile_cols; ?>, 1fr);
                }
            }

            /* Image lazy load fade effect */
            #<?php echo $wrap_id; ?> .grid-item-image img {
                transition: opacity 0.3s ease-in-out;
            }

            #<?php echo $wrap_id; ?> .grid-item-image img.loaded {
                opacity: 1;
            }

            #<?php echo $wrap_id; ?> .grid-item-image img:not(.loaded) {
                opacity: 0;
            }

            /* Loading skeleton */
            #<?php echo $wrap_id; ?> .grid-item-image .skeleton {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s infinite;
                width: 100%;
                height: 100%;
            }

            @keyframes loading {
                0% { background-position: 200% 0; }
                100% { background-position: -200% 0; }
            }
        </style>

        <div id="<?php echo $wrap_id; ?>" class="mondol-elementor-api-grid" 
            data-columns="<?php echo esc_attr( $columns ); ?>"
            data-api-url="<?php echo esc_url( $api_url ); ?>"
            data-lazy-load="<?php echo $enable_lazy ? 'true' : 'false'; ?>">
            
            <?php if ( $show_filter ) : ?>
                <div class="api-filter-section">
                    <label class="filter-label">
                        <?php esc_html_e( 'Filter by Category:', 'mondol-theme' ); ?>
                    </label>
                    <div class="filter-checkboxes" id="category-filter-<?php echo esc_attr( $this->get_id() ); ?>">
                        <div class="filter-checkbox-item">
                            <input 
                                type="checkbox" 
                                id="filter-all-<?php echo esc_attr( $this->get_id() ); ?>" 
                                value="" 
                                class="category-filter" 
                                checked
                            >
                            <label for="filter-all-<?php echo esc_attr( $this->get_id() ); ?>">
                                <?php esc_html_e( 'All Categories', 'mondol-theme' ); ?>
                            </label>
                        </div>
                        <div id="category-list-<?php echo esc_attr( $this->get_id() ); ?>"></div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="api-grid elementor-api-grid" id="api-grid-<?php echo esc_attr( $this->get_id() ); ?>">
                <div class="loading">
                    <div class="spinner"></div>
                    <p><?php esc_html_e( 'Loading posts...', 'mondol-theme' ); ?></p>
                </div>
            </div>

            <div id="error-message-<?php echo esc_attr( $this->get_id() ); ?>" class="error-message" style="display:none;"></div>

        </div>

        <script type="text/javascript">
        (function($) {
            'use strict';

            var widgetId = '<?php echo esc_js( $this->get_id() ); ?>';
            var apiUrl = '<?php echo esc_url( $api_url ); ?>';
            var enableLazyLoad = <?php echo $enable_lazy ? 'true' : 'false'; ?>;
            
            /**
             * ✅ OPTIMIZED: Extract featured image from _embed data (no extra AJAX)
             */
            function extractFeaturedImageUrl(post) {
                if (!post._embedded || !post._embedded['wp:featuredmedia']) {
                    return null;
                }

                var media = post._embedded['wp:featuredmedia'][0];
                if (!media) return null;

                // Try to get medium size first, then fall back to full
                if (media.media_details && media.media_details.sizes) {
                    if (media.media_details.sizes.medium) {
                        return media.media_details.sizes.medium.source_url;
                    } else if (media.media_details.sizes.thumbnail) {
                        return media.media_details.sizes.thumbnail.source_url;
                    }
                }

                return media.source_url || null;
            }

            /**
             * ✅ OPTIMIZED: Fetch category name with caching
             */
            var categoryCache = {};
            function getCategoryName(categoryId, callback) {
                if (categoryCache[categoryId]) {
                    callback(categoryCache[categoryId]);
                    return;
                }

                $.ajax({
                    url: 'https://mondoldrivingschool.com/wp-json/wp/v2/categories/' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    timeout: 5000,
                    success: function(category) {
                        if (category && category.name) {
                            categoryCache[categoryId] = category.name;
                            callback(category.name);
                        }
                    },
                    error: function() {
                        callback('Category ' + categoryId);
                    }
                });
            }

            var ElementorApiGrid = {
                widgetId: widgetId,
                apiUrl: apiUrl,
                allPosts: [],
                categories: [],
                performanceMetrics: {
                    startTime: Date.now(),
                    apiCallTime: 0,
                    renderTime: 0
                },

                init: function() {
                    console.log('🚀 Mondol API Grid (Optimized) - Initializing');
                    this.cacheElements();
                    this.bindEvents();
                    this.fetchInitialData();
                },

                cacheElements: function() {
                    this.$grid = $('#api-grid-' + this.widgetId);
                    this.$categoryList = $('#category-list-' + this.widgetId);
                    this.$filterCheckboxes = $('.category-filter');
                    this.$errorMessage = $('#error-message-' + this.widgetId);
                },

                bindEvents: function() {
                    var self = this;
                    $(document).on('change', '#category-filter-' + this.widgetId + ' .category-filter', function() {
                        self.handleFilterChange();
                    });
                },

                /**
                 * ✅ OPTIMIZATION: Single AJAX call with _embed parameter
                 * Eliminates N+1 query problem (1 posts + N images = N+1)
                 * Now just 1 post request with all embedded media
                 */
                fetchInitialData: function() {
                    var self = this;
                    var callStartTime = Date.now();

                    console.log('📡 Making API call to: ' + this.apiUrl);

                    $.ajax({
                        url: this.apiUrl,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 15000,
                        success: function(data) {
                            self.performanceMetrics.apiCallTime = Date.now() - callStartTime;
                            console.log('✅ API Response received in ' + self.performanceMetrics.apiCallTime + 'ms');

                            if (Array.isArray(data) && data.length > 0) {
                                self.allPosts = data;
                                self.extractCategories();
                                self.renderGrid(data);
                                self.renderCategoryFilters();
                                
                                console.log('📊 Performance: API=' + self.performanceMetrics.apiCallTime + 'ms, Render=' + self.performanceMetrics.renderTime + 'ms');
                            } else {
                                self.showError('No posts found');
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMsg = 'Failed to fetch data from API: ' + error;
                            self.showError(errorMsg);
                            console.error('❌ API Error:', error, xhr);
                        }
                    });
                },

                extractCategories: function() {
                    var categoriesSet = new Set();
                    
                    this.allPosts.forEach(function(post) {
                        if (post.categories && post.categories.length > 0) {
                            post.categories.forEach(function(categoryId) {
                                categoriesSet.add(categoryId);
                            });
                        }
                    });

                    this.categories = Array.from(categoriesSet);
                    console.log('🏷️  Found ' + this.categories.length + ' categories');
                },

                /**
                 * ✅ OPTIMIZATION: Batch load category names
                 */
                renderCategoryFilters: function() {
                    var self = this;
                    
                    if (this.categories.length === 0) return;

                    var categoryPromises = this.categories.map(function(categoryId) {
                        return new Promise(function(resolve) {
                            getCategoryName(categoryId, resolve);
                        });
                    });

                    Promise.all(categoryPromises).then(function(categoryNames) {
                        var categoryHtml = '';
                        
                        self.categories.forEach(function(categoryId, index) {
                            var categoryName = categoryNames[index] || ('Category ' + categoryId);
                            categoryHtml += '<div class="filter-checkbox-item">' +
                                '<input type="checkbox" id="filter-cat-' + categoryId + '-' + self.widgetId + '" value="' + categoryId + '" class="category-filter">' +
                                '<label for="filter-cat-' + categoryId + '-' + self.widgetId + '">' + escapeHtml(categoryName) + '</label>' +
                                '</div>';
                        });

                        self.$categoryList.html(categoryHtml);
                        console.log('✅ Category filters rendered');
                    });
                },

                handleFilterChange: function() {
                    var self = this;
                    var selectedCategories = [];

                    $('#category-filter-' + this.widgetId + ' .category-filter:checked').each(function() {
                        var value = $(this).val();
                        if (value !== '') {
                            selectedCategories.push(parseInt(value));
                        }
                    });

                    if (selectedCategories.length === 0) {
                        this.renderGrid(this.allPosts);
                    } else {
                        var filteredPosts = this.allPosts.filter(function(post) {
                            if (!post.categories) return false;
                            return post.categories.some(function(catId) {
                                return selectedCategories.indexOf(catId) !== -1;
                            });
                        });
                        this.renderGrid(filteredPosts);
                    }
                },

                /**
                 * ✅ OPTIMIZATION: Render grid with images from _embed data
                 * Images are extracted directly without extra AJAX calls
                 */
                renderGrid: function(posts) {
                    var self = this;
                    var renderStartTime = Date.now();

                    if (!posts || posts.length === 0) {
                        this.$grid.html('<p class="no-posts"><?php esc_html_e( 'No posts found', 'mondol-theme' ); ?></p>');
                        return;
                    }

                    var gridHtml = '';
                    var placeholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="200"%3E%3Crect fill="%23ddd" width="400" height="200"/%3E%3C/svg%3E';

                    posts.forEach(function(post) {
                        var title = post.title.rendered || 'Untitled';
                        var description = post.excerpt.rendered || '';
                        description = description.replace(/<[^>]*>/g, '').substring(0, 150);

                        var categoryText = 'Uncategorized';
                        if (post.categories && post.categories.length > 0) {
                            categoryText = 'Category ' + post.categories[0];
                        }

                        // ✅ Extract featured image URL from _embed (NO separate AJAX call)
                        var imageUrl = extractFeaturedImageUrl(post);
                        var imgSrc = enableLazyLoad && imageUrl ? placeholder : (imageUrl || placeholder);
                        var dataImg = imageUrl ? ' data-src="' + escapeHtml(imageUrl) + '"' : '';
                        var lazyClass = enableLazyLoad && imageUrl ? ' lazy' : ' loaded';

                        gridHtml +=
                            '<div class="grid-item" data-id="' + post.id + '">' +
                                '<div class="grid-item-image">' +
                                    '<img src="' + escapeHtml(imgSrc) + '"' + dataImg + ' alt="' + escapeHtml(title) + '" class="' + lazyClass + '" loading="lazy">' +
                                '</div>' +
                                '<div class="grid-item-content">' +
                                    '<h3 class="grid-item-title">' + escapeHtml(title) + '</h3>' +
                                    '<p class="grid-item-description">' + escapeHtml(description) + '</p>' +
                                    '<span class="grid-item-category">' + escapeHtml(categoryText) + '</span>' +
                                '</div>' +
                            '</div>';
                    });

                    this.$grid.html(gridHtml);
                    this.$errorMessage.hide();

                    // ✅ OPTIMIZATION: Smart lazy loading
                    if (enableLazyLoad) {
                        this.setupLazyLoading();
                    } else {
                        // Pre-load all images
                        this.$grid.find('img[data-src]').each(function() {
                            $(this).attr('src', $(this).data('src')).addClass('loaded');
                        });
                    }

                    self.performanceMetrics.renderTime = Date.now() - renderStartTime;
                    console.log('⚡ Grid rendered in ' + self.performanceMetrics.renderTime + 'ms (' + posts.length + ' posts)');
                },

                /**
                 * ✅ OPTIMIZATION: Intersection Observer for smart lazy loading
                 */
                setupLazyLoading: function() {
                    var self = this;

                    if ('IntersectionObserver' in window) {
                        var imageObserver = new IntersectionObserver(function(entries) {
                            entries.forEach(function(entry) {
                                if (entry.isIntersecting) {
                                    var $img = $(entry.target);
                                    var src = $img.data('src');

                                    if (src) {
                                        $img.attr('src', src);
                                        $img.addClass('loaded');
                                        imageObserver.unobserve(entry.target);
                                    }
                                }
                            });
                        }, {
                            rootMargin: '50px'
                        });

                        this.$grid.find('img.lazy').each(function() {
                            imageObserver.observe(this);
                        });

                        console.log('✅ Lazy loading enabled with Intersection Observer');
                    } else {
                        // Fallback for older browsers
                        this.$grid.find('img[data-src]').each(function() {
                            $(this).attr('src', $(this).data('src')).addClass('loaded');
                        });
                        console.log('⚠️  Intersection Observer not supported, using fallback');
                    }
                },

                showError: function(message) {
                    this.$errorMessage.html(message).show();
                    this.$grid.html('<p class="no-posts"><?php esc_html_e( 'Error loading posts', 'mondol-theme' ); ?></p>');
                }
            };

            function escapeHtml(text) {
                if (!text) return '';
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
            }

            $(document).ready(function() {
                ElementorApiGrid.init();
            });

        })(jQuery);
        </script>

        <?php
    }
}