<?php if (! defined('ABSPATH')) {
  die;
} // Cannot access directly.
/**
 *
 * Field: section_tab
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

use ThemeAtelier\TaForms\Admin\Framework\Classes\TaForms;

if (! class_exists('Ta_Forms_Field_section_tab')) {
  class Ta_Forms_Field_section_tab extends Ta_Forms_Fields
  {

    public function __construct($field, $value = '', $unique = '', $where = '', $parent = '')
    {
      parent::__construct($field, $value, $unique, $where, $parent);
    }

    public function render()
    {

      $unallows = array('section_tab');

      echo wp_kses_post($this->field_before());

      echo '<div class="ta-forms-section_tab-nav">';
      $tab_key = 1;
      foreach ($this->field['tabs'] as $key => $tab) {
        $section_tab_icon   = (! empty($tab['icon'])) ? '<i class="ta-forms--icon ' . esc_attr($tab['icon']) . '"></i>' : '';
        $section_tab_active = (empty($key)) ? 'ta-forms-section_tab-active' : '';
        echo '<a href="#" class="' . esc_attr($section_tab_active) . '">' . $section_tab_icon . esc_attr($tab['title']) . '</a>';
        $tab_key++;
      }
      echo '</div>';

      echo '<div class="ta-forms-section_tab-sections">';
      $section_key = 1;
      foreach ($this->field['tabs'] as $key => $tab) {

        $section_tab_hidden = (! empty($key)) ? ' hidden' : '';

        echo '<div class="ta-forms-section_tab-content' . esc_attr($section_tab_hidden) . '">';

        foreach ($tab['fields'] as $field) {

          if (in_array($field['type'], $unallows)) {
            $field['_notice'] = true;
          }

          $field_id      = (isset($field['id'])) ? $field['id'] : '';
          $field_default = (isset($field['default'])) ? $field['default'] : '';
          $field_value   = (isset($this->value[$field_id])) ? $this->value[$field_id] : $field_default;
          $unique_id     = (! empty($this->unique)) ? $this->unique : '';

          TaForms::field($field, $field_value, $unique_id, 'field/section_tab');
        }

        echo '</div>';
      }
      echo '</div>';

      echo wp_kses_post($this->field_after());
    }
  }
}
