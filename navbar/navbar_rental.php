<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
    /* Custom CSS for the side navigation bar */
    .vertical-navbar {
        height: 100vh;
        position: fixed;
        top: 0;
        left: -250px;
        /* Initially hidden to the left */
        background-color:#104911;
        /* Dark gray background color */
        padding-top: 20px;
        padding-left: 15px;
        width: 250px;
        text-align: left;
        border-right: 1px solid #222;
        /* Darker gray border */
        transition: left 0.3s;
        /* Smooth sliding animation */
        z-index: 999;
        /* Ensure it overlaps other content */
    }

    .vertical-navbar a {
        padding: 15px 0;
        text-decoration: none;
        font-size: 18px;
        color: #fff;
        display: block;
        transition: 0.3s;
    }

    .vertical-navbar i {
        margin-right: 10px;
        /* Space between icons and text */
    }

    .vertical-navbar a:hover {
        background-color: #444;
        /* Slightly lighter gray when hovered */
        color: #007BFF;
        /* Blue color for text on hover */
    }

    /* Custom CSS for the button to show/hide the sidebar */
    .sidebar-toggle {
        position: fixed;
        top: 20px;
        left: 20px;
        background-color:#104911;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
    }

    /* Style for the button to hide the sidebar */
    .hide-sidebar-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 50%;
        cursor: pointer;
    }
    .horizontal-navbar {
    background-color: #104911;
}
    </style>
</head>
<body>
    <nav class="vertical-navbar">
        <button class="btn btn-primary" onclick="hideSidebar()">
            <i class="fas fa-times"></i>
        </button>
        <a href="../dashboard_farmer.php"><i class="fas fa-home"></i> Home</a>
        <a href="../profile_farmer.php"><i class="fas fa-user"></i> Profile</a>
        <!-- <a href="editprofile_farmer.php" id="edit_profile"><i class="fas fa-user-plus"></i>Edit profile</a> -->

        <a href="#"><i class="fas fa-newspaper"></i> News</a>
        <a href="../farmer_add_crop.php"><i class="fas fa-leaf"></i> Crops</a>
        <a href="../scheme_applied.php"><i class="fas fa-landmark"></i> Applied Schemes</a>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-lg bg-#104911 horizontal-navbar">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <button class="btn btn-primary" id="showSidebar" onclick="showSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand text-white" style="padding-left: 100px;" href="#"> AgroCompanion</a>
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="../dashboard_farmer.php">Home</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link text-white" href="#">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Explore</a>
                </li> -->

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <!-- <img src="images/avatar/admin.png" style="height: 30px;" alt="User Avatar"class="navbar-avatar"> -->

                        <?php
                        if (isset($_SESSION['useremail'])) {
                            echo '' .$_SESSION['useremail'];
                        }
                        ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Profile</a>
                        <a class="dropdown-item" href="#">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content goes here -->

    <script>
    function showSidebar() {
        const sidebar = document.querySelector(".vertical-navbar");
        sidebar.style.left = "0px";
    }
    function hideSidebar() {
        const sidebar = document.querySelector(".vertical-navbar");
        sidebar.style.left = "-250px";
    }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>