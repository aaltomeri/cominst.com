<?php


/**
 * Register ACF options page
 */
add_action(
    'acf/init',
    function() {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(array(
              'page_title' => __('Réglages généraux', 'cominst'),
              'menu_title' => __('Réglages généraux', 'cominst'),
              'menu_slug' => 'theme-general-settings',
              'post_id' => 'theme-general-settings',
              'capability' => 'manage_options',
              'redirect' => false
            ));
        }
    }
);

if( function_exists('acf_add_local_field_group') ):
    
    // global settings
    acf_add_local_field_group(array(
        'key' => 'group_5acb741f92394',
        'title' => 'Réglages généraux',
        'fields' => array(
            array(
                'key' => 'field_5acb741fb48a1',
                'label' => 'N° de téléphone',
                'name' => 'phone_number',
                'type' => 'text',
                'instructions' => 'Sert principalement au script Google Tracking',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5acc60da6ffb6',
                'label' => 'Adresse',
                'name' => 'address',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5acc614c4f142',
                'label' => 'Contact email principal',
                'name' => 'contact_email',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5acb741fb48b4',
                'label' => 'Réseaux sociaux',
                'name' => 'social_networks',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => 'field_59ff841f40133',
                'min' => 0,
                'max' => 0,
                'layout' => 'row',
                'button_label' => 'Ajouter un réseau social',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5acb741fd8eb1',
                        'label' => 'Nom',
                        'name' => 'name',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5acb741fd8ec7',
                        'label' => 'Url',
                        'name' => 'url',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_5acb741fd8ed7',
                        'label' => 'Icône',
                        'name' => 'icon',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),
                ),
            ),
            array(
                'key' => 'field_5acb741fb48c4',
                'label' => 'Map',
                'name' => 'map',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ),
            array(
                'key' => 'field_5acb741fb48d3',
                'label' => 'Mailchimp : URL d\'inscription à la Newsletter',
                'name' => 'mailchimp_subscribe_url',
                'type' => 'url',
                'instructions' => 'Vous trouverez cette url dans l\'attribut "action" de la balise <form> des formulaires disponibles pour chaque liste Mailchimp.
    
    Cette url ressemble à : https://myaccount.us1.list-manage.com/subscribe/post?u=e9dcc6f157d6a768e8935123c&amp;id=1239b32b9e',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_5b154b072ab45',
                'label' => 'Catégorie Actualités par défaut',
                'name' => 'default_category_id',
                'type' => 'taxonomy',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'taxonomy' => 'category',
                'field_type' => 'select',
                'allow_null' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'id',
                'multiple' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'theme-general-settings',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
    
endif;
