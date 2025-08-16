<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: datetime
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'Ta_Forms_Field_datetime' ) ) {
  class Ta_Forms_Field_datetime extends Ta_Forms_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $defaults = array(
        'allowInput' => true,
      );

      $settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();

      if ( ! isset( $settings['noCalendar'] ) ) {
        $defaults['dateFormat'] = 'm/d/Y';
      }

      $settings = wp_parse_args( $settings, $defaults );

      echo wp_kses_post($this->field_before());

      if ( ! empty( $this->field['from_to'] ) ) {

        $args = wp_parse_args( $this->field, array(
          'text_from' => esc_html__( 'From', 'ta-forms' ),
          'text_to'   => esc_html__( 'To', 'ta-forms' ),
        ) );

        $value = wp_parse_args( $this->value, array(
          'from' => '',
          'to'   => '',
        ) );

        echo '<label class="ta-forms--from">'. esc_attr( $args['text_from'] ) .' <input type="text" name="'. esc_attr( $this->field_name( '[from]' ) ) .'" value="'. esc_attr( $value['from'] ) .'"'. $this->field_attributes() .' data-type="from" /></label>';
        echo '<label class="ta-forms--to">'. esc_attr( $args['text_to'] ) .' <input type="text" name="'. esc_attr( $this->field_name( '[to]' ) ) .'" value="'. esc_attr( $value['to'] ) .'"'. $this->field_attributes() .' data-type="to" /></label>';

      } else {

        echo '<input type="text" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .'/>';

      }

      echo '<div class="ta-forms-datetime-settings" data-settings="'. esc_attr( wp_json_encode( $settings ) ) .'"></div>';

      echo wp_kses_post($this->field_after());

    }

  }
}
