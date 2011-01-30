<?php

/*
Plugin Name: Shared Script
Plugin URI: http://www.varaldebits.com.br/shared-script/
Description: This plugin allows the readers of your blog to share any content in the site in the following social networks: Facebook, Twitter, orkut, delicioso, google, hotmail, LinkedIn, Yahoo, MySpace. Just select the text and share it. You can also use the configurations menu to change the plugin options.
Author: Renato Ferraz
Version: 0.1
Author URI: http://www.varaldebits.com.br
*/

load_plugin_textdomain("shared-script", false, dirname( plugin_basename( __FILE__ ) ) . "/translations/" );

define("SS_NETWORKS", "facebook,twitter,orkut,delicious,google,hotmail,linkedin,myspace,yahoo");
define("SS_TITLE_NETWORKS", __("Share this content.", "shared-script"));

add_action("template_redirect", "shared_script_tags");
add_action("admin_menu", "shared_script_option");
add_action("wp_head", "shared_script_head");

register_activation_hook(__FILE__, "shared_script_activate");
register_deactivation_hook(__FILE__, "shared_script_deactivate");
  
function shared_script_activate() {  
  add_option("ss_selected_networks", SS_NETWORKS);
  add_option("ss_title_networks", SS_TITLE_NETWORKS);
}

function shared_script_deactivate() {
  delete_option("ss_selected_networks");
  delete_option("ss_title_networks");
}

function shared_script_option() {
  add_options_page("Shared Script", "Shared Script", "manage_options", "shared-script-config", "shared_script_ui");
}

function shared_script_tags() {
  wp_enqueue_script("shared-script-js", plugins_url("shared-script.js", __FILE__), array("jquery"));
  wp_enqueue_style("shared-script-style", plugins_url("shared-script.css", __FILE__));
}

function shared_script_head() {
?>
  <script type="text/javascript">
    var _ss_networks = Array('<?php echo str_replace(",", "','", get_option("ss_selected_networks")) ?>');
    var _ss_title_networks = '<?php echo get_option("ss_title_networks") ?>';
  </script>
<?php
}

function shared_script_ui() {
  
  if(isset($_POST["networks"])) {
    update_option("ss_selected_networks", join(",", $_POST["networks"]));
    update_option("title_networks", $_POST["title_networks"]);
    $message = __("Options successfully updated.", "shared-script");
  }

  $selected_networks = explode(",", get_option("ss_selected_networks"));
  $title_networks = get_option("ss_title_networks");
  $networks = explode(",", SS_NETWORKS);

?>
  
<div class="wrap">
  <div id="icon-options-general" class="icon32"><br></div>
  <h2>Shared Script</h2>
    
  <?php if(isset($message)): ?>
  <div id="message" class="updated"><p><?php echo $message; ?></p></div>
  <?php endif; ?>
 
  <p><? _e("Just click on the icon to enable or disable a social network.", "shared-script");  ?></p>
    
  <form name="form" action="" method="post">
  <div class="networks">
    <?php foreach($networks as $network): ?>
      <label for="networks_<?php echo $network ?>" class="ss-image">
        <img src="<?php echo  plugins_url('icons/'.$network.'.png', __FILE__) ?>" class="<?php echo in_array($network, $selected_networks) ? "checked" : "non-checked" ?>" />
        <input type="checkbox" id="networks_<?php echo $network ?>" name="networks[<?php echo $network ?>]" value="<?php echo $network ?>" style="display:none" <?php echo in_array($network, $selected_networks) ? "checked" : "" ?> />
      </label>        
    <?php endforeach; ?>
    <p>
      <label><? _e("Title");  ?></label>
      <input type="text" value="<?php echo $title_networks; ?>" size="60" id="title_networks" name="title_networks" />
    </p>
    <input type="submit" name="Submit" class="button-primary" id="ss-submit" value="<?php esc_attr_e('Save Changes') ?>" />
  </div>
  </form>
  
</div>

<script type="text/javascript"> 

  jQuery(window).load(function() {
                      
    jQuery("label.ss-image").click(function() {                                                
      jQuery(this).find("img").removeClass();                                                
      if(jQuery(this).find("input").is(":checked")) {          
        jQuery(this).find("img").addClass("checked");
      } else {
        jQuery(this).find("img").addClass("non-checked");
      }
    });      
    
  });
</script>
<style type="text/css">.non-checked { opacity:0.4; filter:alpha(opacity=40) }</style>

<?php
  
}
?>
