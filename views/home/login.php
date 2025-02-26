<?php

    function oni_title_filter_login($title)
    {

        $title = get_bloginfo('name') . " | ورود ";
        return $title;
    }
    add_filter('wp_title', 'oni_title_filter_login');

?>
<div class="oni-body mx-auto w-100 pb-5 rounded-3 h-100  position-relative">
    <header id="login" style="margin-top: -30px; padding-top: 30px;"
        class="w-100 rounded-24px  mx-auto d-flex flex-column justify-content-around align-items-center">
        <div class="d-flex flex-column justify-content-around oni-login-header ">
            <img class="" style="height: 105px; width: 186.14px; "
                src="<?php echo oni_panel_image('zendegibaayeha_logo.svg') ?> ">
            <div class="h-16px"></div>

            <img class="img-fluid mt-3" style="height: 70.22px; width: 194.67px; "
                src="<?php echo oni_panel_image('oni_logo.svg') ?> ">
        </div>
        <h3 class="text-center my-3 text-white f-14px"><?php echo get_bloginfo('description') ?></h3>
    </header>
    <div class="h-48px"></div>
    <div class="w-100 rounded-8px oni-form mx-auto d-flex flex-column justify-content-between text-center">

        <form id="loginForm">
            <?php wp_nonce_field('oni_login_page' . oni_cookie()); ?>

            <div id="mobileForm" >
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h2 class="f-22px fw-heavy text-secondary">ورود با شماره همراه</h2>
                    <div class="h-8px"></div>
                    <img style="height: 24px;"  src="<?php echo oni_panel_image('line-question.svg') ?>">
                    <div class="h-8px"></div>
                    <p class="text-secondary fw-500 f-14px">شماره موبایل خود را وارد نمایید</p>
                </div>
                <div class="h-32px"></div>
                <div class="form-group text-center">
                    <input type="tel" inputmode="numeric" pattern="\d*"
                        class="form-control form-control-lg onlyNumbersInput border-1 h-48px f-14px text-primary login-input"
                        id="mobile" maxlength="11" placeholder="09123456789" aria-describedby="sendsms">
                </div>
                <div class="h-48px"></div>
                <div class="form-group d-grid ">
                    <button type="submit" disabled id="send-code"
                        class="btn btn-secondary h-48px w-100 text-center py-3 d-flex flex-row justify-content-center align-items-center gap-3 rounded-8px">
                        <img src="<?php echo oni_panel_image('btn-icon.png') ?>">
                        <span>دریافت کد</span>
                    </button>
                </div>
            </div>

            <div id="codeVerification" style="display: none;">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h2 class="f-22px fw-heavy text-secondary">وارد کردن کد</h2>
                    <div class="h-8px"></div>
                    <img style="height: 24px;" src="<?php echo oni_panel_image('line-question.svg') ?>">
                    <div class="h-8px"></div>
                    <p class="text-secondary fw-500 f-14px">کد ارسال شده را وارد نمایید</p>
                </div>
                <div class="h-32px"></div>
                <div class="form-group text-center mx-auto" style="width: 203px;">
                    <input autocomplete="one-time-code" type="text" inputmode="numeric" pattern="\d*"
                        class="form-control form-control-lg onlyNumbersInput border-1 h-48px text-center f-14px  text-primary login-input"
                        id="verificationCode" maxlength="<?php echo $oni_option[ 'set_code_count' ] ?>"
                        placeholder="<?php for ($i = 0; $i < $oni_option[ 'set_code_count' ]; $i++) {echo 'ـــ ';}?>"
                        aria-describedby="verify">

                    <div class="d-flex flex-row justify-content-between">
                        <div class="timer text-center f-12px  text-primary mx-0 px-0" id="timer">00:00</div>
                        <button type="button" class="btn btn-link btn-block f-12px text-primary mx-0 px-0"
                            id="resendCode" disabled>ارسال مجدد
                            کد</button>
                    </div>

                </div>
                <div class="h-48px"></div>
                <div class="form-group ">
                    <button type="submit" disabled id="verifyCode"
                        class="btn btn-secondary h-48px w-100 text-center py-3 d-flex flex-row justify-content-center align-items-center gap-3 rounded-8px">
                        <img src="<?php echo oni_panel_image('btn-icon.png') ?>">
                        <span>تایید و ورود</span>
                    </button>
                    <div class="h-12px"></div>
                    <button type="button" id="editNumber"
                        class="btn btn-outline-secondary h-48px w-100 text-center py-3 d-flex flex-row justify-content-center align-items-center gap-3 rounded-8px">
                        <img src="<?php echo oni_panel_image('btn-icon.png') ?>">
                        <span>تغییر شماره</span>
                    </button>
                </div>
            </div>
        </form>

        <div class="">

            <img style="width: 30%;" class="" src="<?php echo oni_panel_image('logofooter.svg') ?>">

            <h3 class="f-14px text-primary">در شبکه های اجتماعی</h3>
            <div class="h-24px"></div>
            <div class="d-flex flex-row justify-content-around align-items-center oni-social ">
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
    
<div class="toast-container position-fixed top-0 p-3">
        <div id="loginToast" class="toast align-items-center text-white bg-danger " role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">

                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

 
</div>












<!-- 
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <img src="..." class="rounded me-2" alt="...">
      <strong class="me-auto">Bootstrap</strong>
      <small>11 mins ago</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Hello, world! This is a toast message.
    </div>
  </div>
</div>
 -->











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
                    document.querySelector("#codeVerification #verifyCode").removeAttribute("disabled");

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