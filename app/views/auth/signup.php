<html lang="en">

  <?php if (file_exists(ROOT_DIR . '/app/views/partials/header.php')) {$title = "Home"; require_once ROOT_DIR . '/app/views/partials/header.php'; } ?>

    <header>

      <ul class="topnav" id="myTopnav">
        <li><a class="active" href="<?php echo SITE_URL; ?>/index.php "style="font-family: cursive;">Camagru</a></li>
        <?php if (isset($_SESSION['user'])) { echo '<li><a href="' . SITE_URL . '/auth/logout">Log out</a></li>'; } else { echo '<li><a href="' . SITE_URL . '/auth/login">Log in</a></li>'; } ?>
        <li class="icon"><a href="javascript:void(0);" style="font-size:15px;" onclick="open_close()">☰</a></li>
      </ul>


    </header>

    <?php if (file_exists(ROOT_DIR . '/app/views/flash/flash.php')) { require_once ROOT_DIR . '/app/views/flash/flash.php'; } ?>

    <form id="signup" name="signup" action="<?php echo SITE_URL; ?>/auth/signup" method="POST">
      <div class="container">
        <h3 class="info-text" style="color: teal; text-align: center; font-family: cursive;">Sign Up to create, share & like pics!</h3>
        <label><b class="p-text" id="b-email" style="color: teal; font-family: cursive;">E-mail</b></label>
        <p id="err-email" style="color: red; display: none; font-style: bold;">:</p>
        <input id="email" type="email" placeholder="placeholder@domain.com" name="email" required>

        <label><b class="p-text" id="b-username" style="color: teal; font-family: cursive;">Username</b></label>
        <p id="err-username" style="color: red; display: none; font-style: bold;">:</p>
        <input id="username" type="text" placeholder="e.g. ftait" name="username" required>

        <label><b class="p-text" id="b-password" style="color: teal; font-family: cursive;">Password</b></label>
        <p id="err-password" style="color: red; display: none; font-style: bold;">:</p>
        <input id="password" type="password" placeholder="8 characters minimum" name="password" required>

        <button id="signup-button" type="submit" style="background-color: #333; font-family: cursive;">Sign me up !</button>
      </div>
    </form>

  <?php if (file_exists(ROOT_DIR . '/app/views/partials/footer.php')) { require_once ROOT_DIR . '/app/views/partials/footer.php'; } ?>
</html>
