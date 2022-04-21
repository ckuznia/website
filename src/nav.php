<div id="nav">
  <ul>
    <li>
        <h1 id="site-title">FindAHome</h1>
    </li>
    <li <?php if ($currentTab == 'hometab') { echo "class='current-tab'"; } ?>><a href="home.php">Home</a></li>
    <li <?php if ($currentTab == 'search') { echo "class='current-tab'"; } ?>><a href="search.php">Search</a></li>
    <li <?php if ($currentTab == 'profile') { echo "class='current-tab'"; } ?>><a href="profile.php">Profile</a></li>
    <li class="logout-tab"><a href="handle_logout.php">Logout</a></li>
  </ul>
</div>