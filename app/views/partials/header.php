  <head>
    <title>Camagru <?php if (isset($title)) { echo '- ' . $title; } ?></title>
    <meta name="viewport" charset="UTF-8" content="width=device-width, initial scale=1">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>/public/css/camagru.css">
  </head>

  <body style="background-image: url('<?php 
			if (($ran = rand(0, 2)) == 0)
			{ 
  			echo SITE_URL . "/public/imgs/3.jpg"; 
  		} 
			else if ($ran == 1)
			{ 
  			echo SITE_URL . "/public/imgs/3.jpg"; 
			} 
			else 
			{ 
  			echo SITE_URL . "/public/imgs/1.jpg"; 
  		} 
  	?>');">