<?php 

$lat = get_input('lat', 52.1879034);
$lng = get_input('lng', 6.9043385);
$radius = get_input('radius', 2);

$returnData = array();
$returnData['valid'] = 0;

$entities = get_entities_from_viewport($lat, $lng, ($radius * KILOMETER), 'object', 'event', 20);

foreach ($entities as $event) {
	$eventBox = "<div class='gmaps_infowindow'>";
	$eventBox .= "<div class='gmaps_infowindow_text'>";
	$eventBox .= "<div class='event_manager_event_view_owner'>";
	$eventBox .= "<a href='" . $event->getURL() . "'>" . $event->title . "</a> ";
	$eventBox .= "(" . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $event->start_day) . ")</div>";
	$eventBox .= $event->getLocation(true) . "<br /><br />";
	$eventBox .= $event->shortdescription . "<br />";
	$eventBox .= elgg_view("event_manager/event/action", array("entity" => $event));
	$eventBox .= "</div>";
	$eventBox .= "<div class='gmaps_infowindow_icon'>";
	$eventBox .= "<img src='" . $event->getIcon("medium") . "' />";
	$eventBox .= "</div></div>";
						
	$returnData['markers'][] = array(
		'lat' => $event->getLatitude(), 
		'lng' => $event->getLongitude(), 
		'title' => $event->title, 
		'html' => $eventBox,
		'hasrelation' => $event->getRelationshipByUser(),
		'iscreator' => (($event->getOwnerGUID() == elgg_get_logged_in_user_guid()) ? 'owner' : null)
	);
}

$returnData['valid'] = 1;

echo json_encode($returnData);
exit;
