<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Widgets\Events;

/**
 * Description of UpcommingEvents
 *
 * @author stephan
 */
class UpcommingEvents extends \WP_Widget {

    function __construct() {
        parent::__construct(
// Base ID of your widget
                'club_upcomming_events',
// Widget name will appear in UI
                __('Kommende Anlässe', 'club_event'),
// Widget description
                array('description' => __('Zeigt die kommenden Anlässe an', 'club_event'),)
        );
    }

// Creating widget front-end
// This is where the action happens
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $events = \Club\Admin\Calendar\Event::getAll(new \DateTime(), $instance["num_events"]);
// before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
?>
<table>
    <thead>
        <tr>
            <th>Datum</th>
            <th>Titel</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($events as $event) {?>
        <tr>
            <td><a href="<?php echo get_permalink($event->getPostId());?>"><?php echo $event->getFromFormated(false) ?></a></td>
            <td><a href="<?php echo get_permalink($event->getPostId());?>"><?php echo $event->getTitle() ?></a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php
        echo $args['after_widget'];
    }

// Widget Backend 
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Kommende Anlässe', 'club_event');
        }
        if (isset($instance['num_events'])) {
            $num = $instance['num_events'];
        } else {
            $num = 5;
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('num_events'); ?>"><?php _e('Anzahl Events:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('num_events'); ?>" name="<?php echo $this->get_field_name('num_events'); ?>" type="text" value="<?php echo esc_attr($num); ?>" />
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['num_events'] = (!empty($new_instance['num_events']) ) ? strip_tags($new_instance['num_events']) : '';
        return $instance;
    }

}
