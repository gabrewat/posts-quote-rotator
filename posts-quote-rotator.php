<?php
/*
Plugin Name: Posts Quote Rotator
Plugin URI: http://utopianrealms.org/posts-quote-rotator/
Description: Based on Luke Howell's quote rotator, the posts quote rotator allows you to add the quotes to your site using a shortcode or php snippet in template instead of only as a widget. Also adds flexibility with styling and setting durations.
Version: 0.1.0
Author: Gary Wright
Author URI: http://utopianrealms.org/

---------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
---------------------------------------------------------------------
*/

require_once('classes/pquote-rotator.class.php');
require_once('classes/pquote-rotator-management.class.php');


if( class_exists('PQuoteRotator') && class_exists('PQuoteRotatorManagement') ) :

$pquoteRotator = new PQuoteRotator();
$management = new PQuoteRotatorManagement();

if( isset($pquoteRotator) && isset($management) )
{
	
	function pqwidgetInit()
	{
		global $pquoteRotator, $management;
		
		if( !function_exists('register_sidebar_widget') )
		{
			return;
		}
		
		register_sidebar_widget('Posts Quote Rotator', array(&$pquoteRotator, 'displaypqWidget'));
		register_widget_control('Posts Quote Rotator', array(&$management, 'displaypqWidgetControl'));
	}
	
	function pqmanagementInit()
	{
		global $management;
		
		wp_enqueue_script( 'listman' );
		/* add_management_page('Quotes', 'Quotes', 5, basename(__FILE__), array(&$management, 'displayManagementPage')); */
      add_options_page('Posts Quote Rotator Options', 'Posts Quote Rotator', 10, basename(__FILE__), array(&$management, 'displaypqOptionsPage'));
	}

   function includepqjquery()
   {
      wp_enqueue_script('jquery');   
   }
   	
   // [pquoteRotator [title=""] [delay=""] [fade=""]]
   function pquoteRotator_func($atts) {
   	global $pquoteRotator;

      extract( shortcode_atts( array(
         'title' => '',
         'delay' => '',
         'fade' => '',
         'fadeout' => '',
         ), $atts ) );
      return $pquoteRotator->getPQuoteCode($title, $delay, $fade, $fadeout);
   }
   add_shortcode('pquoteRotator', 'pquoteRotator_func');
   
   
   function pquoteRotator($title=null, $delay=null, $fadeDuration=null, $fadeoutDuration=null) {
      global $pquoteRotator;
      echo $pquoteRotator->getPQuoteCode($title, $delay, $fadeDuration, $fadeoutDuration);
   }
   
	add_action('init', 'includepqjquery');
   add_action('wp_head', array(&$pquoteRotator, 'addpqHeaderContent'));
	add_action('admin_menu', 'pqmanagementInit');
	add_action('plugins_loaded', 'pqwidgetInit');
}

endif;
?>
