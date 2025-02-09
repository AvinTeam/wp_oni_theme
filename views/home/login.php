<?php

    function oni_title_filter_login($title)
    {

        $title = get_bloginfo('name') . " | ورود ";
        return $title;
    }
    add_filter('wp_title', 'oni_title_filter_login');

?>

<div class="oni-body mx-auto  pb-5 rounded-3 h-100 ">
    <header class="oni-d-row mx-auto d-flex flex-column justify-content-around align-items-center rounded-bottom-5 ">
        <div class="d-flex flex-column justify-content-around oni-login-header ">
            <img class="img-fluid w-100" style="height: 105px;"
                src="<?php echo oni_panel_image('zendegibaayeha_logo.png') ?> ">
            <img class="img-fluid mt-3 w-100" src="<?php echo oni_panel_image('oni_logo.png') ?> ">
        </div>
        <h3 class="text-center my-3 text-white font-14px"><?php echo get_bloginfo('description') ?></h3>
    </header>
    <div class="height-48"></div>
    <div class="oni-d-row oni-form mx-auto d-flex flex-column justify-content-between text-center">

        <form id="loginForm">
            <?php wp_nonce_field('oni_login_page' . oni_cookie()); ?>

            <div id="mobileForm">
                <img src="<?php echo oni_panel_image('mobile.png') ?>">
                <div class="height-32"></div>
                <div class="form-group text-center">
                    <input type="tel" inputmode="numeric" pattern="\d*"
                        class="form-control form-control-lg onlyNumbersInput border-2 height-48" id="mobile"
                        maxlength="11" placeholder="09123456789" aria-describedby="sendsms">
                </div>
                <div class="height-48"></div>
                <div class="form-group d-grid ">
                    <button type="submit" disabled id="send-code"
                        class="btn btn-primary height-48 w-100 text-center py-3 d-flex flex-row justify-content-center align-items-center gap-3 ">
                        <img src="<?php echo oni_panel_image('btn-icon.png') ?>">
                        <span>دریافت کد</span>
                    </button>
                </div>
            </div>

            <div id="codeVerification"  style="display: none;">
                <img src="<?php echo oni_panel_image('codeVerify.png') ?>">
                <div class="height-32"></div>
                <div class="form-group text-center">
                    <input autocomplete="one-time-code" type="text" inputmode="numeric" pattern="\d*"
                        class="form-control form-control-lg onlyNumbersInput border-2 height-48 text-center"
                        id="verificationCode" maxlength="<?php echo $oni_option[ 'set_code_count' ] ?>"
                        placeholder="<?php for ($i = 0; $i < $oni_option[ 'set_code_count' ]; $i++) {echo 'ـــ ';}?>"
                        aria-describedby="verify">

                    <div class="d-flex flex-row justify-content-between px-3">
                        <div class="timer text-center font-12px  text-primary" id="timer">00:00</div>
                        <button type="button" class="btn btn-link btn-block font-12px text-primary" id="resendCode" disabled>ارسال مجدد
                        کد</button>
                    </div>

                </div>
                <div class="height-48"></div>
                <div class="form-group ">
                    <button type="submit" disabled id="verifyCode"
                        class="btn btn-primary  height-48 w-100 text-center py-3 d-flex flex-row justify-content-center align-items-center gap-3 ">
                        <img src="<?php echo oni_panel_image('btn-icon.png') ?>">
                        <span>تایید و ورود</span>
                    </button>
                    <div class="height-12"></div>
                    <button type="button" id="editNumber"
                        class="btn btn-outline-primary height-48 w-100 text-center py-3 d-flex flex-row justify-content-center align-items-center gap-3 ">
                        <img src="<?php echo oni_panel_image('btn-icon.png') ?>">
                        <span>تغییر شماره</span>
                    </button>
                </div>
            </div>




        </form>

        <div class="">

            <img style="width: 30%;" class="" src="<?php echo oni_panel_image('logofooter.png') ?>">

            <h3 class="font-14px text-primary">در شبکه های اجتماعی</h3>
            <div class="height-24"></div>
            <div class="d-flex flex-row justify-content-between align-items-center oni-social ">
                <a class="rounded-circle border border-1 oni-border-color p-2" href="#"><img
                        src="<?php echo oni_panel_image('rubika.png') ?>"></a>
                <a class="rounded-circle border border-1 oni-border-color p-2" href="#"><img
                        src="<?php echo oni_panel_image('telegram.png') ?>"></a>
                <a class="rounded-circle border border-1 oni-border-color p-2" href="#"><img
                        src="<?php echo oni_panel_image('instagram.png') ?>"></a>
                <a class="rounded-circle border border-1 oni-border-color p-2" href="#"><img
                        src="<?php echo oni_panel_image('bale.png') ?>"></a>
                <a class="rounded-circle border border-1 oni-border-color p-2" href="#"><img
                        src="<?php echo oni_panel_image('eitaa.png') ?>"></a>
            </div>
        </div>
    </div>
</div>




<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="loginToast" class="toast align-items-center text-white bg-danger border-0" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">

            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
if ('OTPCredential' in window) {
    const verifyCodeButton = document.getElementById('verifyCode');

    // انتخاب فیلد ورودی
    const inputVerificationCode = document.getElementById('verificationCode');

    if (inputVerificationCode) {
        //return; // پایان اسکریپت در صورت عدم وجود فیلد ورودی


        const ac = new AbortController();

        navigator.credentials
            .get({
                otp: {
                    transport: ['sms'],
                },
                signal: ac.signal,
            })
            .then((otp) => {

                if (otp && otp.code) {
                    inputVerificationCode.value = otp.code;

                    verifyCodeButton.click();

                    verifyLogin(otp.code);


                } else {}

                ac.abort();
            })
            .catch((err) => {

                if (ac.signal.aborted === false) {
                    ac.abort();
                }
            });
    }
} else {

    console.warn('OTPCredential API is not supported in this browser.');
}
</script>