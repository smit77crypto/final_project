<nav>
    <div class="right">
        <a href="admin_login.php" style="text-decoration:none">
            <div class="btn">
                <div><i class="fa-solid fa-right-from-bracket"></i></div>
                <div style="font-weight: bold; color: white;">LOG OUT</div>
            </div>
        </a>
    </div>
    <div class="left">
        <ul class="ul-box">
            <li class="nav-item">
                <a class="nav-link" href="admin_home.php" id="gameManagementLink">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="game_management.php" id="gameManagementLink">Game Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="user_management.php" id="userManagementLink">User Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="terms_condition.php" id="termsLink">Terms and Conditions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="viewSlotsLink">View Slots</a>
            </li>
        </ul>
    </div>
    <div class="right">
        <a href="admin_login.php" style="text-decoration:none; color:white">
            <div class="btn">
                <div><i class="fa-solid fa-right-from-bracket"></i></div>
                <div style="font-weight: bold">LOG OUT</div>
            </div>
        </a>
    </div>
</nav>
<nav class="mobile-nav">
    <div class="mobile-menu">
        <i class="fa-solid fa-bars"></i>
    </div>
    <div class="right">
        <a href="admin_login.php" style="text-decoration:none">
            <div class="btn">
                <div><i class="fa-solid fa-right-from-bracket"></i></div>
                <div style="font-weight: bold; color: white;">LOG OUT</div>
            </div>
        </a>
    </div>
    
    <div class="right">
        <a href="admin_login.php" style="text-decoration:none; color:white">
            <div class="btn">
                <div><i class="fa-solid fa-right-from-bracket"></i></div>
                <div style="font-weight: bold">LOG OUT</div>
            </div>
        </a>
    </div>
</nav>
<div class="mobile-box">
        <ul class="m-ul-box">
            <li class="nav-item">
                <a class="m-nav-link" href="admin_home.php" id="gameManagementLink">Home</a>
            </li>
            <li class="nav-item">
                <a class="m-nav-link" href="game_management.php" id="gameManagementLink">Game Management</a>
            </li>
            <li class="nav-item">
                <a class="m-nav-link" href="user_management.php" id="userManagementLink">User Management</a>
            </li>
            <li class="nav-item">
                <a class="m-nav-link" href="terms_condition.php" id="termsLink">Terms and Conditions</a>
            </li>
            <li class="nav-item">
                <a class="m-nav-link" href="#" id="viewSlotsLink">View Slots</a>
            </li>
        </ul>
    </div>
    <script>
        // Toggle the mobile box visibility with transition
        document.querySelector('.mobile-menu').addEventListener('click', function() {
            var mobileBox = document.querySelector('.mobile-box');
            mobileBox.classList.toggle('show'); // Toggle the 'show' class to display the menu
        });
        window.addEventListener('resize', function() {
    var mobileBox = document.querySelector('.mobile-box');
    if (window.innerWidth >= 768) {
        mobileBox.classList.remove('show'); // Hide the mobile menu when resizing to desktop
    }
});
    </script>