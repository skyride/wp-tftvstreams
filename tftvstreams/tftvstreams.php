<?php
  /*
  Plugin Name: TeamFortress.TV Stream Widget
  Plugin URI: teamfortress.tv
  Description: This plugins pulls the teamfortress.tv stream list and displays a widget
  Version: 1.0
  Author: Skyride
  Author URI: skyride.org
  License: Be original, write your own, but if you can't, you got full rights to copy mine for non commercial use.
  */
  
  add_action('widgets_init', 'stream_widgets');

  // Register the sidebar widgit
  function stream_widgets() {
   register_widget('TFTVStreams');
  }
  
  // Actual sidebar widgit starts here
  class TFTVStreams extends WP_Widget {   
 
  public function __construct() {
		// widget actual processes
		parent::__construct(
	 		'TFTVStreams', // Base ID
			'TFTVStreams', // Name
			array( 'description' => __( 'TFTV Stream Widget', 'text_domain' ), ) // Args
		);
	}
 
  // Function that creates the widgit
    function widget($args, $instance) 
	{
		//Its all about the... chaaa ching  cha ching cha ching
		$fname = "wp-content/plugins/tftvstreams/streams.cash";
		
		//Check if file exists
		if(file_exists($fname))
		{
			//Check if file was last edited less than 60 secs ago
			if((time() - filemtime($fname)) > 0)
			{
				//Pull data from tf.tv api
				$xml = file_get_contents("http://teamfortress.tv/rss/streams");
				$xml = simplexml_load_string($xml);
				
				//Parse to HTML
				$streams = '<aside id="tftvstreams" class="widget widget_recent_comments">
				<h3 class="widget-title">TeamFortress.TV Streams</h3>
				<ul>';
				foreach($xml as $stream)
				{
				$streams .= '
					<li><a href="'.$stream->link.'" title="'.$stream->title.'" target="blank" rel="nofollow"> <span style="float: right; ">'.$stream->viewers.' viewers</span>'.htmlspecialchars($stream->name).'</a></li>
';			
				}
				$streams .= "</ul></aside>";
				
				//Write contents
				file_put_contents($fname, $streams);
			}
		} else
		{
			file_put_contents($fname, file_get_contents("http://vanillatf2.org/wp-content/plugins/vtf2streams/vtf2stream_etf2l.php"));
		}
		
		echo file_get_contents($fname);
	}
    
  // Options for the widgit
    function form($instance) {
      
    }
    
    function update($new_instance, $old_instance) {
    }
   
  }
?>