<?php
/*
Template Name: Plugin Single
*/
?>

<?php get_header(); ?>

<div id="content" class="blog plugin-single">

	<h1><?php the_title(); ?></h1>

	<?php
	$slug = $post->post_name;
	$plugin = ps_get_plugin($slug, $plugin_showcase_dir);
	//print_r($plugin);
	?>

	<ul class="plugin-sections">
		<?php
		foreach ($plugin['sections'] as $header => $details) {
			?>
			<li class="section section-<?php echo $header; ?>">
				<h3><?php echo ucwords($header); ?></h3>
				<?php if ($header == 'description' && count($plugin['contributors']) > 0): ?>
				<p class="author">
					<label>Author<?php echo (count($plugin['contributors']) > 1) ? 's' : ''; ?>:</label>
					<?php echo join(', ', $plugin['contributors']); ?></a>
				<?php
				endif;
				
				if ($header == 'screenshots' && isset($plugin['screenshots'])) {
					echo '<ol class="screenshots">';
					foreach ($plugin['screenshots'] as $screenshot) {
						$src = sprintf('/ps/plugins/%s/%s', $slug, $screenshot['image']);
						?>
						<li class="screenshot">
							<img src="<?php echo $src; ?>" class="screenshot" alt="<?php echo $screenshot['caption']; ?>" />
							<p class="caption"><?php echo $screenshot['caption']; ?></p>
						</li>
						<?php
					}
					echo '</ol>';
				}
				else {
					echo $details;
				}
				?>
			</li>
			<?php
		}
		?>
		<li class="section section-other-notes">
			<?php echo $plugin['remaining_content']; ?>
		</li>
	</ul>
	
</div>

<ul id="sidebar" class="single plugin">
	<li class="module download">
		<a class="download" href="/plugin-download/<?php echo $slug; ?>.<?php echo $plugin['version']; ?>.zip">Download</a>
	</li>
	<li class="module meta-data">
		<p class="version"><label>Version:</label> <?php echo $plugin['version']; ?></p>
		<?php if (isset($plugin['last_updated'])) : ?>
		<p class="last-updated"><label>Last Updated:</label> <?php echo $plugin['last_updated']; ?></p>
		<?php endif; ?>
		<p class="requires"><label>Requires Wordpress Version:</label> <?php echo $plugin['requires_at_least']; ?> and higher</p>
		<p class="compatible"><label>Compatible up to:</label> <?php echo $plugin['tested_up_to']; ?></p>
		<p class="support"><a href="http://wordpress.org/tags/<?php echo $slug; ?>">Support Forums &raquo;</a></p>
	</li>
</ul>

<?php get_footer(); ?>
