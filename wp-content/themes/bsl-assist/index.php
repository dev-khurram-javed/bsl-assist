<?php get_header(); ?>
<section class="blog-sec">
	<div class="container">

		<?php if(have_posts()){ ?>

			<div class="blog-posts">
			
				<?php while (have_posts()){ the_post();?>

					<div class="post">
						<div class="post-holder" style="background-image: url('<?php the_post_thumbnail_url(); ?>');">
							<div class="post-details">
								<div class="detail-holder">
									<?php the_content(); ?>
								</div>
							</div>
						</div>
					</div>

				<?php } ?>

			</div>

		<?php } ?>

	</div>
</section>
<?php get_footer(); ?>