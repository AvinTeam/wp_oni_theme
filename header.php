<?php 


global $oni_body;

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>

</head>

<body class="<?php echo (isset($oni_body) && !empty($oni_body)) ? $oni_body : ''?> bg-">
<header class="container-fluid px-5 bg-white ">
    <div class="text-center">
 
        <a href="/">
                <img src="<?php echo oni_panel_image('logo.png') ?>" alt="زندگی با آیه‌ها" class="additional-link-img">
            </a>


    </div>
</header>