<?php
/*
Fast Secure Contact Form
Mike Challis
http://www.642weather.com/weather/scripts.php
*/

// the form is being processed to send the mail now

    // check all input variables
    $cid = $this->ctf_clean_input($_POST['si_contact_CID']);
    if(empty($cid)) {
       $this->si_contact_error = 1;
       $si_contact_error_contact = ($si_contact_opt['error_contact_select'] != '') ? $si_contact_opt['error_contact_select'] : __('Selecting a contact is required.', 'si-contact-form');
    }
    else if (!isset($contacts[$cid]['CONTACT'])) {
        $this->si_contact_error = 1;
        $si_contact_error_contact = __('Requested Contact not found.', 'si-contact-form');
    }
    if (empty($ctf_contacts)) {
       $this->si_contact_error = 1;
    }
    $mail_to    = ( isset($contacts[$cid]['EMAIL']) )   ? $this->ctf_clean_input($contacts[$cid]['EMAIL'])  : '';
    $to_contact = ( isset($contacts[$cid]['CONTACT']) ) ? $this->ctf_clean_input($contacts[$cid]['CONTACT']): '';

    // allow shortcode email_to
    // Webmaster,user1@example.com (must have name,email)
    // multiple emails allowed
    // Webmaster,user1@example.com;user2@example.com
   if ( $shortcode_email_to != '') {
     if(preg_match("/,/", $shortcode_email_to) ) {
       list($key, $value) = preg_split('#(?<!\\\)\,#',$shortcode_email_to); //string will be split by "," but "\," will be ignored
       $key   = trim(str_replace('\,',',',$key)); // "\," changes to ","
       $value = trim(str_replace(';',',',$value)); // ";" changes to ","
       if ($key != '' && $value != '') {
             $mail_to    = $this->ctf_clean_input($value);
             $to_contact = $this->ctf_clean_input($key);
       }
     }
   }

    if ($si_contact_opt['name_type'] != 'not_available') {
        switch ($si_contact_opt['name_format']) {
          case 'name':
             if (isset($_POST['si_contact_name']))
               $name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_name']));
          break;
          case 'first_last':
             if (isset($_POST['si_contact_f_name']))
               $f_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_f_name']));
             if (isset($_POST['si_contact_l_name']))
               $l_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_l_name']));
          break;
          case 'first_middle_i_last':
             if (isset($_POST['si_contact_f_name']))
               $f_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_f_name']));
             if (isset($_POST['si_contact_mi_name']))
               $mi_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_mi_name']));
             if (isset($_POST['si_contact_l_name']))
               $l_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_l_name']));
          break;
          case 'first_middle_last':
             if (isset($_POST['si_contact_f_name']))
               $f_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_f_name']));
             if (isset($_POST['si_contact_m_name']))
               $m_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_m_name']));
             if (isset($_POST['si_contact_l_name']))
               $l_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_l_name']));
         break;
      }
    }
    if ($si_contact_opt['email_type'] != 'not_available') {
       if (isset($_POST['si_contact_email']))
         $email = strtolower($this->ctf_clean_input($_POST['si_contact_email']));
       if ($ctf_enable_double_email == 'true') {
         if (isset($_POST['si_contact_email2']))
          $email2 = strtolower($this->ctf_clean_input($_POST['si_contact_email2']));
       }
    }

    if ($si_contact_opt['subject_type'] != 'not_available') {
        if(isset($_POST['si_contact_subject'])) {
            // posted subject text input
            $subject = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_subject']));
        }else{
            // posted subject select input
            $sid = $this->ctf_clean_input($_POST['si_contact_subject_ID']);
            if(empty($sid) && $si_contact_opt['subject_type'] == 'required') {
               $this->si_contact_error = 1;
               $si_contact_error_subject = ($si_contact_opt['error_subject'] != '') ? $si_contact_opt['error_subject'] : __('Selecting a subject is required.', 'si-contact-form');
            }
            else if (empty($subjects) || !isset($subjects[$sid])) {
               $this->si_contact_error = 1;
               $si_contact_error_subject = __('Requested subject not found.', 'si-contact-form');
            } else {
               $subject = $this->ctf_clean_input($subjects[$sid]);
            }
       }
    }

    if ($si_contact_opt['message_type'] != 'not_available') {
       if (isset($_POST['si_contact_message'])) {
         if ($si_contact_opt['preserve_space_enable'] == 'true')
           $message = $this->ctf_clean_input($_POST['si_contact_message'],1);
         else
           $message = $this->ctf_clean_input($_POST['si_contact_message']);
       }
    }
    if ( $this->isCaptchaEnabled() )
         $captcha_code = $this->ctf_clean_input($_POST['si_contact_captcha_code']);

    // check posted input for email injection attempts
    // fights common spammer tactics
    // look for newline injections
    $this->ctf_forbidifnewlines($name);
    $this->ctf_forbidifnewlines($email);
    if ($ctf_enable_double_email == 'true')
         $this->ctf_forbidifnewlines($email2);

    $this->ctf_forbidifnewlines($subject);

    // look for lots of other injections
    $forbidden = 0;
    $forbidden = $this->ctf_spamcheckpost();
    if ($forbidden)
       wp_die("$forbidden");

   // check for banned ip
   if( $ctf_enable_ip_bans && in_array($_SERVER['REMOTE_ADDR'], $ctf_banned_ips) )
      wp_die(__('Your IP is Banned', 'si-contact-form'));

   // CAPS Decapitator
   if ($si_contact_opt['name_case_enable'] == 'true' && !preg_match("/[a-z]/", $message))
      $message = $this->ctf_name_case($message);

    switch ($si_contact_opt['name_format']) {
       case 'name':
        if($name == '' && $si_contact_opt['name_type'] == 'required') {
          $this->si_contact_error = 1;
          $si_contact_error_name =  ($si_contact_opt['error_name'] != '') ? $si_contact_opt['error_name'] : __('Your name is required.', 'si-contact-form');
        }
      break;
      default:
        if(empty($f_name) && $si_contact_opt['name_type'] == 'required') {
          $this->si_contact_error = 1;
          $si_contact_error_f_name =  ($si_contact_opt['error_name'] != '') ? $si_contact_opt['error_name'] : __('Your name is required.', 'si-contact-form');
        }
        if(empty($l_name) && $si_contact_opt['name_type'] == 'required') {
          $this->si_contact_error = 1;
          $si_contact_error_l_name =  ($si_contact_opt['error_name'] != '') ? $si_contact_opt['error_name'] : __('Your name is required.', 'si-contact-form');
        }
    }

   if(!empty($f_name)) $name .= $f_name;
   if(!empty($mi_name))$name .= ' '.$mi_name;
   if(!empty($m_name)) $name .= ' '.$m_name;
   if(!empty($l_name)) $name .= ' '.$l_name;

   if($si_contact_opt['email_type'] == 'required') {
     if (!$this->ctf_validate_email($email)) {
         $this->si_contact_error = 1;
         $si_contact_error_email = ($si_contact_opt['error_email'] != '') ? $si_contact_opt['error_email'] : __('A proper e-mail address is required.', 'si-contact-form');
     }
     if ($ctf_enable_double_email == 'true' && !$this->ctf_validate_email($email2)) {
         $this->si_contact_error = 1;
         $si_contact_error_email2 = ($si_contact_opt['error_email'] != '') ? $si_contact_opt['error_email'] : __('A proper e-mail address is required.', 'si-contact-form');
     }
     if ($ctf_enable_double_email == 'true' && ($email != $email2)) {
         $this->si_contact_error = 1;
         $si_contact_error_double_email = ($si_contact_opt['error_email2'] != '') ? $si_contact_opt['error_email2'] : __('The two e-mail addresses did not match, please enter again.', 'si-contact-form');
     }
   }

// check attachment directory
$attach_dir_error = 0;
if ($have_attach){
	$attach_dir = WP_PLUGIN_DIR . '/si-contact-form/attachments/';
	if ( !is_dir($attach_dir) ) {
        $this->si_contact_error = 1;
		$attach_dir_error = sprintf( __( 'This contact form has file attachment fields, but the temporary folder for the files (%s) does not exist. Create the folder manually and (<a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">fix permissions</a>)', 'si-contact-form' ), $attach_dir );
    } else if(!is_writable($attach_dir)) {
          $this->si_contact_error = 1;
		 $attach_dir_error = sprintf( __( 'This contact form has file attachment fields, but the temporary folder for the files (%s) is not writable. (<a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">fix permissions</a>)', 'si-contact-form' ), $attach_dir );
    } else {
       // delete files over 3 minutes old in the attachment directory
       $this->si_contact_clean_temp_dir($attach_dir, 3);
	}
}

   // optional extra fields form post validation
      for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '' && $si_contact_opt['ex_field'.$i.'_type'] != 'fieldset-close') {
          if ($si_contact_opt['ex_field'.$i.'_type'] == 'fieldset') {

          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'date') {

                      $cal_date_array = array(
'mm/dd/yyyy' => esc_attr(__('mm/dd/yyyy', 'si-contact-form')),
'dd/mm/yyyy' => esc_attr(__('dd/mm/yyyy', 'si-contact-form')),
'mm-dd-yyyy' => esc_attr(__('mm-dd-yyyy', 'si-contact-form')),
'dd-mm-yyyy' => esc_attr(__('dd-mm-yyyy', 'si-contact-form')),
'mm.dd.yyyy' => esc_attr(__('mm.dd.yyyy', 'si-contact-form')),
'dd.mm.yyyy' => esc_attr(__('dd.mm.yyyy', 'si-contact-form')),
'yyyy/mm/dd' => esc_attr(__('yyyy/mm/dd', 'si-contact-form')),
'yyyy-mm-dd' => esc_attr(__('yyyy-mm-dd', 'si-contact-form')),
'yyyy.mm.dd' => esc_attr(__('yyyy.mm.dd', 'si-contact-form')),
);
               // required validate
               ${'ex_field'.$i} = ( !isset($_POST["si_contact_ex_field$i"]) ) ? '' : $this->ctf_clean_input($_POST["si_contact_ex_field$i"]);
               if( (${'ex_field'.$i} == '' || ${'ex_field'.$i} == $cal_date_array[$si_contact_opt['date_format']]) && $si_contact_opt['ex_field'.$i.'_req'] == 'true') {
                  $this->si_contact_error = 1;
                  ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['error_field'] != '') ? $si_contact_opt['error_field'] : __('This field is required.', 'si-contact-form');
               }
               // max_len validate
               if( ${'ex_field'.$i} != '' && $si_contact_opt['ex_field'.$i.'_max_len'] != '' && strlen(${'ex_field'.$i}) > $si_contact_opt['ex_field'.$i.'_max_len']) {
                  $this->si_contact_error = 1;
                  ${'si_contact_error_ex_field'.$i} = sprintf( __('Maximum of %d characters exceeded.', 'si-contact-form'), $si_contact_opt['ex_field'.$i.'_max_len'] );
               }
               // regex validate
               if( ${'ex_field'.$i} != '' && $si_contact_opt['ex_field'.$i.'_regex'] != '' && !preg_match($si_contact_opt['ex_field'.$i.'_regex'],${'ex_field'.$i}) ) {
                  $this->si_contact_error = 1;
                  ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['ex_field'.$i.'_regex_error'] != '') ? $si_contact_opt['ex_field'.$i.'_regex_error'] : __('Invalid input.', 'si-contact-form');
               }

          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'hidden') {
               ${'ex_field'.$i} = ( !isset($_POST["si_contact_ex_field$i"]) ) ? '' : $this->ctf_clean_input($_POST["si_contact_ex_field$i"]);
          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'time') {
               ${'ex_field'.$i.'h'}  = $this->ctf_clean_input($_POST["si_contact_ex_field".$i."h"]);
               ${'ex_field'.$i.'m'}  = $this->ctf_clean_input($_POST["si_contact_ex_field".$i."m"]);
               if ($si_contact_opt['time_format'] == '12')
                  ${'ex_field'.$i.'ap'} = $this->ctf_clean_input($_POST["si_contact_ex_field".$i."ap"]);
          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'attachment') {
              // need to test if a file was selected for attach.
              $ex_field_file['name'] = '';
              if(isset($_FILES["si_contact_ex_field$i"]))
                  $ex_field_file = $_FILES["si_contact_ex_field$i"];
              if ($ex_field_file['name'] == '' && $si_contact_opt['ex_field'.$i.'_req'] == 'true') {
                   $this->si_contact_error = 1;
                   ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['error_field'] != '') ? $si_contact_opt['error_field'] : __('This field is required.', 'si-contact-form');
              }
              if($ex_field_file['name'] != ''){  // may not be required
                 // validate the attachment now
                 $ex_field_file_check = $this->si_contact_validate_attach( $ex_field_file, "ex_field$i" );
                 if (!$ex_field_file_check['valid']) {
                     $this->si_contact_error = 1;
                     ${'si_contact_error_ex_field'.$i} = $ex_field_file_check['error'];
                 } else {
                    ${'ex_field'.$i} = $ex_field_file_check['file_name'];  // needed for email message
                 }
              }
              unset($ex_field_file);
          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'checkbox' || $si_contact_opt['ex_field'.$i.'_type'] == 'checkbox-multiple') {
             // see if checkbox children
             $exf_opts_array = array();
             $exf_opts_label = '';
             $exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
             if(preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
                  list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
                  $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
                  $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
                  if ($exf_opts_label != '' && $value != '') {
                     if(!preg_match("/;/", $value)) {
                        $this->si_contact_error = 1;
                        ${'si_contact_error_ex_field'.$i} = __('Error: A checkbox field is not configured properly in settings.', 'si-contact-form');
                     } else {
                        // multiple options
                         $exf_opts_array = explode(";",$value);
                     }
                     // required check (only 1 has to be checked to meet required)
                    $ex_cnt = 1;
                    $ex_reqd = 0;
                    foreach ($exf_opts_array as $k) {
                      if( ! empty($_POST["si_contact_ex_field$i".'_'.$ex_cnt]) ){
                        ${'ex_field'.$i.'_'.$ex_cnt} = $this->ctf_clean_input($_POST["si_contact_ex_field$i".'_'.$ex_cnt]);
                        $ex_reqd++;
                      }
                      $ex_cnt++;
                    }
                    if(!$ex_reqd && $si_contact_opt['ex_field'.$i.'_req'] == 'true') {
                        $this->si_contact_error = 1;
                        ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['error_field'] != '') ? $si_contact_opt['error_field'] : __('At least one item in this field is required.', 'si-contact-form');
                     }
                }
             }else{
                ${'ex_field'.$i} = ( !isset($_POST["si_contact_ex_field$i"]) ) ? '' : $this->ctf_clean_input($_POST["si_contact_ex_field$i"]);
                if(${'ex_field'.$i} == '' && $si_contact_opt['ex_field'.$i.'_req'] == 'true') {
                    $this->si_contact_error = 1;
                    ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['error_field'] != '') ? $si_contact_opt['error_field'] : __('This field is required.', 'si-contact-form');
                }
             }
           }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'select-multiple') {
             $exf_opts_array = array();
             $exf_opts_label = '';
             $exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
             if(preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
                  list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
                  $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
                  $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
                  if ($exf_opts_label != '' && $value != '') {
                     if(!preg_match("/;/", $value)) {
                        $this->si_contact_error = 1;
                        ${'si_contact_error_ex_field'.$i} = __('Error: A select-multiple field is not configured properly in settings.', 'si-contact-form');
                     } else {
                        // multiple options
                         $exf_opts_array = explode(";",$value);
                     }
                     // required check (only 1 has to be checked to meet required)
                     $ex_reqd = 0;
                     ${'ex_field'.$i} = ( !isset($_POST["si_contact_ex_field$i"]) ) ? '' : $this->ctf_clean_input($_POST["si_contact_ex_field$i"]);
                     if (is_array(${'ex_field'.$i}) && !empty(${'ex_field'.$i}) ) {
                       // loop
                       foreach ($exf_opts_array as $k) {  // checkbox multi
                          if (in_array($k, ${'ex_field'.$i} ) ) {
                             $ex_reqd++;
                          }
                       }
                     }
                     if((!$ex_reqd || empty(${'ex_field'.$i})) && $si_contact_opt['ex_field'.$i.'_req'] == 'true') {
                        $this->si_contact_error = 1;
                        ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['error_field'] != '') ? $si_contact_opt['error_field'] : __('At least one item in this field is required.', 'si-contact-form');
                     }
                }
             } else {
                  $this->si_contact_error = 1;
                  ${'si_contact_error_ex_field'.$i} = __('Error: A checkbox-multiple field is not configured properly in settings.', 'si-contact-form');
             }
           }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'email') {
                  ${'ex_field'.$i} = ( !isset($_POST["si_contact_ex_field$i"]) ) ? '' : strtolower($this->ctf_clean_input($_POST["si_contact_ex_field$i"]));
                  // required validate
                  if(${'ex_field'.$i} == '' && $si_contact_opt['ex_field'.$i.'_req'] == 'true') {
                    $this->si_contact_error = 1;
                    ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['error_field'] != '') ? $si_contact_opt['error_field'] : __('This field is required.', 'si-contact-form');
                  }
                  // max_len validate
                  if( ${'ex_field'.$i} != '' && $si_contact_opt['ex_field'.$i.'_max_len'] != '' && strlen(${'ex_field'.$i}) > $si_contact_opt['ex_field'.$i.'_max_len']) {
                     $this->si_contact_error = 1;
                     ${'si_contact_error_ex_field'.$i} = sprintf( __('Maximum of %d characters exceeded.', 'si-contact-form'), $si_contact_opt['ex_field'.$i.'_max_len'] );
                  }
                  // regex validate
                  if (${'ex_field'.$i} != '' && !$this->ctf_validate_email(${'ex_field'.$i})) {
                    $this->si_contact_error = 1;
                    ${'si_contact_error_ex_field'.$i} = __('Invalid e-mail address.', 'si-contact-form');
                  }
           }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'url') {
                  ${'ex_field'.$i} = ( !isset($_POST["si_contact_ex_field$i"]) ) ? '' : $this->ctf_clean_input($_POST["si_contact_ex_field$i"]);
                  // required validate
                  if(${'ex_field'.$i} == '' && $si_contact_opt['ex_field'.$i.'_req'] == 'true') {
                    $this->si_contact_error = 1;
                    ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['error_field'] != '') ? $si_contact_opt['error_field'] : __('This field is required.', 'si-contact-form');
                  }
                  // max_len validate
                  if( ${'ex_field'.$i} != '' && $si_contact_opt['ex_field'.$i.'_max_len'] != '' && strlen(${'ex_field'.$i}) > $si_contact_opt['ex_field'.$i.'_max_len']) {
                     $this->si_contact_error = 1;
                     ${'si_contact_error_ex_field'.$i} = sprintf( __('Maximum of %d characters exceeded.', 'si-contact-form'), $si_contact_opt['ex_field'.$i.'_max_len'] );
                  }
                  // regex validate
                  if (${'ex_field'.$i} != '' && !$this->ctf_validate_url(${'ex_field'.$i})) {
                    $this->si_contact_error = 1;
                    ${'si_contact_error_ex_field'.$i} = __('Invalid URL.', 'si-contact-form');
                  }
           }else{
                // text, textarea, radio, select, password
                if ($si_contact_opt['ex_field'.$i.'_type'] == 'textarea' && $si_contact_opt['textarea_html_allow'] == 'true') {
                      ${'ex_field'.$i} = ( !isset($_POST["si_contact_ex_field$i"]) ) ? '' : $_POST["si_contact_ex_field$i"];
                }else{
                      ${'ex_field'.$i} = ( !isset($_POST["si_contact_ex_field$i"]) ) ? '' : $this->ctf_clean_input($_POST["si_contact_ex_field$i"]);
                }
                // required validate
                if(${'ex_field'.$i} == '' && $si_contact_opt['ex_field'.$i.'_req'] == 'true') {
                  $this->si_contact_error = 1;
                  ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['error_field'] != '') ? $si_contact_opt['error_field'] : __('This field is required.', 'si-contact-form');
                }
                // max_len validate
                if( ${'ex_field'.$i} != '' && $si_contact_opt['ex_field'.$i.'_max_len'] != '' && strlen(${'ex_field'.$i}) > $si_contact_opt['ex_field'.$i.'_max_len']) {
                  $this->si_contact_error = 1;
                  ${'si_contact_error_ex_field'.$i} = sprintf( __('Maximum of %d characters exceeded.', 'si-contact-form'), $si_contact_opt['ex_field'.$i.'_max_len'] );
                }
                // regex validate
                if( ${'ex_field'.$i} != '' && $si_contact_opt['ex_field'.$i.'_regex'] != '' && !preg_match($si_contact_opt['ex_field'.$i.'_regex'],${'ex_field'.$i}) ) {
                  $this->si_contact_error = 1;
                  ${'si_contact_error_ex_field'.$i} = ($si_contact_opt['ex_field'.$i.'_regex_error'] != '') ? $si_contact_opt['ex_field'.$i.'_regex_error'] : __('Invalid input.', 'si-contact-form');
                }
           }
        }  // end if label != ''
      } // end foreach

   if ($si_contact_opt['subject_type'] == 'required' && $subject == '') {
       $this->si_contact_error = 1;
       if (count($subjects) == 0) {
         $si_contact_error_subject = ($si_contact_opt['error_subject'] != '') ? $si_contact_opt['error_subject'] : __('Subject text is required.', 'si-contact-form');
       }
   }
   if($si_contact_opt['message_type'] == 'required' &&  $message == '') {
       $this->si_contact_error = 1;
       $si_contact_error_message = ($si_contact_opt['error_message'] != '') ? $si_contact_opt['error_message'] : __('Message text is required.', 'si-contact-form');
   }

  // begin captcha check if enabled
  // captcha is optional but recommended to prevent spam bots from spamming your contact form
  if ( $this->isCaptchaEnabled() ) {
    if($si_contact_gb['captcha_disable_session'] == 'true') {
      //captcha without sessions
      if (empty($captcha_code) || $captcha_code == '') {
         $this->si_contact_error = 1;
         $si_contact_error_captcha = ($si_contact_opt['error_captcha_blank'] != '') ? $si_contact_opt['error_captcha_blank'] : __('Please complete the CAPTCHA.', 'si-contact-form');
      }else if (!isset($_POST['si_code_ctf_'.$form_id_num]) || empty($_POST['si_code_ctf_'.$form_id_num])) {
         $this->si_contact_error = 1;
         $si_contact_error_captcha = __('Could not find CAPTCHA token.', 'si-contact-form');
      }else{
         $prefix = 'xxxxxx';
         if ( isset($_POST['si_code_ctf_'.$form_id_num]) && preg_match('/^[a-zA-Z0-9]{15,17}$/',$_POST['si_code_ctf_'.$form_id_num]) ){
           $prefix = $_POST['si_code_ctf_'.$form_id_num];
         }
         if ( is_readable( $ctf_captcha_dir . $prefix . '.php' ) ) {
			include( $ctf_captcha_dir . $prefix . '.php' );
			if ( 0 == strcasecmp( $captcha_code, $captcha_word ) ) {
              // captcha was matched
              @unlink ($ctf_captcha_dir . $prefix . '.php');
			} else {
              $this->si_contact_error = 1;
              $si_contact_error_captcha = ($si_contact_opt['error_captcha_wrong'] != '') ? $si_contact_opt['error_captcha_wrong'] : __('That CAPTCHA was incorrect.', 'si-contact-form');
            }
	     } else {
           $this->si_contact_error = 1;
           $si_contact_error_captcha = __('Could not read CAPTCHA token file. Try again.', 'si-contact-form');
/*           $check_this_dir = untrailingslashit( $ctf_captcha_dir );
           $si_cec = '';
           if(is_writable($check_this_dir)) {
				//echo '<span style="color: green">OK - Writable</span> ' . substr(sprintf('%o', fileperms($check_this_dir)), -4);
           } else if(!file_exists($check_this_dir)) {
              $si_cec .= '<br />';
              $si_cec .= __('There is a problem with the directory', 'si-contact-form');
              $si_cec .= ' /si-contact-form/captcha/temp/.<br />';
	          $si_cec .= __('The directory is not found, a <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">permissions</a> problem may have prevented this directory from being created.', 'si-contact-form');
              $si_cec .= ' ';
              $si_cec .= __('Fixing the actual problem is recommended, but you can uncheck this setting on the contact form options page: "Use CAPTCHA without PHP session" and the captcha will work this way just fine (as long as PHP sessions are working).', 'si-contact-form');
              $si_contact_error_captcha .= $si_cec;
           } else {
             $si_cec .= '<br />';
             $si_cec .= __('There is a problem with the directory', 'si-contact-form') .' /si-contact-form/captcha/temp/.<br />';
             $si_cec .= __('Directory Unwritable (<a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">fix permissions</a>)', 'si-contact-form').'. ';
             $si_cec .= __('Permissions are: ', 'si-contact-form');
             $si_cec .= ' ';
             $si_cec .= substr(sprintf('%o', fileperms($check_this_dir)), -4);
             $si_cec .= ' ';
             $si_cec .=__('Fixing this may require assigning 0755 permissions or higher (e.g. 0777 on some hosts. Try 0755 first, because 0777 is sometimes too much and will not work.)', 'si-contact-form');
             $si_cec .= ' ';
             $si_cec .= __('Fixing the actual problem is recommended, but you can uncheck this setting on the contact form options page: "Use CAPTCHA without PHP session" and the captcha will work this way just fine (as long as PHP sessions are working).', 'si-contact-form');
             $si_contact_error_captcha .= $si_cec;
          }
          if(!file_exists($ctf_captcha_dir . $prefix . '.php')){  // form will, still go through.  Try uncheck this setting on the contact form options page: "Use CAPTCHA without PHP session"
                $si_cec .= '<br />';
                $si_cec .= __('CAPTCHA token file is missing.', 'si-contact-form');
                $si_cec .= ' ';
                $si_cec .= __('Fixing the actual problem is recommended, but you can uncheck this setting on the contact form options page: "Use CAPTCHA without PHP session" and the captcha will work this way just fine (as long as PHP sessions are working).', 'si-contact-form');
                $si_contact_error_captcha .= $si_cec;

          }*/
	    }
	  }
    } else {
      //captcha with PHP sessions

      // uncomment for temporary advanced debugging only
      /*echo "<pre>";
      echo "COOKIE ";
      var_dump($_COOKIE);
      echo "\n\n";
      echo "SESSION ";
      var_dump($_SESSION);
      echo "</pre>\n";*/

      if (!isset($_SESSION['securimage_code_ctf_'.$form_id_num]) || empty($_SESSION['securimage_code_ctf_'.$form_id_num])) {
          $this->si_contact_error = 1;
          $si_contact_error_captcha = __('Could not read CAPTCHA cookie. Try again.', 'si-contact-form');
          //$si_contact_error_captcha = __('Could not read CAPTCHA cookie. Make sure you have cookies enabled and not blocking in your web browser settings. Or another plugin is conflicting. See plugin FAQ.', 'si-contact-form');
          //$si_contact_error_captcha .= ' '. __('Alternatively, the admin can enable the setting "Use CAPTCHA without PHP Session", then temporary files will be used for storing the CAPTCHA phrase. This allows the CAPTCHA to function without using PHP Sessions. This setting is on the contact form admin settings page.', 'si-contact-form');
      }else{
         if (empty($captcha_code) || $captcha_code == '') {
           $this->si_contact_error = 1;
           $si_contact_error_captcha = ($si_contact_opt['error_captcha_blank'] != '') ? $si_contact_opt['error_captcha_blank'] : __('Please complete the CAPTCHA.', 'si-contact-form');
         } else {
           require_once "$captcha_path_cf/securimage.php";
           $img = new Securimage();
           $img->form_num = $form_id_num; // makes compatible with multi-forms on same page
           $valid = $img->check("$captcha_code");
           // Check, that the right CAPTCHA password has been entered, display an error message otherwise.
           if($valid == true) {
             // ok can continue
           } else {
              $this->si_contact_error = 1;
              $si_contact_error_captcha = ($si_contact_opt['error_captcha_wrong'] != '') ? $si_contact_opt['error_captcha_wrong'] : __('That CAPTCHA was incorrect.', 'si-contact-form');
           }
        }
     }
   } // end if captcha use session
 } // end if enable captcha

  if (!$this->si_contact_error) {
     // ok to send the email, so prepare the email message
     $posted_data = array();
     // new lines should be (\n for UNIX, \r\n for Windows and \r for Mac)
     //$php_eol = ( strtoupper(substr(PHP_OS,0,3) == 'WIN') ) ? "\r\n" : "\n";
	 $php_eol = (!defined('PHP_EOL')) ? (($eol = strtolower(substr(PHP_OS, 0, 3))) == 'win') ? "\r\n" : (($eol == 'mac') ? "\r" : "\n") : PHP_EOL;
	 $php_eol = (!$php_eol) ? "\n" : $php_eol;

     if($subject != '') {
          $subj = $si_contact_opt['email_subject'] ." $subject";
     }else{
          $subj = $si_contact_opt['email_subject'];
     }
     $msg = $this->make_bold(__('To', 'si-contact-form')).": $to_contact$php_eol$php_eol";
     if ($name != '' || $email != '')  {
        $msg .= $this->make_bold(__('From', 'si-contact-form')).":$php_eol";
        switch ($si_contact_opt['name_format']) {
          case 'name':
             if($name != '') {
              $msg .= "$name$php_eol";
              $posted_data['from_name'] = $name;
             }
          break;
          case 'first_last':
              $msg .= ($si_contact_opt['title_fname'] != '') ? $si_contact_opt['title_fname'] : __('First Name', 'si-contact-form').':';
              $msg .= " $f_name$php_eol";
              $msg .= ($si_contact_opt['title_lname'] != '') ? $si_contact_opt['title_lname'] : __('Last Name', 'si-contact-form').':';
              $msg .= " $l_name$php_eol";
              $posted_data['first_name'] = $f_name;
              $posted_data['last_name'] = $l_name;
          break;
          case 'first_middle_i_last':
              $msg .= ($si_contact_opt['title_fname'] != '') ? $si_contact_opt['title_fname'] : __('First Name', 'si-contact-form').':';
              $msg .= " $f_name$php_eol";
              $posted_data['first_name'] = $f_name;
              if($mi_name != '') {
                 $msg .= ($si_contact_opt['title_miname'] != '') ? $si_contact_opt['title_miname'] : __('Middle Initial', 'si-contact-form').':';
                 $msg .= " $mi_name$php_eol";
                 $posted_data['middle_initial'] = $mi_name;
              }
              $msg .= ($si_contact_opt['title_lname'] != '') ? $si_contact_opt['title_lname'] : __('Last Name', 'si-contact-form').':';
              $msg .= " $l_name$php_eol";
              $posted_data['last_name'] = $l_name;
          break;
          case 'first_middle_last':
              $msg .= ($si_contact_opt['title_fname'] != '') ? $si_contact_opt['title_fname'] : __('First Name', 'si-contact-form').':';
              $msg .= " $f_name$php_eol";
              $posted_data['first_name'] = $f_name;
              if($m_name != '') {
                 $msg .= ($si_contact_opt['title_mname'] != '') ? $si_contact_opt['title_mname'] : __('Middle Name', 'si-contact-form').':';
                 $msg .= " $m_name$php_eol";
                 $posted_data['middle_name'] = $m_name;
              }
              $msg .= ($si_contact_opt['title_lname'] != '') ? $si_contact_opt['title_lname'] : __('Last Name', 'si-contact-form').':';
              $msg .= " $l_name$php_eol";
              $posted_data['last_name'] = $l_name;
         break;
      }
      $msg .= "$email$php_eol$php_eol";
      $posted_data['from_email'] = $email;
   }
   // subject can include posted data names feature:
   foreach ($posted_data as $key => $data) {
      if( is_string($data) )
          $subj = str_replace('['.$key.']',$data,$subj);
   }
   $posted_form_name = ( $si_contact_opt['form_name'] != '' ) ? $si_contact_opt['form_name'] : sprintf(__('Form: %d', 'si-contact-form'),$form_id_num);
   $subj = str_replace('[form_label]',$posted_form_name,$subj);
   $posted_data['subject'] = $subj;
   if ($si_contact_opt['ex_fields_after_msg'] == 'true' && $message != '') {
        $msg .= $this->make_bold(__('Message', 'si-contact-form')).":$php_eol$message$php_eol$php_eol";
        $posted_data['message'] = $message;
   }

   // optional extra fields
   for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
      if ( $si_contact_opt['ex_field'.$i.'_label'] != '' && $si_contact_opt['ex_field'.$i.'_type'] != 'fieldset-close') {
          if(preg_match('/^{inline}/',$si_contact_opt['ex_field'.$i.'_label'])) {
            // remove the {inline} modifier tag from the label
              $si_contact_opt['ex_field'.$i.'_label'] = str_replace('{inline}','',$si_contact_opt['ex_field'.$i.'_label']);
          }

         if ($si_contact_opt['ex_field'.$i.'_type'] == 'fieldset') {
             $msg .= $this->make_bold($si_contact_opt['ex_field'.$i.'_label']).$php_eol;
         } else if ($si_contact_opt['ex_field'.$i.'_type'] == 'hidden') {
             list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$si_contact_opt['ex_field'.$i.'_label']); //string will be split by "," but "\," will be ignored
             $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
             $msg .= $this->make_bold($exf_opts_label)."$php_eol${'ex_field'.$i}".$php_eol.$php_eol;
             $posted_data["ex_field$i"] = ${'ex_field'.$i};
         } else if ($si_contact_opt['ex_field'.$i.'_type'] == 'time') {
             if ($si_contact_opt['time_format'] == '12')
               $concat_time = ${'ex_field'.$i.'h'}.':'.${'ex_field'.$i.'m'}.' '.${'ex_field'.$i.'ap'};
             else
               $concat_time = ${'ex_field'.$i.'h'}.':'.${'ex_field'.$i.'m'};
             $msg .= $this->make_bold($si_contact_opt['ex_field'.$i.'_label']).$php_eol.$concat_time.$php_eol.$php_eol;
             $posted_data["ex_field$i"] = $concat_time;
         } else if ($si_contact_opt['ex_field'.$i.'_type'] == 'attachment' && $si_contact_opt['php_mailer_enable'] != 'php' && ${'ex_field'.$i} != '') {
             $msg .= $this->make_bold($si_contact_opt['ex_field'.$i.'_label'])."$php_eol * ".__('File is attached:', 'si-contact-form')." ${'ex_field'.$i}".$php_eol.$php_eol;
             $posted_data["ex_field$i"] = __('File is attached:', 'si-contact-form')." ${'ex_field'.$i}";
         } else if ($si_contact_opt['ex_field'.$i.'_type'] == 'select' || $si_contact_opt['ex_field'.$i.'_type'] == 'radio') {
             list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$si_contact_opt['ex_field'.$i.'_label']); //string will be split by "," but "\," will be ignored
             $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
             $msg .= $this->make_bold($exf_opts_label)."$php_eol${'ex_field'.$i}".$php_eol.$php_eol;
             $posted_data["ex_field$i"] = ${'ex_field'.$i};
         } else if ($si_contact_opt['ex_field'.$i.'_type'] == 'select-multiple') {
             $exf_opts_array = array();
             $exf_opts_label = '';
             $exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
             if(preg_match('#(?<!\\\)\,#', $exf_array_test) && preg_match("/;/", $exf_array_test) ) {
                list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
                $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
                $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
                if ($exf_opts_label != '' && $value != '') {
                    if(!preg_match("/;/", $value)) {
                       // error - a select-multiple field is not configured properly in settings.
                    } else {
                         // multiple options
                         $exf_opts_array = explode(";",$value);
                    }
                    $msg .= $this->make_bold($exf_opts_label).$php_eol;
                    $posted_data["ex_field$i"] = '';
                    if (is_array(${'ex_field'.$i}) && ${'ex_field'.$i} != '') {
                       // loop
                       $ex_cnt = 1;
                       foreach ($exf_opts_array as $k) {  // select-multiple
                          if (in_array($k, ${'ex_field'.$i} ) ) {
                             $msg .= ' * '.$k.$php_eol;
                             $posted_data["ex_field$i"] .= ' * '.$k;
                             $ex_cnt++;
                          }
                       }
                    }
                    $msg .= $php_eol;
                }
             }
         } else if ($si_contact_opt['ex_field'.$i.'_type'] == 'checkbox' || $si_contact_opt['ex_field'.$i.'_type'] == 'checkbox-multiple') {
             $exf_opts_array = array();
             $exf_opts_label = '';
             $exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
             if(preg_match('#(?<!\\\)\,#', $exf_array_test)  && preg_match("/;/", $exf_array_test) ) {
                list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
                $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
                $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
                if ($exf_opts_label != '' && $value != '') {
                    if(!preg_match("/;/", $value)) {
                       // error
                       //A checkbox field is not configured properly in settings.
                    } else {
                         // multiple options
                         $exf_opts_array = explode(";",$value);
                    }
                    $msg .= $this->make_bold($exf_opts_label).$php_eol;
                    $posted_data["ex_field$i"] = '';
                    // loop
                    $ex_cnt = 1;
                    foreach ($exf_opts_array as $k) {  // checkbox multi
                     if( isset(${'ex_field'.$i.'_'.$ex_cnt}) && ${'ex_field'.$i.'_'.$ex_cnt} == 'selected') {
                       $msg .= ' * '.$k.$php_eol;
                       $posted_data["ex_field$i"] .= ' * '.$k;
                     }
                     $ex_cnt++;
                    }
                    $msg .= $php_eol;
                }
             } else {  // checkbox single
                 if(${'ex_field'.$i} == 'selected') {
                   $si_contact_opt['ex_field'.$i.'_label'] = trim(str_replace('\,',',',$si_contact_opt['ex_field'.$i.'_label'])); // "\," changes to ","
                   $msg .= $this->make_bold($si_contact_opt['ex_field'.$i.'_label'])."$php_eol * ".__('selected', 'si-contact-form').$php_eol.$php_eol;
                   $posted_data["ex_field$i"] = '* '.__('selected', 'si-contact-form');
                 }
             }
         } else {  // text, textarea, date, password, email, url
               if(${'ex_field'.$i} != ''){
                   if ($si_contact_opt['ex_field'.$i.'_type'] == 'textarea' && $si_contact_opt['textarea_html_allow'] == 'true') {
                        $msg .= $this->make_bold($si_contact_opt['ex_field'.$i.'_label']).$php_eol.$this->ctf_stripslashes(${'ex_field'.$i}).$php_eol.$php_eol;
                        $posted_data["ex_field$i"] = ${'ex_field'.$i};
                   }else{
                        $msg .= $this->make_bold($si_contact_opt['ex_field'.$i.'_label']).$php_eol.${'ex_field'.$i}.$php_eol.$php_eol;
                        $posted_data["ex_field$i"] = ${'ex_field'.$i};
                        if ($si_contact_opt['ex_field'.$i.'_type'] == 'email' && $email == '' && $si_contact_opt['email_type'] == 'not_available') {
                          // admin set the standard email field 'not_avaulable' then added an email extra field type.
                          // lets capture that as the 'from_email'.
                           $email = ${'ex_field'.$i};
                           $this->ctf_forbidifnewlines($email);
                           $posted_data['from_email'] = $email;
                       }
                   }
               }
         }
       }
    } // end for

   // allow shortcode hidden fields
   if ( $shortcode_hidden != '') {
      $hidden_fields_test = explode(",",$shortcode_hidden);
      if ( !empty($hidden_fields_test) ) {
         foreach($hidden_fields_test as $line) {
           if(preg_match("/=/", $line) ) {
               list($key, $value) = explode("=",$line);
               $key   = trim($key);
               $value = trim($value);
               if ($key != '' && $value != '') {
                 $msg .= $this->make_bold($key).$php_eol.$this->ctf_stripslashes($value).$php_eol.$php_eol;
                 $posted_data[$key] = $value;
              }
          }
        }
      }
   }
    if ($si_contact_opt['ex_fields_after_msg'] != 'true' && $message != '') {
        $msg .= $this->make_bold(__('Message', 'si-contact-form')).":$php_eol$message$php_eol$php_eol";
        $posted_data['message'] = $message;
    }

  // lookup country info for this ip
  // geoip lookup using Visitor Maps and Who's Online plugin
  $geo_loc = '';
  if( $si_contact_opt['sender_info_enable'] == 'true' &&
    file_exists( WP_PLUGIN_DIR . '/visitor-maps/include-whos-online-geoip.php') &&
    file_exists( WP_PLUGIN_DIR . '/visitor-maps/GeoLiteCity.dat') ) {
   require_once(WP_PLUGIN_DIR . '/visitor-maps/include-whos-online-geoip.php');
   $gi = geoip_open_VMWO( WP_PLUGIN_DIR . '/visitor-maps/GeoLiteCity.dat', VMWO_GEOIP_STANDARD);
    $record = geoip_record_by_addr_VMWO($gi, $_SERVER['REMOTE_ADDR']);
   geoip_close_VMWO($gi);
   $li = array();
   $li['city_name']    = (isset($record->city)) ? $record->city : '';
   $li['state_name']   = (isset($record->country_code) && isset($record->region)) ? $GEOIP_REGION_NAME[$record->country_code][$record->region] : '';
   $li['state_code']   = (isset($record->region)) ? strtoupper($record->region) : '';
   $li['country_name'] = (isset($record->country_name)) ? $record->country_name : '--';
   $li['country_code'] = (isset($record->country_code)) ? strtoupper($record->country_code) : '--';
   $li['latitude']     = (isset($record->latitude)) ? $record->latitude : '0';
   $li['longitude']    = (isset($record->longitude)) ? $record->longitude : '0';
   if ($li['city_name'] != '') {
     if ($li['country_code'] == 'US') {
         $geo_loc = $li['city_name'];
         if ($li['state_code'] != '')
            $geo_loc = $li['city_name'] . ', ' . strtoupper($li['state_code']);
     } else {      // all non us countries
             $geo_loc = $li['city_name'] . ', ' . strtoupper($li['country_code']);
     }
   } else {
     $geo_loc = '~ ' . $li['country_name'];
   }
 }
    // add some info about sender to the email message
    $userdomain = '';
    $userdomain = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $user_info_string = '';
    if ($user_ID != '') {
        //user logged in
       if ($current_user->user_login != '') $user_info_string .= __('From a WordPress user', 'si-contact-form').': '.$current_user->user_login . $php_eol;
       if ($current_user->user_email != '') $user_info_string .= __('User email', 'si-contact-form').': '.$current_user->user_email . $php_eol;
       if ($current_user->user_firstname != '') $user_info_string .= __('User first name', 'si-contact-form').': '.$current_user->user_firstname . $php_eol;
       if ($current_user->user_lastname != '') $user_info_string .= __('User last name', 'si-contact-form').': '.$current_user->user_lastname . $php_eol;
       if ($current_user->display_name != '') $user_info_string .= __('User display name', 'si-contact-form').': '.$current_user->display_name . $php_eol;
       if ($current_user->ID != '') $user_info_string .= __('User ID', 'si-contact-form').': '.$current_user->ID . $php_eol;
    }
    $user_info_string .= __('Sent from (ip address)', 'si-contact-form').': '.$_SERVER['REMOTE_ADDR']." ($userdomain)".$php_eol;
    if ( $geo_loc != '' ) {
      $user_info_string .= __('Location', 'si-contact-form').': '.$geo_loc. $php_eol;
      $posted_data['sender_location'] = __('Location', 'si-contact-form').': '.$geo_loc;
    }
    $user_info_string .= __('Date/Time', 'si-contact-form').': '.date_i18n(get_option('date_format').' '.get_option('time_format'), time() ) . $php_eol;
    $user_info_string .= __('Coming from (referer)', 'si-contact-form').': '.$form_action_url. $php_eol;
    $user_info_string .= __('Using (user agent)', 'si-contact-form').': '.$this->ctf_clean_input($_SERVER['HTTP_USER_AGENT']) . $php_eol.$php_eol;
    if ($si_contact_opt['sender_info_enable'] == 'true')
       $msg .= $user_info_string;

    $posted_data['date_time'] = date_i18n(get_option('date_format').' '.get_option('time_format'), time() );

   // Check with Akismet, but only if Akismet is installed, activated, and has a KEY. (Recommended for spam control).
   if( $si_contact_opt['akismet_disable'] == 'false' ) { // per form disable feature
     //if($si_contact_opt['message_type'] != 'not_available' && $message != '' && function_exists('akismet_http_post') && get_option('wordpress_api_key') ){
     if(function_exists('akismet_http_post') && get_option('wordpress_api_key') ){
      global $akismet_api_host, $akismet_api_port;
	  $c['user_ip']    		= preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
	  $c['user_agent'] 		= (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
	  $c['referrer']   		= (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
	  $c['blog']       		= get_option('home');
      $c['blog_lang']       = get_locale(); // default 'en_US'
      $c['blog_charset']    = get_option('blog_charset');
	  $c['permalink']       = $form_action_url;
	  $c['comment_type']    = 'fscontactform';
	  $c['comment_author']  = $name;
      //$c['comment_author']  = "viagra-test-123";  // uncomment this to test spam detection
      // or  You can just put viagra-test-123 as the name when testing the form (no need to edit this php file to test it)
      if($email != '') $c['comment_author_email'] = $email;
	  //$c['comment_content'] = $message;
      $c['comment_content'] = $msg;
	  $ignore = array( 'HTTP_COOKIE', 'HTTP_COOKIE2', 'PHP_AUTH_PW' );
      foreach ( $_SERVER as $key => $value ) {
           if ( !in_array( $key, $ignore ) && is_string($value) )
               $c["$key"] = $value;
            else
               $c["$key"] = '';
      }
      $query_string = '';
	  foreach ( $c as $key => $data ) {
	     if( is_string($data) )
		    $query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';
      }
	  $response = akismet_http_post($query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);
	  if ( 'true' == $response[1] ) {
	    if( $si_contact_opt['akismet_send_anyway'] == 'false' ) {
            $this->si_contact_error = 1; // Akismet says it is spam.
            $si_contact_error_message = ($si_contact_opt['error_input'] != '') ? $si_contact_opt['error_input'] : __('Invalid Input - Spam?', 'si-contact-form');
            if ($user_ID != '' && current_user_can('level_10') ) {
              // show administrator a helpful message
              $si_contact_error_message .= '<br />'. __('Akismet determined your message is spam. This can be caused by the message content, your email address, or your IP address being on the Akismet spam system. The administrator can turn off Akismet for the form in the form Advanced Options.', 'si-contact-form');
            }
        } else {
              // Akismet says it is spam. flag the subject as spam and send anyway.
              $subj = __('Akismet: Spam', 'si-contact-form'). ' - ' . $subj;
              $msg = str_replace(__('Sent from (ip address)', 'si-contact-form'),__('Akismet Spam Check: probably spam', 'si-contact-form').$php_eol.__('Sent from (ip address)', 'si-contact-form'),$msg);
              $posted_data['akismet'] = __('probably spam', 'si-contact-form');
        }
	  }else {
            $msg = str_replace(__('Sent from (ip address)', 'si-contact-form'),__('Akismet Spam Check: passed', 'si-contact-form').$php_eol.__('Sent from (ip address)', 'si-contact-form'),$msg);
            $posted_data['akismet'] = __('passed', 'si-contact-form');
      }
    } // end if(function_exists('akismet_http_post')){
   }
   $posted_data['full_message'] = $msg;

   if ($si_contact_opt['email_html'] == 'true') {
     $msg = str_replace(array("\r\n", "\r", "\n"), "<br>", $msg);
     $msg = '<html><body>' . $php_eol . $msg . '</body></html>'.$php_eol;
   }

     // wordwrap email message
    if ($ctf_wrap_message)
       $msg = wordwrap($msg, 70,$php_eol);

  $email_off = 0;
  if ($si_contact_opt['redirect_enable'] == 'true' && $si_contact_opt['redirect_query'] == 'true' && $si_contact_opt['redirect_email_off'] == 'true')
    $email_off = 1;

  if ($si_contact_opt['export_enable'] == 'true' && $si_contact_opt['export_email_off'] == 'true')
    $email_off = 1;

  if ($si_contact_opt['silent_send'] != 'off' &&  $si_contact_opt['silent_email_off'] == 'true')
    $email_off = 1;

  if (!$this->si_contact_error) {

   if (!$email_off) {
    $header = '';  // for php mail and wp_mail
    $ctf_email_on_this_domain = $si_contact_opt['email_from']; // optional
    // prepare the email header
    $this->si_contact_from_name  = ($name == '') ? 'WordPress' : $name;
    $this->si_contact_from_email = ($email == '') ? get_option('admin_email') : $email;

    if ($ctf_email_on_this_domain != '' ) {
         if(!preg_match("/,/", $ctf_email_on_this_domain)) {
           // just an email: user1@example.com
           $this->si_contact_mail_sender = $ctf_email_on_this_domain;
           if($email == '' || $si_contact_opt['email_from_enforced'] == 'true')
              $this->si_contact_from_email = $ctf_email_on_this_domain;
         } else {
           // name and email: webmaster,user1@example.com
           list($key, $value) = explode(",",$ctf_email_on_this_domain);
           $key   = trim($key);
           $value = trim($value);
           $this->si_contact_mail_sender = $value;
           if($name == '')
             $this->si_contact_from_name = $key;
           if($email == '' || $si_contact_opt['email_from_enforced'] == 'true')
             $this->si_contact_from_email = $value;
         }
    }
    $header_php =  "From: $this->si_contact_from_name <$this->si_contact_from_email>\n"; // header for php mail only

    // process $mail_to user1@example.com,user2@example.com,user3@example.com,[cc]user4@example.com,[bcc]user5@example.com
    // some are cc, some are bcc
    $mail_to_arr = explode( ',', $mail_to );
    $mail_to = '';
    if ($ctf_email_address_bcc != '')
            $ctf_email_address_bcc = $ctf_email_address_bcc. ',';
	foreach ( $mail_to_arr as $key => $this_mail_to ) {
	       if (preg_match("/\[bcc\]/i",$this_mail_to) )  {
                 $this_mail_to = str_replace('[bcc]','',$this_mail_to);
                 $ctf_email_address_bcc .= "$this_mail_to,";
           }else{
                 $this_mail_to = str_replace('[cc]','',$this_mail_to);
                 $mail_to .= "$this_mail_to,";
           }
    }
    $mail_to = rtrim($mail_to, ',');

    if ($ctf_email_address_bcc != '') {
            $ctf_email_address_bcc = rtrim($ctf_email_address_bcc, ',');
            $header .= "Bcc: $ctf_email_address_bcc\n"; // for php mail and wp_mail
    }

    if ($si_contact_opt['email_reply_to'] != '') { // custom reply_to
         $header .= "Reply-To: ".$si_contact_opt['email_reply_to']."\n"; // for php mail and wp_mail
    }else if($email != '') {   // trying this: keep users reply to even when email_from_enforced
         $header .= "Reply-To: $email\n"; // for php mail and wp_mail
    }else {
         $header .= "Reply-To: $this->si_contact_from_email\n"; // for php mail and wp_mail
    }

    if ($ctf_email_on_this_domain != '') {
      $header .= "X-Sender: $this->si_contact_mail_sender\n";  // for php mail
      $header .= "Return-Path: $this->si_contact_mail_sender\n";   // for php mail
    }

    if ($si_contact_opt['email_html'] == 'true') {
            $header .= 'Content-type: text/html; charset='. get_option('blog_charset') . $php_eol;
    } else {
            $header .= 'Content-type: text/plain; charset='. get_option('blog_charset') . $php_eol;
    }

    @ini_set('sendmail_from', $this->si_contact_from_email);

    // Check for safe mode
    $this->safe_mode = ((boolean)@ini_get('safe_mode') === false) ? 0 : 1;

    if ($si_contact_opt['php_mailer_enable'] == 'php') {
      // sending with php mail
       $header_php .= $header;
      if ($ctf_email_on_this_domain != '' && !$this->safe_mode) {
          // Pass the Return-Path via sendmail's -f command.
          @mail($mail_to,$subj,$msg,$header_php, '-f '.$this->si_contact_mail_sender);
      }else{
          // the fifth parameter is not allowed in safe mode
          @mail($mail_to,$subj,$msg,$header_php);
      }
    }else if ($si_contact_opt['php_mailer_enable'] == 'geekmail') {
         // sending with geekmail
         require_once WP_PLUGIN_DIR . '/si-contact-form/ctf_geekMail-1.0.php';
         $ctf_geekMail = new ctf_geekMail();
         if ($si_contact_opt['email_html'] == 'true') {
                 $ctf_geekMail->setMailType('html');
         } else {
                 $ctf_geekMail->setMailType('text');
         }
         $ctf_geekMail->_setcharSet(get_option('blog_charset'));
         $ctf_geekMail->_setnewLine($php_eol);
         if ($ctf_email_on_this_domain != '') {
           $ctf_geekMail->return_path($this->si_contact_mail_sender);
           $ctf_geekMail->x_sender($this->si_contact_mail_sender);
         }
         $ctf_geekMail->from($this->si_contact_from_email, $this->si_contact_from_name);
         $ctf_geekMail->to($mail_to);
         if ($si_contact_opt['email_reply_to'] != '') { // custom reply_to
              $ctf_geekMail->_replyTo($si_contact_opt['email_reply_to']);
         }else if($email != '') {   // trying this: keep users reply to even when email_from_enforced
              $ctf_geekMail->_replyTo($email);
         }else {
              $ctf_geekMail->_replyTo($this->si_contact_from_email);
         }
         if ($ctf_email_address_bcc != '')
           $ctf_geekMail->bcc($ctf_email_address_bcc);
         $ctf_geekMail->subject($subj);
         $ctf_geekMail->message($msg);
         if ( $this->uploaded_files ) {
			    foreach ( $this->uploaded_files as $path ) {
				    $ctf_geekMail->attach($path);
			    }
         }
         @$ctf_geekMail->send();

    } else {
      // sending with wp_mail
      add_filter( 'wp_mail_from', array(&$this,'si_contact_form_from_email'),1);
      add_filter( 'wp_mail_from_name', array(&$this,'si_contact_form_from_name'),1);
      if ($ctf_email_on_this_domain != '') {
         // Add an action on phpmailer_init to add Sender $this->si_contact_mail_sender for Return-path in wp_mail
         // this helps spf checking when the Sender email address matches the site domain name
         add_action('phpmailer_init', array(&$this,'si_contact_form_mail_sender'),1);
      }
        if ( $this->uploaded_files ) {
			    $attach_this_mail = array();
			    foreach ( $this->uploaded_files as $path ) {
				    $attach_this_mail[] = $path;
			    }
			    @wp_mail($mail_to,$subj,$msg,$header,$attach_this_mail);
		} else {
		        @wp_mail($mail_to,$subj,$msg,$header);
		}
    }
   } // end if (!$email_off) {

   // autoresponder feature
   if ($si_contact_opt['auto_respond_enable'] == 'true' && $email != '' && $si_contact_opt['auto_respond_subject'] != '' && $si_contact_opt['auto_respond_message'] != ''){
       $subj = $si_contact_opt['auto_respond_subject'];
       $msg =  $si_contact_opt['auto_respond_message'];

       // $posted_data is an array of the form name value pairs
       // autoresponder can include posted data, tags are set on form settings page
       foreach ($posted_data as $key => $data) {
          if( in_array($key,array('message','full_message','akismet')) )  // disallow these
            continue;
	       if( is_string($data) ) {
	         $subj = str_replace('['.$key.']',$data,$subj);
             $msg = str_replace('['.$key.']',$data,$msg);
           }
       }
       $subj = preg_replace('/(\[ex_field)(\d+)(\])/','',$subj); // remove empty ex_field tags
       $msg = preg_replace('/(\[ex_field)(\d+)(\])/','',$msg);   // remove empty ex_field tags
       $subj = str_replace('[form_label]',$posted_form_name,$subj);

       // wordwrap email message
       if ($ctf_wrap_message)
             $msg = wordwrap($msg, 70,$php_eol);

       $header = '';
       $header_php = '';
       $auto_respond_from_name  = $si_contact_opt['auto_respond_from_name'];
       $auto_respond_from_email = $si_contact_opt['auto_respond_from_email'];
       $auto_respond_reply_to   = $si_contact_opt['auto_respond_reply_to'];
       // prepare the email header

       $header_php =  "From: $auto_respond_from_name <". $auto_respond_from_email . ">\n";
       $this->si_contact_from_name = $auto_respond_from_name;
       $this->si_contact_from_email = $auto_respond_from_email;

       $header .= "Reply-To: $auto_respond_reply_to\n";   // for php mail and wp_mail
       $header .= "X-Sender: $this->si_contact_from_email\n";  // for php mail
       $header .= "Return-Path: $this->si_contact_from_email\n";  // for php mail
       if ($si_contact_opt['auto_respond_html'] == 'true') {
               $header .= 'Content-type: text/html; charset='. get_option('blog_charset') . $php_eol;
       } else {
               $header .= 'Content-type: text/plain; charset='. get_option('blog_charset') . $php_eol;
       }

       @ini_set('sendmail_from' , $this->si_contact_from_email);
       if ($si_contact_opt['php_mailer_enable'] == 'php') {
            // autoresponder sending with php
            $header_php .= $header;
            if (!$this->safe_mode) {
                // Pass the Return-Path via sendmail's -f command.
                @mail($email,$subj,$msg,$header_php, '-f '.$this->si_contact_from_email);
            } else {
                // the fifth parameter is not allowed in safe mode
                @mail($email,$subj,$msg,$header_php);
            }
       }else if ($si_contact_opt['php_mailer_enable'] == 'geekmail') {
            // autoresponder sending with geekmail
            require_once WP_PLUGIN_DIR . '/si-contact-form/ctf_geekMail-1.0.php';
            $ctf_geekMail = new ctf_geekMail();
            if ($si_contact_opt['auto_respond_html'] == 'true') {
                    $ctf_geekMail->setMailType('html');
            } else {
                    $ctf_geekMail->setMailType('text');
            }
            $ctf_geekMail->_setcharSet(get_option('blog_charset'));
            $ctf_geekMail->_setnewLine($php_eol);
            $ctf_geekMail->return_path($this->si_contact_from_email);
            $ctf_geekMail->x_sender($this->si_contact_from_email);
            $ctf_geekMail->from($this->si_contact_from_email, $this->si_contact_from_name);
            $ctf_geekMail->_replyTo($auto_respond_reply_to);
            $ctf_geekMail->to($email);
            $ctf_geekMail->subject($subj);
            $ctf_geekMail->message($msg);
            @$ctf_geekMail->send();

       } else {
            // autoresponder sending with wp_mail
            add_filter( 'wp_mail_from_name', array(&$this,'si_contact_form_from_name'),1);
            add_filter( 'wp_mail_from', array(&$this,'si_contact_form_from_email'),1);
	        @wp_mail($email,$subj,$msg,$header);
       }
  }

    $message_sent = 1;

   unset($_POST['si_contact_action']);  //prevent form double posting
   unset($_POST['si_contact_form_id']); //prevent form double posting

  if ($si_contact_opt['silent_send'] == 'get' && $si_contact_opt['silent_url'] != '') {
     // build query string
     $query_string = $this->si_contact_export_convert($posted_data,$si_contact_opt['silent_rename'],$si_contact_opt['silent_ignore'],$si_contact_opt['silent_add'],'query');
     if(!preg_match("/\?/", $si_contact_opt['silent_url']) )
        $silent_result = wp_remote_get( $si_contact_opt['silent_url'].'?'.$query_string, array( 'timeout' => 20, 'sslverify'=>false ) );
      else
        $silent_result = wp_remote_get( $si_contact_opt['silent_url'].'&'.$query_string, array( 'timeout' => 20, 'sslverify'=>false ) );
	 if ( !is_wp_error( $silent_result ) ) {
       $silent_result = wp_remote_retrieve_body( $silent_result );
	 }
     //echo $silent_result;
  }

  if ($si_contact_opt['silent_send'] == 'post' && $si_contact_opt['silent_url'] != '') {
     // build post_array
     $post_array = $this->si_contact_export_convert($posted_data,$si_contact_opt['silent_rename'],$si_contact_opt['silent_ignore'],$si_contact_opt['silent_add'],'array');
	 $silent_result = wp_remote_post( $si_contact_opt['silent_url'], array( 'body' => $post_array, 'timeout' => 20, 'sslverify'=>false ) );
	 if ( !is_wp_error( $silent_result ) ) {
       $silent_result = wp_remote_retrieve_body( $silent_result );
	 }
     //echo $silent_result;
  }

  if ($si_contact_opt['export_enable'] == 'true') {
      // filter posted data based on admin settings
      $posted_data_export = $this->si_contact_export_convert($posted_data,$si_contact_opt['export_rename'],$si_contact_opt['export_ignore'],$si_contact_opt['export_add'],'array');
      // Use form name from form edit page if one is set.
      $posted_form_name = ( $si_contact_opt['form_name'] != '' ) ? $si_contact_opt['form_name'] : sprintf(__('Form: %d', 'si-contact-form'),$form_id_num);
      // hook for other plugins to use (just after message posted)
      $fsctf_posted_data = (object) array('title' => $posted_form_name, 'posted_data' => $posted_data_export, 'uploaded_files' => (array) $this->uploaded_files );
      do_action_ref_array( 'fsctf_mail_sent', array( &$fsctf_posted_data ) );
   }  // end if export_enable

  } // end if ! error
 } // end if ! error

if ($have_attach){
  // unlink attachment temp files
  foreach ( (array) $this->uploaded_files as $path ) {
   @unlink( $path );
  }
}

?>