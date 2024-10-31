<?php


/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.upwork.com/fl/rayhan1
 * @since             1.0.0
 * @package           Privado's GDPR/CCPA Cookie Consent Solution
 *
 * @wordpress-plugin
 * Plugin Name:       Privado's GDPR/CCPA Cookie Consent Solution
 * Plugin URI:        https://myrecorp.com/
 * Description:       Privado Cookie Consent automatically scans, categorizes and adds description to all cookies found on your website
 * Version:           1.0.1
 * Author:            Privado Inc
 * Author URI:        https://www.privado.ai/cookie-consent
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cookie-consent-solution
 * Domain Path:       /languages
 */

 if ( ! defined( 'WPINC' ) ) {
	die;
}


function rc_cookie_consent_solution() {

	$ccs_scripts = get_option('ccs_scripts');
		if (!empty($ccs_scripts)) {
			$ccs_scripts = html_entity_decode($ccs_scripts);
			$ccs_scripts = stripcslashes($ccs_scripts);
		}
	?>

	<div class="ccs_section">
		<h1><?php _e('Privado\'s GDPR/CCPA Cookie Consent Solution', 'cookie-consent-solution'); ?></h1>
		<div class="ccs_description">
			Privado’s cookie consent solution makes your website compliant with global privacy laws like ePrivacy, GDPR, and CCPA. The ePrivacy law requires you to take consent from users before you load a cookie. Our cookie solution auto-blocks cookies and pixels till the user gives consent. You can also target different consent types based on the geo-location for example in Europe you can set Opt-In(Auto-Block) and in California you can set Opt-Out(Load cookies and block if user opts-out) and Notice for the rest of the world.
		</div>
		<div class="textarea_section">
			<textarea name="ccs_scripts"><?php echo $ccs_scripts; ?></textarea>

			<div class="ccs_description">
				<div class="ccs_title">
					Instructions:
				</div>

				<ul>
					<li>1. Sign-Up to our cookie consent tool at <a href="https://app.privado.ai/signup" target="_blank">https://app.privado.ai/signup</a></li>
					<li>2. Go to the Integrations section from the left navigation menu</li>
					<li>3. Copy the script from the section: Script for Live Environment</li>
					<li>4. Paste the script in the above section and click on Save</li>
				</ul>
				<a href="https://youtu.be/1HefPy9VIU4">Click here to see the video instructions</a>

			</div>
		</div>
		
		<button id="save_ccs_scripts"><?php _e('Save', 'rc_ccs'); ?></button> <br><br> <span class="ccs_saved"><?php _e('Successfully Saved!', 'rc_ccs'); ?></span>
	</div>

<style>
.ccs_section textarea{
  width: 600px;
  min-height: 200px;
}
.ccs_saved {
	display: none;
	color: #fff;
	background-color: green;
	padding: 7px 50px;
	border-radius: 3px;
}
#save_ccs_scripts {
	border: none;
	background-color: #0073aa;
	color: #fff;
	padding: 8px;
	width: 75px;
	margin-top: 10px;
	border-radius: 3px;
	cursor: pointer;
}
.ccs_title {
	font-weight: bold;
	font-size: 20px;
}
.ccs_description {
	margin: 10px 0px;
}
@media (max-width: 700px){
  	.ccs_section textarea{
	    width: 300px;
	    min-height: 150px;
	}
}
</style>


<script>
	(function ($) {
		'use strict';
	
	  	$(document).on("click", "#save_ccs_scripts", function(e){
	  		e.preventDefault();

	  		var ccs_scripts = $('textarea[name="ccs_scripts"]').val();

	  		var encodedStr = ccs_scripts.replace(/[\u00A0-\u9999<>\&]/g, function(i) {
			   return '&#'+i.charCodeAt(0)+';';
			});
	  		 var datas = {
	  		  'action': 'rc_save_ccs_scripts',
	  		  'rc_nonce': '<?php echo wp_create_nonce( 'rc-nonce' ); ?>',
	  		  'ccs_scripts': encodedStr,
	  		};
	  		
	  		$.ajax({
	  		    url: '<?php echo admin_url('admin-ajax.php'); ?>',
	  		    data: datas,
	  		    type: 'post',
	  		    dataType: 'json',
	  		
	  		    beforeSend: function(){
	  		
	  		    },
	  		    success: function(r){
	  		      if(r.success == 'true'){
	  		        console.log(r.response);
	  				
	  				$('.ccs_saved').fadeIn(300);
	  				setTimeout(function() {
	  					$('.ccs_saved').fadeOut();
	  				}, 5000);
	  		        
	  		        } else {
	  		          console.log('Something went wrong, please try again!');
	  		        }
	  		    	
	  		    }, error: function(){
	  		    	
	  		  }
	  		});
	  	});


	})(jQuery);
</script>
	<?php
}

function rc_add_cookie_consent_solution() {
  add_options_page(
   'Privado\'s Cookie Consent Solution', 
    __('Privado\'s Cookie Consent Solution', "rc_ccs"), 
   'manage_options', 
   'cookie_consent_solution', 
   'rc_cookie_consent_solution'
  ); 
}

add_action( 'admin_menu', 'rc_add_cookie_consent_solution' );

function rc_save_ccs_scripts(){
	//$post = $_POST['post'];
	$ccs_scripts = isset($_POST['ccs_scripts']) ? sanitize_textarea_field($_POST['ccs_scripts']) : "";
	$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";


	if(!empty($nonce)){
		if(!wp_verify_nonce( $nonce, "rc-nonce" )){
			echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

			die();
		}
	}

	$response = $ccs_scripts;

	update_option('ccs_scripts', $response);


	echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));


	die();
}
add_action('wp_ajax_rc_save_ccs_scripts', 'rc_save_ccs_scripts');
add_action('wp_ajax_nopriv_rc_save_ccs_scripts', 'rc_save_ccs_scripts');


function rc_ccs_scripts_init(){
	function rc_ccs_scripts_adding_to_wp_head(){
		$ccs_scripts = get_option('ccs_scripts');
		if (!empty($ccs_scripts)) {
			$ccs_scripts = html_entity_decode($ccs_scripts);
			$ccs_scripts = stripcslashes($ccs_scripts);
			echo $ccs_scripts;
		}
	}
	add_action("wp_head", "rc_ccs_scripts_adding_to_wp_head", 1);
}
add_action("init", "rc_ccs_scripts_init");