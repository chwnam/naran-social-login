<?php
/**
 *
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="nsl-field">
    <div id="nsl-field-credentials">
        <ul>
            <li><a href="#nsl-google"><?php esc_html_e( 'Google', 'nsl' ); ?></a></li>
            <li><a href="#nsl-twitter"><?php esc_html_e( 'Twitter', 'nsl' ); ?></a></li>
            <li><a href="#nsl-facebook"><?php esc_html_e( 'Facebook', 'nsl' ); ?></a></li>
        </ul>
        <div id="nsl-google">
            <fieldset>
                <p>
                    <label>API Key</label>
                    <input type="text" class="text large-text">
                </p>
                <p>
                    <label>API Secret</label>
                    <input type="text" class="text large-text">
                </p>
            </fieldset>
        </div>
        <div id="nsl-twitter">
            <fieldset>
                <p>
                    <label>Twitter API Key</label>
                    <input type="text" class="text large-text">
                </p>
                <p>
                    <label>API Secret</label>
                    <input type="text" class="text large-text">
                </p>
            </fieldset>
        </div>
        <div id="nsl-facebook">
            <fieldset>
                <p>
                    <label>Facebook API Key</label>
                    <input type="text" class="text large-text">
                </p>
                <p>
                    <label>API Secret</label>
                    <input type="text" class="text large-text">
                </p>
            </fieldset>
        </div>
    </div>
</div>