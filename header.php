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
    <script async defer src="https://tianji.ayeh.net/tracker.js" data-website-id="cm7fy58450gbg55wmhin3ylbo"></script>
</head>

<body class="<?php echo (isset($oni_body) && !empty($oni_body)) ? $oni_body : ''?> bg-">
