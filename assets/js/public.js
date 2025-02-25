jalaliDatepicker.startWatch({
    minDate: "attr",
    maxDate: "attr"
});


function startLoading() {
    var overlay = document.getElementById("overlay");

    if (overlay) {
        overlay.style.display = "flex"; // نمایش به صورت flex
        overlay.style.opacity = "0"; // آماده‌سازی برای افکت fadeIn
        overlay.style.transition = "opacity 0.5s ease-in-out"; // اضافه کردن انیمیشن

        // تأخیر برای اعمال transition
        setTimeout(() => {
            overlay.style.opacity = "1";
        }, 10);
    }

    document.body.classList.add("no-scroll"); // اضافه کردن کلاس به body
}

function endLoading() {

    var overlay = document.getElementById("overlay");

    if (overlay) {
        overlay.style.transition = "opacity 0.5s ease-in-out"; // اضافه کردن انیمیشن
        overlay.style.opacity = "0"; // شروع افکت fadeOut

        setTimeout(() => {
            overlay.style.display = "none"; // بعد از محو شدن، مخفی کردن کامل
        }, 500); // مقدار 500 باید با زمان transition هماهنگ باشه
    }

    document.body.classList.remove("no-scroll"); // حذف کلاس از body

}

const pageLogin = document.getElementById('loginForm');
if (pageLogin) {


    let isSendSms = true;

    function validateMobile(mobile) {
        let regex = /^09\d{9}$/;
        return regex.test(mobile);
    }
    function send_sms() {
        startLoading();

        let mobile = document.getElementById('mobile').value;
        if (validateMobile(mobile)) {

            const xhr = new XMLHttpRequest();
            xhr.open('POST', oni_js.ajaxurl, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {

                const response = JSON.parse(xhr.responseText);

                endLoading();


                if (xhr.status === 200) {
                    if (response.success) {
                        document.getElementById('mobileForm').style.display = 'none';
                        document.getElementById('codeVerification').style.display = 'block';
                        document.getElementById('resendCode').disabled = true;

                        startTimer();
                        let otpInput = document.getElementById('verificationCode');
                        otpInput.value = '';
                        otpInput.focus();

                    }
                } else {
                    isSendSms = true


                    let loginToast = document.getElementById("loginToast");
                    let loginAlertBody = loginToast.querySelector(".toast-body");
                    loginAlertBody.textContent = response.data;
                    let toast = new bootstrap.Toast(loginToast);
                    toast.show();
                }
            };
            xhr.send(`action=oni_sent_sms&nonce=${oni_js.nonce}&mobileNumber=${mobile}`);

        } else {

            let loginToast = document.getElementById("loginToast");
            let loginAlertBody = loginToast.querySelector(".toast-body");
            loginAlertBody.textContent = 'شماره موبایل نامعتبر است';
            let toast = new bootstrap.Toast(loginToast);
            toast.show();

            endLoading();


        }
    }

    pageLogin.addEventListener('submit', function (event) {
        event.preventDefault();

        if (isSendSms) {
            isSendSms = false;
            send_sms();
        }
    });


    document.getElementById('verifyCode').addEventListener('click', function () {

        startLoading();


        let mobile = document.getElementById('mobile').value;

        let verificationCode = document.getElementById('verificationCode').value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', oni_js.ajaxurl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {

            const response = JSON.parse(xhr.responseText);

            endLoading();

            if (xhr.status === 200) {
                if (response.success) {
                    location.reload();
                }
            } else {
                isSendSms = true


                let loginToast = document.getElementById("loginToast");
                let loginAlertBody = loginToast.querySelector(".toast-body");
                loginAlertBody.textContent = response.data;
                let toast = new bootstrap.Toast(loginToast);
                toast.show();

            }
        };
        xhr.send(`action=oni_sent_verify&nonce=${oni_js.nonce}&otpNumber=${verificationCode}&mobileNumber=${mobile}`);


    });


    document.getElementById('editNumber').addEventListener('click', function () {
        document.getElementById('mobileForm').style.display = 'block';
        document.getElementById('codeVerification').style.display = 'none';
        isSendSms = true;
        startTimer(true);

    });

    document.getElementById('resendCode').addEventListener('click', function () {
        send_sms();
    });


    function startTimer(end = false) {

        if (end) { clearInterval(interval); } else {

            let timer = Number(oni_js.option.set_timer) * 60,
                minutes, seconds;
            interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                document.getElementById('timer').textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    document.getElementById('resendCode').disabled = false;
                }
            }, 1000);
        }
    }


}

document.querySelectorAll('.number-question').forEach(item => {
    item.addEventListener('click', function (e) {
        let id = this.getAttribute('id');
        id = id.replace("qn-", "");
        const mapSection = document.getElementById('question-' + id);
        mapSection.scrollIntoView({
            behavior: 'smooth'
        });

    });
});


document.querySelectorAll('#start-match').forEach(item => {
    item.addEventListener('click', function (e) {
        e.preventDefault();

        const mapSection = document.getElementById('question-1');
        mapSection.scrollIntoView({
            behavior: 'smooth'
        });

        document.getElementById('qn-1').classList.add('q-info')

    });
});


const sections = document.querySelectorAll("section"); // تمام سکشن‌ها رو بگیر
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {

            let id = entry.target.id;
            id = id.replace("question-", "");
            const items = document.querySelectorAll(".number-question");
            if (items) {
                items.forEach(item => {
                    item.classList.remove("q-info");
                });

                let this_has_id = document.getElementById('qn-' + id);

                if (this_has_id.classList.contains("q-info") || this_has_id.classList.contains("q-success") || this_has_id.classList.contains("q-error")) {
                } else {
                    document.getElementById('qn-' + id).classList.add('q-info')
                }
            }
        }
    });
}, { threshold: 1.0 });

sections.forEach(section => observer.observe(section));

jQuery(document).ready(function ($) {

    let countAnswer = 0;

    $('#mobileForm #mobile').keyup(function (e) {
        e.preventDefault();
        let mobile = $(this).val();

        if (mobile.length >= 11) {
            $('#mobileForm #send-code').removeAttr('disabled');
        } else {
            $('#mobileForm #send-code').attr('disabled', '');
        }
    });

    $('#mobileForm #mobile').change(function (e) {
        e.preventDefault();
        let mobile = $(this).val();

        if (mobile.length >= 11) {
            $('#mobileForm #send-code').removeAttr('disabled');
        } else {
            $('#mobileForm #send-code').attr('disabled', '');
        }
    });

    $('#codeVerification #verificationCode').keyup(function (e) {
        e.preventDefault();
        let mobile = $(this).val();

        if (mobile.length >= oni_js.option.set_code_count) {
            $('#codeVerification #verifyCode').removeAttr('disabled');
        } else {
            $('#codeVerification #verifyCode').attr('disabled', '');
        }
    });

    $('#codeVerification #verificationCode').change(function (e) {
        e.preventDefault();
        let mobile = $(this).val();

        if (mobile.length >= oni_js.option.set_code_count) {
            $('#codeVerification #verifyCode').removeAttr('disabled');
        } else {
            $('#codeVerification #verifyCode').attr('disabled', '');
        }
    });

    $('input[type=radio]').change(function (e) {
        const obj = oni_js.answers;
        const _this = this;

        let answer = $(this).val();
        let questionId = $(this).attr('data-id');
        let qrow = $(this).attr('data-i');
        let clickName = $(this).attr('name');

        for (let index = 1; index < 5; index++) {

            if (index != answer) {
                $('input#' + questionId + '_' + index).attr('disabled', 'disabled');
            }
        }

        if (oni_js.answers[clickName] == answer) {
            $(_this).parent().addClass("a-success");
            $('#qn-' + qrow).addClass('q-success');

        } else {
            $(_this).parent().addClass("a-error");
            $('input#' + questionId + '_' + oni_js.answers[clickName]).parent().addClass("a-success");
            $('#qn-' + qrow).addClass('q-error');
        }

        countAnswer++;



        const numberOfElements = Object.keys(obj).length;

        if (countAnswer == numberOfElements) {

            $('button[type=submit]').removeAttr('disabled');
        }



    });

    $('#oni-logout').click(function (e) {
        e.preventDefault();

        const formData = {
            action: 'oni_logout',
        };

        $.ajax({
            url: oni_js.ajaxurl,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {

                window.location.href = response.data;
            }
        });

    });

    $('.onlyNumbersInput').on('input paste', function () {
        // پاک کردن تمام کاراکترهای غیرعددی
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    console.log(oni_js.answers);
    $("#form-question").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        formData.append("action", "oni_sent_question");

        $.ajax({
            url: oni_js.ajaxurl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {

                if (response.success) {
                    $("#endMatch #q-true").text(response.data.count_true);
                    $("#endMatch #all-count").text(response.data.score);
                    $("#endMatch").modal("show");

                    if (Number(response.data.count_true) == 0) {
                        $('#dotlottie_svg').addClass('d-none');
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("خطا در درخواست AJAX:", error);
            }
        });
    });

    let type = 'all-match';
    let date = '';
    let sort = '';

    function ajaxAllMatch(paged = 1) {


        $("#overlay").css("display", "flex").hide().fadeIn();
        $("body").addClass("no-scroll");
        $('#all_result_match').html('');


        const formData = {
            action: 'oniAjaxAllMatch',
            type: type,
            date: date,
            sort: sort,
            paged: paged
        };

        $.ajax({
            url: oni_js.ajaxurl,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#all_result_match').html(response.data);
                }
                else {
                    $('#all_result_match').html(`<div class="alert alert-danger" role="alert">خطایی پیش آمده دوباره تلاش کتید</div>`);
                }

                $("#overlay").fadeOut();
                $("body").removeClass("no-scroll");
            },
            error: function (xhr, status, error) {
                console.error(xhr);
                console.error("خطا در درخواست AJAX:", error);
                $('#all_result_match').html(`<div class="alert alert-danger" role="alert">خطایی پیش آمده دوباره تلاش کتید</div>`);

                $("#overlay").fadeOut();
                $("body").removeClass("no-scroll");

            }
        });

    }

    const allResultMatch = document.getElementById('all_result_match');

    if (allResultMatch) {
        ajaxAllMatch();
    }

    $('.profile-filter').click(function (e) {
        e.preventDefault();
        type = $(this).attr('id');
        $('.profile-filter').removeClass('btn-active');
        $(this).addClass('btn-active');

        $('.count-match').addClass('d-none');
        $('#count-' + type).removeClass('d-none');

        if (type == 'today-match') {
            $('#select-date').addClass('d-none');


        } else {
            $('#select-date').removeClass('d-none');
            $('#date-input').val('');
            $('#select-date span').text('انتخاب روز');
            date = ''
        }

        sort = '';
        $('#sort-input').val(0);
        ajaxAllMatch();

    });

    $('#select-date').click(function (e) {
        e.preventDefault();

        const dateInput = document.getElementById('date-input');
        dateInput.focus();

    });

    $('#date-input').change(function (e) {
        e.preventDefault();
        date = $(this).val();

        let spanText = (date === "") ? 'انتخاب روز' : date;
        $('#select-date span').text(spanText);
        ajaxAllMatch();

    });

    $('#sort-input').change(function (e) {
        e.preventDefault();
        sort = $(this).val();
        ajaxAllMatch();

    });

});

