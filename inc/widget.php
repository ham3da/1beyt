<?php
class OB_Widget extends WP_Widget {

/**
 *     function OB_Widget() 
 *     {
 *         $widget_option = array('classname' => 'widget', 'description' => 'نمايش يک بيت شعر تصادفي');
 *         parent::WP_Widget('yek_beyt', 'يک بيت شعر', $widget_option);
 *     }
 */
    
	function __construct() {
		// Instantiate the parent object
		parent::__construct( false, 'یک بیت شعر' );
	}
    

    function widget($args, $instance) 
    {
        extract($args, EXTR_SKIP);
        $title = ($instance['title']) ? $instance['title'] : 'یک بیت شعر';
        $default=($instance['default'])?$instance['default']:'در صورتی که هیچ شعری برای نمایش وجود نداشته باشد این متن نمایش داده خواهد شد.';
        global $wpdb;
        $table = $wpdb->prefix."poems";
        $sql="SELECT * FROM $table ORDER BY rand() LIMIT 1";
        $result=$wpdb->get_row($sql);
        
        $Verse1=$result->Verse1;
        $Verse2=$result->Verse2;
        $Des1="«".$result->Des1."»";
        ?>
        <?php echo $before_widget ?>
        <?php echo $before_title . $title . $after_title ?>
        <p style="text-align: center;">
            <?php echo !empty($Verse1)?$Verse1:$default; ?><br />
            <?php echo !empty($Verse2)?$Verse2:$default; ?><br />
            <?php echo !empty($Des1)?$Des1:$default; ?><br />        
        </p>
        <?php echo $after_widget ?>
        <?php  
        }
    
    function form($instance)
    {
        if(isset($instance['title']))
        {
            $title=$instance['title'];
        }
        else
        {
            $title="یک بیت شعر";
        }
        if(isset($instance['default']))
        {
            $default=$instance['default'];
        }  else {
            $default="در صورتی که هیچ شعری برای نمایش وجود نداشته باشد این متن نمایش داده خواهد شد.";
        }
        
        ?>
        <p>عنوان ابزارک</p>
        <p><input style="width:100%" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title')?>" type="text" value="<?php echo esc_attr($title); ?>"></p>
        <p>متن پیش فرض :</p>
        <p><textarea style="width:100%;height:200px;" id="<?php echo $this->get_field_id('default') ?>" name="<?php echo $this->get_field_name('default')?>" type="text"><?php echo esc_attr($default); ?></textarea></p>
            <?php
    }
    function update($new_instance, $old_instance)
    {
        $instance=array();
        $instance['title']=  strip_tags($new_instance['title']);
        $instance['default']=  strip_tags($new_instance['default']);
        return $instance;
    }
}
function ob_widget_init()
{
    register_widget("OB_Widget");
}
add_action('widgets_init', 'ob_widget_init');
?>