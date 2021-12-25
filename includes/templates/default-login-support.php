<?php
/**
 * NSL: Default login support
 *
 * Context:
 *
 * @var array<string, string> $all_avail Key: service identifier, value: label string.
 * @var array<string>         $services  Enabled service identifiers.
 * @var array<string, string> $uris      Key: service identifier, value: URI string.
 * @var array<string, string> $icons     Key: service identifier, value: URL to image.
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<ul id="nsl-default-login-support">
	<?php foreach ( $services as $identifier ) : ?>
        <li class="login-icon <?php echo esc_attr( $identifier ); ?>">
			<?php
			$img_url = $icons[ $identifier ] ?? '';
			$label   = $all_avail[ $identifier ] ?? '';
			$uri     = $uris[ $identifier ] ?? '';

			$title = sprintf(
			/* translators: service name */
				__( 'Login via %s', 'nsl' ),
				$label
			);
			?>
            <a href="<?php echo esc_url( $uri ); ?>"
               title="<?php echo esc_attr( $title ); ?>">
                <img class="login-icon-image"
                     src="<?php echo esc_url( $img_url ); ?>"
                     alt="<?php echo esc_attr( $label ); ?>">
                <span class="screen-reader-text"><?php echo esc_html( $label ); ?></span>
            </a>
        </li>
	<?php endforeach; ?>
</ul>
