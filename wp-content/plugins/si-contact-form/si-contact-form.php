<?php
/*
Plugin Name: Fast Secure Contact Form
Plugin URI: http://www.FastSecureContactForm.com/
Description: Fast Secure Contact Form for WordPress. The contact form lets your visitors send you a quick E-mail message. Super customizable with a multi-form feature, optional extra fields, and an option to redirect visitors to any URL after the message is sent. Includes CAPTCHA and Akismet support to block all common spammer tactics. Spam is no longer a problem. <a href="plugins.php?page=si-contact-form/si-contact-form.php">Settings</a> | <a href="http://www.FastSecureContactForm.com/donate">Donate</a>
Version: 3.0.3.2
Author: Mike Challis
Author URI: http://www.642weather.com/weather/scripts.php
*/

$ctf_version = '3.0.3.2';

/*  Copyright (C) 2008-2011 Mike Challis  (http://www.642weather.com/weather/contact_us.php)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// settings get deleted when plugin is deleted from admin plugins page
// this must be outside the class or it does not work
function si_contact_unset_options() {

  delete_option('si_contact_form');
  delete_option('si_contact_form_gb');

  // multi-forms (a unique configuration for each contact form)
  for ($i = 2; $i <= 100; $i++) {
    delete_option("si_contact_form$i");
  }
} // end function si_contact_unset_options

if (!class_exists('siContactForm')) {

 class siContactForm {
     var $si_contact_error;
     var $uploaded_files;
     var $ctf_notes_style;
     var $ctf_version;
     var $ctf_add_script;

function si_contact_add_tabs() {
    add_submenu_page('plugins.php', __('FS Contact Form Options', 'si-contact-form'), __('FS Contact Form Options', 'si-contact-form'), 'manage_options', __FILE__,array(&$this,'si_contact_options_page'));
}

function si_contact_update_lang() {
  global $si_contact_opt, $si_contact_option_defaults;

   // a few language options need to be re-translated now.
   // had to do this becuse the options were actually needed to be set before the language translator was initialized

  // update translation for these options (for when switched from English to another lang)
  if ($si_contact_opt['welcome'] == '<p>Comments or questions are welcome.</p>' ) {
     $si_contact_opt['welcome'] = __('<p>Comments or questions are welcome.</p>', 'si-contact-form');
     $si_contact_option_defaults['welcome'] = $si_contact_opt['welcome'];
  }

  if ($si_contact_opt['email_to'] == 'Webmaster,'.get_option('admin_email')) {
       $si_contact_opt['email_to'] = __('Webmaster', 'si-contact-form').','.get_option('admin_email');
       $si_contact_option_defaults['email_to'] = $si_contact_opt['email_to'];
  }

  if ($si_contact_opt['email_subject'] == get_option('blogname') . ' ' .'Contact:') {
      $si_contact_opt['email_subject'] =  get_option('blogname') . ' ' .__('Contact:', 'si-contact-form');
      $si_contact_option_defaults['email_subject'] = $si_contact_opt['email_subject'];
  }

} // end function si_contact_update_lang

function si_contact_options_page() {
  global $captcha_url_cf, $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults, $ctf_version;

  require_once(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-admin.php');

} // end function si_contact_options_page

function si_contact_captcha_perm_dropdown($select_name, $checked_value='') {
        // choices: Display text => permission_level
        $choices = array (
                 $this->ctf_output_string( __('All registered users', 'si-contact-form')) => 'read',
                 $this->ctf_output_string( __('Edit posts', 'si-contact-form')) => 'edit_posts',
                 $this->ctf_output_string( __('Publish Posts', 'si-contact-form')) => 'publish_posts',
                 $this->ctf_output_string( __('Moderate Comments', 'si-contact-form')) => 'moderate_comments',
                 $this->ctf_output_string( __('Administer site', 'si-contact-form')) => 'level_10'
                 );
        // print the <select> and loop through <options>
        echo '<select name="' . $select_name . '" id="' . $select_name . '">' . "\n";
        foreach ($choices as $text => $capability) :
                if ($capability == $checked_value) $checked = ' selected="selected" ';
                echo "\t". '<option value="' . $capability . '"' . $checked . ">$text</option> \n";
                $checked = '';
        endforeach;
        echo "\t</select>\n";
} // end function si_contact_captcha_perm_dropdown

// this function prints the contact form
// and does all the decision making to send the email or not
// [si_contact_form form='2']
function si_contact_form_short_code($atts) {
  global $captcha_path_cf, $ctf_captcha_dir, $si_contact_opt, $si_contact_gb, $ctf_version, $ctf_add_script;

  $this->ctf_version = $ctf_version;

  // get options
  $si_contact_gb_mf = get_option("si_contact_form_gb");

   extract(shortcode_atts(array(
   'form' => '',
   'redirect' => '',
   'hidden' => '',
   'email_to' => '',
   ), $atts));

    $form_num = '';
    $form_id_num = 1;
    if ( isset($form) && is_numeric($form) && $form <= $si_contact_gb_mf['max_forms'] ) {
       $form_num = (int)$form;
       $form_id_num = (int)$form;
       if ($form_num == 1)
         $form_num = '';
    }

  // http://www.fastsecurecontactform.com/shortcode-options
  $shortcode_redirect = $redirect;
  $shortcode_hidden = $hidden;
  $shortcode_email_to = $email_to;

  // get options
  $si_contact_gb = $this->si_contact_get_options($form_num);

  // a couple language options need to be translated now.
  $this->si_contact_update_lang();

// Email address(s) to receive Bcc (Blind Carbon Copy) messages
$ctf_email_address_bcc = $si_contact_opt['email_bcc']; // optional

// optional subject list
$subjects = array ();
$subjects_test = explode("\n",trim($si_contact_opt['email_subject_list']));
if(!empty($subjects_test) ) {
  $ct = 1;
  foreach($subjects_test as $v) {
       $v = trim($v);
       if ($v != '') {
          $subjects["$ct"] = $v;
          $ct++;
       }
  }
}

// E-mail Contacts
// the drop down list array will be made automatically by this code
// checks for properly configured E-mail To: addresses in options.
$ctf_contacts = array ();
$ctf_contacts_test = trim($si_contact_opt['email_to']);
if(!preg_match("/,/", $ctf_contacts_test) ) {
    if($this->ctf_validate_email($ctf_contacts_test)) {
        // user1@example.com
       $ctf_contacts[] = array('CONTACT' => __('Webmaster', 'si-contact-form'),  'EMAIL' => $ctf_contacts_test );
    }
} else {
  $ctf_ct_arr = explode("\n",$ctf_contacts_test);
  if (is_array($ctf_ct_arr) ) {
    foreach($ctf_ct_arr as $line) {
       // echo '|'.$line.'|' ;
       list($key, $value) = preg_split('#(?<!\\\)\,#',$line); //string will be split by "," but "\," will be ignored
       $key   = trim(str_replace('\,',',',$key)); // "\," changes to ","
       $value = trim($value);
       if ($key != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               // just one email here
               // Webmaster,user1@example.com
               $value = str_replace('[cc]','',$value);
               $value = str_replace('[bcc]','',$value);
               if ($this->ctf_validate_email($value)) {
                  $ctf_contacts[] = array('CONTACT' => $this->ctf_output_string($key),  'EMAIL' => $value);
               }
          } else {
               // multiple emails here
               // Webmaster,user1@example.com;user2@example.com;user3@example.com;[cc]user4@example.com;[bcc]user5@example.com
               $multi_cc_arr = explode(";",$value);
               $multi_cc_string = '';
               foreach($multi_cc_arr as $multi_cc) {
                  $multi_cc_t = str_replace('[cc]','',$multi_cc);
                  $multi_cc_t = str_replace('[bcc]','',$multi_cc_t);
                  if ($this->ctf_validate_email($multi_cc_t)) {
                     $multi_cc_string .= "$multi_cc,";
                   }
               }
               if ($multi_cc_string != '') { // multi cc emails
                  $ctf_contacts[] = array('CONTACT' => $this->ctf_output_string($key),  'EMAIL' => rtrim($multi_cc_string, ','));
               }
         }
      }

   } // end foreach
  } // end if (is_array($ctf_ct_arr) ) {
} // end else

//print_r($ctf_contacts);

// Site Name / Title
$ctf_sitename = get_option('blogname');

// Site Domain without the http://www like this: $domain = '642weather.com';
// Can be a single domain:      $ctf_domain = '642weather.com';
// Can be an array of domains:  $ctf_domain = array('642weather.com','someothersite.com');
        // get blog domain
        $uri = parse_url(get_option('home'));
        $blogdomain = preg_replace("/^www\./i",'',$uri['host']);

$this->ctf_domain = $blogdomain;

// set the type of request (SSL or not)
if ( getenv('HTTPS') == 'on' ) {
    $form_action_url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
} else {
    $form_action_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

// Make sure the form was posted from your host name only.
// This is a security feature to prevent spammers from posting from files hosted on other domain names
// "Input Forbidden" message will result if host does not match
$this->ctf_domain_protect = $si_contact_opt['domain_protect'];

// Double E-mail entry is optional
// enabling this requires user to enter their email two times on the contact form.
$ctf_enable_double_email = $si_contact_opt['double_email'];

// You can ban known IP addresses
// SET  $ctf_enable_ip_bans = 1;  ON,  $ctf_enable_ip_bans = 0; for OFF.
$ctf_enable_ip_bans = 0;

// Add IP addresses to ban here:  (be sure to SET  $ctf_enable_ip_bans = 1; to use this feature
$ctf_banned_ips = array(
'22.22.22.22', // example (add, change, or remove as needed)
'33.33.33.33', // example (add, change, or remove as needed)
);

// Wordwrap E-Mail message text so lines are no longer than 70 characters.
// SET  $ctf_wrap_message = 1;  ON,  $ctf_wrap_message = 0; for OFF.
$ctf_wrap_message = 1;

// add numbered keys starting with 1 to the $contacts array
$cont = array();
$ct = 1;
foreach ($ctf_contacts as $v)  {
    $cont["$ct"] = $v;
    $ct++;
}
$contacts = $cont;
unset($cont);

// initialize vars
$string = '';
$this->si_contact_error = 0;
$si_contact_error_print = '';
$message_sent = 0;
$mail_to    = '';
$to_contact = '';
$name       = $this->si_contact_get_var($form_id_num,'name');
$f_name     = $this->si_contact_get_var($form_id_num,'f_name');
$m_name     = $this->si_contact_get_var($form_id_num,'m_name');
$mi_name    = $this->si_contact_get_var($form_id_num,'mi_name');
$l_name     = $this->si_contact_get_var($form_id_num,'l_name');
$email      = $this->si_contact_get_var($form_id_num,'email');
$email2     = $this->si_contact_get_var($form_id_num,'email');
$subject    = $this->si_contact_get_var($form_id_num,'subject');
$message    = $this->si_contact_get_var($form_id_num,'message');
$captcha_code  = '';

// optional extra fields
// capture query string vars
$have_attach = '';
for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
   if ($si_contact_opt['ex_field'.$i.'_label'] != '') {
      ${'ex_field'.$i} = '';
      ${'si_contact_error_ex_field'.$i} = '';
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'time') {
         ${'ex_field'.$i.'h'} = $this->si_contact_get_var($form_id_num,'ex_field'.$i.'h');
         ${'ex_field'.$i.'m'} = $this->si_contact_get_var($form_id_num,'ex_field'.$i.'m');
         ${'ex_field'.$i.'ap'} = $this->si_contact_get_var($form_id_num,'ex_field'.$i.'ap');
      }
      if( in_array($si_contact_opt['ex_field'.$i.'_type'],array('hidden','text','email','url','textarea','date','password')) ) {
         ${'ex_field'.$i} = $this->si_contact_get_var($form_id_num,'ex_field'.$i);
      }
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'radio' || $si_contact_opt['ex_field'.$i.'_type'] == 'select') {
         $exf_opts_array = $this->si_contact_get_exf_opts_array($si_contact_opt['ex_field'.$i.'_label']);
         $check_ex_field = $this->si_contact_get_var($form_id_num,'ex_field'.$i);
         if($check_ex_field != '' && is_numeric($check_ex_field) && $check_ex_field > 0 ) {
           if( isset($exf_opts_array[$check_ex_field-1]) )
               ${'ex_field'.$i} = $exf_opts_array[$check_ex_field-1];
         }
      }
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'select-multiple') {
         $exf_opts_array = $this->si_contact_get_exf_opts_array($si_contact_opt['ex_field'.$i.'_label']);
         $ex_cnt = 1;
         foreach ($exf_opts_array as $k) {
             if( $this->si_contact_get_var($form_id_num,'ex_field'.$i.'_'.$ex_cnt) == 1 ){
                 ${'ex_field'.$i.'_'.$ex_cnt} = 'selected';
             }
             $ex_cnt++;
         }
      }
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'checkbox' || $si_contact_opt['ex_field'.$i.'_type'] == 'checkbox-multiple') {
         $exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
         if(preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
            $exf_opts_array = $this->si_contact_get_exf_opts_array($si_contact_opt['ex_field'.$i.'_label']);
            $ex_cnt = 1;
            foreach ($exf_opts_array as $k) {
                if( $this->si_contact_get_var($form_id_num,'ex_field'.$i.'_'.$ex_cnt) == 1 ){
                     ${'ex_field'.$i.'_'.$ex_cnt} = 'selected';
                }
                $ex_cnt++;
            }
         }else{
              if($this->si_contact_get_var($form_id_num,'ex_field'.$i) == 1)
              ${'ex_field'.$i} = 'selected';
         }
      }
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'attachment')
         $have_attach = 'enctype="multipart/form-data" '; // for <form post

   }
}
$req_field_ind = ( $si_contact_opt['req_field_indicator_enable'] == 'true' ) ? '<span class="required">'.$si_contact_opt['req_field_indicator'].'</span>' : '';
$si_contact_error_captcha = '';
$si_contact_error_contact = '';
$si_contact_error_name    = '';
$si_contact_error_f_name  = '';
$si_contact_error_m_name  = '';
$si_contact_error_mi_name = '';
$si_contact_error_l_name  = '';
$si_contact_error_email   = '';
$si_contact_error_email2  = '';
$si_contact_error_double_email = '';
$si_contact_error_subject = '';
$si_contact_error_message = '';

// see if WP user
global $current_user, $user_ID;
get_currentuserinfo();

// process form now
if (isset($_POST['si_contact_action']) && ($_POST['si_contact_action'] == 'send')
   && isset($_POST['si_contact_form_id']) && ($_POST['si_contact_form_id'] == $form_id_num)
) {

  // include the code to process the form and send the mail
  include(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-process.php');

} // end if posted si_contact_action = send

if($message_sent) {
       // Redirect to Home Page after message is sent
       $ctf_redirect_enable = $si_contact_opt['redirect_enable'];
       // Used for the delay timer once the message has been sent
       $ctf_redirect_timeout = $si_contact_opt['redirect_seconds']; // time in seconds to wait before loading another Web page
       // Web page to send the user to after the time has expired
       $ctf_redirect_url = $si_contact_opt['redirect_url'];

       // allow shortcode redirect to override redirect settings
       if ( $shortcode_redirect != '') {
           $ctf_redirect_enable = 'true';
           $ctf_redirect_url = $shortcode_redirect;
       }

// The $thank_you is what gets printed after the form is sent.
$ctf_thank_you = '
<p>
';
if ($si_contact_opt['text_message_sent'] != '') {
        $ctf_thank_you .= $si_contact_opt['text_message_sent'];
} else {
        $ctf_thank_you .= __('Your message has been sent, thank you.', 'si-contact-form');
}
$ctf_thank_you .= '
</p>
';

if ($ctf_redirect_enable == 'true') {

    // redirect query string code
   if ($si_contact_opt['redirect_query'] == 'true') {
      // build query string
      $query_string = $this->si_contact_export_convert($posted_data,$si_contact_opt['redirect_rename'],$si_contact_opt['redirect_ignore'],$si_contact_opt['redirect_add'],'query');
      if(!preg_match("/\?/", $ctf_redirect_url) )
        $ctf_redirect_url .= '?'.$query_string;
      else
       $ctf_redirect_url .= '&'.$query_string;
   }

 $ctf_thank_you .= <<<EOT

<script type="text/javascript" language="javascript">
//<![CDATA[
var ctf_redirect_seconds=$ctf_redirect_timeout;
var ctf_redirect_time;
function ctf_redirect() {
  document.title='Redirecting in ' + ctf_redirect_seconds + ' seconds';
  ctf_redirect_seconds=ctf_redirect_seconds-1;
  ctf_redirect_time=setTimeout("ctf_redirect()",1000);
  if (ctf_redirect_seconds==-1) {
    clearTimeout(ctf_redirect_time);
    document.title='Redirecting ...';
    self.location='$ctf_redirect_url';
  }
}
function ctf_addOnloadEvent(fnc){
  if ( typeof window.addEventListener != "undefined" )
    window.addEventListener( "load", fnc, false );
  else if ( typeof window.attachEvent != "undefined" ) {
    window.attachEvent( "onload", fnc );
  }
  else {
    if ( window.onload != null ) {
      var oldOnload = window.onload;
      window.onload = function ( e ) {
        oldOnload( e );
        window[fnc]();
      };
    }
    else
      window.onload = fnc;
  }
}
ctf_addOnloadEvent(ctf_redirect);
//]]>
</script>
EOT;

$ctf_thank_you .= '
<img src="'.WP_PLUGIN_URL.'/si-contact-form/ctf-loading.gif" alt="'.$this->ctf_output_string(__('Redirecting', 'si-contact-form')).'" />&nbsp;&nbsp;
'.__('Redirecting', 'si-contact-form').' ... ';


// do not remove the above EOT line

}

      // thank you message is printed here
      $string .= $ctf_thank_you;
}else{

     // The $ctf_welcome_intro is what gets printed when the contact form is first presented.
     // It is not printed when there is an input error and not printed after the form is completed
     $ctf_welcome_intro = "\n". $si_contact_opt['welcome'];

     // welcome intro is printed here
     $string .= $ctf_welcome_intro;

     // include the code to display the form
     include(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-display.php');

} // end if ( message sent

 return $string;
} // end function si_contact_form_short_code

function si_contact_export_convert($posted_data,$rename,$ignore,$add,$return = 'array') {
    $query_string = '';
    $posted_data_export = array();
    //rename field names array
    $rename_fields = array();
    $rename_fields_test = explode("\n",$rename);
    if ( !empty($rename_fields_test) ) {
      foreach($rename_fields_test as $line) {
         if(preg_match("/=/", $line) ) {
            list($key, $value) = explode("=",$line);
            $key   = trim($key);
            $value = trim($value);
            if ($key != '' && $value != '')
              $rename_fields[$key] = $value;
         }
      }
    }
    // add fields
    $add_fields_test = explode("\n",$add);
    if ( !empty($add_fields_test) ) {
      foreach($add_fields_test as $line) {
         if(preg_match("/=/", $line) ) {
            list($key, $value) = explode("=",$line);
            $key   = trim($key);
            $value = trim($value);
            if ($key != '' && $value != '') {
              if($return == 'array')
		        $posted_data_export[$key] = $value;
              else
                $query_string .= $key . '=' . urlencode( stripslashes($value) ) . '&';
            }
         }
      }
    }
    //ignore field names array
    $ignore_fields = array();
    $ignore_fields = array_map('trim', explode("\n", $ignore));
    // $posted_data is an array of the form name value pairs
    foreach ($posted_data as $key => $value) {
	  if( is_string($value) ) {
         if(in_array($key, $ignore_fields))
            continue;
         $key = ( isset($rename_fields[$key]) ) ? $rename_fields[$key] : $key;
         if($return == 'array')
		    $posted_data_export[$key] = $value;
         else
            $query_string .= $key . '=' . urlencode( stripslashes($value) ) . '&';
      }
    }
    if($return == 'array')
      return $posted_data_export;
    else
      return $query_string;
} // end function si_contact_export_convert


function si_contact_get_var($form_id_num,$name) {
   $value = (isset( $_GET["$form_id_num$name"])) ? $this->ctf_clean_input($_GET["$form_id_num$name"]) : '';
   return $value;
}

function si_contact_get_exf_opts_array($label) {
  $exf_opts_array = array();
  $exf_opts_label = '';
  $exf_array_test = trim($label);
  if(!preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
                // Error: A radio field is not configured properly in settings
  } else {
      list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
      $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
      $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
      if ($exf_opts_label != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
             //Error: A radio field is not configured properly in settings.
          } else {
             // multiple options
             $exf_opts_array = explode(";",$value);
          }
      }
  } // end else
  return $exf_opts_array;
} //end function

// needed for making temp directories for attachments and captcha session files
function si_contact_init_temp_dir($dir) {
    $dir = trailingslashit( $dir );
    // make the temp directory
	wp_mkdir_p( $dir );
	@chmod( $dir, 0733 );
	$htaccess_file = $dir . '.htaccess';
	if ( !file_exists( $htaccess_file ) ) {
	   if ( $handle = @fopen( $htaccess_file, 'w' ) ) {
		   fwrite( $handle, "Deny from all\n" );
		   fclose( $handle );
	   }
    }
    $php_file = $dir . 'index.php';
	if ( !file_exists( $php_file ) ) {
       	if ( $handle = @fopen( $php_file, 'w' ) ) {
		   fwrite( $handle, '<?php //do not delete ?>' );
		   fclose( $handle );
     	}
	}
} // end function si_contact_init_temp_dir

// needed for emptying temp directories for attachments and captcha session files
function si_contact_clean_temp_dir($dir, $minutes = 60) {
    // deletes all files over xx minutes old in a temp directory
  	if ( ! is_dir( $dir ) || ! is_readable( $dir ) || ! is_writable( $dir ) )
		return false;

	$count = 0;
	if ( $handle = @opendir( $dir ) ) {
		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file == '.' || $file == '..' || $file == '.htaccess' || $file == 'index.php')
				continue;

			$stat = @stat( $dir . $file );
			if ( ( $stat['mtime'] + $minutes * 60 ) < time() ) {
			    @unlink( $dir . $file );
				$count += 1;
			}
		}
		closedir( $handle );
	}
	return $count;
}

// used for file attachment feature
function si_contact_validate_attach( $file, $ex_field  ) {
    global $si_contact_opt;

    $result['valid'] = true;

    if ($si_contact_opt['php_mailer_enable'] == 'php') {
        $result['valid'] = false;
		$result['error'] = __('Attachments not supported.', 'si-contact-form');
		return $result;
    }

	if ( ($file['error'] && UPLOAD_ERR_NO_FILE != $file['error']) || !is_uploaded_file( $file['tmp_name'] ) ) {
		$result['valid'] = false;
		$result['error'] = __('Attachment upload failed.', 'si-contact-form');
		return $result;
	}

	if ( empty( $file['tmp_name'] ) ) {
		$result['valid'] = false;
		$result['error'] = __('This field is required.', 'si-contact-form');
		return $result;
	}

    // check file types
    $file_type_pattern = $si_contact_opt['attach_types'];
	if ( $file_type_pattern == '' )
		$file_type_pattern = 'doc,pdf,txt,gif,jpg,jpeg,png';
    $file_type_pattern = str_replace(',','|',$si_contact_opt['attach_types']);
    $file_type_pattern = str_replace(' ','',$file_type_pattern);
	$file_type_pattern = trim( $file_type_pattern, '|' );
	$file_type_pattern = '(' . $file_type_pattern . ')';
	$file_type_pattern = '/\.' . $file_type_pattern . '$/i';

	if ( ! preg_match( $file_type_pattern, $file['name'] ) ) {
		$result['valid'] = false;
		$result['error'] = __('Attachment file type not allowed.', 'si-contact-form');
		return $result;
	}

    // check size
    $allowed_size = 1048576; // 1mb default
	if ( preg_match( '/^([[0-9.]+)([kKmM]?[bB])?$/', $si_contact_opt['attach_size'], $matches ) ) {
	     $allowed_size = (int) $matches[1];
		 $kbmb = strtolower( $matches[2] );
		 if ( 'kb' == $kbmb ) {
		     $allowed_size *= 1024;
		 } elseif ( 'mb' == $kbmb ) {
		     $allowed_size *= 1024 * 1024;
		 }
	}
	if ( $file['size'] > $allowed_size ) {
		$result['valid'] = false;
		$result['error'] = __('Attachment file size is too large.', 'si-contact-form');
		return $result;
	}

	$filename = $file['name'];

	// safer file names for scripts.
	if ( preg_match( '/\.(php|pl|py|rb|cgi)\d?$/', $filename ) )
		$filename .= '.txt';

 	$attach_dir = WP_PLUGIN_DIR . '/si-contact-form/attachments/';

	$filename = wp_unique_filename( $attach_dir, $filename );

	$new_file = trailingslashit( $attach_dir ) . $filename;

	if ( false === @move_uploaded_file( $file['tmp_name'], $new_file ) ) {
		$result['valid'] = false;
		$result['error'] = __('Attachment upload failed while moving file.', 'si-contact-form');
		return $result;
	}

	// uploaded only readable for the owner process
	@chmod( $new_file, 0400 );

	$this->uploaded_files[$ex_field] = $new_file;

    $result['file_name'] = $filename; // needed for email message

	return $result;
}

// makes bold html email labels
function make_bold($label) {
   global $si_contact_opt;

   if ($si_contact_opt['email_html'] == 'true')
        return '<b>'.$label.'</b>';
   else
        return $label;

}

// checks if captcha is enabled based on the current captcha permission settings set in the plugin options
function isCaptchaEnabled() {
   global $si_contact_opt, $ctf_add_script;

   if ($si_contact_opt['captcha_enable'] !== 'true') {
        return false; // captcha setting is disabled for si contact
   }
   // skip the captcha if user is loggged in and the settings allow
   if (is_user_logged_in() && $si_contact_opt['captcha_perm'] == 'true') {
       // skip the CAPTCHA display if the minimum capability is met
       if ( current_user_can( $si_contact_opt['captcha_perm_level'] ) ) {
               // skip capthca
               return false;
        }
   }
   $ctf_add_script = true;
   return true;
} // end function isCaptchaEnabled

function captchaCheckRequires() {
  global $captcha_path_cf;

  $ok = 'ok';
  // Test for some required things, print error message if not OK.
  if ( !extension_loaded('gd') || !function_exists('gd_info') ) {
      $this->captchaRequiresError .= '<p '.$this->ctf_error_style.'>'.__('ERROR: si-contact-form.php plugin says GD image support not detected in PHP!', 'si-contact-form').'</p>';
      $this->captchaRequiresError .= '<p>'.__('Contact your web host and ask them why GD image support is not enabled for PHP.', 'si-contact-form').'</p>';
      $ok = 'no';
  }
  if ( !function_exists('imagepng') ) {
      $this->captchaRequiresError .= '<p '.$this->ctf_error_style.'>'.__('ERROR: si-contact-form.php plugin says imagepng function not detected in PHP!', 'si-contact-form').'</p>';
      $this->captchaRequiresError .= '<p>'.__('Contact your web host and ask them why imagepng function is not enabled for PHP.', 'si-contact-form').'</p>';
      $ok = 'no';
  }
  if ( !@strtolower(ini_get('safe_mode')) == 'on' && !file_exists("$captcha_path_cf/securimage.php") ) {
       $this->captchaRequiresError .= '<p '.$this->ctf_error_style.'>'.__('ERROR: si-contact-form.php plugin says captcha_library not found.', 'si-contact-form').'</p>';
       $ok = 'no';
  }
  if ($ok == 'no')  return false;
  return true;
}

// fix for simple facebook connect plugin
// http://wordpress.org/support/topic/402560
function ctf_sfc_filter($classes) {
  $classes[] = 'ctf-captcha';
  return $classes;
}

// this function adds the captcha to the contact form
function si_contact_get_captcha_html($si_contact_error_captcha,$form_id_num) {
   global $ctf_captcha_url, $ctf_captcha_dir, $captcha_path_cf, $captcha_url_cf, $si_contact_gb, $si_contact_opt;
   $req_field_ind = ( $si_contact_opt['req_field_indicator_enable'] == 'true' ) ? '<span class="required">'.$si_contact_opt['req_field_indicator'].'</span>' : '';

   $capt_disable_sess = 0;
   if ($si_contact_gb['captcha_disable_session'] == 'true')
     $capt_disable_sess = 1;

// fix for simple facebook connect plugin
// http://wordpress.org/support/topic/402560
add_filter('sfc_img_exclude',array(&$this,'ctf_sfc_filter'),1);

  $string = '';

// Test for some required things, print error message right here if not OK.
if ($this->captchaCheckRequires()) {

  $si_contact_opt['captcha_image_style'] = 'border-style:none; margin:0; padding:0px; padding-right:5px; float:left;';
  $si_contact_opt['audio_image_style'] = 'border-style:none; margin:0; padding:0px; vertical-align:top;';
  $si_contact_opt['reload_image_style'] = 'border-style:none; margin:0; padding:0px; vertical-align:bottom;';

// the captch html

 $string = '
<div '.$this->ctf_title_style.'> </div>
 <div ';
$this->ctf_captcha_div_style_sm = $this->si_contact_convert_css($si_contact_opt['captcha_div_style_sm']);
$this->ctf_captcha_div_style_m = $this->si_contact_convert_css($si_contact_opt['captcha_div_style_m']);

// url for no session captcha image
$securimage_show_url = $captcha_url_cf .'/securimage_show.php?';
$securimage_size = 'width="175" height="60"';
if($si_contact_opt['captcha_small'] == 'true') {
  $securimage_show_url .= 'ctf_sm_captcha=1&amp;';
  $securimage_size = 'width="132" height="45"';
}

$parseUrl = parse_url($captcha_url_cf);
$securimage_url = $parseUrl['path'];

if($si_contact_opt['captcha_difficulty'] == 'low') $securimage_show_url .= 'difficulty=1&amp;';
if($si_contact_opt['captcha_difficulty'] == 'high') $securimage_show_url .= 'difficulty=2&amp;';
if($si_contact_opt['captcha_no_trans'] == 'true') $securimage_show_url .= 'no_trans=1&amp;';


if($capt_disable_sess) {
     // clean out old captcha no session temp files
    $this->si_contact_clean_temp_dir($ctf_captcha_dir, 30);
    // pick new prefix token
    $prefix_length = 16;
    $prefix_characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
    $prefix = '';
    $prefix_count = strlen($prefix_characters);
    while ($prefix_length--) {
        $prefix .= $prefix_characters[mt_rand(0, $prefix_count-1)];
    }
    $securimage_show_rf_url = $securimage_show_url . 'prefix=';
    $securimage_show_url .= 'prefix='.$prefix;
} else {  // no session
   $securimage_show_rf_url = $securimage_show_url . 'ctf_form_num=' .$form_id_num;
   $securimage_show_url .= 'ctf_form_num=' .$form_id_num;
}

$string .= ($si_contact_opt['captcha_small'] == 'true') ? $this->ctf_captcha_div_style_sm : $this->ctf_captcha_div_style_m;
$string .= '>
    <img class="ctf-captcha" id="si_image_ctf'.$form_id_num.'" ';
    $string .= ($si_contact_opt['captcha_image_style'] != '') ? 'style="' . $this->ctf_output_string( $si_contact_opt['captcha_image_style'] ).'"' : '';
    $string .= ' src="'.$securimage_show_url.'" '.$securimage_size.' alt="';
    $string .= ($si_contact_opt['tooltip_captcha'] != '') ? $this->ctf_output_string( $si_contact_opt['tooltip_captcha'] ) : $this->ctf_output_string(__('CAPTCHA Image', 'si-contact-form'));
    $string .='" title="';
    $string .= ($si_contact_opt['tooltip_captcha'] != '') ? $this->ctf_output_string( $si_contact_opt['tooltip_captcha'] ) : $this->ctf_output_string(__('CAPTCHA Image', 'si-contact-form'));
    $string .= '" />'."\n";
    if($capt_disable_sess)
        $string .= '    <input id="si_code_ctf_'.$form_id_num.'" type="hidden" name="si_code_ctf_'.$form_id_num.'" value="'.$prefix.'" />'."\n";

    $ctf_audio_type = 'noaudio';
    //Audio feature is disabled by Mike Challis until further notice because a proof of concept code CAPTCHA solving exploit was released - Security Advisory - SOS-11-007.
    $si_contact_opt['enable_audio'] = 'false';

    if($si_contact_opt['enable_audio'] == 'true') {
        $ctf_audio_type = 'wav';
       if($si_contact_opt['enable_audio_flash'] == 'true') {
          $ctf_audio_type = 'flash';
          $securimage_play_url = $securimage_url.'/securimage_play.swf?ctf_form_num='.$form_id_num;
          $securimage_play_url2 = $securimage_url.'/securimage_play.php?ctf_form_num='.$form_id_num;
          if($capt_disable_sess){
             $securimage_play_url = $securimage_url.'/securimage_play.swf?prefix='.$prefix;
             $securimage_play_url2 = $securimage_url.'/securimage_play.php?prefix='.$prefix;
          }
          $string .= '<div id="si_flash_ctf'.$form_id_num.'">
        <object type="application/x-shockwave-flash"
                data="'.$securimage_play_url.'&amp;bgColor1=#8E9CB6&amp;bgColor2=#fff&amp;iconColor=#000&amp;roundedCorner=5&amp;audio='.$securimage_play_url2.'"
                id="SecurImage_as3_'.$form_id_num.'" width="19" height="19">
			    <param name="allowScriptAccess" value="sameDomain" />
			    <param name="allowFullScreen" value="false" />
			    <param name="movie" value="'.$securimage_play_url.'&amp;bgColor1=#8E9CB6&amp;bgColor2=#fff&amp;iconColor=#000&amp;roundedCorner=5&amp;audio='.$securimage_play_url2.'" />
			    <param name="quality" value="high" />
			    <param name="bgcolor" value="#ffffff" />
		</object></div>
        ';
      }else{
         $securimage_play_url = $captcha_url_cf.'/securimage_play.php?ctf_form_num='.$form_id_num;
         if($capt_disable_sess)
                $securimage_play_url = $captcha_url_cf.'/securimage_play.php?prefix='.$prefix;
         $string .= '    <div id="si_audio_ctf'.$form_id_num.'">'."\n";
         $string .= '      <a id="si_aud_ctf'.$form_id_num.'" href="'.$securimage_play_url.'" rel="nofollow" title="';
         $string .= ($si_contact_opt['tooltip_audio'] != '') ? $this->ctf_output_string( $si_contact_opt['tooltip_audio'] ) : $this->ctf_output_string(__('CAPTCHA Audio', 'si-contact-form'));
         $string .= '">
      <img src="'.$captcha_url_cf.'/images/audio_icon.png" width="22" height="20" alt="';
         $string .= ($si_contact_opt['tooltip_audio'] != '') ? $this->ctf_output_string( $si_contact_opt['tooltip_audio'] ) : $this->ctf_output_string(__('CAPTCHA Audio', 'si-contact-form'));
         $string .= '" ';
         $string .= ($si_contact_opt['audio_image_style'] != '') ? 'style="' . $this->ctf_output_string( $si_contact_opt['audio_image_style'] ).'"' : '';
         $string .= ' onclick="this.blur();" /></a>
     </div>'."\n";
     }
   }
         $string .= '    <div id="si_refresh_ctf'.$form_id_num.'">'."\n";
         $string .= '      <a href="#" rel="nofollow" title="';
         $string .= ($si_contact_opt['tooltip_refresh'] != '') ? $this->ctf_output_string( $si_contact_opt['tooltip_refresh'] ) : $this->ctf_output_string(__('Refresh Image', 'si-contact-form'));
         if($capt_disable_sess) {
           $string .= '" onclick="si_contact_captcha_refresh(\''.$form_id_num.'\',\''.$ctf_audio_type.'\',\''.$securimage_url.'\',\''.$securimage_show_rf_url.'\'); return false;">'."\n";
         }else{
           $string .= '" onclick="document.getElementById(\'si_image_ctf'.$form_id_num.'\').src = \''.$securimage_show_url.'&amp;sid=\''.' + Math.random(); return false;">'."\n";
         }
         $string .= '      <img src="'.$captcha_url_cf.'/images/refresh.png" width="22" height="20" alt="';
         $string .= ($si_contact_opt['tooltip_refresh'] != '') ? $this->ctf_output_string( $si_contact_opt['tooltip_refresh'] ) : $this->ctf_output_string(__('Refresh Image', 'si-contact-form'));
         $string .=  '" ';
         $string .= ($si_contact_opt['reload_image_style'] != '') ? 'style="' . $this->ctf_output_string( $si_contact_opt['reload_image_style'] ).'"' : '';
         $string .=  ' onclick="this.blur();" /></a>
   </div>
   </div>

      <div '.$this->ctf_title_style.'>
                <label for="si_contact_captcha_code'.$form_id_num.'">';
     $string .= ($si_contact_opt['title_capt'] != '') ? $si_contact_opt['title_capt'] : __('CAPTCHA Code', 'si-contact-form').':';
     $string .= $req_field_ind.'</label>
        </div>
        <div '.$this->si_contact_convert_css($si_contact_opt['field_div_style']).'>'.$this->ctf_echo_if_error($si_contact_error_captcha).'
                <input '.$this->si_contact_convert_css($si_contact_opt['captcha_input_style']).' type="text" value="" id="si_contact_captcha_code'.$form_id_num.'" name="si_contact_captcha_code" '.$this->ctf_aria_required.' size="'.absint($si_contact_opt['captcha_field_size']).'" />
       </div>
';
} else {
      $string .= $this->captchaRequiresError;
}
  return $string;
} // end function si_contact_get_captcha_html

// shows contact form errors
function ctf_echo_if_error($this_error){
  if ($this->si_contact_error) {
    if (!empty($this_error)) {
         return '
         <div '.$this->ctf_error_style.'>'. $this_error . '</div>'."\n";
    }
  }
} // end function ctf_echo_if_error

// functions for protecting and validating form input vars
function ctf_clean_input($string, $preserve_space = 0) {
    if (is_string($string)) {
       if($preserve_space)
          return $this->ctf_sanitize_string(strip_tags($this->ctf_stripslashes($string)),$preserve_space);
       return trim($this->ctf_sanitize_string(strip_tags($this->ctf_stripslashes($string))));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = $this->ctf_clean_input($value,$preserve_space);
      }
      return $string;
    } else {
      return $string;
    }
} // end function ctf_clean_input

// functions for protecting and validating form vars
function ctf_sanitize_string($string, $preserve_space = 0) {
    if(!$preserve_space)
      $string = preg_replace("/ +/", ' ', trim($string));

    return preg_replace("/[<>]/", '_', $string);
} // end function ctf_sanitize_string

// functions for protecting and validating form vars
function ctf_stripslashes($string) {
        //if (get_magic_quotes_gpc()) {
          // wordpress always has magic_quotes On regardless of PHP settings!!
                return stripslashes($string);
       // } else {
        //       return $string;
       // }
} // end function ctf_stripslashes

// functions for protecting output against XSS. encode  < > & " ' (less than, greater than, ampersand, double quote, single quote).
function ctf_output_string($string) {
    $string = str_replace('&', '&amp;', $string);
    $string = str_replace('"', '&quot;', $string);
    $string = str_replace("'", '&#39;', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    return $string;
} // end function ctf_output_string

// A function knowing about name case (i.e. caps on McDonald etc)
// $name = name_case($name);
function ctf_name_case($name) {
   global $si_contact_opt;

   if ($si_contact_opt['name_case_enable'] !== 'true') {
        return $name; // name_case setting is disabled for si contact
   }
   if ($name == '') return '';
   $break = 0;
   $newname = strtoupper($name[0]);
   for ($i=1; $i < strlen($name); $i++) {
       $subed = substr($name, $i, 1);
       if (((ord($subed) > 64) && (ord($subed) < 123)) ||
           ((ord($subed) > 48) && (ord($subed) < 58))) {
           $word_check = substr($name, $i - 2, 2);
           if (!strcasecmp($word_check, 'Mc') || !strcasecmp($word_check, "O'")) {
               $newname .= strtoupper($subed);
           }else if ($break){
               $newname .= strtoupper($subed);
           }else{
               $newname .= strtolower($subed);
           }
             $break = 0;
       }else{
             // not a letter - a boundary
             $newname .= $subed;
             $break = 1;
       }
   }
   return $newname;
} // end function ctf_name_case

// checks proper url syntax (not perfect, none of these are, but this is the best I can find)
//   tutorialchip.com/php/preg_match-examples-7-useful-code-snippets/
function ctf_validate_url($url) {

    $regex = "((https?|ftp)\:\/\/)?"; // Scheme
	$regex .= "([a-zA-Z0-9+!*(),;?&=\$_.-]+(\:[a-zA-Z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-zA-Z0-9-.]*)\.([a-zA-Z]{2,6})"; // Host or IP
    $regex .= "(\:[0-9]{2,5})?"; // Port
    $regex .= "(\/#\!)?"; // Path hash bang  (twitter) (mike challis added)
    $regex .= "(\/([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor

	return preg_match("/^$regex$/", $url);

} // end function ctf_validate_url

// checks proper email syntax (not perfect, none of these are, but this is the best I can find)
function ctf_validate_email($email) {
   global $si_contact_opt;

   //check for all the non-printable codes in the standard ASCII set,
   //including null bytes and newlines, and return false immediately if any are found.
   if (preg_match("/[\\000-\\037]/",$email)) {
      return false;
   }
   // regular expression used to perform the email syntax check
   // http://fightingforalostcause.net/misc/2006/compare-email-regex.php
   //$pattern = "/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|asia|cat|jobs|tel|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i";
   //$pattern = "/^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+)(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/i";
   $pattern = "/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD";
   if(!preg_match($pattern, $email)){
      return false;
   }
   // Make sure the domain exists with a DNS check (if enabled in options)
   // MX records are not mandatory for email delivery, this is why this function also checks A and CNAME records.
   // if the checkdnsrr function does not exist (skip this extra check, the syntax check will have to do)
   // checkdnsrr available in Linux: PHP 4.3.0 and higher & Windows: PHP 5.3.0 and higher
   if ($si_contact_opt['email_check_dns'] == 'true') {
      if( function_exists('checkdnsrr') ) {
         list($user,$domain) = explode('@',$email);
         if(!checkdnsrr($domain.'.', 'MX') &&
            !checkdnsrr($domain.'.', 'A') &&
            !checkdnsrr($domain.'.', 'CNAME')) {
            // domain not found in DNS
            return false;
         }
      }
   }
   return true;
} // end function ctf_validate_email

// helps spam protect email input
// finds new lines injection attempts
function ctf_forbidifnewlines($input) {
   if (
       stristr($input, "\r")  !== false ||
       stristr($input, "\n")  !== false ||
       stristr($input, "%0a") !== false ||
       stristr($input, "%0d") !== false) {
         //wp_die(__('Contact Form has Invalid Input', 'si-contact-form'));
         $this->si_contact_error = 1;

   }
} // end function ctf_forbidifnewlines

// helps spam protect email input
// blocks contact form posted from other domains
function ctf_spamcheckpost() {

 if(!isset($_SERVER['HTTP_USER_AGENT'])){
     return __('Invalid User Agent', 'si-contact-form');
 }

 // Make sure the form was indeed POST'ed:
 //  (requires your html form to use: si_contact_action="post")
 if(!$_SERVER['REQUEST_METHOD'] == "POST"){
    return __('Invalid POST', 'si-contact-form');
 }

  // Make sure the form was posted from an approved host name.
 if ($this->ctf_domain_protect == 'true') {
     $print_authHosts = '';
   // Host names from where the form is authorized to be posted from:
   if (is_array($this->ctf_domain)) {
      $this->ctf_domain = array_map(strtolower, $this->ctf_domain);
      $authHosts = $this->ctf_domain;
      foreach ($this->ctf_domain as $each_domain) {
         $print_authHosts .= ' '.$each_domain;
      }
   } else {
      $this->ctf_domain =  strtolower($this->ctf_domain);
      $authHosts = array("$this->ctf_domain");
      $print_authHosts = $this->ctf_domain;
   }

   // Where have we been posted from?
   if( isset($_SERVER['HTTP_REFERER']) and trim($_SERVER['HTTP_REFERER']) != '' ) {
      $fromArray = parse_url(strtolower($_SERVER['HTTP_REFERER']));
      // Test to see if the $fromArray used www to get here.
      $wwwUsed = preg_match("/^www\./i",$fromArray['host']);
      if(!in_array((!$wwwUsed ? $fromArray['host'] : preg_replace("/^www\./i",'',$fromArray['host'])), $authHosts ) ){
         return sprintf( __('Invalid HTTP_REFERER domain. See FAQ. The domain name posted from does not match the allowed domain names of this form: %s', 'si-contact-form'), $print_authHosts );
      }
   }
 } // end if domain protect

 // check posted input for email injection attempts
 // Check for these common exploits
 // if you edit any of these do not break the syntax of the regex
 $input_expl = "/(content-type|mime-version|content-transfer-encoding|to:|bcc:|cc:|document.cookie|document.write|onmouse|onkey|onclick|onload)/i";
 // Loop through each POST'ed value and test if it contains one of the exploits fromn $input_expl:
 foreach($_POST as $k => $v){
   if (is_string($v)){
     $v = strtolower($v);
     $v = str_replace('donkey','',$v); // fixes invalid input with "donkey" in string
     $v = str_replace('monkey','',$v); // fixes invalid input with "monkey" in string
     if( preg_match($input_expl, $v) ){
       return __('Illegal characters in POST. Possible email injection attempt', 'si-contact-form');
     }
   }
 }

 return 0;
} // end function ctf_spamcheckpost

function si_contact_plugin_action_links( $links, $file ) {
    //Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ){
        $settings_link = '<a href="plugins.php?page=si-contact-form/si-contact-form.php">' . __( 'Settings', 'si-contact-form' ) . '</a>';
	    array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
} // end function si_contact_plugin_action_links

function si_contact_form_num() {
     // get options
    $si_contact_gb_mf = get_option("si_contact_form_gb");

    $form_num = '';
    if ( isset($_GET['ctf_form_num']) && is_numeric($_GET['ctf_form_num']) && $_GET['ctf_form_num'] > 1 && $_GET['ctf_form_num'] <= $si_contact_gb_mf['max_forms'] ) {
       $form_num = (int)$_GET['ctf_form_num'];
    }
    return $form_num;
} // end function si_contact_form_num

// load things during init
function si_contact_init() {

   if (function_exists('load_plugin_textdomain')) {
      load_plugin_textdomain('si-contact-form', false, dirname(plugin_basename(__FILE__)).'/languages' );
   }

} // end function si_contact_init

function si_contact_get_options($form_num) {
   global $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults;

      $si_contact_gb_defaults = array(
         'donated' => 'false',
         'max_forms' => '4',
         'max_fields' => '4',
         'captcha_disable_session' => 'true',
      );

     $si_contact_option_defaults = array(
         'form_name' => '',
         'welcome' => __('<p>Comments or questions are welcome.</p>', 'si-contact-form'),
         'email_to' => __('Webmaster', 'si-contact-form').','.get_option('admin_email'),
         'php_mailer_enable' => 'wordpress',
         'email_from' => '',
         'email_from_enforced' => 'false',
         'email_reply_to' => '',
         'email_bcc' => '',
         'email_subject' => get_option('blogname') . ' ' .__('Contact:', 'si-contact-form'),
         'email_subject_list' => '',
         'name_format' => 'name',
         'name_type' => 'required',
         'email_type' => 'required',
         'subject_type' => 'required',
         'message_type' => 'required',
         'preserve_space_enable' => 'false',
         'max_fields' => $si_contact_gb_defaults['max_fields'],
         'double_email' => 'false',
         'name_case_enable' => 'false',
         'sender_info_enable' => 'true',
         'domain_protect' => 'true',
         'email_check_dns' => 'false',
         'email_html' => 'false',
         'akismet_disable' => 'false',
         'akismet_send_anyway' => 'true',
         'captcha_enable' => 'true',
         'captcha_small' => 'false',
         'captcha_difficulty' => 'medium',
         'captcha_no_trans' => 'false',
         'enable_audio' => 'true',
         'enable_audio_flash' => 'false',
         'captcha_perm' => 'false',
         'captcha_perm_level' => 'read',
         'redirect_enable' => 'true',
         'redirect_seconds' => '3',
         'redirect_url' => get_option('home'),
         'redirect_query' => 'false',
         'redirect_ignore' => '',
         'redirect_rename' => '',
         'redirect_add' => '',
         'redirect_email_off' => 'false',
         'silent_send' => 'off',
         'silent_url' => '',
         'silent_ignore' => '',
         'silent_rename' => '',
         'silent_add' => '',
         'silent_email_off' => 'false',
         'export_enable' => 'true',
         'export_ignore' => '',
         'export_rename' => '',
         'export_add' => '',
         'export_email_off' => 'false',
         'ex_fields_after_msg' => 'false',
         'date_format' => 'mm/dd/yyyy',
         'cal_start_day' => '0',
         'time_format' => '12',
         'attach_types' =>  'doc,pdf,txt,gif,jpg,jpeg,png',
         'attach_size' =>   '1mb',
         'textarea_html_allow' => 'false',
         'enable_areyousure' => 'false',
         'auto_respond_enable' => 'false',
         'auto_respond_html' => 'false',
         'auto_respond_from_name' => 'WordPress',
         'auto_respond_from_email' => get_option('admin_email'),
         'auto_respond_reply_to' => get_option('admin_email'),
         'auto_respond_subject' => '',
         'auto_respond_message' => '',
         'req_field_indicator_enable' => 'true',
         'req_field_label_enable' => 'true',
         'req_field_indicator' => ' *',
         'border_enable' => 'false',
         'form_style' => 'width:375px;',
         'border_style' => 'border:1px solid black; padding:10px;',
         'required_style' => 'text-align:left;',
         'notes_style' => 'text-align:left;',
         'title_style' => 'text-align:left; padding-top:5px;',
         'field_style' => 'text-align:left; margin:0;',
         'field_div_style' => 'text-align:left;',
         'error_style' => 'text-align:left; color:red;',
         'select_style' => 'text-align:left;',
         'captcha_div_style_sm' => 'width:175px; height:50px; padding-top:2px;',
         'captcha_div_style_m' => 'width:250px; height:65px; padding-top:2px;',
         'captcha_input_style' => 'text-align:left; margin:0; width:50px;',
         'submit_div_style' => 'text-align:left; padding-top:2px;',
         'button_style' => 'cursor:pointer; margin:0;',
         'reset_style' => 'cursor:pointer; margin:0;',
         'powered_by_style' => 'font-size:x-small; font-weight:normal; padding-top:5px;',
         'field_size' => '40',
         'captcha_field_size' => '6',
         'text_cols' => '30',
         'text_rows' => '10',
         'aria_required' => 'false',
         'auto_fill_enable' => 'true',
         'title_border' => '',
         'title_dept' => '',
         'title_select' => '',
         'title_name' => '',
         'title_fname' => '',
         'title_mname' => '',
         'title_miname' => '',
         'title_lname' => '',
         'title_email' => '',
         'title_email2' => '',
         'title_email2_help' => '',
         'title_subj' => '',
         'title_mess' => '',
         'title_capt' => '',
         'title_submit' => '',
         'title_reset' => '',
         'title_areyousure' => '',
         'text_message_sent' => '',
         'tooltip_required' => '',
         'tooltip_captcha' => '',
         'tooltip_audio' => '',
         'tooltip_refresh' => '',
         'tooltip_filetypes' => '',
         'tooltip_filesize' => '',
         'enable_reset' => 'false',
         'enable_credit_link' => 'false',
         'error_contact_select' => '',
         'error_name'           => '',
         'error_email'          => '',
         'error_email2'         => '',
         'error_field'          => '',
         'error_subject'        => '',
         'error_message'        => '',
         'error_input'          => '',
         'error_captcha_blank'  => '',
         'error_captcha_wrong'  => '',
         'error_correct'        => '',
  );

   // optional extra fields
  $si_contact_max_fields = $si_contact_gb_defaults['max_fields'];
  if ($si_contact_opt = get_option("si_contact_form$form_num")) { // when not in admin
     if (isset($si_contact_opt['max_fields'])) // use previous setting if it is set
     $si_contact_max_fields = $si_contact_opt['max_fields'];
  }

  for ($i = 1; $i <= $si_contact_max_fields; $i++) { // initialize new
        $si_contact_option_defaults['ex_field'.$i.'_default'] = '0';
        $si_contact_option_defaults['ex_field'.$i.'_default_text'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_req'] = 'false';
        $si_contact_option_defaults['ex_field'.$i.'_label'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_type'] = 'text';
        $si_contact_option_defaults['ex_field'.$i.'_max_len'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_label_css'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_input_css'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_attributes'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_regex'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_regex_error'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_notes'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_notes_after'] = '';
  }

  // upgrade path from old version
  if (!get_option('si_contact_form') && get_option('si_contact_email_to')) {
    // just now updating, migrate settings
    $si_contact_option_defaults = $this->si_contact_migrate($si_contact_option_defaults);
  }

  // upgrade path from old version  2.0.1 or older
  if (!get_option('si_contact_form_gb') && get_option('si_contact_form')) {
    // just now updating, migrate settings
    $si_contact_gb_defaults = $this->si_contact_migrate2($si_contact_gb_defaults);
  }

  // install the global option defaults
  add_option('si_contact_form_gb',  $si_contact_gb_defaults, '', 'yes');

  // install the option defaults
  add_option('si_contact_form',  $si_contact_option_defaults, '', 'yes');

  // multi-form
  $si_contact_max_forms = ( isset($_POST['si_contact_max_forms']) && is_numeric($_POST['si_contact_max_forms']) ) ? $_POST['si_contact_max_forms'] : $si_contact_gb_defaults['max_forms'];
  for ($i = 2; $i <= $si_contact_max_forms; $i++) {
     add_option("si_contact_form$i", $si_contact_option_defaults, '', 'yes');
  }

  // get the options from the database
  $si_contact_gb = get_option("si_contact_form_gb");

  // array merge incase this version has added new options
  $si_contact_gb = array_merge($si_contact_gb_defaults, $si_contact_gb);

  update_option("si_contact_form_gb", $si_contact_gb);

  // get the options from the database
  $si_contact_gb = get_option("si_contact_form_gb");

  // get the options from the database
  $si_contact_opt = get_option("si_contact_form$form_num");

  if (!isset($si_contact_opt['max_fields'])) {  // updated from version < 3.0.3
          $si_contact_opt['max_fields'] = $si_contact_gb['max_fields'];
          update_option("si_contact_form$form_num", $si_contact_opt);
  }

  // array merge incase this version has added new options
  $si_contact_opt = array_merge($si_contact_option_defaults, $si_contact_opt);

  // strip slashes on get options array
  foreach($si_contact_opt as $key => $val) {
           $si_contact_opt[$key] = $this->ctf_stripslashes($val);
  }
  if ($si_contact_opt['title_style'] == '' && $si_contact_opt['field_style'] == '') {
     // if styles seem to be blank, reset styles
     $si_contact_opt = $this->si_contact_copy_styles($si_contact_option_defaults,$si_contact_opt);
  }

  // new field type defaults on version 2.6.3
  if ( !isset($si_contact_gb['2.6.3']) ) {
          // optional extra fields
    for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '' && $si_contact_opt['ex_field'.$i.'_type'] != 'radio' && $si_contact_opt['ex_field'.$i.'_type'] != 'select' ) {
                $si_contact_opt['ex_field'.$i.'_default'] = '0';
        }
        if ($si_contact_opt['ex_field'.$i.'_label'] == '') {
          $si_contact_opt['ex_field'.$i.'_default'] = '0';
          $si_contact_opt['ex_field'.$i.'_default_text'] = '';
          $si_contact_opt['ex_field'.$i.'_req'] = 'false';
          $si_contact_opt['ex_field'.$i.'_label'] = '';
          $si_contact_opt['ex_field'.$i.'_type'] = 'text';
          $si_contact_opt['ex_field'.$i.'_max_len'] = '';
          $si_contact_opt['ex_field'.$i.'_label_css'] = '';
          $si_contact_opt['ex_field'.$i.'_input_css'] = '';
          $si_contact_opt['ex_field'.$i.'_attributes'] = '';
          $si_contact_opt['ex_field'.$i.'_regex'] = '';
          $si_contact_opt['ex_field'.$i.'_regex_error'] = '';
          $si_contact_opt['ex_field'.$i.'_notes'] = '';
          $si_contact_opt['ex_field'.$i.'_notes_after'] = '';
        }
    }
    update_option("si_contact_form", $si_contact_opt);
    for ($i = 2; $i <= $si_contact_gb['max_forms']; $i++) {
       // get the options from the database
       $si_contact_opt{$i} = get_option("si_contact_form$i");
       for ($f = 1; $f <= $si_contact_opt['max_fields']; $f++) {
         if ($si_contact_opt{$i}['ex_field'.$f.'_label'] != '' && $si_contact_opt{$i}['ex_field'.$f.'_type'] != 'radio' && $si_contact_opt{$i}['ex_field'.$f.'_type'] != 'select' ) {
                $si_contact_opt{$i}['ex_field'.$f.'_default'] = '0';
         }
         if ($si_contact_opt{$i}['ex_field'.$f.'_label'] == '') {
          $si_contact_opt{$i}['ex_field'.$f.'_default'] = '0';
         }
       }
       update_option("si_contact_form$i", $si_contact_opt{$i});
       unset($si_contact_opt{$i});
    }
    $si_contact_opt = get_option("si_contact_form$form_num");
    $si_contact_opt = array_merge($si_contact_option_defaults, $si_contact_opt);
    foreach($si_contact_opt as $key => $val) {
           $si_contact_opt[$key] = $this->ctf_stripslashes($val);
    }
    $si_contact_gb['2.6.3'] = 1;
    update_option("si_contact_form_gb", $si_contact_gb);
    $si_contact_gb = get_option("si_contact_form_gb");
    $si_contact_gb = array_merge($si_contact_gb_defaults, $si_contact_gb);
  }
    return $si_contact_gb;

} // end function si_contact_get_options

// used when resetting or copying style settings
function si_contact_copy_styles($this_form_arr,$destination_form_arr) {

     $style_copy_arr = array(
     'border_enable','form_style','border_style','required_style','notes_style',
     'title_style','field_style','field_div_style','error_style','select_style',
     'captcha_div_style_sm','captcha_div_style_m','captcha_input_style','submit_div_style','button_style', 'reset_style',
     'powered_by_style','field_size','captcha_field_size','text_cols','text_rows');
     foreach($style_copy_arr as $style_copy) {
           $destination_form_arr[$style_copy] = $this_form_arr[$style_copy];
     }
     return $destination_form_arr;
}

function si_contact_start_session() {
  // a PHP session cookie is set so that the captcha can be remembered and function
  // this has to be set before any header output
  //echo "starting session ctf";
  // start cookie session, but do not start session if captcha is disabled in options
  if( !isset( $_SESSION ) ) { // play nice with other plugins
    session_cache_limiter ('private, must-revalidate');
    session_start();
    //echo "session started ctf";
  }
} // end function si_contact_start_session

function si_contact_migrate($si_contact_option_defaults) {
  // read the options from the prior version
   $new_options = array ();
   foreach($si_contact_option_defaults as $key => $val) {
      $new_options[$key] = $this->ctf_stripslashes( get_option( "si_contact_$key" ));
      // now delete the options from the prior version
      delete_option("si_contact_$key");
   }
   // delete settings no longer used
   delete_option('si_contact_email_language');
   delete_option('si_contact_email_charset');
   delete_option('si_contact_email_encoding');
   // by returning this the old settings will carry over to the new version
   return $new_options;
} //  end function si_contact_migrate

function si_contact_migrate2($si_contact_gb_defaults) {
  // read the options from the prior version

   $new_options = array ();
   $migrate_opt = get_option("si_contact_form");
   $new_options['donated'] = $migrate_opt['donated'];
   $new_options['max_forms'] = $si_contact_gb_defaults['max_forms'];
   $new_options['max_fields'] = $si_contact_gb_defaults['max_fields'];
   if(defined('SI_CONTACT_FORM_MAX_FORMS') && SI_CONTACT_FORM_MAX_FORMS > $si_contact_gb_defaults['max_forms']) {
    $new_options['max_forms'] = SI_CONTACT_FORM_MAX_FORMS;
   }
   if(defined('SI_CONTACT_FORM_MAX_FIELDS') && SI_CONTACT_FORM_MAX_FIELDS > $si_contact_gb_defaults['max_fields']) {
    $new_options['max_fields'] = SI_CONTACT_FORM_MAX_FIELDS;
   }
   unset($migrate_opt);

   // by returning this the old settings will carry over to the new version
   //print_r($new_options); exit;
   return $new_options;
} //  end function si_contact_migrate2


// restores settings from a contact form settings backup file
function si_contact_form_backup_restore($bk_form_num) {
  global $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults;

    // form file upload
     if(isset($_FILES['si_contact_backup_file']) && !empty( $_FILES['si_contact_backup_file'] ))
       $file = $_FILES['si_contact_backup_file'];
     else
       return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file is required.', 'si-contact-form').'</p></div>';

	 if ( ($file['error'] && UPLOAD_ERR_NO_FILE != $file['error']) || !is_uploaded_file( $file['tmp_name'] ) )
        return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file upload failed.', 'si-contact-form').'</p></div>';

	 if ( empty( $file['tmp_name'] ) )
        return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file is required.', 'si-contact-form').'</p></div>';

    // check file type
	$file_type_pattern = '/\.txt$/i';
	if ( ! preg_match( $file_type_pattern, $file['name'] ) )
        return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file type not allowed.', 'si-contact-form').'</p></div>';

    // check size
    $allowed_size = 1048576; // 1mb default
	if ( $file['size'] > $allowed_size )
        return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file size is too large.', 'si-contact-form').'</p></div>';

    // get the uploaded file that contains all the data
    $ctf_backup_data = file_get_contents($file['tmp_name']);
    $ctf_backup_data_split = explode("@@@@SPLIT@@@@\r\n", $ctf_backup_data);
    $ctf_backup_array = unserialize($ctf_backup_data_split[1]);

    if ( !isset($ctf_backup_array) || !is_array($ctf_backup_array) || !isset($ctf_backup_array[0]['backup_type']) )
         return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file contains invalid data.', 'si-contact-form').'</p></div>';

   //print_r($ctf_backup_array);
   //exit;

         $ctf_backup_type = $ctf_backup_array[0]['backup_type'];
         unset($ctf_backup_array[0]['backup_type']);

         // is the uploaded file of the "all" type?
         if ( $ctf_backup_type != 'all' && $bk_form_num == 'all'  )
              return '<div id="message" class="updated fade"><p>'.__('Restore failed: Selected All to restore, but backup file is a single form.', 'si-contact-form').'</p></div>';

         // restore all ?
         if($ctf_backup_type == 'all' && $bk_form_num == 'all' ) {
            // all

            // is the uploaded file of the "all" type?
            if ( !isset($ctf_backup_array[2]) || !is_array($ctf_backup_array[2])  )
              return '<div id="message" class="updated fade"><p>'.__('Restore failed: Selected All to restore, but backup file is a single form.', 'si-contact-form').'</p></div>';

            $my_max_forms = $si_contact_gb['max_forms'];
            // if current max_forms or max_fields are more, go with higher value
            if($si_contact_gb['max_forms'] > $ctf_backup_array[0]['max_forms']) {
                $my_max_forms = $ctf_backup_array[0]['max_forms'];
                $ctf_backup_array[0]['max_forms'] = $si_contact_gb['max_forms'];
            } else {
                $my_max_forms = $ctf_backup_array[0]['max_forms'];
            }
            if($si_contact_gb['max_fields'] > $ctf_backup_array[0]['max_fields'])
                $ctf_backup_array[0]['max_fields'] = $si_contact_gb['max_fields'];
            update_option("si_contact_form_gb", $ctf_backup_array[0]);

               // extra field labels might have \, (make sure it does not get removed)
            foreach($ctf_backup_array[1] as $key => $val) {
                $ctf_backup_array[1][$key] = str_replace('\,','\\\,',$val);
            }
            update_option("si_contact_form", $ctf_backup_array[1]);
            // multi-forms > 1
            for ($i = 2; $i <= $my_max_forms; $i++) {
               // extra field labels might have \, (make sure it does not get removed)
              foreach($ctf_backup_array[$i] as $key => $val) {
                  $ctf_backup_array[$i][$key] = str_replace('\,','\\\,',$val);
              }
              if(!get_option("si_contact_form$i")) {
                    add_option("si_contact_form$i", $ctf_backup_array[$i], '', 'yes');
              }else{
                   update_option("si_contact_form$i", $ctf_backup_array[$i]);
              }
            }
           //error_reporting(0); // suppress errors because a different version backup may have uninitialized vars
           // success
           return '<div id="message" class="updated fade"><p>'.__('All form settings have been restored from the backup file.', 'si-contact-form').'</p></div>';

         } // end restoring all

         // restore single?
         if(is_numeric($bk_form_num)){
            // single
            if( ($bk_form_num == 1 && !get_option("si_contact_form")) || ($bk_form_num > 1 && !get_option("si_contact_form$bk_form_num")))
               return '<div id="message" class="updated fade"><p>'.__('Restore failed: Form to restore to does not exist.', 'si-contact-form').'</p></div>';

            // update the globals
            if($si_contact_gb['max_fields'] < $ctf_backup_array[0]['max_fields']) {
                $si_contact_gb['max_fields'] = $ctf_backup_array[0]['max_fields'];
                update_option("si_contact_form_gb", $si_contact_gb);
            }

            // is the uploaded file of the "single" type?
            if ( !isset($ctf_backup_array[2]) || !is_array($ctf_backup_array[2])  ) {
               //single

               // extra field labels might have \, (make sure it does not get removed)
               foreach($ctf_backup_array[1] as $key => $val) {
                  $ctf_backup_array[1][$key] = str_replace('\,','\\\,',$val);
               }
               if ($bk_form_num == 1)
                  update_option("si_contact_form", $ctf_backup_array[1]);

               if ($bk_form_num > 1)
                   update_option("si_contact_form$bk_form_num", $ctf_backup_array[1]);

               // is the uploaded file of the "all" type?
            } else {
               // "all" backup file, but wants to restore only one form, match the form #
               // extra field labels might have \, (make sure it does not get removed)
               foreach($ctf_backup_array[$bk_form_num] as $key => $val) {
                   $ctf_backup_array[$bk_form_num][$key] = str_replace('\,','\\\,',$val);
               }
               if ($bk_form_num == 1)
                  update_option("si_contact_form", $ctf_backup_array[1]);

               if ($bk_form_num > 1)
                  update_option("si_contact_form$bk_form_num", $ctf_backup_array[$bk_form_num]);
             }

              // success
              return '<div id="message" class="updated fade"><p>'.sprintf(__('Form %d settings have been restored from the backup file.', 'si-contact-form'),$bk_form_num).'</p></div>';

         } // end restoring single

} // end function si_contact_form_backup_restore

// outputs a contact form settings backup file
function si_contact_backup_download() {
  global $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults, $ctf_version;

  require_once WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-backup.php';

} // end function si_contact_backup_download


function get_captcha_url_cf() {

  // The captcha URL cannot be on a different domain as the site rewrites to or the cookie won't work
  // also the path has to be correct or the image won't load.
  // WP_PLUGIN_URL was not getting the job done! this code should fix it.

  //http://media.example.com/wordpress   WordPress address get_option( 'siteurl' )
  //http://tada.example.com              Blog address      get_option( 'home' )

  //http://example.com/wordpress  WordPress address get_option( 'siteurl' )
  //http://example.com/           Blog address      get_option( 'home' )

  $site_uri = parse_url(get_option('home'));
  $home_uri = parse_url(get_option('siteurl'));

  $captcha_url_cf  = WP_PLUGIN_URL . '/si-contact-form/captcha';

  if ($site_uri['host'] == $home_uri['host']) {
      $captcha_url_cf  = WP_PLUGIN_URL . '/si-contact-form/captcha';
  } else {
      $captcha_url_cf  = get_option( 'home' ) . '/'.PLUGINDIR.'/si-contact-form/captcha';
  }
  // set the type of request (SSL or not)
  if ( getenv('HTTPS') == 'on' ) {
		$captcha_url_cf = preg_replace('|http://|', 'https://', $captcha_url_cf);
  }

  return $captcha_url_cf;
}

function si_contact_admin_head() {
 // only load this header stuff on the admin settings page
if(isset($_GET['page']) && preg_match('/si-contact-form.php$/',$_GET['page']) ) {
?>
<!-- begin Fast Secure Contact Form - admin settings page header code -->
<style type="text/css">
div.star-holder { position: relative; height:19px; width:100px; font-size:19px;}
div.star {height: 100%; position:absolute; top:0px; left:0px; background-color: transparent; letter-spacing:1ex; border:none;}
.star1 {width:20%;} .star2 {width:40%;} .star3 {width:60%;} .star4 {width:80%;} .star5 {width:100%;}
.star.star-rating {background-color: #fc0;}
.star img{display:block; position:absolute; right:0px; border:none; text-decoration:none;}
div.star img {width:19px; height:19px; border-left:1px solid #fff; border-right:1px solid #fff;}
#main fieldset {border: 1px solid #B8B8B8; padding:19px; margin: 0 0 20px 0;background: #F1F1F1; font:13px Arial, Helvetica, sans-serif;}
.form-tab {background:#F1F1F1; display:block; font-weight:bold; padding:7px 20px; float:left; font-size:13px; margin-bottom:-1px; border:1px solid #B8B8B8; border-bottom:none;}
.submit {padding:7px; margin-bottom:15px;}
.fsc-error{background-color:#ffebe8;border-color:red;border-width:1px;border-style:solid;padding:5px;margin:5px 5px 20px;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;}
.fsc-error a{color:#c00;}
.fsc-notice{background-color:#ffffe0;border-color:#e6db55;border-width:1px;border-style:solid;padding:5px;margin:5px 5px 20px;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;}
</style>
<!-- end Fast Secure Contact Form - admin settings page header code -->
<?php
  } // end if(isset($_GET['page'])

}

function si_contact_form_from_email() {
 return $this->si_contact_from_email;
}

function si_contact_form_from_name() {
 return $this->si_contact_from_name;
}

function si_contact_form_mail_sender($phpmailer) {
 // add Sender for Return-path to wp_mail
 $phpmailer->Sender = $this->si_contact_mail_sender;
}

function ctf_notes($notes) {
           return   '
        <div '.$this->ctf_notes_style.'>
         '.$notes.'
        </div>
        ';
}

function si_contact_convert_css($string) {

    if( preg_match("/^style=\"(.*)\"$/i", $string) ){
      return $string;
    }
    if( preg_match("/^class=\"(.*)\"$/i", $string) ){
      return $string;
    }
    return 'style="'.$string.'"';

} // end function si_contact_convert_css

function si_contact_add_script(){
    global $ctf_add_script;

    if (!$ctf_add_script)
      return;

   wp_register_script('si_contact_form', plugins_url('captcha/ctf_captcha.js', __FILE__), array(), '1.0', true);
   wp_print_scripts('si_contact_form');
}

} // end of class
} // end of if class

// Pre-2.8 compatibility
if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return wp_specialchars( $text );
	}
}

// Pre-2.8 compatibility
if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return attribute_escape( $text );
	}
}

if (class_exists("siContactForm")) {
 $si_contact_form = new siContactForm();
}

if (isset($si_contact_form)) {

  $captcha_url_cf  = $si_contact_form->get_captcha_url_cf();
  $captcha_path_cf = WP_PLUGIN_DIR . '/si-contact-form/captcha';

  // only used for the no-session captcha setting
  $ctf_captcha_url = $captcha_url_cf  . '/temp/';
  $ctf_captcha_dir = $captcha_path_cf . '/temp/';
  $si_contact_form->si_contact_init_temp_dir($ctf_captcha_dir);

  // si_contact initialize options
  add_action('init', array(&$si_contact_form, 'si_contact_init'),1);

  $si_contact_gb = get_option("si_contact_form_gb");
  if ( isset($si_contact_gb['captcha_disable_session']) && $si_contact_gb['captcha_disable_session'] == 'true') {
      // add javascript (conditionally to footer)
      // http://scribu.net/wordpress/optimal-script-loading.html
      add_action( 'wp_footer', array(&$si_contact_form,'si_contact_add_script'));
      add_action( 'admin_footer', array(&$si_contact_form,'si_contact_add_script'));
  }  else {
     // start the PHP session
     add_action('init', array(&$si_contact_form,'si_contact_start_session'),2);
  }

  // si contact form admin options
  add_action('admin_menu', array(&$si_contact_form,'si_contact_add_tabs'),1);
  add_action('admin_head', array(&$si_contact_form,'si_contact_admin_head'),1);

  // this is for downloading settings backup txt file.
  add_action('admin_init', array(&$si_contact_form,'si_contact_backup_download'),1);

  // adds "Settings" link to the plugin action page
  add_filter( 'plugin_action_links', array(&$si_contact_form,'si_contact_plugin_action_links'),10,2);

  // use shortcode to print the contact form or process contact form logic
  // can use dashes or underscores: [si-contact-form] or [si_contact_form]
  add_shortcode('si_contact_form', array(&$si_contact_form,'si_contact_form_short_code'),1);
  add_shortcode('si-contact-form', array(&$si_contact_form,'si_contact_form_short_code'),1);

  // If you want to use shortcodes in your widgets or footer
  add_filter('widget_text', 'do_shortcode');
  add_filter('wp_footer', 'do_shortcode');

    // options deleted when this plugin is deleted in WP 2.7+
  if ( function_exists('register_uninstall_hook') )
     register_uninstall_hook(__FILE__, 'si_contact_unset_options');

}

?>