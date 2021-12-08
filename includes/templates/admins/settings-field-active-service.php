<?php
/**
 * NSL: admins active service field tmplate
 *
 * @var array<string, string> $services
 * @var string                $option_name
 * @var array<string>         $values
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<ul class="m0" id="nsl-field-active-services">
	<?php foreach ( $values as $value ) : ?>
		<?php if ( isset( $services[ $value ] ) ) : ?>
            <li>
                <input id="nsl-active-service-<?php echo esc_attr( $value ); ?>"
                       name="<?php echo esc_attr( $option_name ); ?>[active_services][]"
                       value="<?php echo esc_attr( $value ); ?>"
                       type="checkbox"
                       checked="checked">
                <label for="nsl-active-service-<?php echo esc_attr( $value ); ?>">
					<?php echo esc_html( $services[ $value ] ); ?>
                </label>
            </li>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php foreach ( $services as $id => $label ) : ?>
		<?php if ( ! in_array( $id, $values ) ) : ?>
            <li>
                <input id="nsl-active-service-<?php echo esc_attr( $id ); ?>"
                       name="<?php echo esc_attr( $option_name ); ?>[active_services][]"
                       value="<?php echo esc_attr( $id ); ?>"
                       type="checkbox"
					<?php checked( in_array( $id, $values ) ); ?>>

                <label for="nsl-active-service-<?php echo esc_attr( $id ); ?>">
					<?php echo esc_html( $label ); ?>
                </label>
            </li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>

<p class="description">
	<?php _e( 'Drag each item to rearrange the order.', 'nsl' ); ?>
</p>