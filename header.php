<!doctype html>
<html <?php language_attributes(); ?> class="no-js">

<head>

  <!-- Global site tag (gtag.js) - Google Analytics
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-113904172-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-113904172-1');
  </script>
  -->

  <!-- Page-hiding snippet (recommended)  -->
  <style>.async-hide { opacity: 0 !important} </style>
  <script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
  h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
  (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
  })(window,document.documentElement,'async-hide','dataLayer',4000,
  {'GTM-TL3J5BS':true});</script>
  <!-- Modified Analytics tracking code with Optimize plugin -->
      <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-113904172-1', 'auto');
      ga('require', 'GTM-TL3J5BS');
      ga('send', 'pageview');
      </script>

  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?php bloginfo('description'); ?>">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!--[if lt IE 9]>
  <section class="browser-upgrade" role="alert"><h1>You are using an <strong>outdated</strong> browser.</h1> For a better experience, please <a href="http://outdatedbrowser.com/">upgrade your browser</a>.</section>
<![endif]-->

<header id="header" role="banner">
    <img class="w-100 mb-5" src="<?php echo get_template_directory_uri(); ?>/images/novazone-header.png" alt="<?php bloginfo('name'); ?>">
</header><!--/#header-->
