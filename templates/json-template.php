<?php
function getJsonImage($num) {

	global $more;
	$more = 1;
	$content = get_the_content();
	$count = substr_count($content, '<img');
	$start = 0;
	for($i=1;$i<=$count;$i++) {
	$imgBeg = strpos($content, '<img', $start);
	$post = substr($content, $imgBeg);
	$imgEnd = strpos($post, '>');
	$postOutput = substr($post, 0, $imgEnd+1);
	$image[$i] = $postOutput;
	$start=$imgEnd+1;  
	 
	$cleanF = strpos($image[$num],'src="')+5;
	$cleanB = strpos($image[$num],'"',$cleanF)-$cleanF;
	$imgThumb = substr($image[$num],$cleanF,$cleanB);
	 
}
if(stristr($image[$num],'<img')) {
	
	 return $imgThumb; 
}
$more = 0;
}

$callback = trim(esc_html(get_query_var('callback')));
$charset  = get_option('charset');

	$temp = $wp_query;
	$wp_query= null;
	$wp_query = new WP_Query();

	$wp_query->query("showposts=$json_feed_number"."&paged=".$paged); 

if ( $wp_query->have_posts()) {
	?>
	<?php 
	  
	while ($wp_query ->have_posts()):
	// $post = $do_not_duplicate;
	$wp_query->the_post();						
		$id = (int) $post->ID;
		$url = getJsonImage(1);
	
		if(!isset($url))
			{
				$url = get_post_meta($post->ID,"thumb",true);
			}
	
		$retina = false;                   
		//$image = matthewruddy_image_resize( $url, 300, 200, true, $retina ); 

		$category_name = wp_get_object_terms( $id, "category", array( 'fields' => 'names' ) );
		$category_slug = wp_get_object_terms( $id, "category", array( 'fields' => 'ids' ) );
		$single = array(
			'id'        => $id ,
			'title'     => get_the_title() ,
            'link' => get_permalink(),
            'content'   => get_the_content(),
            'description'   => get_the_excerpt(),
			'author'    => get_the_author() ,
			'pubDate'      => get_the_date('Y-m-d H:i:s','','',false) ,
			'image'    => $url,
			//'category'    => $category[0]->cat_name
			'category'    => $category_name[0],
			'categorySlug'    => $category_slug[0]
			);

		// thumbnail
		if (function_exists('has_post_thumbnail') && has_post_thumbnail($id)) {
			$single["thumbnail"] = preg_replace("/^.*['\"](https?:\/\/[^'\"]*)['\"].*/i","$1",get_the_post_thumbnail($id));
		}




		// tags
		$single["tags"] = array();
		$tags = get_the_tags();
		if ( ! empty( $tags) ) {
			$single["tags"] = wp_list_pluck( $tags, 'name' );
		}

		$json[] = $single;
	endwhile;// else:
	//end posts loop
	$wp_query = null; $wp_query = $temp;  // Reset 

	$json = json_encode($json);
	//$json =  "{\"posts\": ".  json_encode($json). "\n}";

	nocache_headers();
	if (!empty($callback)) {
		header("Content-Type: application/x-javascript; charset={$charset}");
		echo "{$callback}({$json});";
	} else {
		header("Content-Type: application/json; charset={$charset}");
		echo $json;
		//print_r($json);
	}

} else {
	status_header('404');
	wp_die("404 Not Found");
}