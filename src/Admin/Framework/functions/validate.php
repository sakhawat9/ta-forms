<?php if (! defined('ABSPATH')) {
  die;
} // Cannot access directly.
/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('Ta_Forms_validate_email')) {
  function Ta_Forms_validate_email($value)
  {

    if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
      return esc_html__('Please enter a valid email address.', 'ta-forms');
    }
  }
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('Ta_Forms_validate_numeric')) {
  function Ta_Forms_validate_numeric($value)
  {

    if (! is_numeric($value)) {
      return esc_html__('Please enter a valid number.', 'ta-forms');
    }
  }
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('Ta_Forms_validate_required')) {
  function Ta_Forms_validate_required($value)
  {

    if (empty($value)) {
      return esc_html__('This field is required.', 'ta-forms');
    }
  }
}

/**
 *
 * URL validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('Ta_Forms_validate_url')) {
  function Ta_Forms_validate_url($value)
  {

    if (! filter_var($value, FILTER_VALIDATE_URL)) {
      return esc_html__('Please enter a valid URL.', 'ta-forms');
    }
  }
}
if (! function_exists('Ta_Forms_validate_whatsapp_url')) {
  function Ta_Forms_validate_whatsapp_url($value)
  {

    if (! filter_var($value, FILTER_VALIDATE_URL)) {
      return esc_html__('Please enter a valid URL.', 'ta-forms');
    }

    if (strpos($value, 'https://chat.whatsapp.com') !== 0) {
      return esc_html__('Please enter a valid WhatsApp group invite link.', 'ta-forms');
    }
  }
}

/**
 *
 * Email validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('Ta_Forms_customize_validate_email')) {
  function Ta_Forms_customize_validate_email($validity, $value, $wp_customize)
  {

    if (! sanitize_email($value)) {
      $validity->add('required', esc_html__('Please enter a valid email address.', 'ta-forms'));
    }

    return $validity;
  }
}

/**
 *
 * Numeric validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('Ta_Forms_customize_validate_numeric')) {
  function Ta_Forms_customize_validate_numeric($validity, $value, $wp_customize)
  {

    if (! is_numeric($value)) {
      $validity->add('required', esc_html__('Please enter a valid number.', 'ta-forms'));
    }

    return $validity;
  }
}

/**
 *
 * Required validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('Ta_Forms_customize_validate_required')) {
  function Ta_Forms_customize_validate_required($validity, $value, $wp_customize)
  {

    if (empty($value)) {
      $validity->add('required', esc_html__('This field is required.', 'ta-forms'));
    }

    return $validity;
  }
}

/**
 *
 * URL validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (! function_exists('Ta_Forms_customize_validate_url')) {
  function Ta_Forms_customize_validate_url($validity, $value, $wp_customize)
  {

    if (! filter_var($value, FILTER_VALIDATE_URL)) {
      $validity->add('required', esc_html__('Please enter a valid URL.', 'ta-forms'));
    }

    return $validity;
  }
}
