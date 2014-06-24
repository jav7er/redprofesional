<?php
$feedlist = array(
			
		array(
				'title' => 'Indeed',
				'url' => 'http://api.indeed.com/ads/apisearch?publisher='.$indeed_publisher_id.'&co=nl&q='.$q.'&l='.space2plus(get_location_nicename(@$query_vars['loc'])).'&userip='.$_SERVER['REMOTE_ADDR'].'&v=2&useragent='.$_SERVER['HTTP_USER_AGENT'],
				'folder' => 'Jobs',
				'api' => true
		),
		array(
				'title' => 'Simplyhired',
				'url' => 'http://api.simplyhired.nl/a/jobs-api/xml-v2/q-'.$q.'/l-'.space2plus(get_location_nicename(@$query_vars['loc'])).'/pn-1?pshid='.$simpleyhired_publisher_id.'&ssty=3&cflg=r&clip='.$_SERVER['REMOTE_ADDR'],
				'folder' => 'Jobs',
				'api' => 'jobamatic_loader'
		),
		array(
				'title' => 'CareerJet',
				'url' => 'http://rss.career-jet.nl/rss?s='.$q.'&l='.space2plus(get_location_nicename(@$query_vars['loc'])).'&affid='.$careerjet_publisher_id.'',
				'folder' => 'Jobs'
		)
);
