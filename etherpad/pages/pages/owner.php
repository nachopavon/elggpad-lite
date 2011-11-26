<?php
/**
 * List a user's or group's pages
 *
 * @package ElggPages
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('pages/all');
}

// access check for closed groups
group_gatekeeper();

$title = elgg_echo('pages:owner', array($owner->name));

elgg_push_breadcrumb($owner->name);

elgg_register_title_button();

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => array('page_top', 'etherpad'),
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => $limit,
	'full_view' => false,
));
if (!$content) {
	$content = '<p>' . elgg_echo('pages:none') . '</p>';
}

$filter_context = '';
if (elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

$sidebar = elgg_view('pages/sidebar/navigation');
$sidebar .= elgg_view('pages/sidebar');

if (elgg_is_logged_in()) {
	$url = "etherpad/add/" . elgg_get_logged_in_user_guid();
	elgg_register_menu_item('title', array(
			'name' => 'elggpad',
			'href' => $url,
			'text' => elgg_echo('etherpad:new'),
			'link_class' => 'elgg-button elgg-button-action',
			'priority' => 200,
	));
}

$params = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
);

if (elgg_instanceof($owner, 'group')) {
	$params['filter'] = '';
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
