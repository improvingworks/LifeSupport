<?php
/*
Fast Secure Contact Form
Mike Challis
http://www.642weather.com/weather/scripts.php
*/

// display extra fields on the contact form

      $ex_fieldset = 0;
      $printed_tooltip_filetypes = 0;
      $ex_loop_cnt = 1;
      for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '' || $si_contact_opt['ex_field'.$i.'_type'] == 'fieldset-close') {
           $ex_req_field_ind = ($si_contact_opt['ex_field'.$i.'_req'] == 'true') ? $req_field_ind : '';
           $ex_req_field_aria = ($si_contact_opt['ex_field'.$i.'_req'] == 'true') ? $this->ctf_aria_required : '';
           if(!$si_contact_opt['ex_field'.$i.'_type'] ) $si_contact_opt['ex_field'.$i.'_type'] = 'text';
           if(!$si_contact_opt['ex_field'.$i.'_default'] ) $si_contact_opt['ex_field'.$i.'_default'] = '0';
           if(!$si_contact_opt['ex_field'.$i.'_default_text'] ) $si_contact_opt['ex_field'.$i.'_default_text'] = '';
           if(!$si_contact_opt['ex_field'.$i.'_max_len'] ) $si_contact_opt['ex_field'.$i.'_max_len'] = '';
           if(!$si_contact_opt['ex_field'.$i.'_label_css'] ) $si_contact_opt['ex_field'.$i.'_label_css'] = '';
           if(!$si_contact_opt['ex_field'.$i.'_input_css'] ) $si_contact_opt['ex_field'.$i.'_input_css'] = '';
           if(!$si_contact_opt['ex_field'.$i.'_attributes'] ) $si_contact_opt['ex_field'.$i.'_attributes'] = '';
           if(!$si_contact_opt['ex_field'.$i.'_regex'] ) $si_contact_opt['ex_field'.$i.'_regex'] = '';
           if(!$si_contact_opt['ex_field'.$i.'_regex_error'] ) $si_contact_opt['ex_field'.$i.'_regex_error'] = '';
           if(!$si_contact_opt['ex_field'.$i.'_notes'] ) $si_contact_opt['ex_field'.$i.'_notes'] = '';
           if(!$si_contact_opt['ex_field'.$i.'_notes_after'] ) $si_contact_opt['ex_field'.$i.'_notes_after'] = '';

          switch ($si_contact_opt['ex_field'.$i.'_type']) {
           case 'fieldset':
                if($ex_fieldset)
                   $string .=   "</fieldset>\n";
                if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
                   $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
                }
                $string .=   '<fieldset '.$this->ctf_border_style.'>
        <legend>' . $si_contact_opt['ex_field'.$i.'_label'] ."</legend>\n";
                $ex_fieldset = 1;
           break;
           case 'fieldset-close':
                if($ex_fieldset)
                   $string .=   "</fieldset>\n";
                $ex_fieldset = 0;
           break;
           case 'hidden':
           $exf_opts_label = ''; $value = '';
           if(preg_match("/,/", $si_contact_opt['ex_field'.$i.'_label']) )
             list($exf_opts_label, $value) = explode(",",$si_contact_opt['ex_field'.$i.'_label']);
           $exf_opts_label = trim($exf_opts_label); $value = trim($value);
           if ($exf_opts_label == '' || $value == '') {
               // error
               $this->si_contact_error = 1;
               $string .= $this->ctf_echo_if_error(__('Error: A hidden field is not configured properly in settings.', 'si-contact-form'));
            }
            if (${'ex_field'.$i} != '') // guery string can overrride
                 $value = ${'ex_field'.$i};
            $string .= '
                <input type="hidden" name="si_contact_ex_field'.$i.'" value="' . $this->ctf_output_string($value) . '" />
';
           break;
           case 'password':
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
                 $string .= '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <input '.$this->ctf_field_style.' type="password" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="' . $this->ctf_output_string(${'ex_field'.$i}) . '" '.$ex_req_field_aria.' ';
                if($si_contact_opt['ex_field'.$i.'_max_len'] != '')
                  $string .=  ' maxlength="'.$si_contact_opt['ex_field'.$i.'_max_len'].'" ';
                $string .= 'size="'.$ctf_field_size.'"';
                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= ' />
        </div>
';
        if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
              break;
           case 'text':
           case 'email':
           case 'url':
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
                 $string .= '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <input ';
         $string .= ($si_contact_opt['ex_field'.$i.'_input_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_input_css']) : $this->ctf_field_style;
         $string .= ' type="'.$si_contact_opt['ex_field'.$i.'_type'].'" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="';
              if($si_contact_opt['ex_field'.$i.'_default_text'] != '' && ${'ex_field'.$i} == '')
                  $string .=  $this->ctf_output_string($si_contact_opt['ex_field'.$i.'_default_text']);
              else
                 $string .=  $this->ctf_output_string(${'ex_field'.$i});

                 $string .= '" '.$ex_req_field_aria.' ';
                if($si_contact_opt['ex_field'.$i.'_max_len'] != '')
                  $string .= ' maxlength="'.$si_contact_opt['ex_field'.$i.'_max_len'].'" ';
                $string .= 'size="'.$ctf_field_size.'"';
                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= ' />
        </div>
';
        if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
              break;
           case 'textarea':
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
                $string .= '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <textarea ';
         $string .= ($si_contact_opt['ex_field'.$i.'_input_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_input_css']) : $this->ctf_field_style;
         $string .= ' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" '.$ex_req_field_aria.' cols="'.absint($si_contact_opt['text_cols']).'" rows="'.absint($si_contact_opt['text_rows']).'"';
                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= '>';
              if($si_contact_opt['ex_field'.$i.'_default_text'] != '' && ${'ex_field'.$i} == '')
                  $string .=  $this->ctf_output_string($si_contact_opt['ex_field'.$i.'_default_text']);
              else
                $string .= ($si_contact_opt['textarea_html_allow'] == 'true') ? $this->ctf_stripslashes(${'ex_field'.$i}) : $this->ctf_output_string(${'ex_field'.$i});

                $string .= '</textarea>
        </div>
';
        if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
              break;
           case 'select':

           // find the label and the options inside $si_contact_opt['ex_field'.$i.'_label']
           // the drop down list array will be made automatically by this code
$exf_opts_array = array();
$exf_opts_label = '';
$exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
if(!preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
       // error
       $this->si_contact_error = 1;
       $string .= $this->ctf_echo_if_error(__('Error: A select field is not configured properly in settings.', 'si-contact-form'));
} else {
       list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
       $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
       $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
       if ($exf_opts_label != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               // error
               $this->si_contact_error = 1;
               $string .= $this->ctf_echo_if_error(__('Error: A select field is not configured properly in settings.', 'si-contact-form'));
          } else {
               // multiple options
               $exf_opts_array = explode(";",$value);
         }
      }
} // end else
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
           $string .= '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $exf_opts_label . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
               <select ';
         $string .= ($si_contact_opt['ex_field'.$i.'_input_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_input_css']) : $this->ctf_field_style;
         $string .= ' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'"';
                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= '>
        ';

$exf_opts_ct = 1;
$selected = '';
foreach ($exf_opts_array as $k) {
 $k = trim($k);
 if (${'ex_field'.$i} != '') {
    if (${'ex_field'.$i} == "$k") {
      $selected = ' selected="selected"';
    }
 }else{
    if ($exf_opts_ct == $si_contact_opt['ex_field'.$i.'_default']) {
      $selected = ' selected="selected"';
    }
 }

 if ($exf_opts_ct == 1 && preg_match('/^\[(.*)]$/',$k, $matches)) // "[Please select]" becomes "Please select"
  $string .= '          <option value=""'.$selected.'>'.$this->ctf_output_string($matches[1]).'</option>'."\n";
 else
  $string .= '          <option value="'.$this->ctf_output_string($k).'"'.$selected.'>'.$this->ctf_output_string($k).'</option>'."\n";

 $exf_opts_ct++;
 $selected = '';

}
$string .= '           </select>
        </div>
';
        if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
             break;
           case 'select-multiple':

           // find the label and the options inside $si_contact_opt['ex_field'.$i.'_label']
           // the drop down list array will be made automatically by this code
$exf_opts_array = array();
$exf_opts_label = '';
$exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
if(!preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
       // error
       $this->si_contact_error = 1;
       $string .= $this->ctf_echo_if_error(__('Error: A select-multiple field is not configured properly in settings.', 'si-contact-form'));
} else {
       list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
       $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
       $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
       if ($exf_opts_label != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               echo $value;
               // error
               $this->si_contact_error = 1;
               $string .= $this->ctf_echo_if_error(__('Error: A select-multiple field is not configured properly in settings.', 'si-contact-form'));
          } else {
               // multiple options
               $exf_opts_array = explode(";",$value);
         }
      }
} // end else
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
           $string .= '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $exf_opts_label . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
               <select ';
         $string .= ($si_contact_opt['ex_field'.$i.'_input_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_input_css']) : $this->ctf_field_style;
         $string .= ' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'[]" multiple="multiple"';
                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= '>
';

  $ex_get = 0;
  $ex_cnt = 1;
  // any thing already selected by GET method?
  foreach ($exf_opts_array as $k) {
      if(isset(${'ex_field'.$i.'_'.$ex_cnt}) && ${'ex_field'.$i.'_'.$ex_cnt} == 'selected' ){
        $ex_get =1;
        break;
      }
      $ex_cnt++;
  }

$exf_opts_ct = 1;
$selected = '';
foreach ($exf_opts_array as $k) {
 $k = trim($k);
 if (is_array(${'ex_field'.$i}) && ${'ex_field'.$i} != '') {
    if (in_array($k, ${'ex_field'.$i} ) ) {
      $selected = ' selected="selected"';
    }
 }
 // selected by default
 if (!isset($_POST['si_contact_form_id']) && !$ex_get && $exf_opts_ct == $si_contact_opt['ex_field'.$i.'_default']) {
      $selected = ' selected="selected"';
 }
 // selected by get
 if ( $ex_get && isset(${'ex_field'.$i.'_'.$exf_opts_ct}) && ${'ex_field'.$i.'_'.$exf_opts_ct} == 'selected' )
    $selected = ' selected="selected"';
 $string .= '               <option value="'.$this->ctf_output_string($k).'"'.$selected.'>'.$this->ctf_output_string($k).'</option>'."\n";
 $exf_opts_ct++;
 $selected = '';

}
$string .= '               </select>
        </div>
';
        if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
             break;
           case 'checkbox':
           case 'checkbox-multiple':

$exf_opts_array = array();
$exf_opts_label = '';
$exf_opts_inline = 0;
$exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
if( preg_match('#(?<!\\\)\,#', $exf_array_test) && preg_match("/;/", $exf_array_test) ) {
       list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
       $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
       $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
       if ($exf_opts_label != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               // error
               $this->si_contact_error = 1;
               $string .= $this->ctf_echo_if_error(__('Error: A checkbox field is not configured properly in settings.', 'si-contact-form'));
          } else {
               // multiple options
               $exf_opts_array = explode(";",$value);
         }
      }

  // checkbox children
         if(preg_match('/^{inline}/',$exf_opts_label)) {
              $exf_opts_label = str_replace('{inline}','',$exf_opts_label);
              $exf_opts_inline = 1;
         }
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
           $string .= '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label>' . $exf_opts_label  . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'. $this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i});
$string .=   "\n";

  $ex_get = 0;
  $ex_cnt = 1;
  // any thing already selected by GET method?
  foreach ($exf_opts_array as $k) {
      if(isset(${'ex_field'.$i.'_'.$ex_cnt}) && ${'ex_field'.$i.'_'.$ex_cnt} == 'selected' ){
        $ex_get =1;
        break;
      }
      $ex_cnt++;
  }

  $ex_cnt = 1;
  foreach ($exf_opts_array as $k) {
     $k = trim($k);
     if(!$exf_opts_inline && $ex_cnt > 1)
               $string .= "                <br />\n";
     $string .= '                <span style="white-space:nowrap;"><input type="checkbox" style="width:13px;" id="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'" name="si_contact_ex_field'.$i.'_'.$ex_cnt.'" value="selected"  ';

    if (!isset($_POST['si_contact_form_id']) && !$ex_get && $ex_cnt == $si_contact_opt['ex_field'.$i.'_default']) {
      $string .= ' checked="checked"';
    }

    if ( isset(${'ex_field'.$i.'_'.$ex_cnt}) && ${'ex_field'.$i.'_'.$ex_cnt} == 'selected' )
    $string .= ' checked="checked"';

                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= ' />
                <label style="display:inline;" for="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'">' . $k .'</label></span>'."\n";
     $ex_cnt++;
  }

   $string .= '        </div>'."\n";

} else {

  // single
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
               $string .=   '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <input type="checkbox" style="width:13px;" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="selected" ';
    if (${'ex_field'.$i} != '') {
      if (${'ex_field'.$i} == 'selected') {
         $string .= 'checked="checked"';
      }
    }else{
      if (!isset($_POST['si_contact_action']) && $si_contact_opt['ex_field'.$i.'_default'] == '1') {
         $string .= 'checked="checked"';
      }
    }
    $si_contact_opt['ex_field'.$i.'_label'] = trim(str_replace('\,',',',$si_contact_opt['ex_field'.$i.'_label'])); // "\," changes to ","

                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= ' />
                <label style="display:inline;" for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] . $ex_req_field_ind.'</label>
        </div>
';

} // end else
        if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
             break;
           case 'radio':

           // find the label and the options inside $si_contact_opt['ex_field'.$i.'_label']
           // the radio list array will be made automatically by this code
$exf_opts_array = array();
$exf_opts_label = '';
$exf_opts_inline = 0;
$exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
if(!preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
       // error
       $this->si_contact_error = 1;
       $string .= $this->ctf_echo_if_error(__('Error: A radio field is not configured properly in settings.', 'si-contact-form'));
} else {
       list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
       $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
       $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
       if ($exf_opts_label != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               // error
               $this->si_contact_error = 1;
               $string .= $this->ctf_echo_if_error(__('Error: A radio field is not configured properly in settings.', 'si-contact-form'));
          } else {
               // multiple options
               $exf_opts_array = explode(";",$value);
         }
      }
} // end else
         if(preg_match('/^{inline}/',$exf_opts_label)) {
              $exf_opts_label = str_replace('{inline}','',$exf_opts_label);
              $exf_opts_inline = 1;
         }
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
           $string .= '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label>' . $exf_opts_label  . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'. $this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i});
$string .=   "\n";

$selected = '';
$ex_cnt = 1;
foreach ($exf_opts_array as $k) {
 $k = trim($k);
 if (${'ex_field'.$i} != '') {
    if (${'ex_field'.$i} == "$k") {
      $selected = ' checked="checked"';
    }
 }else{
    if ($ex_cnt == $si_contact_opt['ex_field'.$i.'_default']) {
      $selected = ' checked="checked"';
    }
 }
      if(!$exf_opts_inline && $ex_cnt > 1)
               $string .= "           <br />\n";
 $string .= '           <span style="white-space:nowrap;"><input type="radio" style="width:13px;" id="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'" name="si_contact_ex_field'.$i.'" value="'.$this->ctf_output_string($k).'"'.$selected;
                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= ' />
           <label style="display:inline;" for="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'">' . $k .'</label></span>'."\n";
 $selected = '';
 $ex_cnt++;
}
$string .= '
        </div>
';
        if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
             break;
           case 'attachment':
     if ($si_contact_opt['php_mailer_enable'] != 'php') {
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
            $string .= '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <input '.$this->ctf_field_style.' type="file" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="' . $this->ctf_output_string(${'ex_field'.$i}) . '" '.$ex_req_field_aria.' size="20" ';
                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= ' />';
 if(!$printed_tooltip_filetypes || ($printed_tooltip_filetypes+1) != $ex_loop_cnt) {
    $string .=  '<br /><span style="font-size:x-small;">';
    $string .= ($si_contact_opt['tooltip_filetypes'] != '') ? $si_contact_opt['tooltip_filetypes'] : __('Acceptable file types:', 'si-contact-form');
    $string .= ' '.$si_contact_opt['attach_types'] . '.<br />';
    $string .= ($si_contact_opt['tooltip_filesize'] != '') ? $si_contact_opt['tooltip_filesize'] : __('Maximum file size:', 'si-contact-form');
    $string .= ' '.$si_contact_opt['attach_size'].'.</span>';
 }
 $printed_tooltip_filetypes = $ex_loop_cnt;
$string .= '        </div>
';
        if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
        }
          break;
             case 'date':
            $cal_date_array = array(
'mm/dd/yyyy' => $this->ctf_output_string(__('mm/dd/yyyy', 'si-contact-form')),
'dd/mm/yyyy' => $this->ctf_output_string(__('dd/mm/yyyy', 'si-contact-form')),
'mm-dd-yyyy' => $this->ctf_output_string(__('mm-dd-yyyy', 'si-contact-form')),
'dd-mm-yyyy' => $this->ctf_output_string(__('dd-mm-yyyy', 'si-contact-form')),
'mm.dd.yyyy' => $this->ctf_output_string(__('mm.dd.yyyy', 'si-contact-form')),
'dd.mm.yyyy' => $this->ctf_output_string(__('dd.mm.yyyy', 'si-contact-form')),
'yyyy/mm/dd' => $this->ctf_output_string(__('yyyy/mm/dd', 'si-contact-form')),
'yyyy-mm-dd' => $this->ctf_output_string(__('yyyy-mm-dd', 'si-contact-form')),
'yyyy.mm.dd' => $this->ctf_output_string(__('yyyy.mm.dd', 'si-contact-form')),
);
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
                 $string .= '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' .$si_contact_opt['ex_field'.$i.'_label'] . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <input ';
         $string .= ($si_contact_opt['ex_field'.$i.'_input_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_input_css']) : $this->ctf_field_style;
         $string .= ' type="text" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="';
                $string .=   ( isset(${'ex_field'.$i}) && ${'ex_field'.$i} != '') ? $this->ctf_output_string(${'ex_field'.$i}): $cal_date_array[$si_contact_opt['date_format']];
                $string .=   '" '.$ex_req_field_aria.' size="15" ';
                if($si_contact_opt['ex_field'.$i.'_attributes'] != '')
                  $string .= ' '.$si_contact_opt['ex_field'.$i.'_attributes'];
                $string .= ' />
        </div>
';
        if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
             break;
             case 'time':
           // the time drop down list array will be made automatically by this code
$exf_opts_array = array();
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
           $string .=   '
        <div ';
         $string .= ($si_contact_opt['ex_field'.$i.'_label_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_label_css']) : $this->ctf_title_style;
         $string .= '>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] . $ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
               <select ';
         $string .= ($si_contact_opt['ex_field'.$i.'_input_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_input_css']) : $this->ctf_field_style;
         $string .= ' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'h">
        ';

$selected = '';
// hours
$tf_hours = ($si_contact_opt['time_format'] == '24') ? '23' : '12';
for ($ii = ($si_contact_opt['time_format'] == '24') ? 0 : 1; $ii <= $tf_hours; $ii++) {
 $ii = sprintf("%02d",$ii);
 if (${'ex_field'.$i.'h'} != '') {
    if (${'ex_field'.$i.'h'} == "$ii") {
      $selected = ' selected="selected"';
    }
 }
 $string .= '           <option value="'.$this->ctf_output_string($ii).'"'.$selected.'>'.$this->ctf_output_string($ii).'</option>'."\n";
 $selected = '';

}
$string .= '            </select>:<select ';
         $string .= ($si_contact_opt['ex_field'.$i.'_input_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_input_css']) : $this->ctf_field_style;
         $string .= ' id="si_contact_ex_field'.$form_id_num.'_'.$i.'m" name="si_contact_ex_field'.$i.'m">
        ';
$selected = '';
// minutes
for ($ii = 00; $ii <= 59; $ii++) {
      $ii = sprintf("%02d",$ii);
 if (${'ex_field'.$i.'m'} != '') {
    if (${'ex_field'.$i.'m'} == "$ii") {
      $selected = ' selected="selected"';
    }
 }
 $string .= '            <option value="'.$this->ctf_output_string($ii).'"'.$selected.'>'.$this->ctf_output_string($ii).'</option>'."\n";
 $selected = '';

}
$string .= '            </select>';
if ($si_contact_opt['time_format'] == '12'){
$string .= '<select ';
         $string .= ($si_contact_opt['ex_field'.$i.'_input_css'] != '') ? $this->si_contact_convert_css($si_contact_opt['ex_field'.$i.'_input_css']) : $this->ctf_field_style;
         $string .= ' id="si_contact_ex_field'.$form_id_num.'_'.$i.'ap" name="si_contact_ex_field'.$i.'ap">
        ';
$selected = '';
// am/pm
foreach (array($this->ctf_output_string(__('AM', 'si-contact-form')), $this->ctf_output_string(__('PM', 'si-contact-form')) ) as $k) {
 if (${'ex_field'.$i.'ap'} != '') {
    if (${'ex_field'.$i.'ap'} == "$k") {
      $selected = ' selected="selected"';
    }
 }
 $string .= '            <option value="'.$this->ctf_output_string($k).'"'.$selected.'>'.$this->ctf_output_string($k).'</option>'."\n";
 $selected = '';

}
$string .= '            </select>';
}
$string .= '
        </div>
 ';
         if($si_contact_opt['ex_field'.$i.'_notes_after'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes_after']);
        }
             break;

          }

        } // end if label
       $ex_loop_cnt++;
      } // end foreach

 // how many extra fields are date fields?
     $ex_date_found = array();
     for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '' && $si_contact_opt['ex_field'.$i.'_type'] == 'date') {
          $ex_date_found[$i] = $i;
        }
     }
     if (isset($ex_date_found) && count($ex_date_found) > 0 ) {
     $string .=   '
<link rel="stylesheet" type="text/css" href="'.WP_PLUGIN_URL.'/si-contact-form/date/ctf_epoch_styles.css?'.time().'" />
<script type="text/javascript">
	var ctf_daylist = new Array( \''.__('Su', 'si-contact-form').'\',\''.__('Mo', 'si-contact-form').'\',\''.__('Tu', 'si-contact-form').'\',\''.__('We', 'si-contact-form').'\',\''.__('Th', 'si-contact-form').'\',\''.__('Fr', 'si-contact-form').'\',\''.__('Sa', 'si-contact-form').'\',\''.__('Su', 'si-contact-form').'\',\''.__('Mo', 'si-contact-form').'\',\''.__('Tu', 'si-contact-form').'\',\''.__('We', 'si-contact-form').'\',\''.__('Th', 'si-contact-form').'\',\''.__('Fr', 'si-contact-form').'\',\''.__('Sa', 'si-contact-form').'\' );
	var ctf_months_sh = new Array( \''.__('Jan', 'si-contact-form').'\',\''.__('Feb', 'si-contact-form').'\',\''.__('Mar', 'si-contact-form').'\',\''.__('Apr', 'si-contact-form').'\',\''.__('May', 'si-contact-form').'\',\''.__('Jun', 'si-contact-form').'\',\''.__('Jul', 'si-contact-form').'\',\''.__('Aug', 'si-contact-form').'\',\''.__('Sep', 'si-contact-form').'\',\''.__('Oct', 'si-contact-form').'\',\''.__('Nov', 'si-contact-form').'\',\''.__('Dec', 'si-contact-form').'\' );
	var ctf_monthup_title = \''.__('Go to the next month', 'si-contact-form').'\';
	var ctf_monthdn_title = \''.__('Go to the previous month', 'si-contact-form').'\';
	var ctf_clearbtn_caption = \''.__('Clear', 'si-contact-form').'\';
	var ctf_clearbtn_title = \''.__('Clears any dates selected on the calendar', 'si-contact-form').'\';
	var ctf_maxrange_caption = \''.__('This is the maximum range', 'si-contact-form').'\';
    var ctf_cal_start_day = '.$si_contact_opt['cal_start_day'].';
    var ctf_date_format = \'';
 if($si_contact_opt['date_format'] == 'mm/dd/yyyy')
      $string .=   'm/d/Y';
 if($si_contact_opt['date_format'] == 'dd/mm/yyyy')
      $string .=   'd/m/Y';
 if($si_contact_opt['date_format'] == 'mm-dd-yyyy')
      $string .=   'm-d-Y';
 if($si_contact_opt['date_format'] == 'dd-mm-yyyy')
      $string .=   'd-m-Y';
 if($si_contact_opt['date_format'] == 'mm.dd.yyyy')
      $string .=   'm.d.Y';
 if($si_contact_opt['date_format'] == 'dd.mm.yyyy')
      $string .=   'd.m.Y';
 if($si_contact_opt['date_format'] == 'yyyy/mm/dd')
      $string .=   'Y/m/d';
 if($si_contact_opt['date_format'] == 'yyyy-mm-dd')
      $string .=   'Y-m-d';
 if($si_contact_opt['date_format'] == 'yyyy.mm.dd')
      $string .=   'Y.m.d';

 $string .= '\';
</script>
<script type="text/javascript" src="'.WP_PLUGIN_URL.'/si-contact-form/date/ctf_epoch_classes.js?'.time().'"></script>
<script type="text/javascript">
var ';
        $ex_date_var_string = '';
        foreach ($ex_date_found as $v) {
          $ex_date_var_string .= "dp_cal$form_id_num".'_'."$v,";
        }
        $ex_date_var_string = substr($ex_date_var_string,0,-1);
$string .= "$ex_date_var_string;\n";
$string .= 'window.onload = function () {
';
        foreach ($ex_date_found as $v) {
          $string .= "dp_cal$form_id_num".'_'."$v  = new Epoch('epoch_popup$form_id_num".'_'."$v','popup',document.getElementById('si_contact_ex_field$form_id_num".'_'."$v'));\n";
        }
$string .=   "};\n</script>\n";

     }
     if($ex_fieldset)
        $string .=   "</fieldset>\n";
?>