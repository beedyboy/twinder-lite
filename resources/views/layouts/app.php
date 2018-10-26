<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?=$this->siteTitle(); ?></title> 
<link rel="stylesheet" href="<?=base_url.'public/css/style.css'; ?>"> 
<link rel="stylesheet" href="<?=base_url.'public/css/color.css'; ?>">
<link rel="stylesheet" href="<?=base_url.'public/css/layout.css'; ?>">
<link rel="stylesheet" href="<?=base_url.'public/css/form.css'; ?>">
<link rel="stylesheet" href="<?=base_url.'public/css/navbar.css'; ?>">
<link rel="stylesheet" href="<?=base_url.'public/css/cards.css'; ?>">
<link rel="stylesheet" href="<?=base_url.'public/css/table.css'; ?>">
<link rel="stylesheet" href="<?=base_url.'public/css/beedy_kaydee.css'; ?>">
<link rel="stylesheet" href="<?=base_url.'public/css/fontawesome-all.css'; ?>">
<link rel="stylesheet" href="<?=base_url.'public/css/themify-icons.css'; ?>">

<?=$this->content('head'); ?>
<style>
  	
body{
	 box-sizing: border-box; 
}  
  </style>
</head>
<body>
<input type="hidden" id="base_url" value="<?=base_url?>">
	 <?php include 'main_menu.php'; ?>
<div class="container border">
	

<?= $this->content('body'); ?>




</div>
<script src="<?=base_url.'public/js/vue.js'; ?>"></script>
<script src="<?=base_url.'public/js/axios.js'; ?>"></script>

<script src="<?=base_url.'public/js/jquery-3.3.1.min.js'; ?>"></script>
<script src="<?=base_url.'public/js/apps.js'; ?>"></script>

<?= $this->content('scripts'); ?>	
	
<script type="text/javascript">


 document.addEventListener('DOMContentLoaded', function () {
 
  // Get all "navbar-burger" elements
  var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  // Check if there are any navbar burgers
  if ($navbarBurgers.length > 0) {

    // Add a click event on each of them
    $navbarBurgers.forEach(function ($el) {
      $el.addEventListener('click', function () {

        // Get the target from the "data-target" attribute
        var target = $el.dataset.target;
        var $target = document.getElementById(target);

        // Toggle the class on both the "navbar-burger" and the "navbar-menu"
        $el.classList.toggle('is-active');
        $target.classList.toggle('menu-active');

      });
    });
  }

});


</script>

</body>
</html>