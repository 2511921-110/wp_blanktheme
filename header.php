<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<header class="header">
   <nav class="header__nav col-xs-120">
    <span class="header__nav_close"></span>
    <?php
      wp_nav_menu(
        array(
          'menu' => 'global',
          'container_id' => 'gnavi',
          'container_class' => 'nav',
          'items_wrap'      => '<ul class="globalnav">%3$s</ul>',
        )
      );
    ?>
    </nav> 
  </header>