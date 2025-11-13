<?php
/**
 * Front page template with API Grid
 * 
 * @package MondolTheme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container">
    

    <!-- Elementor Content Area -->
    <div id="elementor-content" style="margin-top: 60px;">
        <?php
            if ( have_posts() ) {
                while ( have_posts() ) {
                    the_post();
                    the_content();
                }
            }
        ?>
    </div>

</div>

<?php get_footer();
