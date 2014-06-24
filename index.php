<?php
include("config.php");
include("core.php");
?>
<!DOCTYPE html>
<html lang="<?php echo $language_code; ?>">
	<head>
	<base href="<?php echo $siteurl; ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo $sitedescription; ?> - <?php echo $document_title; ?> - <?php echo _text( get_locale() ); ?>"  />
	<meta name="keywords" content="<?php echo ($document_title); ?>,<?php echo $sitekeywords; ?>" />
	<title><?php echo $document_title; ?> - <?php echo $sitename; ?> - <?php echo _text( get_locale() ); ?></title>
	<?php if ( isset($theme) ): ?>
	<link id="theme_switcher" href="assets/themes/<?php echo $theme; ?>/bootstrap.min.css" rel="stylesheet">
	<?php else: ?>
	<link id="theme_switcher" href="assets/themes/default/bootstrap.min.css" rel="stylesheet">
	<?php endif; ?>
	<link href="assets/css/carousel.css" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/estilos.css" rel="stylesheet">

	<!-- FontAwesome icons -->
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">
	<!--[if lt IE 7]>
	<link href="assets/css/font-awesome-ie7.min.css" rel="stylesheet">
	<![endif]-->

	<script src="http://code.jquery.com/jquery-2.0.0.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript" src="http://gdc.indeed.com/ads/apiresults.js"></script>
	<script type="text/javascript" src="assets/js/spin.min.js"></script>
	<script type="text/javascript" src="assets/js/iosOverlay.js"></script>
	<script type="text/javascript" src="assets/js/jquery.tinysort.min.js"></script>
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
    <![endif]-->

	<!-- Favorite and ios icons -->
	<link rel="shortcut icon" href="assets/ico/favicon.ico">
	<link rel="apple-touch-icon" href="assets/ico/icon.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="assets/ico/icon-72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="assets/ico/icon@2x.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="assets/ico/icon-72@2x.png" />
	<?php echo $statistics_code; ?>
</head>
<body data-spy="scroll" data-target=".navbar">

	<nav id="topnav" class="navbar navbar-fixed-top navbar-default" role="navigation">
		<div class="container">
	  		<!-- Brand and toggle get grouped for better mobile display -->
	  		<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
		 			<span class="sr-only">Toggle navigation</span>
		  			<span class="icon-bar"></span>
		  			<span class="icon-bar"></span>
		  			<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo $siteurl; ?>"><?php echo $sitename; ?></a>
	  		</div>

	  		<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="<?php echo get_site_url(array(), 'top'); ?>"><i class="icon-home"></i> <?php echo _text('MENU_HOME'); ?></a></li>
					<li><a href="<?php echo get_site_url(array(), 'search'); ?>"><i class="icon-search"></i> <?php echo _text('MENU_SEARCH'); ?></a></li>
					<!--
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo _text('MENU_BROWSE'); ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo get_site_url(array(), 'categories'); ?>"><i class="icon-th-large"></i> <?php echo _text('MENU_CATEGORIES'); ?></a></li>
							<li><a href="<?php echo get_site_url(array(), 'locations'); ?>"><i class="icon-map-marker"></i> <?php echo _text('MENU_LOCATIONS'); ?></a></li>
						</ul>
					</li>
					-->
					<li><a href="<?php echo get_site_url(array(), 'categories'); ?>"><i class="icon-th-large"></i> <?php echo _text('MENU_CATEGORIES'); ?></a></li>
					<li><a href="<?php echo get_site_url(array(), 'locations'); ?>"><i class="icon-map-marker"></i> <?php echo _text('MENU_LOCATIONS'); ?></a></li>
					<li><a href="#<?php echo _text('JOB_RESULTS'); ?>"><i class="icon-briefcase"></i> <?php echo _text('MENU_LATEST'); ?></a></li>
					<!-- <li data-toggle="modal" data-target="#about_modal"><a href="#about"><i class="icon-info-sign"></i> <?php echo _text('MENU_ABOUT_US'); ?></a></li> -->
					<?php if ( ((isset($language_switcher) && $language_switcher=='on') || !isset($language_switcher)) && $languages = get_languages() ): ?>
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo _text('MENU_LANGUAGE'); ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php foreach ($languages as $lang):
								@list($tmp, $_country) = explode('-', $lang);
								$return = base64_encode( get_current_url() );
								$lang_url = trailingslashit($siteurl) . $_country . '/';
							?>
							<li><a href="<?php echo $lang_url; ?>"><?php echo _text($lang); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>
					<?php endif; ?>
				</ul>
				<?php if (isset($themeswitcher) && $themeswitcher=='on'): ?>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="http://codecanyon.net/item/instant-job-search-engine-aggregator/5541867?ref=vidal"><i class="icon-download"></i> <?php echo _text('MENU_DOWNLOAD'); ?></a></li>
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span id="current_theme"><?php echo isset($theme) && isset($_themes[$theme]) ? $_themes[$theme] : 'Default'; ?></span> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php foreach ($_themes as $t => $label): ?>
							<li<?php if(isset($theme) && $theme==$t) echo ' class="active"'; ?>><a data-toggle="theme" href="<?php echo "#$t"; ?>"><?php echo $label; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>
				</ul>
				<?php endif; ?>
			</div>
			<!-- /.navbar-collapse -->
		</div>
	</nav>




	<!-- HEADER -->
	<header id="top">
	
	</header>
	<!-- / HEADER -->

	<!-- SEARCH -->
	<section id="search">
		<div class="container">
			<div class="row text-center ">
				<h1><i class="icon-search"></i> <?php echo _text('HEADING_SEARCH_JOBS'); ?></h1>
				<p class="lead"> <?php echo _text('LEADING_FIND_JOBS_BY_KEYWORD_OR_LOCATION'); ?> </p>
				<!-- search form -->
				<hr>
				<div class="col-sm-12 col-lg-12">
					<div class="text-center col-sm-12 col-lg-12">

						<form class="form-inline form-search" role="form" action="#<?php echo _text('JOB_RESULTS'); ?>" method="get">
						  <div class="form-group">
							<input type="text" class="form-control input-lg" name="keyword" value="<?php if (isset($q)) { echo $q;}?>" id="q" placeholder="<?php echo _text('PLACEHOLDER_SEARCH'); ?>">
						  </div>
						  <div class="form-group">
							<input type="text" class="form-control input-lg" name="job_location" value="<?php if (isset($query_vars['loc'])) { echo $query_vars['loc']; }?>" id="loc" placeholder="<?php echo _text('PLACEHOLDER_LOCATION'); ?>">
						  </div>
						  <button type="submit" class="btn btn-info btn-lg"><?php echo _text('SEARCH'); ?></button>
						</form>
						<!-- /search form -->
					</div>

				</div>
				<hr>
			</div>
		</div>
		<p class="text-center"><?php echo _text('TEXT_SEARCH_ON'); ?></p>
	
	<!-- ADS -->
	<div class="container anuncios"><div class="well well-lg text-center"><?php echo $adsenseads; ?></div></div>
	<!-- / ADS-->

	</section>
	<!-- / SEARCH -->


	<section id="sitios">
		<div class="col-md-12">
			<h4 class="line3 center standart-h4title">
			<span>Los mejores sitios de empleos</span>
			</h4>
			<ul class="list-unstyled marcas">
				<li><a href="#Indeed" data-toggle="jump2feed">Indeed</a></li>
				<li><a href="#Simplyhired" data-toggle="jump2feed">Simplyhired</a></li>
				<li><a href="#Opción Empleo" data-toggle="jump2feed">Opción Empleo</a></li>
				<li><a href="#CareerBuilder" data-toggle="jump2feed">CareerBuilder</a></li>					
			</ul>
		</div>
		
	</section>

	
	<?php if ( isset($show_categories) && $show_categories == true ): ?>
	<!-- CATEGORIES -->
	<section id="categories">
		<div class="container">
			<div class="row">
				<div class="page-header text-center col-sm-12 col-lg-12">
					<h2><i class="icon-th-large"></i> <?php echo _text('HEADING_CATEGORIES'); ?></h2>
					<p><?php echo _text('DESCRIPTION_CATEGORIES', $sitename); ?></p>
				</div>
			</div>
			<div class="row multi-columns-row"><?php print_categories( $categories ); ?></div>
			
		</div><!-- / CONTAINER-->
	</section>
 	<!-- / CATEGORIES -->
	<?php endif; ?>

	<?php if ( isset($show_locations) && $show_locations == true ): ?>
	<!-- LOCATIONS -->
	<section id="locations">
		<div class="container">
			<div class="row">
				<div class="page-header text-center col-sm-12 col-lg-12">
					<h2><i class="icon-map-marker"></i> <?php echo _text('HEADING_LOCATIONS'); ?></h2>
					<p><?php echo _text('DESCRIPTION_LOCATIONS'); ?></p>
				</div>
			</div>
			<div class="row multi-columns-row"><?php print_locations( $locations ); ?></div>
		</div>

	<!-- ADS -->
	<div class="container">
		<div class="well well-lg text-center"><?php echo $adsenseads; ?></div>
	</div>
	<!-- / ADS-->

	</section>
	<!-- / LOCATIONS -->
	<?php endif; ?>





	
	<!--  SEARCHRESULTS -->
	<section id="<?php echo _text('JOB_RESULTS'); ?>" class="results resultados">
		<div class="container">
			<div class="row">
				<div class="page-header text-center col-sm-12 col-lg-12">
					<h2><i class="icon-briefcase"></i> <?php
						if ( !empty($q) && empty($query_vars['loc']) ){
							echo _text('SEARCHRESULT_JOBS_FOR_TODAY_JOB', $q);
						} else if ( empty($q) && !empty($query_vars['loc']) ){
							echo _text('SEARCHRESULT_JOBS_FOR_TODAY_LOC', ucfirst($query_vars['loc']) );
						} else if ( !empty($q) && !empty($query_vars['loc']) ){
							echo sprintf( _text('SEARCHRESULT_JOBS_FOR_TODAY_ALL'), ucfirst($q), ucfirst($query_vars['loc']) );
						}
					?> </h2>
					<p><?php
						$rp = !isset($q) || empty($q) ? ' ' : $q;
						echo _text('SEARCHRESULT_DESCRIPTION', $rp); ?></p>
				</div>
			</div>


			<!-- CONTENT SIDE-->
	
			<?php
			if ( isset($feedlist) && is_array($feedlist) ){
				$c = 0;
			?>

				<div class="tabbable" id="myTabs">
				  	<ul class="nav nav-tabs">
					    <li class="active"><a id="tabAll" href="#"><?php echo _text('TAB_ALL'); ?></a></li>
					    <?php foreach($feedlist as $section): ?>
					    <li><a href="#tab<?php echo $c++; ?>" data-toggle="tab" title="<?php echo $section['title']; ?>"><?php echo $section['title']; ?></a></li>
					    <?php endforeach; $c = 0; ?>
				  	</ul>
					<div class="tab-content">
						<?php foreach($feedlist as $section): ?>
						<div class="tab-pane active" id="tab<?php echo $c++; ?>"></div>
						<?php endforeach; $c = 0; ?>
					</div>
				</div>
				<script type="text/javascript">
					google.load("feeds", "1");
					var opts = {
						lines: 13, // The number of lines to draw
						length: 11, // The length of each line
						width: 5, // The line thickness
						radius: 17, // The radius of the inner circle
						corners: 1, // Corner roundness (0..1)
						rotate: 0, // The rotation offset
						color: '#FFF', // #rgb or #rrggbb
						speed: 1, // Rounds per second
						trail: 60, // Afterglow percentage
						shadow: false, // Whether to render a shadow
						hwaccel: false, // Whether to use hardware acceleration
						className: 'spinner', // The CSS class to assign to the spinner
						zIndex: 2e9, // The z-index (defaults to 2000000000)
						top: 'auto', // Top position relative to parent in px
						left: 'auto' // Left position relative to parent in px
					};
					var target = document.createElement("div");
					document.body.appendChild(target);
					var spinner = new Spinner(opts).spin(target);
					var overlay = iosOverlay({
						text: "<?php echo _text('LOADING'); ?>",
						spinner: spinner
					});

					var numfeed = <?php echo count($feedlist); ?>;
				<?php
				
				
				$count = count($feedlist);
				foreach ($feedlist as $feed){
					if ( !isset($feed['api']) || !$feed['api'] ) {
					
						echo 'google.setOnLoadCallback(showFeed' . $c . ');';
						echo 'function showFeed' . $c . '() {
							var feed' . $c . ' = new google.feeds.Feed("' . $feed['url'] . '");
							feed' . $c . '.setNumEntries('.$limit_listings.');
							feed' . $c . '.includeHistoricalEntries();';
							?>
							feed<?php echo $c; ?>.load(function(result) {
								if (!result.error) {
									var i=0;
									$.each(result.feed.entries, function(i, entry){
										var div = document.createElement("div");
										div_innerHTML = '<h3><a href="' + entry.link + '" target="_blank">' + entry.title + '</a>' + '</h3>';
										var timestamp = new Date(entry.publishedDate).getDate();
										div_innerHTML += '<p class="clearfix"><span class="label label-info"> <?php echo $feed['title']; ?></span> <?php echo _text('PUBLISHED_ON'); ?> <span class="realdate">' + entry.publishedDate + '</span></p>';
										div_innerHTML += '<p>' + entry.contentSnippet + '</p><hr/>';
										div.innerHTML = div_innerHTML;
										jQuery(div).appendTo('#tab<?php echo $c; ?>');
									});
									if (result.feed.entries.length == 0){
										<?php
											$js_q = '';
											if ($q){
												$js_q = "\"$q\"";
											}
										?>
										jQuery('#tab<?php echo $c; ?>').html( '<?php echo sprintf( _text('TEXT_NO_RESULT'), $js_q, $feed['title'] ) ?>' );
									}
								} else {
									jQuery('#tab<?php echo $c; ?>').html('<div><a href=\"<?php echo $feed['url']; ?>\"><?php echo $feed['title']; ?></a></div>');
								}
								//There has got to be a way to get this to trigger only after completion right? Figure it out.
								try{
									if (jQuery('#tab<?php echo $c; ?> > div').length )
										jQuery('#tab<?php echo $c; ?> > div').tsort("span.realdate", {order:'desc'});
								} catch(e){}
							});
							if ( --numfeed == 0 ){
								overlay.hide();
							}
						}
					<?php
					} else {
						if ( is_string($feed['api']) ){
							$items = call_user_func_array($feed['api'], array($feed['url']));
						} else {
							$items = get_live_feeds($feed['url']);
						}
						if ( count($items) ){
							foreach ($items as $entry):
								$att = isset($entry['onmousedown']) ? ' onmousedown="' . $entry['onmousedown'] . '"' : '';
							?>
							try{
								var div = document.createElement("div");
								div_innerHTML = '<h3><a href="<?php echo $entry['url']; ?>" target="_blank" <?php echo addslashes($att); ?> ><?php echo addslashes($entry['title']); ?></a>' + '</h3>';
								var timestamp = new Date('<?php echo $entry['date']; ?>').getDate();
								div_innerHTML += '<p class="clearfix"><span class="label label-info"> <?php echo $feed['title']; ?></span> <?php echo _text('PUBLISHED_ON'); ?> <span class="realdate"><?php echo $entry['date']; ?></span></p>';
								div_innerHTML += "<p><?php echo clean_string( $entry['snippet'] ); ?></p><hr/>";
								div.innerHTML = div_innerHTML;
								jQuery(div).appendTo('#tab<?php echo $c; ?>');
							} catch(e){
								console.log && console.log(e);
							}
							<?php
							endforeach;
						}
						
						
						?>
						if ( --numfeed == 0 ){
							overlay.hide();
						}
						<?php
					}
					$c++;
				}
				?>
				</script>

				<div class="row">
					<div class="col-12 col-lg-12 page-header page-header-custom text-center">
						<p><a class="btn btn-lg btn-success newsearch" href="<?php echo get_site_url(array(), 'search'); ?>"><i class="icon-search"></i> <?php echo _text('START_NEW_SEARCH'); ?></a>	</p>
					</div>
				</div>
			<?php } ?>
			<!-- /CONTENT SIDE-->
		</div>
		<!-- / CONTAINER-->
	</section>
	<!-- / SEARCHRESULTS -->


	<!-- ADS -->
	<div class="container">
		<div class="well well-lg text-center"><?php echo $adsenseads; ?></div>
	</div>
	<!-- / ADS-->

	<!-- Profesiones -->
	<section id="profesiones">
		<div class="container">
			<div class="row">
				<div class="page-header text-center col-sm-12 col-lg-12">
					<h2><i class="icon-th-large"></i>Empleos para profesionistas</h2>
					<p>Encuentra un empleo seleccionando tu profesión.</p>
				</div>
			</div>
			<div class="row multi-columns-row"><?php print_categories( $profesiones ); ?></div>
			
		</div><!-- / CONTAINER-->
	</section>
 	<!-- / Profesiones -->
 
	<!-- FOOTER -->
	<footer id="footer">
		<div class="container">
	
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
					<h4 class="line3 center standart-h4title">
						<span><?php echo _text('HEADING_NAVIGATION'); ?></span>
					</h4>
					<ul class="list-unstyled footer-links">
						<li><a href="#top"><?php echo _text('MENU_HOME'); ?></a></li>
						<li><a href="<?php echo get_site_url(array(), 'search'); ?>"><?php echo _text('MENU_SEARCH'); ?></a></li>
						<li><a href="<?php echo get_site_url(array(), 'categories'); ?>"><?php echo _text('MENU_CATEGORIES'); ?></a></li>
						<li><a href="<?php echo get_site_url(array(), 'locations'); ?>"><?php echo _text('MENU_LOCATIONS'); ?></a></li>
						<li data-toggle="modal" data-target="#about_modal"><a href="#about"><?php echo _text('MENU_ABOUT_US'); ?></a></li>
						<li data-toggle="modal" data-target="#privacy_modal"><a href="#privacy"><?php echo _text('MENU_PRIVACY'); ?></a></li>
						<li data-toggle="modal" data-target="#termofuse_modal"><a href="#termofuse"><?php echo _text('MENU_TERM_OF_USE'); ?></a></li>
					</ul>
				</div>
	
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
					<h4 class="line3 center standart-h4title">
						<span><?php echo _text('HEADING_JOBSITES_ADDED'); ?></span>
					</h4>
					<ul class="list-unstyled footer-links">
						<?php
						foreach ($feedlist as $feed){
							echo '<li><a href="#'.$feed['title'].'" data-toggle="jump2feed">'.$feed['title'].'</a></li>';
						}
						?>
					</ul>
				</div>
	
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
					<h4 class="line3 center standart-h4title">
						<span><?php echo _text('HEADING_STAY_IN_TOUCH'); ?></span>
					</h4>
					<p><?php echo empty($q) ? _text('TEXT_SOCIAL', ' ') : _text('TEXT_SOCIAL', $q); ?></p>
					<ul class="list-unstyled share-group clearfix">
						<li><a href="<?php echo get_facebook_share_url();?>"><i class="icon-facebook icon-2x"></i></a></li>
						<li><a href="<?php echo get_twitter_share_url();?>"><i class="icon-twitter icon-2x"></i></a></li>
						<li><a href="<?php echo get_linkedin_share_url(); ?>"><i class="icon-linkedin icon-2x"></i></a></li>
						<li><a href="<?php echo get_google_plus_share_url();?>"><i class="icon-google-plus icon-2x"></i></a></li>
					</ul>
				</div>
	
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
					<h4 class="line3 center standart-h4title">
						<span><?php echo _text('HEADING_OUR_OFFICE'); ?></span>
					</h4>
					<address>
						<strong><?php echo $sitename; ?> </strong><br> <i
							class="icon-map-marker"></i>
						<?php echo $contact_address_street; ?>
						<br>
						<?php echo $contact_address_city; ?>
						<!-- <br> <i class="icon-phone-sign"></i>
						 <?php echo $contact_tel; ?> -->
	
					</address>
				</div>
	
			</div>
		</div>
	

	</footer>
	<!-- / FOOTER -->


	<!-- ABOUT -->
	<div id="about_modal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
					<h4 class="modal-title"><?php echo _text('MENU_ABOUT_US'); ?></h4>
				</div>
				<div class="modal-body">
					<p><?php echo $about_text; ?></p>
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-default" type="button"><?php echo _text('CLOSE'); ?></button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- / ABOUT -->
	
	<!-- PRIVACY -->
	<div id="privacy_modal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
					<h4 class="modal-title"><?php echo _text('MENU_PRIVACY'); ?></h4>
				</div>
				<div class="modal-body">
					<p><?php echo $privacy_text;?></p>
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-default" type="button"><?php echo _text('CLOSE'); ?></button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- / PRIVACY -->
	
	<!-- TERMOFUSE -->
	<div id="termofuse_modal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
					<h4 class="modal-title"><?php echo _text('MENU_TERM_OF_USE'); ?></h4>
				</div>
				<div class="modal-body">
					<p><?php echo $term_of_use_text; ?></p>
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" class="btn btn-default" type="button"><?php echo _text('CLOSE'); ?></button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- / TERMOFUSE -->

    
	<!-- Latest compiled and minified JavaScript -->
	<script src="assets/js/bootstrap.min.js"></script>
	<!-- PAGE CUSTOM SCROLLER -->
	<?php if (isset($smooth_scroll) && $smooth_scroll=='on'): ?>
	<script src="assets/js/jquery.nicescroll.min.js" type="text/javascript" ></script>
	<script src="assets/js/jquery.localscroll-1.2.7-min.js" type="text/javascript" ></script>
	<script src="assets/js/jquery.scrollTo-1.4.6-min.js" type="text/javascript" ></script>
	<?php endif; ?>
	<?php if ( !empty($q) || !empty($query_vars['loc']) ): ?>
	<script type="text/javascript" >if (!window.location.hash) window.location.hash = '#<?php echo _text('JOB_RESULTS'); ?>'; </script>
	<?php endif; ?>
	<script src="assets/js/app.js" type="text/javascript" ></script>
</body>
</html>