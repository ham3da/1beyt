<?php
function yek_byet_func() 
{
        global $wpdb;
        $table = $wpdb->prefix."poems";
        $sql="SELECT * FROM $table ORDER BY rand() LIMIT 1";
        $result=$wpdb->get_row($sql);
        
        $Verse1=$result->Verse1;
        $Verse2=$result->Verse2;
        $Des1=$result->Des1;
       
       return '<p id="yek_byet" style="text-align: center;">'.
            $Verse1.'<br />'.
            $Verse2.'<br />«'.
            $Des1.'»<br />
        </p>';   
}
add_shortcode('yek_beyt','yek_byet_func');
//