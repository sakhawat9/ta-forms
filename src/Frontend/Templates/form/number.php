<?php

/**
 * Number.
 *
 * This template can be overridden by copying it to plugin/ta-forms/Templates/form/number.php
 *
 * @package    TaForms
 * @subpackage TaForms/Frontend
 */

$label = isset($form_field['label']) ? $form_field['label'] : 'Number';
$placeholder = isset($form_field['placeholder']) ? $form_field['placeholder'] : 'Your Number';
$required = !empty($form_field['required']) ? 'required' : 1;
$custom_validation_message = !empty($form_field['custom_validation_message']) ? $form_field['custom_validation_message'] : '';

$input_label = isset($form_options['input_label']) ? $form_options['input_label'] : true;
?>
<div class="form_field">
    <?php if ($label && $input_label) { ?>
        <label class="form-label" for="ta_forms_phone"><span><?php echo esc_html($label);
                                                                                            echo $required ? '<span>*</span>' : ''; ?></span></label>
    <?php } ?>
    <input title="<?php echo esc_attr($custom_validation_message) ?>" type="number" id="ta_forms_phone" name="ta_forms_phone" value="88234324234" <?php echo esc_html($required); ?> placeholder="<?php echo esc_html($placeholder); ?>" />
</div>