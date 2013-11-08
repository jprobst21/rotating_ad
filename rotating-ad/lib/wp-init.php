<?php
/*
Plugin Name: Rotating Ad Widget
Plugin URI: 
Description: Rotates a group of ads using a widget
Author: Josh Probst
Version: 0.0.1
Author URI: 
*/
/*  Copyright 2013  Josh Probst  (email : jprobst21@gmail.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once(WP_PLUGIN_DIR . '/rotating-ad/rotating-ad-install.php');

register_activation_hook( ROTATING_AD_FILE, 'ra_setup' );


#widget
include_once(ROTATING_AD_CORE_LIB . '/rotating-ad-widget.php');
add_action( 'widgets_init', create_function('', 'return register_widget("RotateAdWidget");') );
add_action( 'admin_init', 'ra_admin_init' );
add_action( 'plugins_loaded', 'rotating_ad_update_db_check' );
add_action( 'admin_menu', 'ra_menu' );
add_action( 'admin_enqueue_scripts', 'my_admin_scripts');
 

function my_admin_scripts() {
    if (isset($_GET['page']) && $_GET['page'] == 'rotating_ad') {
        wp_enqueue_media();
        wp_register_script('ra-admin-js', ROTATING_AD_CORE_URL . '/js/rotating_ad_admin.js', array('jquery'));
        wp_enqueue_script('ra-admin-js');
    }
}


function ra_setup(){
  ra_install();
  ra_install_data();
}

function ra_admin_init(){
  wp_register_style( 'rotating_ad_admin_style', ROTATING_AD_CORE_URL . '/css/rotating_ad_admin.css');
}


function rotating_ad_update_db_check() {
    global $ra_db_version;
    if (get_site_option( 'ra_db_version' ) != $ra_db_version) {
        ra_install();
    }
}


function ra_menu() {
  

  add_options_page( 'Rotating Ad Options', 'Rotating Ad', 'manage_options', 'rotating_ad', 'rotating_ad_plugin_options' );
  add_options_page( 'Manage Rotating Ad Groups', '', 'manage_options', 'rotating_ad_groups', 'rotating_ad_plugin_group_options' );
 
  wp_enqueue_style( 'rotating_ad_admin_style', ROTATING_AD_CORE_URL . '/css/rotating_ad_admin.css' );
}


function rotating_ad_plugin_group_options(){
  global $wpdb;
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  
  if( !empty($_POST) ){

    if( isset($_POST['id'])){
      $id = $_POST['id'];
      $name = $_POST['name'];
      $width = $_POST['width'];
      $height = $_POST['height'];

      $table_name = $wpdb->prefix . "rotating_ad_groups";

      $rows_affected = $wpdb->update( $table_name, array( 'name' => $name, 'width' => $width, 'height' => $height ), array( 'id' => $id));
    }
    else if( isset($_POST['name'])){
      $name = $_POST['name'];
      $width = $_POST['width'];
      $height = $_POST['height'];

      $table_name = $wpdb->prefix . "rotating_ad_groups";

      $rows_affected = $wpdb->insert( $table_name, array( 'name' => $name, 'width' => $width, 'height' => $height ) );
    }


  }

  $group_sql = "SELECT id, name, width, height FROM " . $wpdb->prefix . "rotating_ad_groups ORDER BY id";
    
  $group_options = $wpdb->get_results($group_sql);

  $manage_ads = add_query_arg(array('page' => 'rotating_ad'), admin_url('options-general.php'));

  ?>
  <div class="wrap">
    <h2>Manage Ad Groups</h2>
  </div>
  <div class="postbox-container metabox-holder meta-box-sortables" style="width: 45%">
    <div style="margin:0 5px;">
      <div><a href="<?php echo $manage_ads; ?>">Back to Manage Ads</a></div>
      <div class="postbox">
          <h3 class="hndle"><?php _e( 'Manage Rotating Ad Groups', 'colomat' ) ?></h3>
          <div class="inside">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th colspan=2>Size</th>
                </tr>
              </thead>
            <?php
            foreach($group_options as $group){
              echo "<tr><form name='form-".$group->id." action='' method='POST'>";
              echo "<td>".$group->id."<input type='hidden' name='id' value='".$group->id."' /></td>";
              echo "<td><input type='text' name='name' value=".$group->name." /></td>";
              /*echo "<td><select name='size'>";
              echo "<option";
              if($group->size == '250x250') echo ' SELECTED';
              echo ">250x250</option>";
              echo "<option";
              if($group->size == '300x250') echo ' SELECTED';
              echo ">300x250</option>";
              echo "<option";
              if($group->size == '240x400') echo ' SELECTED';
              echo ">240x400</option>";
              echo "<option";
              if($group->size == '500x300') echo ' SELECTED';
              echo ">500x300</option>";
              echo "<option";
              if($group->size == '800x200') echo ' SELECTED';
              echo ">800x200</option>";
              echo "</select></td>";*/
              echo "<td><label for='width'>width</label><input id='width' type='text' maxlength=4 size=4 name='width' value='".$group->width."' /></td><td><label for='height'>height</label><input id='height' type='text' maxlength=4 size=4 name='height' value='".$group->height."'/>";
              echo "<td><input type='submit' value='Save' />";
              echo "</form></tr>";
            }
            echo "<tr><form name='new-group' action='' method='POST'><td colspan=4><hr></td></tr>";
            echo "<tr><form name='form-new' action='' method='POST'>";
            echo "<td>&nbsp;</td>";
            echo "<td><input type='text' name='name' /></td>";
            /*echo "<td><select name='size'>";
            echo "<option>250x250</option>";
            echo "<option>300x250</option>";
            echo "<option>240x400</option>";
            echo "<option>500x300</option>";
            echo "<option>800x200</option>";
            echo "</select></td>";*/
            echo "<td><label for='width2'>width</label><input id='width2' type='text' maxlength=4 size=4 name='width' /></td><td><label for='height2'>height</label><input id='height2' type='text' maxlength=4 size=4 name='height' />";
            echo "<td><input type='submit' value='Add New' /></td>";
            echo "</form></tr>";
            ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php

}

/** Step 3. */
function rotating_ad_plugin_options() {
  global $wpdb;
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }

  $group_sql = "SELECT id, name, width, height FROM " . $wpdb->prefix . "rotating_ad_groups ORDER BY id";
    
  $group_options = $wpdb->get_results($group_sql);

  if( isset($_POST['groupselect'])){
    $selected_group = $_POST['groupselect'];
  }
  if( isset($_POST['Submit']) ){
    
    // for the uploaded file
    $filedest = '';
    if( isset($_POST['ad_image'])){
        $filename = end(explode('/', $_POST['ad_image']));

        $file_url = $_POST['ad_image'];

        //TODO: save record in rotating ad database
        $table_name = $wpdb->prefix . "rotating_ad";
        
        $selected_group = $_POST['group'];
        $file = $file_url;

        $insert_array = array('image' => $file, 'link' => $_POST['link'], 'group_id' => $selected_group);

        $rows_affected = $wpdb->insert( $table_name, $insert_array );
    }

  }
  else if(isset($_POST['delete'])){
    if(isset($_POST['delete_id'])){
      $table = $wpdb->prefix . "rotating_ad";
      $delete_id = $_POST['delete_id'];
      $wpdb->delete($table, array('id' => $delete_id));
    }
  }

  $selected_group = (isset($selected_group)) ? $selected_group : "1";

  //get group options
  
  $select_sql = "SELECT id, name FROM " . $wpdb->prefix . "rotating_ad_groups ORDER BY id";
  
  $group_options = $wpdb->get_results($select_sql);

  $image_select = $wpdb->get_results("SELECT id, image, link FROM " .$wpdb->prefix . "rotating_ad WHERE group_id = " . $selected_group . " ORDER BY id");

  ?>
  <div class="wrap">
    <h2>Rotating Ad Options</h2>
  </div>
  <div class="postbox-container metabox-holder meta-box-sortables" style="width: 69%">
      <div style="margin:0 5px;">
        <div class="postbox">
            <h3 class="hndle"><?php _e( 'Default Rotating Ad Settings', 'colomat' ) ?></h3>
            <div class="inside">

                <p><?php _e("Group:", 'menu-test' ); ?> 
                <form name='grpfrm' action='' method='POST'>
                <select name="groupselect" id="groupselect" onchange="this.form.submit();">
                  <?php
                  foreach ($group_options as $group){
                    echo "<option value='". $group->id ."' ";
                    if($group->id == $selected_group) echo "SELECTED";
                    echo ">".$group->name ."</option>";
                  }
                  $manage_groups_page = add_query_arg(array('page' => 'rotating_ad_groups'), admin_url('options-general.php'));
                  echo "</select>";
                  echo "</form>";
                  echo "<a href='". $manage_groups_page . "'>Manage groups</a>";
                  ?>
                </p>
                <p><?php
                  echo "<table class='wp-list-table widefat fixed display_group'>";
                  echo "<thead><tr><th>Image</th><th>Link</th><th class='remove'>Remove</th></tr></thead><tbody>";
                  foreach($image_select as $image){
                    echo "<form method='post' action=''>";
                    echo "<tr><td class='image'><img src='".$image->image."'/></td>";
                    echo "<td> ".$image->link."</td>";
                    echo "<td class='remove'><input type='submit' name='delete' value='Delete' /></td></tr>";
                    echo "<input type='hidden' name='delete_id' value='".$image->id."' />";
                    echo "</form>";
                  }
                  echo "</tbody></table>";
                ?></p>
                <hr />
                <h3>Add File</h3>
                <form name="form1" method="post" action="" enctype="multipart/form-data">
                  <label for="upload_image">
                    <input id="upload_image" type="text" size="36" name="ad_image" value="http://" /> 
                    <input id="upload_image_button" class="button" type="button" value="Upload Image" />
                    <br />Enter a URL or upload an image
                  </label>
                  <p>
                  Ad Link URL: <input type="text" name="link" id="link" size="30" value="http://" />
                  </p>

                  <p class="submit">
                  <?php 
                  echo "<input type=\"hidden\" name=\"group\" id=\"group\" value=\"$selected_group\" />"
                  ?>
                  <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
                  </p>

                </form>
            </div>
        </div>
      </div>
  </div>
  <?php
  
}
?>