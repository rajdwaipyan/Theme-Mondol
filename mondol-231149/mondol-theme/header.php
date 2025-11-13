<?php
/**
 * Header template
 * 
 * @package MondolTheme
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="site-header">
        <div class="container">
            <div class="site-title">
                <?php
                    $custom_logo_id = get_theme_mod( 'custom_logo' );
                    
                    if ( $custom_logo_id ) {
                        echo wp_get_attachment_image( $custom_logo_id, 'medium' );
                    } else {
                        echo esc_html( get_bloginfo( 'name' ) );
                    }
                ?>
            </div>
            
            <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'fallback_cb'    => 'wp_page_menu',
                    'container'      => 'nav',
                    'container_class'=> 'site-navigation',
                ) );
            ?>
        </div>
    </header>

    <main id="main" class="site-main">
