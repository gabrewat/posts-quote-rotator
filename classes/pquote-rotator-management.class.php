<?php
//if( !class_exists('PQuoteRotatorManagement') ) :

class PQuoteRotatorManagement
{	
	var $pluginPath;
	var $pluginFile;
	
	function PQuoteRotatorManagement()
	{
		
		if( !function_exists('get_option') )
		{
			require_once('../../../wp-config.php');
		}
		
		$this->pluginPath = get_settings('siteurl') . '/wp-content/plugins/posts-quote-rotator/';
		$this->pluginFile = $this->pluginPath . 'posts-quote-rotator.php';
	}
	
	

	function displaypqOptionsPage()
	{
		/* 2010-03-25 added by colin@brainbits.ca to fix broken options saving in wordpress 2.9.2 */
		if($_POST['action'] == 'update'){
                	update_option('pqr_title', $_POST['pqr_title'] );
                	update_option('pqr_delay', $_POST['pqr_delay'] );
                	update_option('pqr_fade', $_POST['pqr_fade'] );
                	update_option('pqr_fadeout', $_POST['pqr_fadeout'] );
                	update_option('pqr_height', $_POST['pqr_height'] );
                	update_option('pqr_width', $_POST['pqr_width'] );
                	update_option('pqr_random', $_POST['pqr_random'] );
                	update_option('pqr_stylesheet', $_POST['pqr_stylesheet'] );

                	?><div class="updated"><p><strong><?php _e('Options saved.', 'eg_trans_domain' ); ?></strong></p></div><?php
		}
		/* end of added by colin@brainbits.ca */

      ?>
      <div class="wrap">
      <h2>Posts Quote Rotator Options</h2>
      
      <form method="post">
      <?php wp_nonce_field('update-options'); ?>
     
      <table class="form-table">
      <tr valign="top">
      <th scope="row">Title</th>
      <td><input type="text" name="pqr_title" value="<?php echo get_option('pqr_title'); ?>" /><br/>(adds a header above quote area, leave blank if no header desired)</td>
      </tr>
       
      <tr valign="top">
      <th scope="row">Delay (in seconds)</th>
      <td><input type="text" name="pqr_delay" value="<?php echo get_option('pqr_delay'); ?>" /></td>
      </tr>
      
      <tr valign="top">
      <th scope="row">Fade in duration (in seconds)</th>
      <td><input type="text" name="pqr_fade" value="<?php echo get_option('pqr_fade'); ?>" /></td>
      </tr>

      <tr valign="top">
      <th scope="row">Fade out duration (in seconds)</th>
      <td><input type="text" name="pqr_fadeout" value="<?php echo get_option('pqr_fadeout'); ?>" /></td>
      </tr>
            
      <tr valign="top">
      <th scope="row">Height override (overrides CSS)</th>
      <td><input type="text" name="pqr_height" value="<?php echo get_option('pqr_height'); ?>" />px</td>
      </tr>
      
      <tr valign="top">
      <th scope="row">Width override (overrides CSS)</th>
      <td><input type="text" name="pqr_width" value="<?php echo get_option('pqr_width'); ?>" />px</td>
      </tr>
            
      <tr valign="top">
      <th scope="row">Random?</th>
      <td>
         Yes <input type="radio" name="pqr_random" value="1"<?php if(get_option('pqr_random')==1) echo ' checked="checked"';?> />&nbsp;&nbsp;&nbsp;
         No <input type="radio" name="pqr_random" value="0"<?php if(get_option('pqr_random')!=1) echo ' checked="checked"';?> />
      </td>
      </tr>

      <tr valign="top">
      <th scope="row">Stylesheet</th>
      <td>
         <select name="pqr_stylesheet">
            <option>none</option>
         <?php
            $style = get_option('pqr_stylesheet'); 
            $stylesdir	= ABSPATH . 'wp-content/plugins/posts-quote-rotator/styles/';
            $allCSS = array();
            $dir = opendir($stylesdir);
            while ( $dir && ($f = readdir($dir)) ) {
            	if( eregi("\.css$",$f) && !eregi("calendar\.css$",$f) ){
            		array_push($allCSS, $f);
            	}
            }
            sort($allCSS);
            foreach ( $allCSS as $f ) {
            	if( $f==$style )
            	    	echo '<option style="background:#fbd0d3" selected="selected" value="'.$f.'">'.$f.'</option>'."\n";
            	else
            			echo '<option value="'.$f.'">'.$f.'</option>';																		
            }
         ?>
         </select><br/>
         (you can add your own stylesheet to the directory /wp-content/plugins/posts-quote-rotator/styles/ for full control over styling)
      </td>
      </tr>
      </table>
      
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="page_options" value="pqr_title,pqr_delay,pqr_fade,pqr_fadeout,pqr_height,pqr_width,pqr_random,pqr_stylesheet" />
      
      <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
      </p>
      
      </form>
      </div>
      <?php
   }
   	
	function displaypqWidgetControl()
	{
		$options = get_option('widgetPQuoteRotator');
		if ( !is_array($options) ){
			$options = array();
			$options['title'] = 'Quotes';
			$options['delay'] = 5;
			$options['fade'] = 2;
			$options['fontsize'] = 14;
			$options['fontunit'] = 'px';
			$options['random'] = 0;
			$options['height'] = 100;
			$options['color'] = 'black';
		}
		if ( $_POST['pquoterotator-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['pquoterotator-title']));
			$options['delay'] = strip_tags(stripslashes($_POST['pquoterotator-delay']));
			$options['fade'] = strip_tags(stripslashes($_POST['pquoterotator-fade']));
			$options['fontsize'] = strip_tags(stripslashes($_POST['pquoterotator-fontsize']));
			$options['fontunit'] = strip_tags(stripslashes($_POST['pquoterotator-fontunit']));
			$options['random'] = strip_tags(stripslashes($_POST['pquoterotator-random']));
			$options['height'] = strip_tags(stripslashes($_POST['pquoterotator-height']));
			$options['color'] = strip_tags(stripslashes($_POST['pquoterotator-color']));
			update_option('widgetPQuoteRotator', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$delay = htmlspecialchars($options['delay'], ENT_QUOTES);
		$fade = htmlspecialchars($options['fade'], ENT_QUOTES);
		$fontsize = htmlspecialchars($options['fontsize'], ENT_QUOTES);
		$fontunit = htmlspecialchars($options['fontunit'], ENT_QUOTES);
		$random = htmlspecialchars($options['random'], ENT_QUOTES);
		$height = htmlspecialchars($options['height'], ENT_QUOTES);
		$color = htmlspecialchars($options['color'], ENT_QUOTES);
		
		echo '<p><label for="pquoterotator-title">Title: </label><input style="width: 200px;" id="pquoterotator-title" name="pquoterotator-title" type="text" value="'.$title.'" /></p>';
		echo '<p><label for="pquoterotator-delay">Delay(seconds): </label><input style="width: 200px;" id="pquoterotator-delay" name="pquoterotator-delay" type="text" value="'.$delay.'" /></p>';
		echo '<p><label for="pquoterotator-fade">Fade Time(seconds): </label><input style="width: 200px;" id="pquoterotator-fade" name="pquoterotator-fade" type="text" value="'.$fade.'" /></p>';
		echo '<p><label for="pquoterotator-fontsize">Font Size: </label><input style="width: 50px;" id="pquoterotator-fontsize" name="pquoterotator-fontsize" type="text" value="'.$fontsize.'" />';
		echo '<select id="pquoterotator-fontunit" name="pquoterotator-fontunit">';
		echo '<label for="pquoterotator-fontunit"><option value="px">px</option>';
		echo '<option value="em"';
		if ( isset($options['fontunit']) && 'em' == $options['fontunit'] ) echo ' selected="selected"';
		echo ' >em</option>';
		echo '<option value="%"';
		if ( isset($options['fontunit']) && '%' == $options['fontunit'] ) echo ' selected="selected"';
		echo ' >%</option>';
		echo '</select>';
		echo '</label></p>';

		echo '<p><label for="pquoterotator-height">Height(pixels): </label><input style="width: 200px;" id="pquoterotator-height" name="pquoterotator-height" type="text" value="'.$height.'" /></p>';
		echo '<p><label for="pquoterotator-color">Text Color: </label><input style="width: 200px;" id="pquoterotator-color" name="pquoterotator-color" type="text" value="'.$color.'" /></p>';

		echo '<p><label for="pquoterotator-random">Random?: </label><input id="pquoterotator-random1" name="pquoterotator-random" type="radio" ';
		if($random==1)
			echo 'checked="yes" ';
		echo 'value="1" /> Yes';
		echo '<input id="pquoterotator-random2" name="pquoterotator-random" type="radio" ';
		if($random==0)
			echo 'checked="no" ';
		echo 'value="0" /> No </label></p>';
		echo '<input type="hidden" id="pquoterotator-submit" name="pquoterotator-submit" value="1" />';
	}
}

//endif;
