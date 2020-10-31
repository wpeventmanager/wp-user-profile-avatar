<?php
/**
 *   Control file size function Page
 */
 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wp_Control_File_Size
{
  // upload_and_control_control_file_size_menu
  static function control_and_upload_increase_file_size_setting()
  {
   add_submenu_page('users.php','Control Upload File Size', 'WP Control File Size', 'manage_options', 'upload_control_file_size', array(__CLASS__, 'control_control_file_size_function'));
  } 

  static function get_closest($search, $arr)
  {
      $closest = null;
      foreach ($arr as $item) {
          if ($closest === null || abs($search - $closest) > abs($item - $search)) {
              $closest = $item;
          }
      }
      return $closest;
  } 

  static function init()
  {
    if (is_admin()) {
      add_action('admin_menu', array(__CLASS__, 'control_and_upload_increase_file_size_setting'));
       if (isset($_POST['control_and_upload_file_size']) 
          && wp_verify_nonce($_POST['control_and_upload_file_size_nonce'], 'control_and_upload_file_size_option')
          && is_numeric($_POST['control_and_upload_file_size'])) {
          $max_size = (int) $_POST['control_and_upload_file_size'] * 1024 * 1024;
          update_option('control_file_size', $max_size);
      }
    }
      
    add_filter('upload_size_limit', array(__CLASS__, 'control_increase_file_size'));
  } 
  
 static function control_control_file_size_function()
  {
    if (isset($_POST['submit'])) {
        echo '<div style="color:green"><p>Maximum Control and Upload File Size Setting has been Changed. </p></div>';
    }
	
    $wp_size = wp_max_upload_size();
    if (!$wp_size) {
        $wp_size = 'unknown';
    } else {
        $wp_size = round(($wp_size / 1024 / 1024));
        $wp_size = $wp_size == 1024 ? '1GB' : $wp_size . 'MB';
    }

    $max_size = get_option('control_file_size');
    if (!$max_size) {
        $max_size = 64 * 1024 * 1024;
    }
    $max_size = $max_size / 1024 / 1024;


    $upload_sizes = array(16, 32, 64, 128, 256, 512, 1024);

    $current_max_size = self::get_closest($max_size, $upload_sizes);
    echo '<form method="post">';
    settings_fields("header_section");
    echo '<table class="form-table"><tbody><tr><th scope="row"><label for="control_and_upload_file_size">Choose Max Upload File Size</label></th><td>';
    echo '<select id="control_and_upload_file_size" name="control_and_upload_file_size">';
    foreach ($upload_sizes as $size) {
        echo '<option value="' . $size . '" ' . ($size == $current_max_size ? 'selected' : '') . '>' . ($size == 1024 ? '1GB' : $size . 'MB') . '</option>';
    }
    echo '</select>';
    echo '</td></tr></tbody></table>';
    echo wp_nonce_field('control_and_upload_file_size_option', 'control_and_upload_file_size_nonce');
    submit_button();
    echo '</form>';
    

    echo '</div>';
  } 
  static function control_increase_file_size()
  {
      $max_size = (int) get_option('control_file_size');
      if (!$max_size) {
          $max_size = 64 * 1024 * 1024;
      }

      return $max_size;
  } 
  
  
} 
add_action('init', array('Wp_Control_File_Size', 'init'));
