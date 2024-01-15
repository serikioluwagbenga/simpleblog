<?php
include_once('resources/init.php');
//$posts = (isset($_GET['id'])) ? get_posts($_GET['id']) : get_posts();
$posts = get_posts((isset($_GET['id'])) ? $_GET['id'] : null);
$title = "Crypto Nice Info!";
$des = "All Crypto news at your finger Tip.";
if(isset($_GET['id']) && count($posts) > 0){
   $title = $posts[0]['title'];
   $des = substr($posts[0]['contents'], 500);
}
?>
<!DOCTYPE html>
<!--[if lt IE 8 ]><html class="no-js ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 8)|!(IE)]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->

<head>

   <!--- Basic Page Needs
   ================================================== -->
   <meta charset="utf-8">
   <title><?= $title ?></title>
   <meta name="title" content="<?= $title ?>">
   <meta name="description" content="<?= $des ?>">
   <meta name="key" content="">
   <meta name="author" content="">
   <!-- mobile specific metas
   ================================================== -->
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

   <!-- CSS
    ================================================== -->
   <link rel="stylesheet" href="css/default.css">
   <link rel="stylesheet" href="css/layout.css">
   <link rel="stylesheet" href="css/media-queries.css">

   <!-- Script
   ================================================== -->
   <script src="js/modernizr.js"></script>

   <!-- Favicons
	================================================== -->
   <link rel="shortcut icon" href="images/crypto.png">
   <style>
      a {
         color: #93b876;
      }
   </style>
</head>

<body>

   <!-- Header
   ================================================== -->
   <header id="top">

      <div class="row">

         <div class="header-content twelve columns">

            <h1 id="logo-text"><a href="index.php" title="">Crypto Nice Info!</a></h1>
            <p id="intro">All Crypto news at your finger Tip.</p>

         </div>

      </div>
      <!-- <nav id="nav-wrap">

         <a class="mobile-btn" href="#nav-wrap" title="Show navigation">Show Menu</a>
         <a class="mobile-btn" href="#" title="Hide navigation">Hide Menu</a>

         <div class="row">

            <ul id="nav" class="nav">
               <li class="current"><a href="index.php">Home</a></li>
               <!--<li class="has-children"><a href="#">Dropdown</a>
	                  <ul>
	                     <li><a href="#">Submenu 01</a></li>
	                     <li><a href="#">Submenu 02</a></li>
	                     <li><a href="#">Submenu 03</a></li>
	                  </ul>
	               </li>
	               <li><a href="demo.html">Demo</a></li>	
	               <li><a href="archives.html">Archives</a></li>
			      	<li class="has-children"><a href="single.html">Blog</a>
							<ul>
	                     <li><a href="blog.html">Blog Entries</a></li>
	                     <li><a href="single.html">Single Blog</a></li>	                     
	                  </ul>
			      	</li>
               <li><a href="page.html">About</a></li>
            </ul> 

         </div>

      </nav> -->
       <!-- end #nav-wrap -->

   </header> <!-- Header End -->

   <!-- Content
   ================================================== -->
   <div id="content-wrap">

      <div class="row">

         <div id="main" class="eight columns">

            <article class="entry">
               <?php
               foreach ($posts as $post) {
               ?>
                  <header class="entry-header">

                     <h2 class="entry-title">
                        <h2><a href='index.php?id=<?php echo $post['post_id']; ?>'><?php echo $post['title']; ?></a></h2>
                     </h2>

                     <div class="entry-meta">
                        <ul>
                           <li> <?php echo date('d-m-y h:i:s', strtotime($post['date_posted'])); ?></li>
                           <span class="meta-sep">&bull;</span>
                           <!-- <li><a href="#" title="" rel="category tag">In <a href='category.php?id=<?php echo $post['category_id']; ?>'><?php echo "<font color='green'>" . $post['name'] . "</font>"; ?></a></li> -->
                           <span class="meta-sep">&bull;</span>
                           <li><!-- Blogger user--></li>
                        </ul>
                     </div>

                  </header>


                  <div class="entry-content">
                     <p><?php 
                     if(!isset($_GET['id'])){
                        $more = "";
                        $lenn = 200;
                        if(strlen($post['contents']) > $lenn) {
                           $more = " <a href='?id=".$post['post_id']."'>Read more...</a>";
                        }

                        echo substr(nl2br($post['contents']), 0, $lenn).$more; 
                     }else{
                        // echo "yes baby";
                        echo nl2br($post['contents']); 
                     }
                     
                     ?></p>
                  </div>
                  <hr>
               <?php
               }
               ?>

            </article> <!-- end entry -->

         </div> <!-- end main -->
         <!-- end sidebar -->

      </div> <!-- end row -->

   </div> <!-- end content-wrap -->


   <!-- Footer
   ================================================== -->
   <footer>

      <div class="row">

    

         <p class="copyright">&copy; Copyright <?= date("Y") ?>.</p>

      </div> <!-- End row -->

      <div id="go-top"><a class="smoothscroll" title="Back to Top" href="#top"><i class="fa fa-chevron-up"></i></a></div>

   </footer> <!-- End Footer-->


   <!-- Java Script
   ================================================== -->
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
   <script>
      window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')
   </script>
   <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
   <script src="js/main.js"></script>

</body>

</html>