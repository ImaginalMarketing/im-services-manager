<?php
/*
Plugin Name: IM Services Manager
Plugin URI: https://github.com/ImaginalMarketing/im-services-manager/
Version: 1.0.1
Author: Michael Milstead
Description: A simple <em>Services</em> custom post type with categories and custom meta. Use the shortcode <code>[im-services category="sample-category" location="sample-location"]</code> to output basic tables with prices and descriptions. Tables can be targeted by any combination of a <code>#imst-sample-category</code> ID and <code>.im_service_table</code> class for easy CSS styling. Supports basic text descriptions for posts as well as categories. This plugin is currently dependent on the "Taxonomy Meta" Plugin which was included with this package.
GitHub Plugin URI: https://github.com/ImaginalMarketing/im-services-manager/

*/


require_once( 'BFIGitHubPluginUploader.php' );
if ( is_admin() ) {
    new BFIGitHubPluginUpdater( __FILE__, 'ImaginalMarketing', "im-services-manager" );
}




///// A D D   C S S   T O   H E A D E R 
////////////////////////////

function wptuts_styles_with_the_lot()
{
    // Register the style like this for a plugin:
    wp_register_style( 'service-styles', plugins_url( '/css/styles.css', __FILE__ ), array(), '1.0', 'all' );
    // or
    // Register the style like this for a theme:
    wp_register_style( 'service-styles', get_template_directory_uri() . '/css/styles.css', array(), '1.0', 'all' );
 
    // For either a plugin or a theme, you can then enqueue the style:
    wp_enqueue_style( 'service-styles' );
}
add_action( 'wp_enqueue_scripts', 'wptuts_styles_with_the_lot' );


///// P O S T   T Y P E
///// + T A X O N O M Y
////////////////////////////


function services_post_type() {
	$labels = array(
		'name'                => _x( 'Services', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Services', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Service:', 'text_domain' ),
		'all_items'           => __( 'All Services', 'text_domain' ),
		'view_item'           => __( 'View Service', 'text_domain' ),
		'add_new_item'        => __( 'Add New Service', 'text_domain' ),
		'add_new'             => __( 'Add New Service', 'text_domain' ),
		'edit_item'           => __( 'Edit Service', 'text_domain' ),
		'update_item'         => __( 'Update Service', 'text_domain' ),
		'search_items'        => __( 'Search Services', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$args = array(
		'label'               => __( 'service', 'text_domain' ),
		'description'         => __( 'Service and category management', 'text_domain' ),
		'labels'              => $labels,
		'menu_icon'           => 'dashicons-hammer',
		'supports'            => array( 'title', 'page-attributes' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
     	'exclude_from_search' => true
	);
	register_post_type( 'service', $args );
}
add_action( 'init', 'services_post_type', 0 );

// custom taxonomy
add_action( 'init', 'create_servecat_hierarchical_taxonomy', 0 );
function create_servecat_hierarchical_taxonomy() {
  $labels = array(
    'name' => _x( 'Service Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Service Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Service Categories' ),
    'all_items' => __( 'All Service Categories' ),
    'parent_item' => __( 'Parent Service Category' ),
    'parent_item_colon' => __( 'Parent Service Category:' ),
    'edit_item' => __( 'Edit Service Category' ), 
    'update_item' => __( 'Update Service Category' ),
    'add_new_item' => __( 'Add New Service Category' ),
    'new_item_name' => __( 'New Service Category Name' ),
    'menu_name' => __( 'Manage Service Categories' ),
  ); 	
  register_taxonomy('servicecategories',array('service'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'servicecategories' ),
  ));
}
// custom taxonomy
add_action( 'init', 'create_serveloc_hierarchical_taxonomy', 0 );
function create_serveloc_hierarchical_taxonomy() {
  $labels = array(
    'name' => _x( 'Shop Locations', 'taxonomy general name' ),
    'singular_name' => _x( 'Shop Location', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Shop Locations' ),
    'all_items' => __( 'All Shop Locations' ),
    'parent_item' => __( 'Parent Shop Location' ),
    'parent_item_colon' => __( 'Parent Shop Location:' ),
    'edit_item' => __( 'Edit Shop Location' ), 
    'update_item' => __( 'Update Shop Location' ),
    'add_new_item' => __( 'Add New Shop Location' ),
    'new_item_name' => __( 'New Shop Location Name' ),
    'menu_name' => __( 'Manage Shop Locations' ),
  ); 	
  register_taxonomy('servicelocations',array('service'), array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'servicelocations' ),
  ));
}




///// M E T A   B O X E S
////////////////////////////
// Read these sometime so we don't have to rely on a plugin:
// http://code.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-1-intro-and-basic-fields--wp-23259
// http://www.wpexplorer.com/creating-highly-customized-post-types-with-custom-meta-boxes/

require 'taxonomy-meta.php';
// from: http://www.deluxeblogtips.com/taxonomy-meta-script-for-wordpress/
add_action( 'admin_init', 'im_register_taxonomy_meta_boxes' );
/**
 * Register meta boxes
 *
 * @return void
 */
function im_register_taxonomy_meta_boxes()
{
    // Make sure there's no errors when the plugin is deactivated or during upgrade
    if ( !class_exists( 'RW_Taxonomy_Meta' ) )
        return;
    $meta_sections = array();
    // First meta section
    $meta_sections[] = array(
        'title'      => 'Service Tiers',             // section title
        'taxonomies' => array('servicecategories', 'servicelocations'), // list of taxonomies. Default is array('category', 'post_tag'). Optional
        'id'         => 'service_tiers',                 // ID of each section, will be the option name
        'fields' => array(                             // List of meta fields
            // TEXT
            array(
                'name' => 'Hide category header?',                      // field name
                'id'   => 'hide_head',                      // field id, i.e. the meta key
                'type' => 'checkbox',                      // field type
            ),
            array(
                'name' => 'Level 1',                      // field name
                'desc' => 'Simple text field',         // field description, optional
                'id'   => 'service_tier1',                      // field id, i.e. the meta key
                'type' => 'text',                      // field type
                'std'  => '',                      // default value, optional
            ),
            array(
                'name' => 'Level 2',                      // field name
                'desc' => 'Simple text field',         // field description, optional
                'id'   => 'service_tier2',                      // field id, i.e. the meta key
                'type' => 'text',                      // field type
                'std'  => '',                      // default value, optional
            ),
            array(
                'name' => 'Level 3',                      // field name
                'desc' => 'Simple text field',         // field description, optional
                'id'   => 'service_tier3',                      // field id, i.e. the meta key
                'type' => 'text',                      // field type
                'std'  => '',                      // default value, optional
            ),
            array(
                'name' => 'Level 4',                      // field name
                'desc' => 'Simple text field',         // field description, optional
                'id'   => 'service_tier4',                      // field id, i.e. the meta key
                'type' => 'text',                      // field type
                'std'  => '',                      // default value, optional
            ),
            array(
                'name' => 'Level 5',                      // field name
                'desc' => 'Simple text field',         // field description, optional
                'id'   => 'service_tier5',                      // field id, i.e. the meta key
                'type' => 'text',                      // field type
                'std'  => '',                      // default value, optional
            ),
        ),
    );
    foreach ( $meta_sections as $meta_section )
    {
        new RW_Taxonomy_Meta( $meta_section );
    }
}



// Define tier details
function add_servicedetails_meta_box() {
    add_meta_box(
        'servicedetails_meta_box', // $id
        'Enter Service Details', // $title
        'show_servicedetails_meta_box', // $callback
        'service', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'add_servicedetails_meta_box');

function show_servicedetails_meta_box() {
    global $post;  
    $service_price1 = get_post_meta($post->ID, 'service_price1', true);  
    $service_price2 = get_post_meta($post->ID, 'service_price2', true);  
    $service_price3 = get_post_meta($post->ID, 'service_price3', true);  
    $service_price4 = get_post_meta($post->ID, 'service_price4', true);  
    $service_price5 = get_post_meta($post->ID, 'service_price5', true);  
    $service_description = get_post_meta($post->ID, 'service_description', true);  
    // Use nonce for verification  
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
    echo '<table class="form-table">';   
        // begin a table row with
        echo '
            <tr>
            <th><label for="service_price1">Level 1 Price</label></th>
            <td><input name="service_price1" id="service_price1" value="'.$service_price1.'" size="20" /><br/>
                <span class="description">e.g. "$12.00", "Call for details", so on and so forth.</span></td>
            </tr>
            <tr>
            <th><label for="service_price2">Level 2 Price</label></th>
            <td><input name="service_price2" id="service_price2" value="'.$service_price2.'" size="20" /></td>
            </tr>
            <tr>
            <th><label for="service_price3">Level 3 Price</label></th>
            <td><input name="service_price3" id="service_price3" value="'.$service_price3.'" size="20" /></td>
            </tr>
            <tr>
            <th><label for="service_price4">Level 4 Price</label></th>
            <td><input name="service_price4" id="service_price4" value="'.$service_price4.'" size="20" /></td>
            </tr>
            <tr>
            <th><label for="service_price5">Level 5 Price</label></th>
            <td><input name="service_price5" id="service_price5" value="'.$service_price5.'" size="20" /></td>
            </tr>
            <tr>
            <th><label for="service_description">Description</label></th>
            <td><textarea name="service_description" id="service_description" cols="60" rows="4">'.$service_description.'</textarea><br/> 
                <span class="description">A description for this service, if you\'re so inclined.</span></td>
            </tr>';
    echo '</table>';
}

// Save the metas' data
function save_service_meta($post_id) {   
    // verify nonce
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('service' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }  
    $price1old = get_post_meta($post_id, "service_price1", true);
    $pricenew = $_POST["service_price1"];
    if ($pricenew && $pricenew != $price1old) {
        update_post_meta($post_id, "service_price1", $pricenew);
    } elseif ('' == $pricenew && $price1old) {
        delete_post_meta($post_id, "service_price1", $price1old);
    }
    $price2old = get_post_meta($post_id, "service_price2", true);
    $pricenew = $_POST["service_price2"];
    if ($pricenew && $pricenew != $price2old) {
        update_post_meta($post_id, "service_price2", $pricenew);
    } elseif ('' == $pricenew && $price2old) {
        delete_post_meta($post_id, "service_price2", $price2old);
    }
    $price3old = get_post_meta($post_id, "service_price3", true);
    $pricenew = $_POST["service_price3"];
    if ($pricenew && $pricenew != $price3old) {
        update_post_meta($post_id, "service_price3", $pricenew);
    } elseif ('' == $pricenew && $price3old) {
        delete_post_meta($post_id, "service_price3", $price3old);
    }
    $price4old = get_post_meta($post_id, "service_price4", true);
    $pricenew = $_POST["service_price4"];
    if ($pricenew && $pricenew != $price4old) {
        update_post_meta($post_id, "service_price4", $pricenew);
    } elseif ('' == $pricenew && $price4old) {
        delete_post_meta($post_id, "service_price4", $price4old);
    }
    $price5old = get_post_meta($post_id, "service_price5", true);
    $pricenew = $_POST["service_price5"];
    if ($pricenew && $pricenew != $price5old) {
        update_post_meta($post_id, "service_price5", $pricenew);
    } elseif ('' == $pricenew && $price5old) {
        delete_post_meta($post_id, "service_price5", $price5old);
    }
    $descold = get_post_meta($post_id, "service_description", true);
    $descnew = $_POST["service_description"];
    if ($descnew && $descnew != $descold) {
        update_post_meta($post_id, "service_description", $descnew);
    } elseif ('' == $descnew && $descold) {
        delete_post_meta($post_id, "service_description", $descold);
    }
}
add_action('save_post', 'save_service_meta');





///// S H O R T C O D E
////////////////////////////
function wp_services_list($atts){
    // paramater to grab category id input from the user
    extract(shortcode_atts(array(
        'category' => NULL,
        'location' => NULL,
    ), $atts));
    // are there locations? if not, ignore that taxonomy or else you get blank tables
    if (isset($location)) {
        // gather posts into array
        $args = array(
            'post_type' => 'service',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'tax_query'=>array(array('taxonomy'=>'servicelocations',
                        'field'=>'slug',
                        'terms'=>$location
                        ),
                        array('taxonomy'=>'servicecategories',
                        'field'=>'slug',
                        'terms'=>$category
                        ))
            );
    } else {
        // gather posts into array
        $args = array(
            'post_type' => 'service',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'tax_query'=>array(array('taxonomy'=>'servicecategories',
                        'field'=>'slug',
                        'terms'=>$category
                        ))
            );
    }
    $postArray = get_posts( $args );
    // get current category
    $getservices = query_posts(array('post_type' => 'service', 'taxonomy' => 'servicecategories', 'term' => $category, 'orderby' => 'menu_order', 'order' => 'ASC','posts_per_page' => -1));
    global $post;
    foreach ($getservices as $post) {
        $terms = get_the_terms( $post->ID, 'servicecategories' ) ;
        if ( $terms && ! is_wp_error( $terms ) ) : 
            $service_cats = array();
            foreach ( $terms as $term ) {
                $service_category['name'] = $term->name;
                $service_category['description'] = $term->description;
                $service_category['id'] = $term->term_id;
            }
        endif;
    }

    
    $meta = get_option('service_tiers');
	if (empty($meta)) $meta = array();
	if (!is_array($meta)) $meta = (array) $meta;
	$meta = isset($meta[$service_category['id']]) ? $meta[$service_category['id']] : array();
	$tier1 = $meta['service_tier1'];
	$tier2 = $meta['service_tier2'];
	$tier3 = $meta['service_tier3'];
	$tier4 = $meta['service_tier4'];
	$tier5 = $meta['service_tier5'];
    if (isset($meta['hide_head'])) { // hide thead?
        $hide_head = ' style="display: none";';
    } else { $hide_head = ''; }
	echo $value; // if you want to show

    // let's start displaying stuff
    // use concatenated returns instead of echo to make shortcode output in correct/expected spots on page
    // $num_col will help count the columns and add blank cells in case different items have a different number of levels
    $returnString = '<table id="imst-'.$category.'" class="table im_service_table" width="100%">
            <thead'.$hide_head.'>
                <tr>
                    <th><h3>'.$service_category['name'].'</h3>';
                    if (strlen($service_category['description']) > 0) {
                        $returnString .= '<p>'.$service_category['description'].'</p></th>';
                    }
                        
                    // if you know the # of columns, create them as categories in WP anyway, else fields might display weird since thead can't detect tbody cells entry  below here
                    if (strlen($tier1) > 0) {
                    	$returnString .= '<th width="15%">'.$tier1.'</th>';
                    } else { $returnString .= '<th colspan="4"></th>'; }
                    if (strlen($tier2) > 0) {
                    	$returnString .= '<th width="15%">'.$tier2.'</th>';
                    } else { $returnString .= ''; }
                    if (strlen($tier3) > 0) {
                    	$returnString .= '<th width="15%">'.$tier3.'</th>';
                    } else { $returnString .= ''; }
                    if (strlen($tier4) > 0) {
                    	$returnString .= '<th width="15%">'.$tier4.'</th>';
                    } else { $returnString .= ''; }
                    if (strlen($tier5) > 0) {
                    	$returnString .= '<th width="15%">'.$tier5.'</th>';
                    } else { $returnString .= ''; }
                $returnString .= '</tr>
            </thead>
            <tbody>';
    wp_reset_query();
    // print each service line
    foreach($postArray as $post) {
        $service_title = $post->post_title;
        $service_price1 = get_post_meta($post->ID, 'service_price1', true); 
        $service_price2 = get_post_meta($post->ID, 'service_price2', true); 
        $service_price3 = get_post_meta($post->ID, 'service_price3', true); 
        $service_price4 = get_post_meta($post->ID, 'service_price4', true); 
        $service_price5 = get_post_meta($post->ID, 'service_price5', true); 
        $service_description = get_post_meta($post->ID, 'service_description', true);
        // this 'if' is just for styling -- if there's a desc, hide the tr's bottom border
        if (strlen($service_description) > 0) {
            $returnString .= '<tr class="has_desc">';
        } else { $returnString .= '<tr>'; }
        $returnString .= '<td class="serv_title">'.$service_title.'</td>';
                    // compensate for uneven columns during output
                    if (strlen($service_price5) > 0) {
                        $price_col5 = '<td class="serv_price" width="15%">'.$service_price5.'</td>';
                        $price_col4 = '<td></td>';
                        $price_col3 = '<td></td>';
                        $price_col2 = '<td></td>';
                        $price_col1 = '<td></td>';
                    } elseif (strlen($tier5) > 0) { $price_col5 = '<td></td>'; }

                    if (strlen($service_price4) > 0) {
                        $price_col4 = '<td class="serv_price" width="15%">'.$service_price4.'</td>';
                        $price_col3 = '<td></td>';
                        $price_col2 = '<td></td>';
                        $price_col1 = '<td></td>';
                    } elseif (strlen($tier4) > 0) { $price_col4 = '<td></td>'; }

                    if (strlen($service_price3) > 0) {
                        $price_col3 = '<td class="serv_price" width="15%">'.$service_price3.'</td>';
                        $price_col2 = '<td></td>';
                        $price_col1 = '<td></td>';
                    } elseif (strlen($tier3) > 0) { $price_col3 = '<td></td>'; }

                    if (strlen($service_price2) > 0) {
                        $price_col2 = '<td class="serv_price" width="15%">'.$service_price2.'</td>';
                        $price_col1 = '<td></td>';
                    } elseif (strlen($tier2) > 0) { $price_col2 = '<td></td>'; }

                    if (strlen($service_price1) > 0) {
                        $price_col1 = '<td class="serv_price" width="15%">'.$service_price1.'</td>';
                    } elseif (strlen($tier1) > 0) { $price_col1 = '<td></td>'; }
                    $returnString .= $price_col1.$price_col2.$price_col3.$price_col4.$price_col5;

        $returnString .= '        </tr>';
        if (strlen($service_description) > 0) {
            $returnString .= '<tr class="serv_desc-row"><td class="serv_desc" width="100%" colspan="5">'.$service_description.'</td></tr>';
        }
    }
    $returnString .= '  </tbody>
        </table>';
    // spit it all back out
    return $returnString;
    // just one loop is fine, thanks
    wp_reset_query();
}
add_shortcode('im-services', 'wp_services_list');





///// A D M I N
///// C L E A N U P
////////////////////////////
// delete permalink under wordpress post title
add_filter( 'get_sample_permalink_html', 'wpse_125800_sample_permalink' );
function wpse_125800_sample_permalink( $return ) {

    if( get_post_type() === 'service' ) {
        $return = '';
    }

    return $return;
}
// remove View/Preview buttons
add_filter( 'page_row_actions', 'wpse_125800_row_actions', 10, 2 );
add_filter( 'post_row_actions', 'wpse_125800_row_actions', 10, 2 );
function wpse_125800_row_actions( $actions, $post ) {
    if( get_post_type() === 'service' ) {
        unset( $actions['inline hide-if-no-js'] );
        unset( $actions['view'] );
    }
    return $actions;
}
// Simplify publish box
global $pagenow;
if ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) {
    add_action( 'admin_head', 'wpse_125800_custom_publish_box' );
    function wpse_125800_custom_publish_box() {
        if( !is_admin() )
            return;
        if( get_post_type() === 'service' ) {
            $style = '';
            $style .= '<style type="text/css">';
            $style .= '#edit-slug-box, #minor-publishing-actions, #visibility, .num-revisions, .curtime';
            $style .= '{display: none; }';
            $style .= '</style>';
            echo $style;
        }
    }
}
add_filter( 'post_updated_messages', 'rw_post_updated_messages' );
// Set some custom status messages
function rw_post_updated_messages( $messages ) {
    $post             = get_post();
    $post_type        = get_post_type( $post );
    $post_type_object = get_post_type_object( $post_type );
    $messages['service'] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => __( 'Service item updated.' ),
        2  => __( 'Custom field updated.' ),
        3  => __( 'Custom field deleted.'),
        4  => __( 'Service item updated.' ),
        /* translators: %s: date and time of the revision */
        5  => isset( $_GET['revision'] ) ? sprintf( __( 'Service item restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6  => __( 'Service item published.' ),
        7  => __( 'Service item saved.' ),
        8  => __( 'Service item submitted.' ),
        9  => sprintf(
            __( 'Service item scheduled for: <strong>%1$s</strong>.' ),
            // translators: Publish box date format, see http://php.net/date
            date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
        ),
        10 => __( 'Service item draft updated.' )
    );
    return $messages;
}

?>