<?php 
/*
Plugin Name: Get Pinterest Feed
Description: PinterestのボードをWordPressに表示しよう！
Version: 0.0.2
Author: Takumin
Author URI: http://takumin.ddo.jp
License: GPLv2
Text Domain: GetPinFeed
Domain Path: /languages
*/
class GetPinterestFeed{
	
	public function __construct(){
		$simplepie = ABSPATH.WPINC.'/class-simplepie.php';
		if(file_exists($simplepie)){
			@require_once($simplepie);
		}
		add_shortcode( 'GetPinFeed' , array($this, 'shortcode_main') );
		wp_register_script('imagesloaded',plugin_dir_url( __FILE__ ).'js/jquery.imagesloaded.min.js',array('jquery'),null,true);
		wp_register_script('jquery_masonry',plugin_dir_url( __FILE__ ).'js/jquery.masonry.min.js',array('jquery','imagesloaded'),null,true);
	}/*End of construct*/
	
	public function get_feed($getPintrestUrl=false,$limit=10){
		
		if(!$getPintrestUrl){ return false;}
		if(!class_exists('SimplePie')){ return false;}
		
		$feeds = new SimplePie();
		$feeds->set_feed_url($getPintrestUrl);
		$feeds = $this->check_cache($feeds);
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
	}/*End of get_feed*/
	
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
	}/*End of return_html*/
	
	public function make_html($ImgUrlArray){
		echo $this->return_html($ImgUrlArray);
	}/*End of make_html*/
	
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
	}/*End of return_script*/
	
	public function make_script($options){
	
		echo $this->return_script($options);
	}/*End of make_script*/
	
	public function return_style(){
		return <<<EOS
<style>
.pinterest_wall .item{
	margin:0;
	padding:0;
}
</style>
EOS;
	}/*End of return_style*/
	
	public function make_style(){
		echo $this->return_style();
	}/*End of make_style*/
	
	
	public function make_script_with_option(){
		
		$this->make_script($this->option);
		
	}/*End of make_script_with_option*/
	
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
		
		if(!$exp){ $exp = 'Error by GetPinterestFeed';}
		return $exp;
	}/*End of shortcode_main*/
	
	public function check_cache($feeds){
		if(is_writable(WP_CONTENT_DIR)){
			if(!is_dir(WP_CONTENT_DIR.'/cache/get-pinteresr-feed')){
				$old = umask(0);
				
				if(!is_dir(WP_CONTENT_DIR.'/cache')){
					$flag = @mkdir(WP_CONTENT_DIR.'/cache',0755);
				}else{ //cacheは既に有る
					$flag = true;
				}
				
				if($flag)
					$flag = @mkdir(WP_CONTENT_DIR.'/cache/get-pinteresr-feed',0755);
				
				if($flag){
						$feeds->set_cache_location(WP_CONTENT_DIR.'/cache/get-pinteresr-feed');
				}else{ //キャッシュディレクトリを作れなかった。。
					$feeds->enable_cache(false);
				}
				
				umask($old);
				
			}else if(is_writable(WP_CONTENT_DIR.'/cache/get-pinteresr-feed')){
				$feeds->set_cache_location(WP_CONTENT_DIR.'/cache/get-pinteresr-feed');
				$feeds->enable_cache(true);
			}else{ //何故かajax_feedのキャッシュディレクトリが書き込めないのでキャッシュを諦める
				$feeds->enable_cache(false);
			}
		}else{ //wp_contentに書き込みできなければキャッシュを作らない
			$feeds->enable_cache(false);
		}
		return $feeds;
	}/*End of check_cache*/
	
}/*End of Class GetPinterestFeed*/

$GetPinFeed = new GetPinterestFeed();
?>