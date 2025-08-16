<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'Ta_Forms_Field_icon' ) ) {
  class Ta_Forms_Field_icon extends Ta_Forms_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'button_title' => esc_html__( 'Add Icon', 'ta-forms' ),
        'remove_title' => esc_html__( 'Remove Icon', 'ta-forms' ),
      ) );

      echo wp_kses_post($this->field_before());

      $nonce  = wp_create_nonce( 'Ta_Forms_icon_nonce' );
      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo '<div class="ta-forms-icon-select">';
      echo '<span class="ta-forms-icon-preview'. esc_attr( $hidden ) .'"><i class="'. esc_attr( $this->value ) .'"></i></span>';
      echo '<a href="#" class="button button-primary ta-forms-icon-add" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html($args['button_title']) .'</a>';
      echo '<a href="#" class="button ta-forms-warning-primary ta-forms-icon-remove'. esc_attr( $hidden ) .'">'. wp_kses_post($args['remove_title']) .'</a>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'" class="ta-forms-icon-value"'. $this->field_attributes() .' />';
      echo '</div>';

      echo wp_kses_post($this->field_after());

    }

    public function enqueue() {
      add_action( 'admin_footer', array( 'Ta_Forms_Field_icon', 'add_footer_modal_icon' ) );
      add_action( 'customize_controls_print_footer_scripts', array( 'Ta_Forms_Field_icon', 'add_footer_modal_icon' ) );
    }

    public static function add_footer_modal_icon() {
    ?>
      <div id="ta-forms-modal-icon" class="ta-forms-modal ta-forms-modal-icon hidden">
        <div class="ta-forms-modal-table">
          <div class="ta-forms-modal-table-cell">
            <div class="ta-forms-modal-overlay"></div>
            <div class="ta-forms-modal-inner">
              <div class="ta-forms-modal-title">
                <?php esc_html_e( 'Add Icon', 'ta-forms' ); ?>
                <div class="ta-forms-modal-close ta-forms-icon-close"></div>
              </div>
              <div class="ta-forms-modal-header">
                <input type="text" placeholder="<?php esc_html_e( 'Search...', 'ta-forms' ); ?>" class="ta-forms-icon-search" />
              </div>
              <div class="ta-forms-modal-content">
                <div class="ta-forms-modal-loading"><div class="ta-forms-loading"></div></div>
                <div class="ta-forms-modal-load"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }

  }
}
