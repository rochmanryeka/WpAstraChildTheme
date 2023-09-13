<?php
/**
 * Custom RSS2 Feed Template for displaying featured image.
 *
 * @package CustomRssFeed
 */

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php do_action('rss2_ns'); ?>>
<channel>
	<title><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss('description') ?></description>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php bloginfo_rss('language'); ?></language>
	<sy:updatePeriod><?php echo apply_filters('rss_update_period', 'hourly'); ?></sy:updatePeriod>
	<sy:updateFrequency><?php echo apply_filters('rss_update_frequency', '1'); ?></sy:updateFrequency>
	<?php do_action('rss2_head'); ?>
	<?php while (have_posts()) : the_post(); ?>
		<item>
			<title><?php the_title_rss(); ?></title>
			<link><?php the_permalink_rss(); ?></link>
			<guid isPermaLink="false"><?php the_guid(); ?></guid>
			<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
			<pubDate><?php echo get_the_date('D, d M Y H:i:s +0000'); ?></pubDate>
			<?php do_action('rss2_item'); ?>
			<!-- Topics -->
			<?php
			$terms = get_the_terms($post->ID, 'topics');
			if ($terms && !is_wp_error($terms)) :
				$topics = array();
				foreach ($terms as $term) {
					$topics[] = $term->name;
				}
				$topics = implode(', ', $topics);
			?>
				<topics><?php echo $topics; ?></topics>
			<?php endif; ?>
			<!-- Category -->
			<?php
			$terms = get_the_terms($post->ID, 'category');
			if ($terms && !is_wp_error($terms)) :
				$categories = array();
				foreach ($terms as $term) {
					$categories[] = $term->name;
				}
				$categories = implode(', ', $categories);
			?>
				<category><?php echo $categories; ?></category>
			<?php endif; ?>
			<!-- Featured Image -->
			<?php
			$images = get_attached_media('image', $post->ID);
			if ($images) :
				$image = array_shift($images);
			?>
				<featuredImage><?php echo esc_url(wp_get_attachment_image_url($image->ID, 'large')); ?></featuredImage>
			<?php endif; ?>
			<!-- Add content if request ?content=1 -->
			<?php if (isset($_GET['content']) && $_GET['content'] == 1) : ?>
				<content:encoded><![CDATA[<?php the_content(); ?>]]></content:encoded>
			<?php endif; ?>
			<!-- Add Author Name -->
			<author><?php the_author(); ?></author>
		</item>
	<?php endwhile; ?>
</channel>
</rss>
