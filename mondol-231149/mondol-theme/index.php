<?php
/**
 * Main template file
 * 
 * @package MondolTheme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container">
    <?php
        if ( have_posts() ) {
            echo '<div class="posts-list">';
            while ( have_posts() ) {
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h2><?php the_title(); ?></h2>
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
                <?php
            }
            echo '</div>';
            the_posts_pagination();
        } else {
            echo '<p>' . esc_html__( 'No posts found', 'mondol-theme' ) . '</p>';
        }
    ?>
</div>

<?php get_footer();
