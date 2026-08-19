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
            
       <!--    <main id="main">-->
       <!--     <?php  include("news/".$_GET["EventDetails"].".php") ?>-->
       
       <!--</main>-->
       
       <main id="main">
     <?php
        $eventDetails = $_GET['EventDetails'] ?? '';

        // Allow only safe filename characters
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $eventDetails)) {
            http_response_code(404);
            echo '<h2 style="text-align:center; padding:100px 20px;">Event Not Found</h2>';
        } else {
            $eventFile = __DIR__ . '/news/' . $eventDetails . '.php';

            // Check that the requested file actually exists
            if (is_file($eventFile)) {
                include $eventFile;
            } else {
                http_response_code(404);
                echo '<h2 style="text-align:center; padding:100px 20px;">Event Not Found</h2>';
            }
        }
    ?>
    </main>
            
          <?php include('common/footer.php'); ?>
    </body>
</html>