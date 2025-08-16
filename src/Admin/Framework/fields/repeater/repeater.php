<?php
/**
 *
 * Field: repeater
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
use ThemeAtelier\TaForms\Admin\Framework\Classes\TaForms;

if ( ! class_exists( 'Chat_Help_Pro_Field_repeater' ) ) {
  class Chat_Help_Pro_Field_repeater extends Chat_Help_Pro_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'max'          => 0,
        'min'          => 0,
        'button_title' => '<i class="icofont-plus"></i>',
      ) );

      if ( preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->unique ) ) {

        echo '<div class="ta-forms-notice ta-forms-notice-danger">'. esc_html__( 'Error: Field ID conflict.', 'ta-forms' ) .'</div>';

      } else {

        echo wp_kses_post($this->field_before());

        echo '<div class="ta-forms-repeater-item ta-forms-repeater-hidden" data-depend-id="'. esc_attr( $this->field['id'] ) .'">';
        echo '<div class="ta-forms-repeater-content">';
        foreach ( $this->field['fields'] as $field ) {

          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
          $field_unique  = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .'][0]' : $this->field['id'] .'[0]';

          TaForms::field( $field, $field_default, '___'. $field_unique, 'field/repeater' );

        }
        echo '</div>';
        echo '<div class="ta-forms-repeater-helper">';
        echo '<div class="ta-forms-repeater-helper-inner">';
        echo '<i class="ta-forms-repeater-sort icofont-drag"></i>';
        echo '<i class="ta-forms-repeater-clone icofont-copy-invert"></i>';
        echo '<i class="ta-forms-repeater-remove ta-forms-confirm icofont-close" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'ta-forms' ) .'"></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="ta-forms-repeater-wrapper ta-forms-data-wrapper" data-field-id="['. esc_attr( $this->field['id'] ) .']" data-max="'. esc_attr( $args['max'] ) .'" data-min="'. esc_attr( $args['min'] ) .'">';

        if ( ! empty( $this->value ) && is_array( $this->value ) ) {

          $num = 0;

          foreach ( $this->value as $key => $value ) {

            echo '<div class="ta-forms-repeater-item">';
            echo '<div class="ta-forms-repeater-content">';
            foreach ( $this->field['fields'] as $field ) {

              $field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
              $field_value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';

              TaForms::field( $field, $field_value, $field_unique, 'field/repeater' );

            }
            echo '</div>';
            echo '<div class="ta-forms-repeater-helper">';
            echo '<div class="ta-forms-repeater-helper-inner">';
            echo '<i class="ta-forms-repeater-sort icofont-drag"></i>';
            echo '<i class="ta-forms-repeater-clone icofont-copy-invert"></i>';
            echo '<i class="ta-forms-repeater-remove ta-forms-confirm icofont-close" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'ta-forms' ) .'"></i>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            $num++;

          }

        }

        echo '</div>';

        echo '<div class="ta-forms-repeater-alert ta-forms-repeater-max">'. esc_html__( 'You cannot add more.', 'ta-forms' ) .'</div>';
        echo '<div class="ta-forms-repeater-alert ta-forms-repeater-min">'. esc_html__( 'You cannot remove more.', 'ta-forms' ) .'</div>';
        echo '<a href="#" class="button button-primary ta-forms-repeater-add">'. wp_kses_post($args['button_title']) .'</a>';

        echo wp_kses_post($this->field_after());

      }

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}
