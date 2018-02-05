<html>
    <head>
        <title>Main Page</title>
        <link rel="stylesheet" href="design/style.css">
    </head>
    <body>
        <div class="body_block">
            <?php
                if (!isset($_SESSION)) :
                    header('Location: login.php');
                else :
                    header('Location: main.php');
                endif;
            ?>
        </div>
    </body>
</html>