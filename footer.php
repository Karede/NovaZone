<footer class="mt-5" id="footer" role="contentinfo">
    <img class="footer-top w-100" src="<?php echo get_template_directory_uri(); ?>/images/footer-top.png" alt="<?php bloginfo('name'); ?>">
    <div class="background">
        <div class="container pt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6">
                    <h2 class="mt-4">CONTACT US</h2>
                    <?php echo do_shortcode('[contact-form-7 id="6" title="NovaZone Contact Form"]'); ?>
                </div><!--/.col-12-->
            </div><!--/.row-->
        </div><!--/.container-->
        <img class="w-100" src="<?php echo get_template_directory_uri(); ?>/images/footer-bottom.png" alt="<?php bloginfo('name'); ?>">
    </div><!--/.background-->
</footer><!--/.footer-->

<?php wp_footer(); ?>

<?php
$gaId = rwmb_meta('google_analytics_id', array('object_type' => 'setting'), 'site_options');
if (!is_preview() && !empty($gaId)): 
?>
        <script>
            window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
            ga('create', <?php echo $gaId; ?>,'auto');ga('send','pageview')
        </script>
        <script src="https://www.google-analytics.com/analytics.js" async defer></script>
<?php endif; ?>
    </body>
</html>
