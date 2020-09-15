<?php get_header () ?>
 <div container>
   
<?php while(have_posts()) : the_post() ?>
    <h1> Pour bien préparer Noël .. </h1>

    <audio controls>
       <source src="<?=get_theme_file_uri('/assets/img/music.mp3')?>"
</audio>


<?php endwhile; ?>
</nav>
</div>
<?php get_footer() ?>