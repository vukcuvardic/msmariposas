<?php
/*
Plugin Name: Media Library Plus Reset
Plugin URI: http://maxgalleria.com
Description: Plugin for reseting Media Library Plus
Author: Max Foundry
Author URI: http://maxfoundry.com
Version: 3.0.12
Copyright 2015 Max Foundry, LLC (http://maxfoundry.com)

*/


function mlp_reset_menu() {
  add_menu_page('Media Library Plus Reset', 'Media Library Plus Reset', 'manage_options', 'mlp-reset', 'mlp_reset' );
  add_submenu_page('mlp-reset', 'Display Attachment URLs', 'Display Attachment URLs', 'manage_options', 'mlpr-show-attachments', 'mlpr_show_attachments');
  add_submenu_page('mlp-reset', 'Display Folder Data', 'Display Folder Data', 'manage_options', 'mlpr-show-folders', 'mlpr_show_folders');
  add_submenu_page('mlp-reset', 'Reset Database', 'Reset Database', 'manage_options', 'clean_database', 'clean_database');
}
add_action('admin_menu', 'mlp_reset_menu');

function mlp_reset() {

	echo "<h3>Media Library Plus Reset Instructions</h3>";
  echo "<h4>If you need to rescan your database, please deactivate the Media Library Plus plugin and then click Media Library Plus Reset->Reset Database to erase the folder data. Then deactivate Media Library Plus Reset and reactivate Media Library Plus which will perform a fresh scan of your database.</h4>";
  
}

function clean_database() {  
    global $wpdb;
    
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mgmlp_upload_folder_name'";
    $wpdb->query($sql);
    
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mgmlp_upload_folder_id'";
    $wpdb->query($sql);
		
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mgmlp_database_checked'";
    $wpdb->query($sql);
		        
    echo "Deleteing mgmlp_folders<br>";
    
    $sql = "TRUNCATE TABLE $wpdb->prefix" . "mgmlp_folders";
    $wpdb->query($sql);
    
    $sql = "DROP TABLE $wpdb->prefix" . "mgmlp_folders";    
    $wpdb->query($sql);
    
    echo "Removing mgmlp_media_folder posts<br>";
    $sql = "delete from $wpdb->prefix" . "posts where post_type = 'mgmlp_media_folder'";
    $wpdb->query($sql);
    
    echo "Done. You can now reactivate Media Library Plus.<br>";
  
}

function mlpr_show_attachments () {
  global $wpdb;
  $sql = "select ID, guid from $wpdb->prefix" . "posts where post_type = 'attachment' order by ID";
  //echo $sql;
	
	echo "<h2>Attachment URLs</h2>";


  $rows = $wpdb->get_results($sql);
	?>
	<table>
		<tr>
			<th>Attachment ID</th>
			<th>Attachment URL</th>
		</tr>	
    
  <?php  
  
  foreach($rows as $row) {
	  ?>
		<tr>
			<td><?php echo $row->ID; ?></td>	
			<td><?php echo $row->guid; ?></td>	
		</tr>
    <?php				
  }    
	?>
	</table>
  <?php
}

function mlpr_show_folders() {
  global $wpdb;
	
	echo "<h2>Folder URLs</h2>";
  
  $upload_dir = wp_upload_dir();  
  
  $upload_dir1 = $upload_dir['basedir'];
  
  echo "Uploads folder: " . $upload_dir1 . "<br>";
        
  echo "Uploads URL " . $upload_dir['baseurl'] . "<br><br>";

  
  $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
            
  $sql = "select distinct ID, guid, post_title, $folder_table.folder_id
from $wpdb->prefix" . "posts
LEFT JOIN $folder_table ON ($wpdb->prefix" . "posts.ID = $folder_table.post_id)
where post_type = 'mgmlp_media_folder' 
order by ID";
	  
  $rows = $wpdb->get_results($sql);
	
	?>
	<table>
		<tr>
			<th>Folder ID</th>
			<th>Folder Name</th>
			<th>Folder URL</th>
			<th>Parent ID</th>
		</tr>	
    
  <?php  
  foreach($rows as $row) {
	  ?>
		<tr>
			<td><?php echo $row->ID; ?></td>	
			<td><?php echo $row->post_title; ?></td>	
			<td><?php echo $row->guid; ?></td>	
			<td><?php echo $row->folder_id; ?></td>	
		</tr>
    <?php		
  }	
	?>
	</table>
  <?php
		  
}

