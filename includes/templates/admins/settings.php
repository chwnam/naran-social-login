<?php
/**
 * NSL: admins settings template
 *
 * Context:
 *
 * @var string $option_group
 * @var string $page
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap">
	<h1><?php _e( 'Naran Social Login Settings', 'nsl' ); ?></h1>

	<?php do_action( 'nsl_before_option_form' ); ?>

	<form method="post" action="<?php echo admin_url( 'options.php' ); ?>" novalidate>
		<?php
		settings_fields( $option_group );
		do_action( 'nsl_after_settings_fields' );
		?>

		<?php
		do_settings_sections( $page );
		do_action( 'nsl_after_do_settings_sections' );
		?>

		<?php submit_button(); ?>
	</form>

	<?php do_action( 'nsl_after_option_form' ); ?>
</div>
