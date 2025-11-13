<?php
/**
 * Page template
 * 
 * @package MondolTheme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container">
    <?php
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php
                        if ( ! is_front_page() ) {
                            echo '<h1>';
                            the_title();
                            echo '</h1>';
                        }
                    ?>
                    <div class="page-content mondol" >
                        <?php the_content(); ?>
                    </div>
                </article>
                <?php
            }
        }
    ?>
</div>

<?php get_footer();
