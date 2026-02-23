<link rel="stylesheet" href="styles.css">
<aside class="sidebar">
  <div class="profile-box">
    <?php if(!empty($student['photo'])): ?>
      <img class="sidebar-photo" src="uploads/<?php echo $student['photo']; ?>" />
    <?php else: ?>
      <div class="sidebar-photo placeholder"></div>
    <?php endif; ?>

    <h3 class="sidebar-name">
      <?php echo $student['full_name']; ?>
    </h3>
  </div>

  <nav class="sidebar-menu">
    <a href="home.php" class="<?= isset($active) && $active == 'home' ? 'active' : '' ?>">Home</a>
    <a href="dashboard.php" class="<?= isset($active) && $active == 'profile' ? 'active' : '' ?>">Profile</a>
    <a href="store.php" class="<?= isset($active) && $active == 'store' ? 'active' : '' ?>">Store</a>
    <a href="logout.php" class="<?= isset($active) && $active == 'logout' ? 'active' : '' ?>">Logout</a>
  </nav>
</aside>

