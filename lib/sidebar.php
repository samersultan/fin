<?php 
// Register Sidebars
$sidebars = array('Footer','Default','Home','Single');
foreach ($sidebars as $sidebar) {
	register_sidebar(array('name'=> $sidebar,
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));
}

/**
 * Add additional classes onto widgets
 *
 * @link http://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets
 */
function fin_add_widget_classes($params) {
  global $current_widget;
  
    $sidebar_id = $params[0]['id'];
    $all_widgets = wp_get_sidebars_widgets();
    $sidebar_widgets = $all_widgets[$sidebar_id];
  	
    if (!$current_widget) {
      $current_widget = array();
    }
    
  	// No registered widgets in sidebar
    if (!isset($sidebar_widgets) || !is_array($sidebar_widgets)) {
      return $params;
    }
  	
    if (isset($current_widget[$sidebar_id])) {
      $current_widget[$sidebar_id] ++;
    } else {
      $current_widget[$sidebar_id] = 1;
    }
    
    // Add fraction class
    switch (count($sidebar_widgets)) {
    	case 1:
    		$fraction = 'small-12';
    		break;
    	case 2:
    		$fraction = 'small-6';
    		break;
    	case 3:
    		$fraction = 'small-4';
    		break;
    	case 4:
    		$fraction = 'small-6 large-3';
    		break;
    	case 5:
    		$fraction = 'small-6 one-fifth';
    		break;
    	case 6:
    		$fraction = 'small-6 one-sixth';
    		break;
    	case 7:
    		$fraction = 'small-6 one-seventh';
    		break;
    	case 8:
    		$fraction = 'small-6 one-eighth';
    		break;
    	default:
    		$fraction = 'small-6';
    }
    $class = 'class="' . $fraction . ' columns ';
  	
  	// Add widget-#
  	$class .= 'widget-' . $current_widget[$sidebar_id] . ' ';
  	
  	// Add widget-first and widget-last
    if ($current_widget[$sidebar_id] == 1) {
      $class .= 'first ';
    } elseif ($current_widget[$sidebar_id] == count($sidebar_widgets)) {
      $class .= 'last ';
    }
    
    // Add new classes
    $params[0]['before_widget'] = preg_replace('/class=\"/', "$class", $params[0]['before_widget'], 1);
  
    return $params;
}
add_filter('dynamic_sidebar_params', 'fin_add_widget_classes');