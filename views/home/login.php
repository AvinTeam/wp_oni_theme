<?php

    function oni_title_filter_login($title)
    {

        $title = get_bloginfo('name') . " | ورود ";
        return $title;
    }
    add_filter('wp_title', 'oni_title_filter_login');

?>



<style>
footer {
    position: absolute;
    bottom: 0;
}
</style>




<div class="h-100 mt-5 p-5">
    <div class="d-flex justify-content-center align-content-center  login-box">

        <form id="loginForm" class="w-50 bg-white">

            <div id="mobileForm">
                <div class="text-center"><img src="<?php echo oni_panel_image('file.svg') ?>"></div>
                <h3 class="text-center mt-2">ورود / ثبت نام</h3>
                <p class="text-center">جهت ورود به مسابقه</p>

                <?php wp_nonce_field('oni_login_page' . oni_cookie()); ?>

                <div class="form-group text-start">
                    <label for="mobile">شماره موبایل</label>


                    <div class="input-group mb-3">
                        <span class="input-group-text" id="sendsms"><i class="bi bi-phone"></i></span>
                        <input type="text" inputmode="numeric" pattern="\d*" class="form-control  onlyNumbersInput"
                            id="mobile" maxlength="11" placeholder="شماره موبایل خود را وارد کنید"
                            aria-describedby="sendsms">

                    </div>
                </div>
                <div class="form-group d-grid mt-2 ">
                    <button type="submit" class="btn btn-primary bg-gradiant  btn-block">ورود</button>

                </div>
            </div>
            <div id="codeVerification" class="text-start" style="display: none;">
                <h4 class="text-center">کد تایید</h4>
                <div class="form-group d-grid mt-2">
                    <label for="verificationCode">کد تایید</label>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="verify"><i class="bi bi-person-fill"></i></span>
                        <input autocomplete="one-time-code" type="text" inputmode="numeric" pattern="\d*"
                            class="form-control onlyNumbersInput" id="verificationCode"
                            maxlength="<?php echo $oni_option[ 'set_code_count' ] ?>"
                            placeholder="کد تایید را وارد کنید" aria-describedby="verify">

                    </div>
                </div>
                <div class="d-grid mt-2 gap-2">
                    <div class="timer text-center" id="timer">00:00</div>

                    <button type="submit" class="btn btn-primary bg-gradiant btn-block" id="verifyCode">تایید
                        کد</button>
                    <button type="button" class="btn btn-secondary btn-block" id="resendCode" disabled>ارسال مجدد
                        کد</button>
                    <button type="button" class="btn btn-link btn-block" id="editNumber">ویرایش شماره</button>
                </div>
            </div>
            <div id="login-alert" class="alert alert-danger mt-2 d-none" role="alert"></div>

        </form>

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