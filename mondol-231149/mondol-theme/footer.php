<?php
/**
 * Footer template
 * 
 * @package MondolTheme
 */

defined( 'ABSPATH' ) || exit;
?>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All Rights Reserved.</p>
                <p><?php bloginfo( 'description' ); ?></p>
                
                <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'fallback_cb'    => false,
                        'container'      => 'nav',
                        'container_class'=> 'footer-navigation',
                        'depth'          => 1,
                    ) );
                ?>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
