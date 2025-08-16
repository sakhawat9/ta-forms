<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'Chat_Help_Pro_Field_backup' ) ) {
  class Chat_Help_Pro_Field_backup extends Chat_Help_Pro_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unique = $this->unique;
      $nonce  = wp_create_nonce( 'Chat_Help_Pro_backup_nonce' );
      $export = add_query_arg( array( 'action' => 'ta-forms-export', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

      echo wp_kses_post($this->field_before());

      echo '<textarea name="Chat_Help_Pro_import_data" class="ta-forms-import-data"></textarea>';
      echo '<button type="submit" class="button button-primary ta-forms-confirm ta-forms-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Import', 'ta-forms' ) .'</button>';
      echo '<hr />';
      echo '<textarea readonly="readonly" class="ta-forms-export-data">'. esc_attr( wp_json_encode( get_option( $unique ) ) ) .'</textarea>';
      echo '<a href="'. esc_url( $export ) .'" class="button button-primary ta-forms-export" target="_blank">'. esc_html__( 'Export & Download', 'ta-forms' ) .'</a>';
      echo '<hr />';
      echo '<button type="submit" name="Chat_Help_Pro_transient[reset]" value="reset" class="button ta-forms-warning-primary ta-forms-confirm ta-forms-reset" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Reset', 'ta-forms' ) .'</button>';

      echo wp_kses_post($this->field_after());

    }

  }
}
