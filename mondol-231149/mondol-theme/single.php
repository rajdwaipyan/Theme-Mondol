<?php
/**
 * Single post template
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
                    <h1><?php the_title(); ?></h1>
                    
                    <div class="post-meta">
                        <span class="post-date">
                            <?php echo esc_html( get_the_date() ); ?>
                        </span>
                        <span class="post-author">
                            <?php esc_html_e( 'By', 'mondol-theme' ); ?> <?php the_author(); ?>
                        </span>
                    </div>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="post-content">
                        <?php the_content(); ?>
                    </div>

                    <div class="post-navigation">
                        <?php the_post_navigation(); ?>
                    </div>
                </article>
                <?php
            }
        }
    ?>
</div>

<?php get_footer();
