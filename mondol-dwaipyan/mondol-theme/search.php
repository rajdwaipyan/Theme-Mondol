<?php
/**
 * Search results template
 * 
 * @package MondolTheme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container">
    <h1><?php printf( esc_html__( 'Search Results for: %s', 'mondol-theme' ), '<span>' . get_search_query() . '</span>' ); ?></h1>

    <?php
        if ( have_posts() ) {
            echo '<div class="search-results">';
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
            echo '<p>' . esc_html__( 'No results found for your search.', 'mondol-theme' ) . '</p>';
        }
    ?>
</div>

<?php get_footer();
