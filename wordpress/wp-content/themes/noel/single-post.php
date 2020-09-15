<?php get_header() ?>

       <?php the_post_thumbnail() ?>
       <p> 
           <img src="<?php the_post_thumbnail_url(); ?>" alt="" style="width: 100%; height:auto;">
     <?php the_content() ?>
 
<?php get_footer() ?>