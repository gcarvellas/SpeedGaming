<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<header>
    <img class="logo" src="logo.png">
    <nav class="nav">
        <a class="fa fa-home fa-2x" href="index.php"></a>
        <?php
            if (basename($_SERVER['PHP_SELF']) === "queue.php"){
                include 'jQuery/isOnline.php';
            }
            elseif (basename($_SERVER['PHP_SELF']) === "queue.inc.php"){
                include '../jQuery/isOnline.php';
            }
            
            require_once 'includes/functions.inc.php';
            
            if (isset($_SESSION["useruid"])){
                echo "<a class='tab' href='profile.php'>" . "<img class='tab' id='tabPicture' src=" . showProfilePicture() . ">" . "</a>";
                echo "<a class='tab' href='includes/logout.inc.php'>Logout</a>";
                //echo "<a class='tab' href='messenger.php'>Find Player</a>";
                echo "<a class='tab' id='findPlayer' href='queue.php'>Find Player</a>";
            }
            else{
                echo "<a class='tab' href='login.php'>Login</a>";
                echo "<a class='tab' href='signup.php'>Sign Up</a>";
            }
        ?>
    </nav>
</header>
