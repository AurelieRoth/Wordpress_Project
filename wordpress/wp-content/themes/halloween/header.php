<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    <?php wp_head() ?>
    <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
                    <li> <a href="#"> Je préfère poser les questions ! </a> </li>
                    <li> <a href="#"> Je préfère donner les réponses !</a> </li>
                <br>
<?php if (current_user_can('administrator')) : ?>
    <ul>
    <li> <a href="#"> Article qui fait peur </a></li>
    <li> <a href="#"> Article sur les films d'horreurs</a></li>
    <li> <a href="#"> Article sur la soirée d'Halloween  </a></li>
</ul>
<?php endif ?>
                   
                </ul>
            </div> 
    <div class="container">
  