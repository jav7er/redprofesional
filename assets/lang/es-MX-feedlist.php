<?php
$feedlist = array(
			
		array(
				'title' => 'Indeed',
				'url' => 'http://api.indeed.com/ads/apisearch?publisher=9622554552359243&q='.$q.'&l='.space2plus(get_location_nicename(@$query_vars['loc'])).'&co=mx&userip='.$_SERVER['REMOTE_ADDR'].'&v=2&useragent='.$_SERVER['HTTP_USER_AGENT'],
				'folder' => 'Jobs',
				'api' => true
		),
		array(
				'title' => 'Simplyhired',
				//http://api.simplyhired.mx/a/jobs-api/xml-v2/q-Sales/pn-1?pshid=75335&ssty=3&cflg=r&clip=189.220.97.79
			  //'url' => 'http://api.simplyhired.com/a/jobs-api/xml-v2/q-'.$q.'/l-'.space2plus(get_location_nicename(@$query_vars['loc'])).'?pshid='.$simpleyhired_publisher_id.'&ssty=3&cflg=r&clip='.$_SERVER['REMOTE_ADDR'],
				'url' => 'http://api.simplyhired.mx/a/jobs-api/xml-v2/q-'.$q.'/l-'.space2plus(get_location_nicename(@$query_vars['loc'])).'?pshid='.$simpleyhired_publisher_id.'&ssty=3&cflg=r&clip='.$_SERVER['REMOTE_ADDR'],
				'folder' => 'Jobs',
				'api' => 'jobamatic_loader'
		),
		array(
				'title' => 'Opción Empleo',
				// http://rss.opcionempleo.com.mx/rss?l=M%C3%A9xico&lid=36815&affid=0ed89c8824afa5bc5dc17a976acf7200&psz=20&snl=100
				//'url' => 'http://rss.careerjet.com/rss?s='.$q.'&l='.space2plus(get_location_nicename(@$query_vars['loc'])).'&affid='.$careerjet_publisher_id.'',
				'url' => 'http://rss.opcionempleo.com.mx/rss?s='.$q.'&l='.space2plus(get_location_nicename(@$query_vars['loc'])).'&affid='.$careerjet_publisher_id.'',
				'folder' => 'Jobs'
		),
		array(
				'title' => 'CareerBuilder',
				'url' => 'http://rtq.careerbuilder.com/RTQ/rss20.aspx?RSSID=RSS_PD&city=atlanta&state=GA&country=US&kw='.$q.'',
				'folder' => 'Jobs',
		)
		//,
//		array(
//				'title' => 'Monster',
				//'url' => 'http://rss.jobsearch.monster.com/rssquery.ashx?brd=1&q='.$q.'&cy=us&where=&where= OR&rad=20rad_units=miles&baseurl=jobview.monster.com',
//				'url' => 'http://rss.jobsearch.monster.com/rssquery.ashx?brd=1&q='.$q.'&cy=mx&where=&where= OR&rad=20rad_units=miles&baseurl=jobview.monster.com',
//				'folder' => 'Jobs',
//		),
//		array(
//				'title' => 'JobStreet',
//				'url' => 'http://job-search.jobstreet.co.id/indonesia/job-rss.php?key='.$q.'',
//				'folder' => 'Jobs',
//		),
		//array(
		//		'title' => 'Naukri',
		//		'url' => 'http://jobsearch.naukri.com/mynaukri/mn_newsmartsearch.php?xz=1_0_15&xo=2727&xl=rss&rss=r&type=all&qp='.$q.'',
		//		'folder' => 'Jobs',
//		)
);
