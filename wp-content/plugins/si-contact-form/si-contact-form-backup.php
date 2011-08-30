<?php
/*
Fast Secure Contact Form
Mike Challis
http://www.642weather.com/weather/scripts.php
*/

// outputs a contact form settings backup file

// backup requested
if ( isset($_POST['ctf_action'] )
    && $_POST['ctf_action'] == __('Backup Settings', 'si-contact-form')
    && isset($_POST['si_contact_backup_type'])
    && (is_numeric($_POST['si_contact_backup_type']) || $_POST['si_contact_backup_type'] == 'all') ) {
        check_admin_referer( 'si-contact-form-backup_settings'); // nonce

        if ( function_exists('current_user_can') && !current_user_can('manage_options') )
             wp_die(__('You do not have permissions for managing this option', 'si-contact-form'));

        $backup_type = $_POST['si_contact_backup_type'];
        // get the global options from the database
        $si_contact_bk_gb = get_option("si_contact_form_gb");
        $si_contact_bk_gb['backup_type'] = $backup_type;
        $eol = "\r\n";

        // format the data to be stored in contact-form-backup.txt
        $string .= "**SERIALIZED DATA, DO NOT HAND EDIT!**$eol";
        $string .= "Backup of forms and settings for 'Fast Secure Contact Form' WordPress plugin $ctf_version$eol";
        $string .= 'Form ID included in this backup: '.$backup_type.$eol;
        $string .= "Web site: ".get_option('home').$eol;
        $string .= "Web site name: ".get_option('blogname').$eol;
        $string .= "Backup date: ".date_i18n(get_option('date_format').' '.get_option('time_format'), time() )."$eol*/$eol";
        $string .= "@@@@SPLIT@@@@$eol";
        $backup_array = array();
        $backup_array[0] = $si_contact_bk_gb;

        $ok = 0;
        if ($backup_type == 'all' || $backup_type == '1'){
            // form 1
            $si_contact_bk_opt = get_option('si_contact_form');
            // strip slashes on get options array
            foreach($si_contact_bk_opt as $key => $val) {
                $si_contact_bk_opt[$key] = $this->ctf_stripslashes($val);
            }
            $backup_array[1] = $si_contact_bk_opt;
            $ok = 1;
        }
        if ($backup_type == 'all'){
            // multi-forms > 1
            for ($i = 2; $i <= $si_contact_bk_gb['max_forms']; $i++) {
              // get the form options from the database
              $si_contact_bk_opt = get_option("si_contact_form$i");
              // strip slashes on get options array
              foreach($si_contact_bk_opt as $key => $val) {
                  $si_contact_bk_opt[$key] = $this->ctf_stripslashes($val);
              }
              $backup_array[$i] = $si_contact_bk_opt;
            }
            $ok = 1;
         }else if (is_numeric($backup_type)
           && $backup_type > 1
           && $si_contact_bk_opt = get_option('si_contact_form'.$backup_type)){
           // form x
           // strip slashes on get options array
           foreach($si_contact_bk_opt as $key => $val) {
                $si_contact_bk_opt[$key] = $this->ctf_stripslashes($val);
           }
           $backup_array[1] = $si_contact_bk_opt;
           $ok = 1;
         }

         if(!$ok){
            // bail out
            wp_die(__('Requested form to backup is not found.', 'si-contact-form'));
         }
         $string .= serialize($backup_array);

         $filename = 'contact-form-backup-'.$backup_type.'.txt';

        // force download dialog to web browser
        ob_end_clean();
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/force-download');
		header('Content-Type: application/octet-stream');
		header('Content-Type: application/download');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' .(string)(strlen($string)) );
        flush();
        echo $string;
        exit;

} // end backup action

?>