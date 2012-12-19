<?php 
/*
Plugin Name: Get Pinterest Feed
Description: PinterestのボードをWordPressに表示しよう！
Version: 0.0.1
Author: Taumin
Author URI: http://takumin.ddo.jp
License: GPLv2
Text Domain: GetPinFeed
Domain Path: /languages
*/
	
	require_once('autoloader.php');
	
	class GetPinterestFeed{
		
		public function __construct(){
			add_shortcode( 'GetPinFeed' , array($this, 'shortcode_main') );
			wp_register_script('imagesloaded',plugin_dir_url( __FILE__ ).'js/jquery.imagesloaded.min.js',array('jquery'),null,true);
			wp_register_script('jquery_masonry',plugin_dir_url( __FILE__ ).'js/jquery.masonry.min.js',array('jquery','imagesloaded'),null,true);
		}
		
		public function get_feed($getPintrestUrl=false,$limit=10){
			
			if(!$getPintrestUrl){ return false;}
			
			$feeds = new SimplePie();
			$feeds->set_feed_url($getPintrestUrl);
			$feeds->enable_cache(false);
			//$feeds->force_feed(true);
			$feeds->init();
			
			$feeds->handle_content_type();
			$feedItems=$feeds->get_items(0,$limit);
			
			foreach($feedItems as $item){
				$link = $item->get_link();
				$descri = $item->get_description();
				
				preg_match('/<img src="(.+)">/',$descri,$mache);
				$extImgUrlArray[] = array($mache[1],$link);
			}
			return($extImgUrlArray);
		}
		
		public function return_html($ImgUrlArray){
			if(!$ImgUrlArray || !is_array($ImgUrlArray)){ return false;}
			$ret =  '<div class="pinterest_wall">';
				foreach($ImgUrlArray as $src){
					$ret .= '<div class="item">';
						$ret .= '<a href="'.$src[1].'"><img src="'.$src[0].'"></a>';
					$ret .= '</div>';
				}
			$ret .= '</div>';
			return $ret;
		}
		
		public function make_html($ImgUrlArray){
			echo $this->return_html($ImgUrlArray);
		}
		
		public function return_script($options){
			
			$columnWidth = $options['columnWidth'];
			$gutterWidth = $options['gutterWidth'];
			return <<<EOS
<script>
	jQuery(document).ready(function($){
		var container = jQuery('.pinterest_wall');
		container.imagesLoaded( function(){
			container.masonry({
				itemSelector : '.item',
				columnWidth : $columnWidth,
				gutterWidth : $gutterWidth,
			});
		});
	});
</script>
EOS;
		}
		
		public function make_script($options){
		
			echo $this->return_script($options);
		}
		
		public function return_style(){
			return <<<EOS
<style>
	.pinterest_wall .item{
		margin:0;
		padding:0;
	}
</style>
EOS;
		}
		
		public function make_style(){
			echo $this->return_style();
		}
		
		
		public function make_script_with_option(){
			
			$this->make_script($this->option);
			
		}
		
		public function shortcode_main($atts){
			extract(shortcode_atts(array(
				'url' => false,
				'limit' => 10,
				'columnwidth' => 200,
				'gutterwidth' => 9,
			), $atts));
			$exp = null;
			wp_enqueue_script('imagesloaded');
			wp_enqueue_script('jquery_masonry');
			
			$this->option = array(
				'columnWidth' => $columnwidth,
				'gutterWidth' => $gutterwidth,
			);
			
			if($url){
				//$exp  = $this->return_style();
				$exp .= $this->return_html($this->get_feed($url,$limit));
				add_action('wp_print_footer_scripts',array($this, 'make_script_with_option'));
			}
			
			if(!$exp){ $exp = 'エラー by GetPinterestFeed';}
			return $exp;
		}
	}
	
	$GetPinFeed = new GetPinterestFeed();
?>