<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'name'=>'Menu superior',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    )); ?>