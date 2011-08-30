<?php
/*
Fast Secure Contact Form
Mike Challis
http://www.642weather.com/weather/scripts.php
*/

// copy settings from one form to another

// copy settings requested
if ( isset($_POST['ctf_action'])
    && $_POST['ctf_action'] == __('Copy Settings', 'si-contact-form')
    && isset($_POST['si_contact_copy_what'])
    && isset($_POST['si_contact_this_form'])
    && is_numeric($_POST['si_contact_this_form'])
    && isset($_POST['si_contact_destination_form']) ) {
        check_admin_referer( 'si-contact-form-copy_settings'); // nonce

        if ( function_exists('current_user_can') && !current_user_can('manage_options') )
             wp_die(__('You do not have permissions for managing this option', 'si-contact-form'));

        $copy_what = $_POST['si_contact_copy_what'];
        $this_form = $_POST['si_contact_this_form'];
        $destination_form = $_POST['si_contact_destination_form'];

        // get the global options from the database
        $si_contact_bk_gb = get_option("si_contact_form_gb");

        // get the options to copy from
        if($this_form == 1)
          $this_form_arr = get_option('si_contact_form');
        else
          $this_form_arr = get_option("si_contact_form$this_form");

          // strip slashes on get options array
/*          foreach($this_form_arr as $key => $val) {
             $this_form_arr[$key] = addslashes($val);
          }*/

        $ok = 0;
        if ($destination_form == '1'){
            // form 1
            if ($copy_what == 'styles') {
                $destination_form_arr = get_option('si_contact_form');
/*                foreach($destination_form_arr as $key => $val) {
                   $destination_form_arr[$key] = addslashes($val);
                }*/
                $destination_form_arr = $this->si_contact_copy_styles($this_form_arr,$destination_form_arr);
                update_option("si_contact_form", $destination_form_arr);
            } else {
                update_option("si_contact_form", $this_form_arr);
            }

            $ok = 1;
        }
        if ($destination_form == 'all'){
            // multi-forms > 1
            for ($i = 2; $i <= $si_contact_bk_gb['max_forms']; $i++) {
               if ($copy_what == 'styles') {
                   $destination_form_arr = get_option("si_contact_form$i");
/*                   foreach($destination_form_arr as $key => $val) {
                      $destination_form_arr[$key] = addslashes($val);
                   }*/
                   $destination_form_arr = $this->si_contact_copy_styles($this_form_arr,$destination_form_arr);
                   update_option("si_contact_form$i", $destination_form_arr);
               } else {
                   update_option("si_contact_form$i", $this_form_arr);
               }
            }
            $ok = 1;
         }else if (is_numeric($destination_form) && $destination_form > 1 ){
           // form x
            if ($copy_what == 'styles') {
                $destination_form_arr = get_option("si_contact_form$destination_form");
/*                foreach($destination_form_arr as $key => $val) {
                   $destination_form_arr[$key] = addslashes($val);
                }*/
                $destination_form_arr = $this->si_contact_copy_styles($this_form_arr,$destination_form_arr);
                update_option("si_contact_form$destination_form", $destination_form_arr);
            } else {
                update_option("si_contact_form$destination_form", $this_form_arr);
            }
           $ok = 1;
         }

         if(!$ok){
            // bail out
            wp_die(__('Requested form to copy settings from is not found.', 'si-contact-form'));
         }

       // success
       if ($destination_form == 'all'){
          echo '<div id="message" class="updated fade"><p>'.sprintf(__('Form %d settings have been copied to all forms.', 'si-contact-form'),$this_form).'</p></div>';
       }else{
          echo '<div id="message" class="updated fade"><p>'.sprintf(__('Form %d settings have been copied to form %d.', 'si-contact-form'),$this_form,$destination_form).'</p></div>';
       }

} // end backup action

?>