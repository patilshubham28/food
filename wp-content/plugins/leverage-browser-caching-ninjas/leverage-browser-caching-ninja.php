<?php
/*
Plugin Name: Leverage Browser Caching Ninja
Plugin URI: http://wordpress.org/plugins/leverage-browser-caching-ninja/
Description: Leverage Browser Caching Ninja
Version: 2.1
Author: CustomWPNinjas
Author URI: http://www.CustomWPNinjas.com/
Contributor: Ishan Kukadia
Tested up to: 4.1
*/

/*  Copyright 2012, CustomWPNinjas.com.

Permission to use, copy, modify, and/or distribute this software for any purpose
with or without fee is strickly prohibited.

*/
// Register the activation hook to install
register_activation_hook( __FILE__, 'LBCachingNinja_install' );
register_deactivation_hook( __FILE__, 'LBCachingNinja_uninstall' );

add_action('admin_menu', 'LBCachingNinja_menu');

function LBCachingNinja_menu() {
	add_menu_page( 'Leverage Browser Caching Ninjas', 'Leverage Browser Caching Ninjas', 'activate_plugins', 'leverage-browser-caching-ninjas/leverage-browser-caching-ninja-setting.php' );
}

if( !function_exists( 'LBCachingNinja_install' ) ) {
	function LBCachingNinja_install() {
		$status = true;
		$admin_email = get_option( 'admin_email' );
		$backup_filename = 'LBCachingNinja_install_backup' . time() . '.htaccess';
		if(file_exists(ABSPATH . '.htaccess')) {
			if(!copy ( ABSPATH . '.htaccess' , ABSPATH . $backup_filename )) {
				$status = false;
				$msg = 'Can not create backup file.';
			}
			$status = LBCachingNinja_write_file(ABSPATH . '.htaccess');
		} else {
			$status = LBCachingNinja_write_file(ABSPATH . '.htaccess');
		}
		$backup_filename = 'LBCachingNinja_uninstall_backup' . time() . '.htaccess';
		LBCachingNinja_erase_file(false, $backup_filename);
		add_filter( 'wp_mail_content_type', 'LBCachingNinja_set_content_type' );
		$message = 'Dear User,<br /><br />Leverage Browser Caching Ninja Plugin  has been successfully installed on '.site_url().' installed successfully.<br /><br />In case you find any problem after installing this plugin, replace '.$backup_filename.' with .htaccess<br /><br />Leverage Browser Caching is a very natural and very simple fix that does not cause problems with your website.  All browsers use it and almost every server uses it. The reason that it will sometimes fail is based on your server\'s restrictions.  The reason you probably installed this software in the first place is because of your website seeming slow or sluggish - we understand. Ours was too.<br /><br />Then we began to understand - wait - not all servers are created equally.  If you are unhappy with your current site speed or server setup, we invite you to give us a try.  We are WordPress only and consider your business to be a valuable resource, not a bother.  <a href="https://www.wphostingninjas.com/">https://www.wphostingninjas.com/</a> shows our three most used setups with FREE transfers and FREE plugin tune ups.<br /><br />If, after installing, you notice that your website is not responding, quickly email us <a href="ninjas@customwpninjas.com">ninjas@customwpninjas.com</a> or call 1.866.264.6527 in the US.  Send us your FTP login information if you feel comfortable and we will have it fixed right away.<br /><br />Thank you for installing our plugin and we hope your site becomes very fast!<br /><br />-= Ninjas =-<br /><a href="http://www.customwpninjas.com">http://www.customwpninjas.com</a>';
		wp_mail( $admin_email, 'Leverage Browser Caching Ninja Install Email', $message );
		remove_filter( 'wp_mail_content_type', 'LBCachingNinja_set_content_type' );
		if($status) {
			update_option( 'LBCachingNinja_status', 1);
		} else {
			update_option( 'LBCachingNinja_status', 0);
		}
		return $status;
	}
	
	function LBCachingNinja_write_file( $file_path ){
		$status = true;
		$wouldblock = 1;
		if (is_writable($file_path)) {
			$fp = fopen($file_path, "a");
			if (flock($fp, LOCK_EX, $wouldblock)) {  // acquire an exclusive lock
				fwrite($fp, "# Leverage Browser Caching Ninja -- Starts here\n");
				fwrite($fp, "# Do not write anything between \"Leverage Browser Caching Ninja -- Starts\" and \"Leverage Browser Caching Ninja -- Ends\"\n");
				fwrite($fp, "# It will be deleted while uninstalling Leverage Browser Caching Ninja plugin\n");
				fwrite($fp, "<IfModule mod_expires.c>\n");
				fwrite($fp, "ExpiresActive On \n");
				fwrite($fp, "ExpiresDefault \"access plus 1 month\" \n");
				fwrite($fp, "ExpiresByType image/x-icon \"access plus 1 year\" \n");
				fwrite($fp, "ExpiresByType image/gif \"access plus 1 month\" \n");
				fwrite($fp, "ExpiresByType image/png \"access plus 1 month\" \n");
				fwrite($fp, "ExpiresByType image/jpg \"access plus 1 month\" \n");
				fwrite($fp, "ExpiresByType image/jpeg \"access plus 1 month\" \n");
				fwrite($fp, "ExpiresByType text/css \"access 1 month\" \n");
				fwrite($fp, "ExpiresByType application/javascript \"access plus 1 year\" \n");
				fwrite($fp, "</IfModule> \n");
				fwrite($fp, "# Leverage Browser Caching Ninja -- Ends here\n");
				fflush($fp);
				flock($fp, LOCK_UN, $wouldblock);    // release the lock
			} else {
				$status = false;
			}
			fclose($fp);
			$status = true;
		} else {
			$status = false;
		}
		return $status;
	}
	
	function LBCachingNinja_uninstall() {
		$admin_email = get_option( 'admin_email' );
		$backup_filename = 'LBCachingNinja_uninstall_backup' . time() . '.htaccess';
		$status = LBCachingNinja_erase_file(true, $backup_filename);
		add_filter( 'wp_mail_content_type', 'LBCachingNinja_set_content_type' );
		$message = 'Dear User,<br /><br />Leverage Browser Caching Ninja  Plugin has been successfully uninstalled on '.site_url().' successfully.<br /><br />In case you find any problem after uninstalling this plugin, replace '.$backup_filename.' with .htaccess<br /><br />If the plugin did not work for you, let us know.  Email <a href="mailto:ninjas@customwpninjas.com">ninjas@customwpninjas.com</a> or call 1-866-264-6527 for assistance. Thank you for trying our plugin and we will be more than happy to help you set it up properly or help you in finding a new hosting account with <a href="https://www.wphostingninjas.com">https://www.wphostingninjas.com</a><br /><br />-= Ninjas =-<br /><a href="http://www.customwpninjas.com">http://www.customwpninjas.com</a>';
		wp_mail( $admin_email, 'Leverage Browser Caching Ninja Uninstall Email', $message );
		remove_filter( 'wp_mail_content_type', 'LBCachingNinja_set_content_type' );
		if($status) {
			update_option( 'LBCachingNinja_status', 0);
		} else {
			update_option( 'LBCachingNinja_status', 1);
		}
		return $status;
	}
	
	function LBCachingNinja_erase_file($backup, $backup_filename){
		
		$status = false;
		$wouldblock = 1;
		
		if($backup) {
			if(copy ( ABSPATH . '.htaccess' , ABSPATH . $backup_filename )) {
				$status = true;
			} else {
				$status = false;
				$msg = 'Can not create backup file.';
			}
			if(!$status) {
				return;
			}
		}
		if($status) {
			$fp = fopen(ABSPATH . '.htaccess', "w");
			$lines = file( ABSPATH . $backup_filename );
			
			if (flock($fp, LOCK_EX, $wouldblock)) {  // acquire an exclusive lock
				ftruncate($fp, 0);      // truncate file
				$inLoop = false;
				foreach($lines as $line) {
					if(strpos($line, 'Leverage Browser Caching Ninja -- Starts here') !== false) {
						$inLoop = true;
					}
					if(strpos($line, 'Leverage Browser Caching Ninja -- Ends here') !== false) {
						$inLoop = false;
						continue;
					}
					if(!$inLoop) {
						fwrite($fp, $line);
						fflush($fp);
					}
				}
				flock($fp, LOCK_UN, $wouldblock);    // release the lock
			} else {
				$status = false;
				$msg = 'Couldn\'t get the lock!';
			}
			fclose($fp);
		}
		return $status;
	}

	function LBCachingNinja_set_content_type( $content_type ){
		return 'text/html';
	}
}