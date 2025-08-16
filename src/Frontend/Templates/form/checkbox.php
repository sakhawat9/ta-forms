<?php

/**
 * Checkbox.
 *
 * This template can be overridden by copying it to plugin/ta-forms/Templates/form/checkbox.php
 *
 * @package    TaForms
 * @subpackage TaForms/Frontend
 */

$label = isset($form_field['label']) ? $form_field['label'] : '';
$placeholder = isset($form_field['placeholder']) ? $form_field['placeholder'] : '';
$required = !empty($form_field['required']) ? 'required' : 1;
$custom_validation_message = isset($form_field['custom_validation_message']) ? $form_field['custom_validation_message'] : '';
?>
<div class="input_checkbox">
    <input title="<?php echo esc_attr($custom_validation_message) ?>" type="checkbox" id="ta_forms_checkbox" name="ta_forms_checkbox_<?php echo esc_attr($field_id) ?>" <?php echo esc_html($required); ?> placeholder="<?php echo esc_html($placeholder); ?>" />
    <?php if ($label) { ?>
        <label class="form-label" for="ta_forms_checkbox"><span><?php echo esc_html($label); ?></span></label>
    <?php } ?>
</div>