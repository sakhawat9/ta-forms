<?php

/**
 * Submit.
 *
 * This template can be overridden by copying it to plugin/ta-forms/Templates/form/submit.php
 *
 * @package    TaForms
 * @subpackage TaForms/Frontend
 */

$submit_button = isset($form_options['submit_button']) ? $form_options['submit_button'] : 'Submit';
?>

<input type="submit" value="<?php echo esc_html($submit_button); ?>" />