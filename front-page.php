<?php get_header(); ?>

<div class="container">
        <div class="row mb-3">
                <div class="col-sm-6 mb-3">
                <div class="row">
                        <div class="col-md-2 hidden-sm-down">
                                <img class="carats h-100" src="<?php echo get_template_directory_uri(); ?>/images/large-left-carat.png" alt="<?php bloginfo('name'); ?>">
                        </div><!--/.col-sm-2-->
                        <div class="col-md-8 slider-quote">                      
                            <?php $args = array( 'post_type' => 'slider-quote', 'posts_per_page' <= -1 );
                            $loop = new WP_Query( $args );
                            while ( $loop->have_posts() ) : $loop->the_post();
                                echo '<p>';
                                the_content();
                                echo '</p>';
                            endwhile; ?>
                        </div><!--/.col-sm-8-->
                        <div class="col-md-2 hidden-sm-down">
                                <img class="carats h-100" src="<?php echo get_template_directory_uri(); ?>/images/large-right-carat.png" alt="<?php bloginfo('name'); ?>">
                        </div><!--/.col-sm-2-->
                </div><!--/.row-->
                <div class="row">
                        <div class="col-sm-12">
                                <img class="maple-leaf" src="<?php echo get_template_directory_uri(); ?>/images/maple-leaf.png" alt="<?php bloginfo('name'); ?>">
                        </div><!--/.col-sm-12-->
                </div><!--/.row-->

                </div><!--/.col-sm-6-->
                <div class="col-sm-6">
                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                        the_content();
                        endwhile; else: ?>
                        <p>Sorry, no posts matched your criteria.</p>
                        <?php endif; ?>
                        <hr>
                        <p class="visit-partners">Visit our partner sites:</p>
                        <a href="http://novaporte.ca/"> <!--<a href="http://novazone.novastream.ca/test-1/">-->
                                <img class="partner-image mb-3" src="<?php echo get_template_directory_uri(); ?>/images/novaporte-banner.jpg" alt="<?php bloginfo('name'); ?>">
                        </a>
                        <a href="https://canderel.com/en"> <!--<a href="http://novazone.novastream.ca/test-2/">-->
                                <img class="partner-image mb-3" src="<?php echo get_template_directory_uri(); ?>/images/canderel-banner.jpg" alt="<?php bloginfo('name'); ?>">
                        </a>
                        <a href="http://cbrm-ftz.ca/"> <!--<a href="http://novazone.novastream.ca/test-3/">-->
                                <img class="partner-image mb-3" src="<?php echo get_template_directory_uri(); ?>/images/cbrmftz-banner.jpg" alt="<?php bloginfo('name'); ?>">
                        </a>
                </div><!--/.col-sm-6-->
        </div><!--/.row-->
</div>
<div class="img-gallery">
<div class="container">
        <div class="row">
                <div class="col-sm-12">
                        <h2 class="gallery-title mt-4 mb-4">PHOTO GALLERY</h2>
                        <?php echo do_shortcode('[envira-gallery id="71"]'); ?>
                </div><!--/.col-sm-12-->
        </div><!--/.row-->
</div><!--/.container-->
</div>

<?php get_footer(); ?>