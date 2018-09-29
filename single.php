<?php get_header(); ?>

<mian id="singlePage">
	<?php //query_posts('category_name=news&posts_per_page=10'); ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	  <p><?php the_time('Y.m.d'); ?></p><p><?php $cat = get_the_category(); ?>
	<?php $cat = $cat[0]; ?>
	<?php echo get_cat_name($cat->term_id); ?></p>
	  <h3><?php the_title(); ?></h3>
	  <div><?php the_content(); ?></div>
	<?php endwhile; endif; ?>
</mian>

<?php get_footer(); ?>