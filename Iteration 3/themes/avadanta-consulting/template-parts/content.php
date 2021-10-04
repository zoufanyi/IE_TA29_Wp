<?php 
/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * 
 * @subpackage Avadanta
 */
?>
<div class="col-lg-12 col-md-12">

<div class="post post-full avadanta-business" id="post-<?php the_ID(); ?>">
	<div class="row">
		<div class="col-lg-5 col-md-6 p-0">
	<div class="post-thumb">
		<?php if(has_post_thumbnail()) { ?>
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail(); ?>
		</a>
		 <?php } else { ?>
		 	<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/default-blog.png
		 	">
		 <?php } ?>
	</div>
</div>
	<div class="col-lg-7 col-md-6">
	<div class="post-entry d-sm-flex d-block align-items-start avadanta-consult">
		<div class="post-content">
			<div class="post-author d-flex align-items-center">
				<div class="author-thumb">
					<?php echo get_avatar( get_the_author_meta('user_email'), $size = '50'); ?>
				</div>
				<div class="author-name">
					<?php avadanta_posted_by(); ?>
				</div>
				<div class="post-tag d-flex comment">
				<?php avadanta_get_comments_numbers(); ?>
				</div>
				<div class="post-tag d-flex date">
				<?php avadanta_posted_on(); ?>
				</div>

			</div>
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<div class="content">
					<?php
						the_excerpt();
					?>
			</div>
			<div class="post-tag post-entry d-flex avadantaconslt-readmre">
				<a href="<?php the_permalink(); ?>"><?php esc_html_e('Read More','avadanta-consulting'); ?>
				</a>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>