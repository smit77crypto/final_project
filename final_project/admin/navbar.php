<nav>
    <div class="logo">
        <img src="uploads/gip_logo.png" alt="logo" width="auto" height="50px">
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
                <a class="nav-link" href="terms_condition.php" id="termsLink">Terms & Condition</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="games_slots.php" id="viewSlotsLink">Book Game</a>
            </li>
        </ul>
    </div>
    <!-- Profile Icon Section -->
    <div class="profile" onclick="toggleProfilePopup()">
        <i class="fas fa-user-circle"></i> <!-- Profile Icon -->
        <!-- Popup that will show on click -->
        <div class="profile-popup">
            <a href="admin_profile.php">Profile</a> <!-- Profile Option -->
            <a href="admin_login.php">Logout</a> <!-- Logout Option -->
        </div>
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

    <div class="logo">
        <img src="uploads/gip_logo.png" alt="logo">
    </div>
    <div class="profile" onclick="toggleProfilePopup()">
        <i class="fas fa-user-circle"></i> 
        <div class="profile-popup">
            <a href="admin_profile.php">Profile</a> 
            <a href="admin_login.php">Logout</a> 
        </div>
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
            <a class="m-nav-link" href="games_slots.php" id="viewSlotsLink">Book Game</a>
        </li>
       
    </ul>
</div>
<script>
// Function to toggle the profile popup for both desktop and mobile
const profileIcons = document.querySelectorAll('.profile');
const profilePopups = document.querySelectorAll('.profile-popup');

profileIcons.forEach((profileIcon) => {
  profileIcon.addEventListener('click', function (event) {
    event.stopPropagation(); // Prevent the event from bubbling up
    profileIcon.classList.toggle('show');
  });
});

// Close the popup if the user clicks outside the profile
document.addEventListener('click', function (event) {
  profileIcons.forEach((profileIcon) => {
    if (!profileIcon.contains(event.target)) {
      profileIcon.classList.remove('show');
    }
  });
});


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