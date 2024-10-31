<?php
/*
Template Name: Plugin Index
*/
?>

<?php get_header(); ?>

<div id="content" class="page plugin-index">

	<h1><?php the_title(); ?></h1>

	<div class="intro">
		<?php the_post(); the_content("more..."); ?>
	</div>

	<ul class="posts">
	<?php
	$plugins = ps_get_plugins($plugin_showcase_dir);
	foreach ($plugins as $plugin) {
		$support_url = 'http://wordpress.org/tags/' . $plugin['slug'];
		if (strpos($plugin['pluginuri'], 'wordpress.org') !== false) {
			$read_more = 'visit wordpress.org';
		}
		else {
			$read_more = 'read more';
		}
		?>
		<li class="post plugin plugin-<?php echo $plugin['slug']; ?>">
			<h2><a href="<?php echo $plugin['pluginuri']; ?>"><?php echo $plugin['title']; ?></a></h2>
			<p>
				<?php echo $plugin['short_description']; ?>
				<a class="read-more" href="<?php echo $plugin['pluginuri']; ?>"><?php echo $read_more; ?> &raquo;</a>
			</p>
		</li>
		<?php
	}
	?>
	</ul>
	
</div>

<?php get_footer(); ?>
