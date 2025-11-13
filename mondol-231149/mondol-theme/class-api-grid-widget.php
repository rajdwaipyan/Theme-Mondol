<?php
/**
 * Mondol API Grid Widget for Elementor
 * 
 * @package MondolTheme
 */

namespace MondolTheme\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class API_Grid_Widget extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'mondol_api_grid';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return esc_html__( 'API Grid - Posts', 'mondol-theme' );
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return array( 'general' );
    }

    /**
     * Register widget controls
     */
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
                'description' => esc_html__( 'Display on tablets (768px - 1024px)', 'mondol-theme' ),
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
                'description' => esc_html__( 'Display on mobile devices (< 768px)', 'mondol-theme' ),
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
                'range'   => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
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
                'range'   => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
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

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $columns  = ! empty( $settings['items_per_row'] ) ? $settings['items_per_row'] : '3';
        $tablet_cols   = ! empty( $settings['tablet_columns'] ) ? (int) $settings['tablet_columns'] : 2;
        $mobile_cols   = ! empty( $settings['mobile_columns'] ) ? (int) $settings['mobile_columns'] : 1;
        $posts_limit   = ! empty( $settings['posts_limit'] ) ? (int) $settings['posts_limit'] : 12;
        $api_url  = ! empty( $settings['api_url'] ) ? $settings['api_url'] . "?_embed&per_page={$posts_limit}" : "https://mondoldrivingschool.com/wp-json/wp/v2/posts?_embed&per_page={$posts_limit}";
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
        </style>

        <div id="<?php echo $wrap_id; ?>" class="mondol-elementor-api-grid "
        data-columns="<?php echo esc_attr( $columns ); ?>"
        data-api-url="<?php echo esc_url( $api_url ); ?>" >
            
            <?php if ( $show_filter ) : ?>
                <!-- Filter Section -->
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

            <!-- Grid Container -->
             <div class="api-grid elementor-api-grid" id="api-grid-<?php echo esc_attr( $this->get_id() ); ?>">
                <div class="loading">
                    <div class="spinner"></div>
                    <p><?php esc_html_e( 'Loading posts...', 'mondol-theme' ); ?></p>
                </div>
            </div>

            <!-- Error Message -->
            <div id="error-message-<?php echo esc_attr( $this->get_id() ); ?>" class="error-message" style="display:none;"></div>

        </div>

        <script type="text/javascript">
        (function($) {
            'use strict';

            var widgetId = '<?php echo esc_js( $this->get_id() ); ?>';
            var apiUrl = '<?php echo $api_url; ?>' ;
            console.log(apiUrl);
            function getFeaturedImage(post, callback) {
                if (!post._links || !post._links['wp:featuredmedia']) {
                    callback(null);
                    return;
                }
            
                var featuremediaURI = post._links['wp:featuredmedia'][0].href;
            
                $.get(featuremediaURI, function(res) {
                    if (!res) {
                        callback(null);
                        return;
                    }
            
                    var url = null;
            
                    // use smaller image if possible
                    if (res.media_details && res.media_details.sizes) {
                        if (res.media_details.sizes.medium) {
                            url = res.media_details.sizes.medium.source_url;
                        } else if (res.media_details.sizes.thumbnail) {
                            url = res.media_details.sizes.thumbnail.source_url;
                        }
                    }
            
                    if (!url && res.source_url) {
                        url = res.source_url;
                    }
            
                    callback(url);
                }).fail(function() {
                    callback(null);
                });
            }

            var ElementorApiGrid = {
                widgetId: widgetId,
                apiUrl: apiUrl,
                allPosts: [],
                categories: [],

                init: function() {
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
                
                fetchInitialData: function() {
                    var self = this;
                    
                    $.ajax({
                        url: this.apiUrl,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 10000,
                        success: function(data) {
                            if (Array.isArray(data)) {
                                self.allPosts = data;
                                self.extractCategories();
                                self.renderGrid(data);
                                self.renderCategoryFilters();
                            } else {
                                self.showError('Invalid data format received from API');
                            }
                        },
                        error: function(xhr, status, error) {
                            self.showError('Failed to fetch data from API');
                            console.error('API Error:', error);
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
                },

                renderCategoryFilters: function() {
                    var self = this;
                    var categoryHtml = '';

                    var categoryUrls = [];
                    this.categories.forEach(function(categoryId) {
                        categoryUrls.push(
                            $.ajax({
                                url: 'https://mondoldrivingschool.com/wp-json/wp/v2/categories/' + categoryId,
                                type: 'GET',
                                dataType: 'json'
                            })
                        );
                    });

                    if (categoryUrls.length > 0) {
                        $.when.apply($, categoryUrls).done(function() {
                            var responses = arguments.length === 1 ? [arguments[0]] : Array.from(arguments).map(function(arg) {
                                return arg[0];
                            });

                            responses.forEach(function(category) {
                                if (category && category.id) {
                                    categoryHtml += '<div class="filter-checkbox-item">' +
                                        '<input type="checkbox" id="filter-cat-' + category.id + '-' + self.widgetId + '" value="' + category.id + '" class="category-filter">' +
                                        '<label for="filter-cat-' + category.id + '-' + self.widgetId + '">' + escapeHtml(category.name) + '</label>' +
                                        '</div>';
                                }
                            });

                            self.$categoryList.html(categoryHtml);
                        });
                    }
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

                    if ($('#filter-all-' + this.widgetId).is(':checked') && selectedCategories.length > 0) {
                        selectedCategories = [];
                        this.renderGrid(this.allPosts);
                    } else if (selectedCategories.length === 0) {
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

                renderGrid: function(posts) {
    var self = this;

    if (!posts || posts.length === 0) {
        this.$grid.html('<p class="no-posts"><?php esc_html_e( 'No posts found', 'mondol-theme' ); ?></p>');
        return;
    }

    var gridHtml = '';
    var placeholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="200"%3E%3Crect fill="%23ddd" width="400" height="200"/%3E%3C/svg%3E';

    // 1) First pass: build grid with placeholder images
    posts.forEach(function(post) {
        var title = post.title.rendered || 'Untitled';
        var description = post.excerpt.rendered || '';
        description = description.replace(/<[^>]*>/g, '').substring(0, 150);

        var categoryText = 'Uncategorized';
        if (post.categories && post.categories.length > 0) {
            categoryText = 'Category ' + post.categories[0];
        }

        gridHtml +=
            '<div class="grid-item" data-id="' + post.id + '">' +
                '<div class="grid-item-image">' +
                    '<img src="' + escapeHtml(placeholder) + '" alt="' + escapeHtml(title) + '" loading="lazy">' +
                '</div>' +
                '<div class="grid-item-content">' +
                    '<h3 class="grid-item-title">' + escapeHtml(title) + '</h3>' +
                    '<p class="grid-item-description">' + escapeHtml(description) + '</p>' +
                    '<span class="grid-item-category">' + categoryText + '</span>' +
                '</div>' +
            '</div>';
    });

    // Show the grid immediately
    this.$grid.html(gridHtml);
    this.$errorMessage.hide();

    // 2) Second pass: async load real images and replace placeholder
    posts.forEach(function(post) {
        getFeaturedImage(post, function(url) {
            if (!url) return;

            var $img = self.$grid
                .find('.grid-item[data-id="' + post.id + '"] img');

            if ($img.length) {
                $img.attr('src', escapeHtml(url));
            }
        });
    });
},

                showError: function(message) {
                    this.$errorMessage.html(message).show();
                    this.$grid.html('<p class="no-posts"><?php esc_html_e( 'Error loading posts', 'mondol-theme' ); ?></p>');
                }
            };

            function escapeHtml(text) {
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            }
            
            

            $(document).ready(function() {
                ElementorApiGrid.init();
            });

        })(jQuery);
        </script>

        <?php
    }
}
