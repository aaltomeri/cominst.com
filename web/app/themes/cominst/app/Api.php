<?php

namespace App;

/**
 * we need to add some custom data to some WP REST API responses
 */
add_action('rest_api_init', function () {
    // an image tag for featured images
    register_rest_field(
        ['post', 'page'],
        'featured_media_html',
        [
            'get_callback' => '\App\get_featured_media_html'
        ]
    );

    // all featured_image sizes for a post or page
    register_rest_field(
        ['post', 'page'],
        'featured_media_metadata',
        [
            'get_callback' => '\App\get_featured_media_metadata'
        ]
    );

    // categories names
    register_rest_field(
        'post',
        'categories_names',
        [
            'get_callback' => '\App\get_categories_names'
        ]
    );

    // Media taxonomy terms - names
    register_rest_field(
        'post',
        'medias_names',
        [
            'get_callback' => '\App\get_medias_names'
        ]
    );

    // formatted published date
    register_rest_field(
        'post',
        'formatted_published_date',
        [
            'get_callback' => '\App\get_formatted_published_date'
        ]
    );

    // path to post - used by the client Router
    register_rest_field(
        'post',
        'path',
        [
            'get_callback' => '\App\get_path'
        ]
    );
});

function get_featured_media_html($object)
{
    $html = '';
    if (isset($object['featured_media']) && $object['featured_media']) {
        $html = wp_get_attachment_image($object['featured_media'], 'sm');
    }
    return $html;
}

function get_featured_media_metadata($object)
{
    $metadata = [];
    if (isset($object['featured_media']) && $object['featured_media']) {
        $metadata  = wp_get_attachment_metadata($object['featured_media']);
        foreach ($metadata['sizes'] as $size => $size_infos) {
            $metadata['sizes'][$size]['url'] = wp_get_attachment_image_src($object['featured_media'], $size)[0];
        }
        $metadata['sizes']['original']['url'] = wp_get_attachment_image_src($object['featured_media'], null)[0];
    }
    return $metadata;
}

function get_categories_names($object)
{
    return wp_get_object_terms($object['id'], 'category', ['fields' => 'names']);
}

function get_medias_names($object)
{
    return wp_get_object_terms($object['id'], 'media', ['fields' => 'names']);
}

function get_formatted_published_date($object)
{
    return get_the_date(null, $object['id']);
}

function get_path($object)
{
    return str_replace(get_option('home'), '', get_the_permalink($object['id']));
}


/**
 * Allow the use of the 'term_order' sorting parameter
 * in REST API calls by adding the parameter to the white list
 * note: this parameter is brought in by the taxonomy-terms-order plugin
 */
add_filter(
    'rest_category_collection_params',
    function ($params) {
        $params['orderby']['enum'][] = 'term_order';
        return $params;
    }
);


/**
 * a simple Class to access WP data
 * either via the REST API
 * or in some cases
 * (when we don't need to fetch data fro the client - and don't want to create custom endpoints)
 * with regular WP methods
 */
class Api
{
    /**
     * get data from REST API
     * to be used in initial data payload for JS app
     */

    /**
     * posts
     */
    public static function get_posts($per_page = 10, $category_id = 0)
    {
        $request = new \WP_REST_Request('GET', '/wp/v2/posts');
        $request->set_param('per_page', $per_page);
        $request->set_param('categories', $category_id ? [$category_id] : []);
        $response = rest_do_request($request);
        return [
            'data' => $response->data,
            'category_id' => $category_id,
            'paging' => [
                'currentPage' => 1,
                'total' => $response->headers['X-WP-Total'],
                'totalPages' => $response->headers['X-WP-TotalPages'],
            ]
        ];
    }

    /** get ALL Posts IDs & slug */
    public static function get_all_posts_ids_and_slugs()
    {
        global $wpdb;

        $wpml_is_installed = defined('ICL_LANGUAGE_CODE');
        $lang = $wpml_is_installed ? ICL_LANGUAGE_CODE : 'fr';

        $join_statement = $wpml_is_installed
            ? ' INNER JOIN wp_icl_translations wpml ON p.ID = wpml.`element_id`'
            : '';

        $where_statement = "post_type = 'post' AND post_status IN ('publish')";
        $where_statement .= $wpml_is_installed
            ? " AND wpml.`language_code` = '$lang'"
            : '';
        $query = sprintf(
            "SELECT ID, post_name
            FROM wp_posts p
            %s
            WHERE %s
            ORDER BY post_date DESC",
            $join_statement,
            $where_statement
        );

        return $wpdb->get_results($query);
    }

    /**
     * post categories
     */
    public static function get_categories($per_page = 99)
    {
        $request = new \WP_REST_Request('GET', '/wp/v2/categories');
        $request->set_param('per_page', $per_page);
        $request->set_param('orderby', 'term_order');
        $response = rest_do_request($request);
        return $response->data;
    }

    /**
     * taxonomies
     */
    public static function get_taxonomies($per_page = 99)
    {
        $request = new \WP_REST_Request('GET', '/wp/v2/taxonomies');
        $request->set_param('per_page', $per_page);
        $response = rest_do_request($request);
        return $response->data;
    }

    /**
     * post types
     */
    public static function get_post_types($per_page = 99)
    {
        $request = new \WP_REST_Request('GET', '/wp/v2/types');
        $request->set_param('per_page', $per_page);
        $response = rest_do_request($request);
        return $response->data;
    }

    /**
     * pages
     */
    public static function get_pages($params)
    {
        $request = new \WP_REST_Request('GET', '/wp/v2/pages');
        foreach ($params as $key => $value) {
            $request->set_param($key, $value);
        }
        $response = rest_do_request($request);
        return $response->data;
    }

    public static function get_top_pages()
    {
        $params = [
            'per_page' => 99, // max allowed is 99 - if more is needed > make anothe call and combine
            // 'parent' => 0
        ];
        return self::get_pages($params);
    }

    /**
     * menus
     */

    /**
     * Main navigation
     */
    public static function get_primary_navigation()
    {
        $request = new \WP_REST_Request('GET', '/wp-api-menus/v2/menu-locations/primary_navigation');
        $response = rest_do_request($request);
        return $response->data;
    }

    /**
     * Secondary navigation
     *
     * we treat this one differently as we need a title to be displayed before the items
     */
    public static function get_secondary_navigation()
    {
        $location = 'secondary_navigation';
        $locations  = get_nav_menu_locations();
        if (!isset($locations[$location])) {
            return null;
        }
        $menu_id = $locations[$location];
        $wp_menu = wp_get_nav_menu_object($menu_id);
        $title = $wp_menu->name;

        // use the endpoint to benefit from formatting done by the wp-api-menu plugin
        $request = new \WP_REST_Request('GET', '/wp-api-menus/v2/menu-locations/secondary_navigation');
        $response = rest_do_request($request);
        $items = $response->data;

        $navigation = [
            'title' => $title,
            'items' => $items,
        ];

        return $navigation;
    }

    /**
     * Theme options
     * used for Client infos like contact details, social networks ...
     */
    public static function get_theme_options()
    {
        $request = new \WP_REST_Request('GET', '/acf/v3/options/theme-general-settings');
        $response = rest_do_request($request);
        return $response->data;
    }

    /**
     * Sectors & References for 'Nos références'
     * we get a list of sectors and associate the 'references'
     * we are not using the REST API because it would require custom endpoints
     * and we won't need to fetch the date from the client
     */
    public static function get_references_by_sectors()
    {
        $sectors = get_terms(['taxonomy' => 'sector', 'orderby' => 'name']);
        foreach ($sectors as $key => $sector) {
            $sectors[$key]->image = get_field('image', $sector);
            if (isset($sectors[$key]->image) && $sectors[$key]->image) {
                $sectors[$key]->image_html = wp_get_attachment_image($sectors[$key]->image['ID'], 'medium');
            }
            $sectors[$key]->references = get_posts([
                'post_type' => 'reference',
                'tax_query' => [['taxonomy' => 'sector', 'field' => 'term_id', 'terms' => [$sector->term_id]]]
            ]);
        }
        return $sectors;
    }
}

/**
 * comment this de-activation out
 * because we're attempting to fix https://github.com/aaltomeri/cominst.com/issues/6
 * by updating wp-super-cache from the source
 */
// remove_action( 'template_redirect', 'wp_shortlink_header', 11);

/** Contact Form - response function  */
function cominst_contact_form()
{

    // will return a 403 error if nonce can't be verified
    check_ajax_referer('contactForm', 'nonce');

    $data = $_POST['formData'];

    $to = get_option('admin_email');
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Formulaire de contact - cominst.com <' . get_option('admin_email') . '>',
        'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>',
    ];
    $subject = "cominst.com | Message de " . $data['name'];

    ob_start();

    echo wpautop($data['message']);

    $message = ob_get_contents();

    ob_end_clean();

    $mail = wp_mail($to, $subject, $message, $headers);

    if ($mail) {
        echo 'success';
    } else {
        echo __('Error sending email', 'cominst');
    }
    wp_die();
}
add_action('wp_ajax_nopriv_cominst_contact_form', 'App\\cominst_contact_form');
add_action('wp_ajax_cominst_contact_form', 'App\\cominst_contact_form');
