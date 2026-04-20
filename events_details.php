<?php $pagename = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>

<html lang="en">
    <head>
     
<?php include('common/head.php'); ?>
    </head>
    <body class="appear-animate">
        
        <!-- Page Loader -->        
        <div class="page-loader">
            <div class="loader">Loading...</div>
        </div>
        <!-- End Page Loader -->

      
        
        <!-- Page Wrap -->
        <div class="page" id="top">
            
           <?php include('common/nav.php'); ?>
            
           <main id="main">
            <?php  include("news/".$_GET["EventDetails"].".php") ?>
       
       </main>
            
          <?php include('common/footer.php'); ?>
    </body>
</html>