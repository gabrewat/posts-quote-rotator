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
		global $allquotes;
		
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
        
        
		  $allquotes .= "<link rel=\"stylesheet\" href=\"" . get_settings('siteurl') . '/' . $stylesdir . $cssfile ."\" type=\"text/css\" media=\"screen\" />\n";
		  
		
	      $allquotes .= "<script type='text/javascript'>\n";
	      $allquotes .= "$('ul#quotearea').quote_rotator({ rotation_speed: 8000" . ", pause_on_hover: true, randomize_first_quote: true});\n";
	      $allquotes .= "</script>\n";


	      // echo " <script type='text/javascript'>
          // $(document).ready(function() { $('ul#quotearea').quote_rotator(); });
          // </script>";
	
		
		$allquotes .= "<ul id=\"quotearea\">\n";
		
						// fadeDuration: ".$fade.",
					// fadeoutDuration: ".$fadeout.",
					// delay: ".$delay.",
	  
        foreach($results as $result){
        	$allquotes .= "<li><a href=\"" . get_permalink( $result->ID ) . "\">";
        	
            $allquotes .= "<$result->meta_value";
            
            if($result->post_title != '')
			   $allquotes .= " From <span id='quoteauthor'>$result->post_title</span>";
         	$allquotes .= "</a></li>\n";
        }
	   
	    $allquotes .= "</ul>\n";
	
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
	  // global $allquotes;
	  global $wpdb;
		
	  $allquotes =  "";
           
      if (isset($title) && $title != "") {
   		$allquotes .=  "<h2>" . $title . "</h2>";
		} else {
         $title_from_settings = get_option('fqr_title');
         if (isset($title_from_settings) && $title_from_settings != "") {
      		$allquotes .=  "<h2>" . $title_from_settings . "</h2>";
   		}
      }
      $style = "";
      if (get_option('fqr_height') != "") $style .= "height:".get_option('fqr_height')."px;";
      if (get_option('fqr_width') != "") $style .= "width:".get_option('fqr_width')."px;";
      if ($style != "") $style = " style='".$style."'";
		$allquotes .= "<div id=\"quotearea\"$style><div id=\"quoterotator\">\n";
		
		
		
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
        
        
		  $allquotes .= "<link rel=\"stylesheet\" href=\"" . get_settings('siteurl') . '/' . $stylesdir . $cssfile ."\" type=\"text/css\" media=\"screen\" />\n";
		  
		
	      $allquotes .= "<script type='text/javascript'>\n";
	      $allquotes .= "$('ul#quotearea').quote_rotator({ rotation_speed: 8000" . ", pause_on_hover: true, randomize_first_quote: true});\n";
	      $allquotes .= "</script>\n";


	  echo " <script type='text/javascript'>
          $(document).ready(function() { $('ul#quotearea').quote_rotator(); });
          </script>";
	
		
		$allquotes .= "<ul id=\"quotearea\">\n";
		
						// fadeDuration: ".$fade.",
					// fadeoutDuration: ".$fadeout.",
					// delay: ".$delay.",
	  
        foreach($results as $result){
        	$allquotes .= "<li><a href=\"" . get_permalink( $result->ID ) . "\">";
        	
            $allquotes .= "$result->meta_value";
            
            if($result->post_title != '')
			   $allquotes .= " <span id='quoteauthor'>From $result->post_title</span>";
         	$allquotes .= "</a></li>\n";
        }
	   
	    $allquotes .= "</ul>\n";

		
		
		
		
		// addpqHeaderContent();
		
		
		
		// $allquotes .= "Loading Quotes...\n";
		$allquotes .= "</div></div>\n";
		
		return $allquotes;
	}



}
endif;

?>
