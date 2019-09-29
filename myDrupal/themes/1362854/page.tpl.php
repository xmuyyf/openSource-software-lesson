
<?php  
$supra_bar_combo_menu = "";
global $user;
global $base_url;
if ($user->uid){
	print '<div style="height: '.theme_get_setting('cea00_top_margin').'px;"></div>';
	$picture = file_load($user->picture);
	if($picture){
		$filepath = $picture->uri;
		$user_pic = theme('image', array('path' => $filepath, 'alt' => '', 'title' => ''));
    }
    else{
    	$picture = '';
    }
    $user_uri = user_uri($user);
	$user_link = l($user_pic.' '.$user->name,$user_uri['path'], array('html'=>true) );
	$mini_pic = '<div id="mini_pic">'.$user_link.'</div>';
	$menu_custom = menu_navigation_links(theme_get_setting('cea00_menu_select'), $level = 0);
	$combo_menu = "<form><select onchange='window.location.href = this.value'>";
	foreach ( $menu_custom as $menuItem ){
		$combo_menu .= '<option style="display:none" value="?q='.$menuItem['href'].'" >'.$menuItem['title'].'</option>';
		$combo_menu .= '<option value="?q='.$menuItem['href'].'" >'.$menuItem['title'].'</option>';
	}
	$combo_menu .= "</select></form>";
	$home_link = "<div id='supra_bar_home_link'>
	<a href='$base_url'>".t('Home')."</a>
	</div><br />";
	$supra_bar_combo_menu = "<div id='supra_bar_combo_menu'>".$combo_menu.$home_link."</div>".$mini_pic;
}


?>


<?php 
drupal_add_css(drupal_get_path('theme', 'cea00') . '/schemas/'.theme_get_setting('cea00_schema').'.css', array('group' => CSS_DEFAULT, 'every_page' => TRUE));
?>
<div id="supra_bar" class="clearfix">
	<div style="float:left" id="supra_bar_main_menu">
	 <?php print theme('links__system_main_menu', array(
            'links' => $main_menu,
            'attributes' => array(
              'id' => 'main-menu',
              'class' => array('links', 'clearfix'),
            ),
            'heading' => array(
              'text' => t('Main menu'),
              'level' => 'h2',
              'class' => array('element-invisible'),
            ),
          )); ?>
	</div>  <!-- /#supra_bar_main_menu -->
	<?php print $supra_bar_combo_menu;  ?>
</div>

<div id="supra_logo" class="clearfix">
  <header id="header" role="banner" class="clearfix">
	<?php if ($logo): ?>
      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" id="logo">
        <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
      </a>
    <?php endif; ?>
    <?php if ($site_name || $site_slogan): ?>
      <hgroup id="site-name-slogan">
        <?php if ($site_name): ?>
          <h1 id="site-name">
            <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"><span><?php print $site_name; ?></span></a>
          </h1>
        <?php endif; ?>
        <?php if ($site_slogan): ?>
          <h2 id="site-slogan"><?php print $site_slogan; ?></h2>
        <?php endif; ?>
      </hgroup>
    <?php endif; ?>

    <?php print render($page['header']); ?>

    <?php if ($main_menu || $secondary_menu || !empty($page['navigation'])): ?>
      <nav id="navigation" role="navigation" class="clearfix">
        <?php if (!empty($page['navigation'])): ?> <!--if block in navigation region, override $main_menu and $secondary_menu-->
          <?php print render($page['navigation']); ?>
        <?php endif; ?>
        <?php if (empty($page['navigation'])): ?>
		 


		  <?php print theme('links__system_secondary_menu', array(
            'links' => $secondary_menu,
            'attributes' => array(
              'id' => 'secondary-menu',
              'class' => array('links', 'clearfix'),
            ),
            'heading' => array(
              'text' => t('Secondary menu'),
              'level' => 'h2',
              'class' => array('element-invisible'),
            ),
          )); ?>
        <?php endif; ?>
      </nav> <!-- /#navigation -->
    <?php endif; ?>
    
  </header> <!-- /#header -->
</div>

<div id="container" class="clearfix">

  <div id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
    <?php if ($main_menu): ?>
      <a href="#navigation" class="element-invisible element-focusable"><?php print t('Skip to navigation'); ?></a>
    <?php endif; ?>
  </div>



  <section id="main" role="main" class="clearfix">
<?php if ($breadcrumb): print $breadcrumb; endif;?>
    <?php print $messages; ?>
    <a id="main-content"></a>
    <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>
    <?php print render($title_prefix); ?>
    <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php if (!empty($tabs['#primary'])): ?><div class="tabs-wrapper clearfix"><?php print render($tabs); ?></div><?php endif; ?>
    <?php print render($page['help']); ?>
    <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
    <?php print render($page['content']); ?>
  </section> <!-- /#main -->
  
  <?php if ($page['sidebar_first']): ?>
    <aside id="sidebar-first" role="complementary" class="sidebar clearfix">
      <?php print render($page['sidebar_first']); ?>
    </aside>  <!-- /#sidebar-first -->
  <?php endif; ?>

  <?php if ($page['sidebar_second']): ?>
    <aside id="sidebar-second" role="complementary" class="sidebar clearfix">
      <?php print render($page['sidebar_second']); ?>
    </aside>  <!-- /#sidebar-second -->
  <?php endif; ?>

  <footer id="footer" role="contentinfo" class="clearfix">
    <?php print render($page['footer']) ?>
    <?php print $feed_icons ?>
  </footer> <!-- /#footer -->

</div> <!-- /#container -->
