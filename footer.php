		<footer class="footer"></footer>

	<?php wp_footer(); ?>
  <script>
    var THEME_URL = "<?php echo get_template_directory_uri() ?>";
    var BASEURL = "<?php echo home_url('/wp-json/wp/v2/') ?>";
    var HOMEURL = "<?php echo home_url() ?>";
    <?php if(is_page()): ?>
      var page_id = "<?php echo get_the_ID(); ?>";
    <?php endif; ?>
  </script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="http://maps.google.com/maps/api/js?key=AIzaSyB7G2H-VADxE_heaPPV31LGpexdZT1nRVY"></script>
  <script src="<?php echo get_template_directory_uri() ?>/js/bundle.js" type="text/javascript" charset="utf-8" async defer></script>
	</body>
</html>
