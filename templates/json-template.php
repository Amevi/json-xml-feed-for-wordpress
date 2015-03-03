<?php
global $post, $posts;
// encode images url in correct format
function jxf_encodeImage($url)
{
				$path_parts = pathinfo($url);
				$filename   = $path_parts['basename'];
				//echo urlencode($filename);
				return str_replace($filename, '', $url) . urlencode($filename);
}
function jxf_getJsonImage($num)
{
				$first_img = '';
				ob_start();
				ob_end_clean();
				$output    = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
				$first_img = $matches[1][0];
				return $first_img;
}
$callback = trim(esc_html(get_query_var('callback')));
$charset  = get_option('charset');
$posts    = query_posts('showposts=' . $json_feed_number . '&cat=-1');
if (have_posts()) {
				//global $wp_query;
				$query_array = $wp_query->query;
				// Make sure query args are always in the same order
				if (is_array($query_array)) {
								ksort($query_array);
				}
				$json = array();
				$i    = 1;
				if (have_posts()):
?>
	<?php
								while (have_posts()):
												the_post();
												$id  = (int) $post->ID;
												$url = jxf_getJsonImage(1);
												if ($url== '') {
																$url = get_post_meta($post->ID, "thumb", true);
												}
												$retina        = false;
												// $category_name = wp_get_object_terms($id, "category", array(
																// 'fields' => 'names'
												// ));
												// $category_slug = wp_get_object_terms($id, "category", array(
																// 'fields' => 'ids'
												// ));
												
												$categories = get_the_category();
												
												$single        = array(
																'id' => $id,
																'title' => html_entity_decode(get_the_title()),
																'link' => get_permalink(),
																'content' => get_the_content(),
																'description' => get_the_excerpt(),
																'author' => get_the_author(),
																'pubDate' => get_the_date('Y-m-d H:i:s', '', '', false),
																'image' => jxf_encodeImage($url),
																'category'    => $categories[0]->cat_name,
																'categorySlug'    => $categories[0]->slug
												);
												// thumbnail
												if (function_exists('has_post_thumbnail') && has_post_thumbnail($id)) {
																$single["thumbnail"] = preg_replace("/^.*['\"](https?:\/\/[^'\"]*)['\"].*/i", "$1", get_the_post_thumbnail($id));
												}
												// tags
												$single["tags"] = array();
												$tags           = get_the_tags();
												if (!empty($tags)) {
																$single["tags"] = wp_list_pluck($tags, 'name');
												}
												$json[] = $single;
								endwhile; // else:
								
				//end posts loop
				endif;
				$wp_query = null;
				$wp_query = $temp; // Reset 
				//$json     = json_encode($json, JSON_UNESCAPED_UNICODE); //dealing with utf8 issue (special character)
				$json = json_encode($json);
				//$json =  "{\"posts\": ".  json_encode($json). "\n}";
				nocache_headers();
				if (!empty($callback)) {
								header("Content-Type: application/x-javascript; charset=utf-8");
								echo "{$callback}({$json});";
				} else {
								header("Content-Type: application/json; charset=utf-8");
								echo $json;
				}
} else {
				status_header('404');
				wp_die("404 Not Found");
}
