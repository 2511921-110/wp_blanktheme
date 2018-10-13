<?php
//wp_enqueue_script('jquery');

//管理画面で読み込むCSS
//add_editor_style('css/style.css');

//headerで読み込むCSS
if ( !is_admin() ) {
	function add_cssfiles() {
   	// サイト共通のCSSの読み込み
    wp_enqueue_style( 'cssflame', get_stylesheet_directory_uri() . '/css/bootstrap-grid.css', '20180830', true );
    wp_enqueue_style( 'allstyle', get_stylesheet_directory_uri() . '/css/style.css' , '20180830', true);
    wp_enqueue_style( 'addstyle', get_stylesheet_directory_uri() . '/style.css' , '20180830', true);
  	//wp_enqueue_style( 'motostyle', get_stylesheet_directory_uri() . '/style.css', array('moto_style') );
	}
	function add_jsfiles() {
	  // サイト共通のJSの読み込み
	  //wp_enqueue_script( 'jquery.japan-map', get_stylesheet_directory_uri() . '/js/jquery.japan-map.min.js', array(), '1.0.0', true );
	}
	add_action( 'wp_enqueue_scripts', 'add_cssfiles' );
	add_action( 'wp_enqueue_scripts', 'add_jsfiles' );
}

//コンテンツのimgパスを置き換える
function imagepassshort($arg) {
$content = str_replace('"assets/', '"' . get_bloginfo('template_directory') . '/assets/', $arg);
return $content;
}
add_action('the_content', 'imagepassshort');


//コンテンツ内のリンクパスを置き換える
function urlpassshort($arg) {
$content = str_replace('"home_url/', '"' . get_bloginfo('siteurl') . '/', $arg);
return $content;
}
add_action('the_content', 'urlpassshort');

//ショートコードで投稿を取得する
/*
使い方は投稿の編集画面内で
[showcatposts cat="1" show="3"]
とする。
*/
function show_Cat_Posts_func($atts) {
    global $post;
    $output = "";

    extract(shortcode_atts(array(
        'cat' => 1, // デフォルトカテゴリーID = 1
        'show' => 3 // デフォルト表示件数 = 3
    ), $atts));

    $cat = rtrim($cat, ",");
    // get_postsで指定カテゴリーの記事を指定件数取得
    $args = array(
        'cat' => $cat,
        'posts_per_page' => $show
    );
    $my_posts = get_posts($args);

    // 上記条件の投稿があるなら$outputに出力、マークアップはお好みで
    if ($my_posts) {
        // カテゴリーを配列に
        $cat = explode(",", $cat);
        $catnames = "";
        foreach ($cat as $catID) : // カテゴリー名取得ループ
            $catnames .= get_the_category_by_ID($catID).", ";
        endforeach;
        $catnames = rtrim($catnames, ", ");

        /*$output .= '<aside class="showcatposts">'."\n";
        $output .= '<h2 class="showcatposts-title">カテゴリー「'.$catnames.'」'."の最新記事（".$show."件）</h2>\n";*/
        $output .= '<div class="post post1 mb30">'."\n";
        foreach ($my_posts as $post) : // ループスタート
            setup_postdata($post); // get_the_title() などのテンプレートタグを使えるようにする
            $output .= '<div id="post-'.get_the_ID().'" '.get_post_class().' class="post-item"><div class="post-date">' . get_the_date() . '</div><a href="'.get_permalink().'"><div class="post-title">'.get_the_title()."</a></div></div>\n";
        endforeach; // ループ終わり
        $output .= "</div>\n";
        //$output .= "</aside>\n";
    }
    // クエリのリセット
    wp_reset_postdata();
    return $output;
}
add_shortcode('showcatposts', 'show_Cat_Posts_func');

//headerの不要なタグを消す
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head');
// Since 4.4
remove_action('wp_head','wp_oembed_add_discovery_links');
remove_action('wp_head','rest_output_link_wp_head');
//add_filter( 'author_rewrite_rules', '__return_empty_arr


//Pagenation
function pagination($pages = '', $range = 2)
{
     $showitems = ($range * 2)+1;//表示するページ数（５ページを表示）

     global $paged;//現在のページ値
     if(empty($paged)) $paged = 1;//デフォルトのページ

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;//全ページ数を取得
         if(!$pages)//全ページ数が空の場合は、１とする
         {
             $pages = 1;
         }
     }

     if(1 != $pages)//全ページが１でない場合はページネーションを表示する
     {
		 echo "<div class=\"pagenation\">\n";
		 echo "<ul>\n";
		 //Prev：現在のページ値が１より大きい場合は表示
         if($paged > 1) echo "<li class=\"prev\"><a href='".get_pagenum_link($paged - 1)."'>Prev</a></li>\n";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                //三項演算子での条件分岐
                echo ($paged == $i)? "<li class=\"active\">".$i."</li>\n":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>\n";
             }
         }
		//Next：総ページ数より現在のページ値が小さい場合は表示
		if ($paged < $pages) echo "<li class=\"next\"><a href=\"".get_pagenum_link($paged + 1)."\">Next</a></li>\n";
		echo "</ul>\n";
		echo "</div>\n";
     }
}

//スマホ表示分岐
function is_mobile(){
  $useragents = array(
    'iPhone', // iPhone
    'iPod', // iPod touch
    'Android.*Mobile', // 1.5+ Android *** Only mobile
    'Windows.*Phone', // *** Windows Phone
    'dream', // Pre 1.5 Android
    'CUPCAKE', // 1.5+ Android
    'blackberry9500', // Storm
    'blackberry9530', // Storm
    'blackberry9520', // Storm v2
    'blackberry9550', // Storm v2
    'blackberry9800', // Torch
    'webOS', // Palm Pre Experimental
    'incognito', // Other iPhone browser
    'webmate' // Other iPhone browser
  );
  $pattern = '/'.implode('|', $useragents).'/i';
  return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}

register_nav_menus();
register_sidebar();
add_theme_support('post-thumbnails');
//remove_filter('the_content', 'wpautop');
//remove_filter('the_excerpt', 'wpautop');


//TinyMCE Advanceにスタイルを追加
function plugin_mce_css( $mce_css ) {
    if ( ! empty( $mce_css ) )
        $mce_css .= ',';

    $font_url = get_stylesheet_directory_uri().'/editor-style.css';
    $mce_css .= str_replace( ',', '%2C', $font_url );

    return $mce_css;
}
add_filter( 'mce_css', 'plugin_mce_css' );
//add_editor_style('editor-style.css');
function my_mce4_options( $init ) {
$default_colors = '
  "000000", "Black",
  "993300", "Burnt orange",
  "333300", "Dark olive",
  "003300", "Dark green",
  "003366", "Dark azure",
  "000080", "Navy Blue",
  "333399", "Indigo",
  "333333", "Very dark gray",
  "800000", "Maroon",
  "FF6600", "Orange",
  "808000", "Olive",
  "008000", "Green",
  "008080", "Teal",
  "0000FF", "Blue",
  "666699", "Grayish blue",
  "808080", "Gray",
  "FF0000", "Red",
  "FF9900", "Amber",
  "99CC00", "Yellow green",
  "339966", "Sea green",
  "33CCCC", "Turquoise",
  "3366FF", "Royal blue",
  "800080", "Purple",
  "999999", "Medium gray",
  "FF00FF", "Magenta",
  "FFCC00", "Gold",
  "FFFF00", "Yellow",
  "00FF00", "Lime",
  "00FFFF", "Aqua",
  "00CCFF", "Sky blue",
  "993366", "Brown",
  "C0C0C0", "Silver",
  "FF99CC", "Pink",
  "FFCC99", "Peach",
  "FFFF99", "Light yellow",
  "CCFFCC", "Pale green",
  "CCFFFF", "Pale cyan",
  "99CCFF", "Light sky blue",
  "CC99FF", "Plum",
  "FFFFFF", "White"
  ';
$custom_colors = '
  "ce2c17", "独自red",
  "3c258d", "独自blue",
  "ff5c86", "独自pink",
  "ef3e00", "独自オレンジ",
  "59ab95", "独自グリーン",
  "48a722", "独自yグリーン",
  "00d96e", "独自Eグリーン",
  "2eacad", "独自mグリーン",
  "69c0f6", "独自skyblue",
  "624525", "brown"
  ';
$style_formats = array(
  array(
    'title' => '余白無し',
    'block' => 'p',
    'classes' => 'mb0'
  ),
  array(
    'title' => '太文字',
    'inline' => 'b'
  ),
);
$init['style_formats'] = json_encode($style_formats);
$init['textcolor_map'] = '['.$default_colors.','.$custom_colors.']';
$init['textcolor_rows'] = 6; // 色を最大何行まで表示させるか
$init['textcolor_cols'] = 10; // 色を最大何列まで表示させるか
$init['fontsize_formats'] = '10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 32px 33px 35px 38px 40px 46px';

$init['body_class'] = 'editor-area';

return $init;
}
add_filter('tiny_mce_before_init', 'my_mce4_options');


/*
* shortcodeがpタグに囲まれるfix
*/
function shortcode_empty_paragraph_fix($content) {
  $array = array (
    '<p>[' => '[',
    ']</p>' => ']',
    ']<br />' => ']'
  );
  $content = strtr($content, $array);
  return $content;
}


//カテゴリーの順番が変わらないようにする
function solecolor_wp_terms_checklist_args( $args, $post_id ){
  if ( $args['checked_ontop'] !== false ){
    $args['checked_ontop'] = false;
  }
  return $args;
}
add_filter('wp_terms_checklist_args', 'solecolor_wp_terms_checklist_args',10,2);


//Rest apiへ追加する
//カテゴリ名を取得する関数を登録
add_action( 'rest_api_init', 'register_category_name' );

function register_category_name() {
//register_rest_field関数を用いget_category_name関数からカテゴリ名を取得し、追加する
  register_rest_field( 'post',
    'category_name',
    array(
      'get_callback'    => 'get_category_name'
    )
  );
}

//$objectは現在の投稿の詳細データが入る
function get_category_name( $object ) {
  $category = get_the_category($object[ 'id' ]);
  $cat_name = $category[0]->cat_name;
  return $cat_name;
}

//タグ名を取得する関数を登録
add_action( 'rest_api_init', 'register_tag_name' );

function register_tag_name() {
//register_rest_field関数を用いget_tag_name関数からカテゴリ名を取得し、追加する
  register_rest_field( 'post',
    'tag_name',
    array(
      'get_callback'    => 'get_tag_name'
    )
  );
}

//$objectは現在の投稿の詳細データが入る
function get_tag_name( $object ) {
  $tag = get_the_tags($object[ 'id' ]);
  $tag_name = $tag;
  return $tag_name;
}