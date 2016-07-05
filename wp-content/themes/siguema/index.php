<?php get_header(); ?>

<article class="content">
    <?php if (have_posts()) : the_post(); ?>
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
    <?php endif; ?>
    <div class="clear"></div>
</article>

<?php get_footer(); ?>