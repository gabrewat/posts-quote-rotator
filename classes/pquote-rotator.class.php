<?php
if( !class_exists('PQuoteRotator') ) :

class PQuoteRotator
{
	var $pluginPath;
	var $currentVersion;
	
	function PQuoteRotator()
	{
		
		if( !function_exists('get_option') )
		{
			require_once('../../../wp-config.php');
		}
		
		$this->currentVersion = '0.1.0';
		$this->pluginPath = get_settings('siteurl') . '/wp-content/plugins/posts-quote-rotator/';
		
		$options = get_option('widgetPQuoteRotator');
		$options['version'] = $this->currentVersion;
		update_option('widgetPQuoteRotator', $options);
	}
	
	
	function addpqHeaderContent()
	{
		global $wpdb;
		
		if( !function_exists('get_option') )
		{
			require_once('../../../wp-config.php');
		}
      
		$delay = get_option('pqr_delay');
		if (!isset($delay) || $delay == "") $delay = 5;
		$fade = get_option('pqr_fade');
		if (!isset($fade) || $fade == "") $fade = 2;
		$fadeout = get_option('pqr_fadeout');
		if (!isset($fadeout) || $fadeout == "") $fadeout = 0;
		$random = get_option('pqr_random');
		if (!isset($random) || $random == "") $random = 0;
		/* AND wpostmeta.meta_value = '1'  */
		
		
		$sql = "
			SELECT wposts.ID, wposts.post_title, wpostmeta.meta_value
			FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
			WHERE wposts.ID = wpostmeta.post_id 
			AND wpostmeta.meta_key = 'post_quote' 
			AND wposts.post_status = 'publish' 
			AND (wposts.post_type = 'post')";



					
		
		if($random)
			$sql = $sql . " ORDER BY RAND(".time().")";

	    $results = $wpdb->get_results($sql);
		
		$stylesdir = 'wp-content/plugins/posts-quote-rotator/styles/';
		$cssfile = get_option('pqr_stylesheet');
		
        if (file_exists(ABSPATH . $stylesdir . $cssfile))
		  echo "
			<link rel=\"stylesheet\" href=\"" . get_settings('siteurl') . '/' . $stylesdir . $cssfile ."\" type=\"text/css\" media=\"screen\" />";
		  /* 2010-03-25 replacing Scriptaculous animation code with jQuery - Thanks to colin@brainbits.ca for supplying the code */
		  echo "	<script type='text/javascript'>
				quoteRotator = {
					i: 1,
					quotes: [";

               	$i=0;

               $len = count($results);
               	foreach($results as $result){
                    echo "\"$result->meta_value";

                    $permlink = get_permalink( $result->ID );
                    if($result->post_title != '')
                    {
                       echo "<p></p>";
			echo " From <a href='$permlink'>$result->post_title</a>";
                    }


                    if ($i < $len - 1)
                       echo "\", ";
                    else 
                       echo "\"";

		     $i++;
		}
		echo "
					],
					numQuotes: ".$i.",
					fadeDuration: ".$fade.",
					fadeoutDuration: ".$fadeout.",
					delay: ".$delay.",
					quotesInit: function(){
						if (this.numQuotes < 1){
							document.getElementById('quoterotator').innerHTML=\"No Quotes Found\";
						} else {
							this.quoteRotate();
							setInterval('quoteRotator.quoteRotate()', (this.fadeDuration + this.fadeoutDuration + this.delay) * 1000);
						}
					},
					quoteRotate: function(){
						jQuery('#quoterotator').hide().html(this.quotes[this.i - 1]).fadeIn(this.fadeDuration * 1000).css('filter','').delay(this.delay * 1000).fadeOut(this.fadeoutDuration * 1000);
						this.i = this.i % (this.numQuotes) + 1;
					}
	
				}
			</script>";
	}
		
	function displaypqWidget($args)
	{
		extract($args);
		
		$options = get_option('widgetPQuoteRotator');
		$title = $options['title'];

      $color = $options['color'];
      
      $style = "";
      if ($options['fontsize'] != "") $style .= "font-size:".$options['fontsize'].$options['fontunit'].";";
      if ($options['height'] != "") $style .= "height:".$options['height']."px;";
      if ($options['color'] != "") $style .= "color:".$options['color'].";";
      if ($style != "") $style = " style='".$style."'";

		echo $before_widget . $before_title . $title . $after_title;
		
		echo "<div id=\"quotearea\"$style><div id=\"quoterotator\">\n";
		echo "Loading Quotes...\n";
		echo "</div></div>\n";
		echo "<script type=\"text/javascript\">setTimeout(\"pquoteRotator.quotesInit()\", 2000)</script>\n";
		
		echo $after_widget;
	}
	
	
	
	
	function getPQuoteCode($title=null, $delay=null, $fadeDuration=null, $fadeoutDuration=null)
	{
	  $output =  "";
           
      if (isset($title) && $title != "") {
   		$output .=  "<h2>" . $title . "</h2>";
		} else {
         $title_from_settings = get_option('fqr_title');
         if (isset($title_from_settings) && $title_from_settings != "") {
      		$output .=  "<h2>" . $title_from_settings . "</h2>";
   		}
      }
      $style = "";
      if (get_option('fqr_height') != "") $style .= "height:".get_option('fqr_height')."px;";
      if (get_option('fqr_width') != "") $style .= "width:".get_option('fqr_width')."px;";
      if ($style != "") $style = " style='".$style."'";
		$output .= "<div id=\"quotearea\"$style><div id=\"quoterotator\">\n";
		$output .= "Loading Quotes...\n";
		$output .= "</div></div>\n";
		$output .= "<script type=\"text/javascript\">";
      if (isset($delay) && $delay != "") {
   		$output .=  "quoteRotator.delay=".$delay.";";
		}
      if (isset($fadeDuration) && $fadeDuration != "") {
   		$output .=  "quoteRotator.fadeDuration=".$fadeDuration.";";
		}
      if (isset($fadeoutDuration) && $fadeoutDuration != "") {
   		$output .=  "quoteRotator.fadeoutDuration=".$fadeoutDuration.";";
		}
      $output .= "quoteRotator.quotesInit();</script>\n";
		return $output;
	}



}
endif;

?>
