<?php
/**
 * @var string               $option_name  Option's name
 * @var array                $option_value Current credentials values
 * @var array<string, array> $items        Key: identifier
 *                                         Value: array
 *                                         - name
 *                                         - url
 *                                         - dev_title
 *                                         - redirect_uri
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( ! empty( $items ) ) : ?>
    <div id="nsl-field-credentials">
        <ul>
			<?php foreach ( $items as $id => $item ) : ?>
                <li>
                    <a href="#<?php echo esc_attr( 'nsl-' . $id ); ?>">
						<?php echo esc_html( $item['name'] ?? '' ); ?>
                    </a>
                </li>
			<?php endforeach; ?>
        </ul>
		<?php foreach ( $items as $id => $item ) : ?>
            <div id="<?php echo esc_attr( 'nsl-' . $id ); ?>">
                <fieldset>
                    <div>
                        <label for="nsl-api-key-<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'API Key', 'nsl' ); ?></label>
                        <input id="nsl-api-key-<?php echo esc_attr( $id ); ?>"
                               name="<?php echo esc_attr( "$option_name" ); ?>[credentials][<?php echo esc_attr( $id ); ?>][key]"
                               type="text"
                               class="text large-text"
                               autocomplete="off"
                               value="<?php echo esc_attr( $option_value[ $id ]['key'] ?? '' ); ?>">
                    </div>
                    <div>
                        <label for="nsl-api-secret-<?php echo esc_attr( $id ); ?>">
							<?php esc_html_e( 'API Secret', 'nsl' ); ?>
                        </label>
                        <input id="nsl-api-secret-<?php echo esc_attr( $id ); ?>"
                               name="<?php echo esc_attr( "$option_name" ); ?>[credentials][<?php echo esc_attr( $id ); ?>][secret]"
                               type="text"
                               class="text large-text"
                               autocomplete="off"
                               value="<?php echo esc_attr( $option_value[ $id ]['secret'] ?? '' ); ?>">
                    </div>
                    <div>
                        <label for="nsl-redirect-uri-<?php echo esc_attr( $id ); ?>">
							<?php esc_html_e( 'Redirect URI', 'nsl' ); ?>
                        </label>
                        <div class="redirect-uri-wrap">
                            <input id="nsl-redirect-uri-<?php echo esc_attr( $id ); ?>"
                                   type="text"
                                   class="text large-text"
                                   readonly="readonly"
                                   value="<?php echo esc_url( $item['redirect_uri'] ?? '' ); ?>">
                            <div class="copy-uri">
                                <a href="javascript: void(0);"
                                   data-text-copy="<?php echo esc_attr_x( 'Copy', 'Copy redirect uri to clipboard', 'nsl' ); ?>"
                                   data-text-copied="<?php echo esc_attr_x( 'Copied', 'Redirect uri copied to clipboard', 'nsl' ); ?>"
                                   data-uri="<?php echo esc_url( $item['redirect_uri'] ?? '' ); ?>"
                                   aria-label="<?php echo esc_attr(
									   sprintf(
									   /* translators: service name */
										   __( 'Copy redirect URI of %s to clipboard.', 'nsl' ),
										   $item['name'] ?? ''
									   )
								   ); ?>">
									<?php esc_html_e( 'Copy', 'nsl' ); ?>
                                </a>
                            </div>

                        </div>
                    </div>
                    <p class="description">
						<?php
						$html = sprintf(
						/* translators: 1: developers page url, 2: developers page title */
							__( 'Visit <a href="%1$s" target="_blank">%2$s</a> to get API key and secret.', 'nsl' ),
							esc_url( $item['url'] ),
							esc_html( $item['dev_title'] )
						);
						echo wp_kses( $html, [ 'a' => [ 'href' => true, 'target' => true ] ] );
						?>
                    </p>
                </fieldset>
            </div>
		<?php endforeach; ?>
    </div>
<?php else: ?>
    <p><?php esc_html_e( 'No available services.', 'nsl' ); ?></p>
<?php endif; ?>
