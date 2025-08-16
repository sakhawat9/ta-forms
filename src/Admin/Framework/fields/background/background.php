<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
use ThemeAtelier\TaForms\Admin\Framework\Classes\TaForms;

if ( ! class_exists( 'Ta_Forms_Field_background' ) ) {
  class Ta_Forms_Field_background extends Ta_Forms_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args                             = wp_parse_args( $this->field, array(
        'background_color'              => true,
        'background_image'              => true,
        'background_position'           => true,
        'background_repeat'             => true,
        'background_attachment'         => true,
        'background_size'               => true,
        'background_origin'             => false,
        'background_clip'               => false,
        'background_blend_mode'         => false,
        'background_gradient'           => false,
        'background_gradient_color'     => true,
        'background_gradient_direction' => true,
        'background_image_preview'      => true,
        'background_auto_attributes'    => false,
        'compact'                       => false,
        'background_image_library'      => 'image',
        'background_image_placeholder'  => esc_html__( 'Not selected', 'ta-forms' ),
      ) );

      if ( $args['compact'] ) {
        $args['background_color']           = false;
        $args['background_auto_attributes'] = true;
      }

      $default_value                    = array(
        'background-color'              => '',
        'background-image'              => '',
        'background-position'           => '',
        'background-repeat'             => '',
        'background-attachment'         => '',
        'background-size'               => '',
        'background-origin'             => '',
        'background-clip'               => '',
        'background-blend-mode'         => '',
        'background-gradient-color'     => '',
        'background-gradient-direction' => '',
      );

      $default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

      $this->value = wp_parse_args( $this->value, $default_value );

      echo wp_kses_post($this->field_before());

      echo '<div class="ta-forms--background-colors">';

      //
      // Background Color
      if ( ! empty( $args['background_color'] ) ) {

        echo '<div class="ta-forms--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="ta-forms--title">'. esc_html__( 'From', 'ta-forms' ) .'</div>' : '';

        TaForms::field( array(
          'id'      => 'background-color',
          'type'    => 'color',
          'default' => $default_value['background-color'],
        ), $this->value['background-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Color
      if ( ! empty( $args['background_gradient_color'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="ta-forms--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="ta-forms--title">'. esc_html__( 'To', 'ta-forms' ) .'</div>' : '';

        TaForms::field( array(
          'id'      => 'background-gradient-color',
          'type'    => 'color',
          'default' => $default_value['background-gradient-color'],
        ), $this->value['background-gradient-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Direction
      if ( ! empty( $args['background_gradient_direction'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="ta-forms--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="ta-forms---title">'. esc_html__( 'Direction', 'ta-forms' ) .'</div>' : '';

        TaForms::field( array(
          'id'          => 'background-gradient-direction',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Gradient Direction', 'ta-forms' ),
            'to bottom' => esc_html__( '&#8659; top to bottom', 'ta-forms' ),
            'to right'  => esc_html__( '&#8658; left to right', 'ta-forms' ),
            '135deg'    => esc_html__( '&#8664; corner top to right', 'ta-forms' ),
            '-135deg'   => esc_html__( '&#8665; corner top to left', 'ta-forms' ),
          ),
        ), $this->value['background-gradient-direction'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      echo '</div>';

      //
      // Background Image
      if ( ! empty( $args['background_image'] ) ) {

        echo '<div class="ta-forms--background-image">';

        TaForms::field( array(
          'id'          => 'background-image',
          'type'        => 'media',
          'class'       => 'ta-forms-assign-field-background',
          'library'     => $args['background_image_library'],
          'preview'     => $args['background_image_preview'],
          'placeholder' => $args['background_image_placeholder'],
          'attributes'  => array( 'data-depend-id' => $this->field['id'] ),
        ), $this->value['background-image'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      $auto_class   = ( ! empty( $args['background_auto_attributes'] ) ) ? ' ta-forms--auto-attributes' : '';
      $hidden_class = ( ! empty( $args['background_auto_attributes'] ) && empty( $this->value['background-image']['url'] ) ) ? ' ta-forms--attributes-hidden' : '';

      echo '<div class="ta-forms--background-attributes'. esc_attr( $auto_class . $hidden_class ) .'">';

      //
      // Background Position
      if ( ! empty( $args['background_position'] ) ) {

        TaForms::field( array(
          'id'              => 'background-position',
          'type'            => 'select',
          'options'         => array(
            ''              => esc_html__( 'Background Position', 'ta-forms' ),
            'left top'      => esc_html__( 'Left Top', 'ta-forms' ),
            'left center'   => esc_html__( 'Left Center', 'ta-forms' ),
            'left bottom'   => esc_html__( 'Left Bottom', 'ta-forms' ),
            'center top'    => esc_html__( 'Center Top', 'ta-forms' ),
            'center center' => esc_html__( 'Center Center', 'ta-forms' ),
            'center bottom' => esc_html__( 'Center Bottom', 'ta-forms' ),
            'right top'     => esc_html__( 'Right Top', 'ta-forms' ),
            'right center'  => esc_html__( 'Right Center', 'ta-forms' ),
            'right bottom'  => esc_html__( 'Right Bottom', 'ta-forms' ),
          ),
        ), $this->value['background-position'], $this->field_name(), 'field/background' );

      }

      //
      // Background Repeat
      if ( ! empty( $args['background_repeat'] ) ) {

        TaForms::field( array(
          'id'          => 'background-repeat',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Background Repeat', 'ta-forms' ),
            'repeat'    => esc_html__( 'Repeat', 'ta-forms' ),
            'no-repeat' => esc_html__( 'No Repeat', 'ta-forms' ),
            'repeat-x'  => esc_html__( 'Repeat Horizontally', 'ta-forms' ),
            'repeat-y'  => esc_html__( 'Repeat Vertically', 'ta-forms' ),
          ),
        ), $this->value['background-repeat'], $this->field_name(), 'field/background' );

      }

      //
      // Background Attachment
      if ( ! empty( $args['background_attachment'] ) ) {

        TaForms::field( array(
          'id'       => 'background-attachment',
          'type'     => 'select',
          'options'  => array(
            ''       => esc_html__( 'Background Attachment', 'ta-forms' ),
            'scroll' => esc_html__( 'Scroll', 'ta-forms' ),
            'fixed'  => esc_html__( 'Fixed', 'ta-forms' ),
          ),
        ), $this->value['background-attachment'], $this->field_name(), 'field/background' );

      }

      //
      // Background Size
      if ( ! empty( $args['background_size'] ) ) {

        TaForms::field( array(
          'id'        => 'background-size',
          'type'      => 'select',
          'options'   => array(
            ''        => esc_html__( 'Background Size', 'ta-forms' ),
            'cover'   => esc_html__( 'Cover', 'ta-forms' ),
            'contain' => esc_html__( 'Contain', 'ta-forms' ),
            'auto'    => esc_html__( 'Auto', 'ta-forms' ),
          ),
        ), $this->value['background-size'], $this->field_name(), 'field/background' );

      }

      //
      // Background Origin
      if ( ! empty( $args['background_origin'] ) ) {

        TaForms::field( array(
          'id'            => 'background-origin',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Origin', 'ta-forms' ),
            'padding-box' => esc_html__( 'Padding Box', 'ta-forms' ),
            'border-box'  => esc_html__( 'Border Box', 'ta-forms' ),
            'content-box' => esc_html__( 'Content Box', 'ta-forms' ),
          ),
        ), $this->value['background-origin'], $this->field_name(), 'field/background' );

      }

      //
      // Background Clip
      if ( ! empty( $args['background_clip'] ) ) {

        TaForms::field( array(
          'id'            => 'background-clip',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Clip', 'ta-forms' ),
            'border-box'  => esc_html__( 'Border Box', 'ta-forms' ),
            'padding-box' => esc_html__( 'Padding Box', 'ta-forms' ),
            'content-box' => esc_html__( 'Content Box', 'ta-forms' ),
          ),
        ), $this->value['background-clip'], $this->field_name(), 'field/background' );

      }

      //
      // Background Blend Mode
      if ( ! empty( $args['background_blend_mode'] ) ) {

        TaForms::field( array(
          'id'            => 'background-blend-mode',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Blend Mode', 'ta-forms' ),
            'normal'      => esc_html__( 'Normal', 'ta-forms' ),
            'multiply'    => esc_html__( 'Multiply', 'ta-forms' ),
            'screen'      => esc_html__( 'Screen', 'ta-forms' ),
            'overlay'     => esc_html__( 'Overlay', 'ta-forms' ),
            'darken'      => esc_html__( 'Darken', 'ta-forms' ),
            'lighten'     => esc_html__( 'Lighten', 'ta-forms' ),
            'color-dodge' => esc_html__( 'Color Dodge', 'ta-forms' ),
            'saturation'  => esc_html__( 'Saturation', 'ta-forms' ),
            'color'       => esc_html__( 'Color', 'ta-forms' ),
            'luminosity'  => esc_html__( 'Luminosity', 'ta-forms' ),
          ),
        ), $this->value['background-blend-mode'], $this->field_name(), 'field/background' );

      }

      echo '</div>';

      echo wp_kses_post($this->field_after());

    }

    public function output() {

      $output    = '';
      $bg_image  = array();
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

      // Background image and gradient
      $background_color        = ( ! empty( $this->value['background-color']              ) ) ? $this->value['background-color']              : '';
      $background_gd_color     = ( ! empty( $this->value['background-gradient-color']     ) ) ? $this->value['background-gradient-color']     : '';
      $background_gd_direction = ( ! empty( $this->value['background-gradient-direction'] ) ) ? $this->value['background-gradient-direction'] : '';
      $background_image        = ( ! empty( $this->value['background-image']['url']       ) ) ? $this->value['background-image']['url']       : '';


      if ( $background_color && $background_gd_color ) {
        $gd_direction   = ( $background_gd_direction ) ? $background_gd_direction .',' : '';
        $bg_image[] = 'linear-gradient('. $gd_direction . $background_color .','. $background_gd_color .')';
        unset( $this->value['background-color'] );
      }

      if ( $background_image ) {
        $bg_image[] = 'url('. $background_image .')';
      }

      if ( ! empty( $bg_image ) ) {
        $output .= 'background-image:'. implode( ',', $bg_image ) . $important .';';
      }

      // Common background properties
      $properties = array( 'color', 'position', 'repeat', 'attachment', 'size', 'origin', 'clip', 'blend-mode' );

      foreach ( $properties as $property ) {
        $property = 'background-'. $property;
        if ( ! empty( $this->value[$property] ) ) {
          $output .= $property .':'. $this->value[$property] . $important .';';
        }
      }

      if ( $output ) {
        $output = $element .'{'. $output .'}';
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}
