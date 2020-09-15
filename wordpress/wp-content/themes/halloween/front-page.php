<?php get_header () ?>

<?php while(have_posts()) : the_post() ?>
    <h1><?php the_title() ?></h1>
    <nav class="navbar navbar-dark bg-dark" id="nav">
  <span class="navbar-brand mb-0 h1"> Nos Articles </span>

    
    <a href="<?= get_post_type_archive_link('post') ?>">Voir tout les articles </a>
    <table class="table table-bordered table-dark">
      <audio controls autoplay="" loop="" style="display: none;">
       <source src="<?=get_theme_file_uri('/assets/img/horreur.mp3')?>">
      </audio>
  <tbody>
    <tr>
      <td>Article qui fait peur .. </td>
    </tr>
    <tr>
      <td>Article sur les films d'horreurs .. </td>
    </tr>
    <tr>
      <td>Article sur la soirée d'Halloween .. </td>
    </tr>
  </tbody>

<?php endwhile; ?>
</nav>

<?php get_footer() ?>