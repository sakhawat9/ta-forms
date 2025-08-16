<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'Ta_Forms_Field_switcher' ) ) {
  class Ta_Forms_Field_switcher extends Ta_Forms_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $active     = ( ! empty( $this->value ) ) ? ' ta-forms--active' : '';
      $text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'ta-forms' );
      $text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'ta-forms' );
      $text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: '. esc_attr( $this->field['text_width'] ) .'px;"': '';

      echo wp_kses_post($this->field_before());

      echo '<div class="ta-forms--switcher'. esc_attr( $active ) .'"'. $text_width .'>';
      echo '<span class="ta-forms--on">'. esc_attr( $text_on ) .'</span>';
      echo '<span class="ta-forms--off">'. esc_attr( $text_off ) .'</span>';
      echo '<span class="ta-forms--ball"></span>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .' />';
      echo '</div>';

      echo ( ! empty( $this->field['label'] ) ) ? '<span class="ta-forms--label">'. esc_attr( $this->field['label'] ) . '</span>' : '';

      echo wp_kses_post($this->field_after());

    }

  }
}
