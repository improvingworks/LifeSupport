<?php
/*
Fast Secure Contact Form
Mike Challis
http://www.642weather.com/weather/scripts.php
*/

// display extra fields on the contact form

      $ex_fieldset = 0;
      for ($i = 1; $i <= $si_contact_gb['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '' || $si_contact_opt['ex_field'.$i.'_type'] == 'fieldset-close') {
           $ex_req_field_ind = ($si_contact_opt['ex_field'.$i.'_req'] == 'true') ? $req_field_ind : '';
           $ex_req_field_aria = ($si_contact_opt['ex_field'.$i.'_req'] == 'true') ? $this->ctf_aria_required : '';
           if(!$si_contact_opt['ex_field'.$i.'_type'] ) $si_contact_opt['ex_field'.$i.'_type'] = 'text';
           if(!$si_contact_opt['ex_field'.$i.'_default'] ) $si_contact_opt['ex_field'.$i.'_default'] = '0';
           if(!$si_contact_opt['ex_field'.$i.'_notes'] ) $si_contact_opt['ex_field'.$i.'_notes'] = '';
           //if ($si_contact_opt['ex_field'.$i.'_notes'] != '')
           //    $si_contact_opt['ex_field'.$i.'_notes'] =  '<p>'.$si_contact_opt['ex_field'.$i.'_notes'].'</p>';

          switch ($si_contact_opt['ex_field'.$i.'_type']) {
           case 'fieldset':
                if($ex_fieldset)
                   $string .=   "</fieldset>\n";
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
            $string .=   '
                <input type="hidden" name="si_contact_ex_field'.$i.'" value="' . $this->ctf_output_string($value) . '" />
';
           break;
           case 'password':
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
                 $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <input '.$this->ctf_field_style.' type="password" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="' . $this->ctf_output_string(${'ex_field'.$i}) . '" '.$ex_req_field_aria.' size="'.$ctf_field_size.'" />
        </div>';
              break;
           case 'text':
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
                 $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <input '.$this->ctf_field_style.' type="text" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="' . $this->ctf_output_string(${'ex_field'.$i}) . '" '.$ex_req_field_aria.' size="'.$ctf_field_size.'" />
        </div>';
              break;
           case 'textarea':
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
                $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <textarea '.$this->ctf_field_style.' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" '.$ex_req_field_aria.' cols="'.absint($si_contact_opt['text_cols']).'" rows="'.absint($si_contact_opt['text_rows']).'">';
                $string .= ($si_contact_opt['textarea_html_allow'] == 'true') ? $this->ctf_stripslashes(${'ex_field'.$i}) : $this->ctf_output_string(${'ex_field'.$i});
                $string .= '</textarea>
        </div>';
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
           $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $exf_opts_label .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
               <select '.$this->ctf_field_style.' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'">
        ';

$exf_opts_ct = 1;
$selected = '';
foreach ($exf_opts_array as $k) {
 if (${'ex_field'.$i} != '') {
    if (${'ex_field'.$i} == "$k") {
      $selected = ' selected="selected"';
    }
 }else{
    if ($exf_opts_ct == $si_contact_opt['ex_field'.$i.'_default']) {
      $selected = ' selected="selected"';
    }
 }

 $string .= '<option value="'.$this->ctf_output_string($k).'"'.$selected.'>'.$this->ctf_output_string($k).'</option>'."\n";
 $exf_opts_ct++;
 $selected = '';

}
$string .= '</select>
        </div>';
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
           $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $exf_opts_label .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
               <select '.$this->ctf_field_style.' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'[]" multiple="multiple">
        ';

$exf_opts_ct = 1;
$selected = '';
foreach ($exf_opts_array as $k) {
 if (is_array(${'ex_field'.$i}) && ${'ex_field'.$i} != '') {
    if (in_array($k, ${'ex_field'.$i} ) ) {
      $selected = ' selected="selected"';
    }
 }
 if (!isset($_POST['si_contact_form_id']) && $exf_opts_ct == $si_contact_opt['ex_field'.$i.'_default']) {
      $selected = ' selected="selected"';
 }
 $string .= '<option value="'.$this->ctf_output_string($k).'"'.$selected.'>'.$this->ctf_output_string($k).'</option>'."\n";
 $exf_opts_ct++;
 $selected = '';

}
$string .= '</select>
        </div>';
             break;
           case 'checkbox':

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
           $string .=   '
        <div '.$this->ctf_title_style.'>
        <label>' . $exf_opts_label .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'. $this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i});

     $ex_cnt = 1;
  foreach ($exf_opts_array as $k) {
     if(!$exf_opts_inline && $ex_cnt > 1)
               $string .= "<br />\n";
     $string .=   '<span style="white-space:nowrap;"><input type="checkbox" style="width:13px;" id="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'" name="si_contact_ex_field'.$i.'_'.$ex_cnt.'" value="selected"  ';

    if (!isset($_POST['si_contact_form_id']) && $ex_cnt == $si_contact_opt['ex_field'.$i.'_default']) {
      $string .= ' checked="checked"';
    }

    if ( isset(${'ex_field'.$i.'_'.$ex_cnt}) && ${'ex_field'.$i.'_'.$ex_cnt} == 'selected' )
    $string .= ' checked="checked" ';


                 $string .= '/>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'">' . $k .'</label></span>'."\n";
     $ex_cnt++;
  }

   $string .=   '
        </div> '."\n";

} else {

  // single
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
               $string .=   '
        <div '.$this->ctf_title_style.'>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
            <input type="checkbox" style="width:13px;" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="selected" ';
    if (${'ex_field'.$i} != '') {
      if (${'ex_field'.$i} == 'selected') {
         $string .= 'checked="checked" ';
      }
    }else{
      if (!isset($_POST['si_contact_action']) && $si_contact_opt['ex_field'.$i.'_default'] == '1') {
         $string .= 'checked="checked" ';
      }
    }
                 $string .= '/>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div>
';

} // end else

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
           $string .=   '
        <div '.$this->ctf_title_style.'>
        <label>' . $exf_opts_label .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'. $this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i});
         // if($exf_opts_inline)
         //     $string .= "<br />\n";
$selected = '';
$ex_cnt = 1;
foreach ($exf_opts_array as $k) {
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
               $string .= "<br />\n";
 $string .= '<span style="white-space:nowrap;"><input type="radio" style="width:13px;" id="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'" name="si_contact_ex_field'.$i.'" value="'.$this->ctf_output_string($k).'"'.$selected.' />
 <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'">' . $k .'</label></span>'."\n";
 $selected = '';
 $ex_cnt++;
}
$string .= '
        </div>';
             break;

           case 'attachment':
     if ($si_contact_opt['php_mailer_enable'] != 'php') {
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
            $string .= '        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <input '.$this->ctf_field_style.' type="file" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="' . $this->ctf_output_string(${'ex_field'.$i}) . '" '.$ex_req_field_aria.' size="20" />
          <br /><span style="font-size:x-small;">'.sprintf(__('Acceptable file types: %s.', 'si-contact-form'),$si_contact_opt['attach_types']).'<br />
                '.sprintf(__('Maximum file size: %s.', 'si-contact-form'),$si_contact_opt['attach_size']).'</span>
        </div>';
        }
          break;


             case 'date':
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
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
                 $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' .$si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
                <input '.$this->ctf_field_style.' type="text" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="';
                $string .=   ( isset(${'ex_field'.$i}) && ${'ex_field'.$i} != '') ? $this->ctf_output_string(${'ex_field'.$i}): $cal_date_array[$si_contact_opt['date_format']];
                $string .=   '" '.$ex_req_field_aria.' size="15" />
        </div>';

             break;


             case 'time':
           // the time drop down list array will be made automatically by this code
$exf_opts_array = array();
        if($si_contact_opt['ex_field'.$i.'_notes'] != '') {
           $string .=  $this->ctf_notes($si_contact_opt['ex_field'.$i.'_notes']);
        }
           $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
               <select '.$this->ctf_field_style.' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'h">
        ';

$selected = '';
// hours
for ($ii = 1; $ii <= 12; $ii++) {
 $ii = sprintf("%02d",$ii);
 if (${'ex_field'.$i.'h'} != '') {
    if (${'ex_field'.$i.'h'} == "$ii") {
      $selected = ' selected="selected"';
    }
 }
 $string .= '<option value="'.$this->ctf_output_string($ii).'"'.$selected.'>'.$this->ctf_output_string($ii).'</option>'."\n";
 $selected = '';

}
$string .= '</select>:<select '.$this->ctf_field_style.' id="si_contact_ex_field'.$form_id_num.'_'.$i.'m" name="si_contact_ex_field'.$i.'m">
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
 $string .= '<option value="'.$this->ctf_output_string($ii).'"'.$selected.'>'.$this->ctf_output_string($ii).'</option>'."\n";
 $selected = '';

}
$string .= '</select><select '.$this->ctf_field_style.' id="si_contact_ex_field'.$form_id_num.'_'.$i.'ap" name="si_contact_ex_field'.$i.'ap">
        ';
$selected = '';
// am/pm
foreach (array(esc_attr(__('AM', 'si-contact-form')), esc_attr(__('PM', 'si-contact-form')) ) as $k) {
 if (${'ex_field'.$i.'ap'} != '') {
    if (${'ex_field'.$i.'ap'} == "$k") {
      $selected = ' selected="selected"';
    }
 }
 $string .= '<option value="'.$this->ctf_output_string($k).'"'.$selected.'>'.$this->ctf_output_string($k).'</option>'."\n";
 $selected = '';

}
$string .= '</select>
 </div>';
             break;

          }

        } // end if label
      } // end foreach

 // how many extra fields are date fields?
     $ex_date_found = array();
     for ($i = 1; $i <= $si_contact_gb['max_fields']; $i++) {
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