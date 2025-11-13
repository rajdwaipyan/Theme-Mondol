<?php
/**
 * 404 error page template
 * 
 * @package MondolTheme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container">
    <article class="error-404">
        <h1><?php esc_html_e( 'Page Not Found', 'mondol-theme' ); ?></h1>
        <p><?php esc_html_e( 'Sorry, the page you are looking for could not be found.', 'mondol-theme' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">
            <?php esc_html_e( 'Return to Home', 'mondol-theme' ); ?>
        </a>
    </article>
</div>

<?php get_footer();
