<?php
class RotateAdWidget extends WP_Widget
{

  function RotateAdWidget()
  {
    $widget_ops = array('classname' => 'RotateAdWidget', 'description' => 'Rotates Ads' );
    $this->WP_Widget('RotateAdWidget', 'Rotates Ads', $widget_ops);
    if( is_active_widget( '', '', $this->id_base ) ) { 
        if (!is_admin()) {
          wp_enqueue_script('jquery');
        }
        
        wp_enqueue_style( 'rotating_ad_admin_style', ROTATING_AD_CORE_URL . '/css/rotating_ad_admin.css' );

    }
  }
 
  function form($instance)
  {
    global $wpdb;
    $select_sql = "SELECT id, name FROM " . $wpdb->prefix . "rotating_ad_groups ORDER BY id";
    $group_options = $wpdb->get_results($select_sql);

    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
    $selected = esc_attr($instance['active_group']);
    $seconds = $instance['seconds'];
    
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <p>
    <label for="<?php echo $this->get_field_id('active_group'); ?>"><?php _e('Choose Ad Group'); ?></label>
    <select name="<?php echo $this->get_field_name('active_group'); ?>" id="<?php echo $this->get_field_id('active_group'); ?>" class="widefat">
      <?php
      //$options = array('ad group one', 'ad group two', 'ad group three');
      foreach ($group_options as $option) {
        echo '<option value="' . $option->id . '" id="' . $option->name . '"', $selected == $option->id ? ' SELECTED' : '', '>', $option->name, '</option>';
      }
      ?>
    </select>
    <label for="<?php echo $this->get_field_id('random_order'); ?>"><?php _e('Random start?'); ?></label>
    <input type="checkbox" id="<?php echo $this->get_field_id('random_order'); ?>" name="<?php echo $this->get_field_name('random_order'); ?>" <?php checked(isset($instance['random_order']) ? 1 : 0); ?> />
    <br />
    <label for="<?php echo $this->get_field_id('ad_seconds'); ?>"><?php _e('Number of seconds to show each ad'); ?></label>
    <input type="text" size="2" value="<?php echo $seconds; ?>" id="<?php echo $this->get_field_id('seconds'); ?>" name="<?php echo $this->get_field_name('seconds'); ?>" />
  </p>
    <?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['active_group'] = strip_tags($new_instance['active_group']);
    $instance['random_order'] = $new_instance['random_order'];
    $instance['seconds'] = round($new_instance['seconds']);
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
    global $wpdb;
    wp_enqueue_script('rotating_ad_js', ROTATING_AD_CORE_URL . '/js/rotating_ad.js');
    $seconds = $instance['seconds'];
    wp_localize_script( 'rotating_ad_js', 'ra_vars', array('seconds'=>$seconds) );

    $images = $wpdb->get_results("SELECT id, image, link FROM " .$wpdb->prefix . "rotating_ad WHERE group_id = " . $instance['active_group'] . " ORDER BY id");
    $group = $wpdb->get_results("SELECT size FROM " . $wpdb->prefix . "rotating_ad_groups WHERE id = '" . $instance['active_group'] ."'" );
    
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
    // TODO: finish this
    $start = 1;
    
    if($instance['random_order'] == 'on'){
      $start = rand(0, count($images)-1);
    }
    echo "<div id='rotating_ads' class='_".$group[0]->size."'>";
    foreach($images as $key => $image){
      echo "<a href='".$image->link."' target='__blank' ";
      if($key == $start){
        echo "class='active'";
      }
      echo "><img class='_".$group[0]->size."' src='".$image->image."' /></a>";
    }
    echo "</div>";
 
    echo $after_widget;
  }
 
}
?>