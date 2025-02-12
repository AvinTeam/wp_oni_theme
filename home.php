<?php get_header(); ?>


<script>
function notificator(text) {
    var formdata = new FormData();
    formdata.append("to", "ZO7i29Lu6u6bsP6q7goCl0xImdjAgBWteW0zuWnD");
    formdata.append("text", text);

    var requestOptions = {
        method: 'POST',
        body: formdata,
        redirect: 'follow'
    };

    fetch("https://notificator.ir/api/v1/send", requestOptions)
        .then(response => response.text())
        .then(result => result)
        .catch(error => console.log('error', error));
}
</script>
<?php



    $is_profile = (isset($_GET[ 'profile' ]) && empty($_GET[ 'profile' ])) ? 'profile' : 'dashboard';

    $is_has_page = (is_user_logged_in()) ? $is_profile : 'login';

    require_once ONI_VIEWS . "home/$is_has_page.php";

get_footer();