<?php
/**
 * Template Name: Colaboração Cientifica 
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

?>
<?php get_header(); ?>
<?php header('Content-Type: text/html; charset=utf-8'); ?>
<?php get_sidebar(); ?>
<div class="home-card projects-card">
            <?php
            $mypages = get_pages( array( 'parent' => '7634', 'sort_column' => 'post_name', 'sort_order' => 'asc' ) );
            foreach( $mypages as $page ) {
                $title = $page->post_title; 
                $content = $page->post_content;               
                $link = get_the_permalink($page->ID);
                $title = apply_filters( 'the_title', $title );
                $thumbnail_tag = get_the_post_thumbnail($page->ID, 'full');
                ?>
                <div class="project-landing-Page">
                    <a href="<?php echo $link; ?>">
                         <h3 class="project-title-landing-Page"><?php echo $title ?></h3>
                          <div class="project-logo-container-landing-Page">
                            <?php echo $thumbnail_tag; ?>                          
                        </div>  
                        </a>
                    <p class="project-landing-Page"><?php echo $content ?> </p>
                </div>
                <?php
            }
            ?>   
</div> 
<?php get_footer(); ?>