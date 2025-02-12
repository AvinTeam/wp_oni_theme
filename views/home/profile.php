<?php
    use oniclass\ONIDB;
    global $exam;
    $all_user_questions   = absint(get_user_meta(get_current_user_id(), 'questions', true));
    $all_user_count_true  = absint(get_user_meta(get_current_user_id(), 'count_true', true));
    $all_user_count_match = absint(get_user_meta(get_current_user_id(), 'count_match', true));

    $matchdl                   = new ONIDB('match');
    $this_data                 = date('Y-m-d');
    $all_count_true_today      = $matchdl->sum('count_true', [ 'iduser' => get_current_user_id() ], "DATE(`created_at`) = '$this_data'");
    $all_count_questions_today = $matchdl->sum('count_questions', [ 'iduser' => get_current_user_id() ], "DATE(`created_at`) = '$this_data'");
    $all_count_match_today     = $matchdl->num([ 'iduser' => get_current_user_id() ], "DATE(`created_at`) = '$this_data'");
?>

<div class="oni-body mx-auto w-100 pb-5 rounded-3 h-100  position-relative">
    <header id="login" style="margin-top: -60px; padding-top: 60px; height: 388px;"
        class="w-100 rounded-24px  mx-auto d-flex flex-column justify-content-around align-items-center">
        <img class="w-50" src="<?php echo oni_panel_image('zendegibaayeha_logo.svg') ?> ">
        <div class="d-flex flex-row justify-content-between align-items-center w-75 p-12px bg-white rounded-16px">
            <span class="text-primary f-14px"><?php echo get_user_meta(get_current_user_id(), 'mobile', true) ?></span>
            <div>
                <a href="/" style="line-height: 2;" class="btn btn-secondary rounded-8px my-4px f-14px">آزمون جدید</a>
                <a id="oni-logout" style=" line-height: 2;  " class="btn btn-outline-primary my-4px"><img
                        class="p-0 m-0" src="<?php echo oni_panel_image('logout.svg') ?>"></a>
            </div>

        </div>
    </header>
    <div class="h-16px"></div>

    <div class="d-flex flex-row justify-content-center align-items-center w-100 mx-auto p-12px bg-white rounded-16px ">
        <div class="profile-filter w-100 p-12px btn text-primary rounded-8px my-4px f-14px btn-active" id="all-match">کل
            مسابقات شرکت شده</div>
        <div class="profile-filter w-100 p-12px btn text-primary rounded-8px my-4px f-14px" id="today-match">مسابقات
            امروز
        </div>
    </div>
    <div class="h-16px"></div>

    <div id="count-all-match"
        class="count-match d-flex flex-row justify-content-around align-items-center w-100 mx-auto p-12px rounded-16px gap-3 ">

        <div style="height: 90px; background-color: #37BEC1 ;"
            class="w-100 p-10px text-white rounded-8px text-center d-flex flex-column justify-content-center align-items-center ">
            <span class="fw-heavy f-28px"><?php echo $all_user_count_match ?></span>
            <div class="h-8px"></div>
            <p class="fw-bold f-12px">تعداد شرکت در مسابقه</p>
        </div>
        <div style="height: 90px; background-color: #393C97 ;"
            class="w-100 p-10px text-white rounded-8px text-center d-flex flex-column justify-content-center align-items-center ">
            <span class="fw-heavy f-28px"><?php echo $all_user_count_true ?></span>
            <div class="h-8px"></div>
            <p class="fw-bold f-12px">مجموع امتیاز کسب شده</p>
        </div>
    </div>

    <div id="count-today-match"
        class="count-match d-flex flex-row justify-content-around align-items-center w-100 mx-auto p-12px rounded-16px gap-3 d-none ">

        <div style="height: 90px; background-color: #E0AD70 ;"
            class="w-100 p-10px text-white rounded-8px text-center d-flex flex-column justify-content-center align-items-center ">
            <span class="fw-heavy f-28px"><?php echo $all_count_match_today ?></span>
            <div class="h-8px"></div>
            <p class="fw-bold f-12px">تعداد شرکت در مسابقه امروز</p>
        </div>
        <div style="height: 90px; background-color: #82C341 ;"
            class="w-100 p-10px text-white rounded-8px text-center d-flex flex-column justify-content-center align-items-center ">
            <span class="fw-heavy f-28px"><?php echo $all_count_true_today ?></span>
            <div class="h-8px"></div>
            <p class="fw-bold f-12px">مجموع امتیاز کسب شده امروز</p>
        </div>
    </div>

    <div class="bg-white rounded-8px border-1 border-primary-200 p-16px">
        <div class="h-16px"></div>
        <img class="mx-auto w-100" src="<?php echo oni_panel_image('match-history.svg') ?>">
        <div class="h-32px"></div>
        <div id="all_result_match">
            <div class="w-100 bg-primary-100 d-flex flex-column rounded-8px ">
                <div class="d-flex flex-row justify-content-around align-items-center border-primary-200"
                    style="border-bottom: 1px solid">
                    <div class="p-12px w-100  border-primary-200 " style="border-left: 1px solid">
                        <span class="f-14px text-primary-600">تاریخ شروع</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">1403/04/08</span>
                    </div>
                    <div class="p-12px w-100">
                        <span class="f-14px text-primary-600">تعداد شرکت</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">5</span>
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-around align-items-center">
                    <div class="p-12px w-100  border-primary-200 " style="border-left: 1px solid">
                        <span class="f-14px text-primary-600">نتیجه</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">نتیجه 10 از 15</span>
                    </div>
                    <div class="p-12px w-100">
                        <span class="f-14px text-primary-600">امتیاز کسب شده</span>
                        <div class="h-12px"></div>
                        <span class="fw-bold f-16px text-primary">10 امتیاز</span>
                    </div>
                </div>
            </div>
            <div class="h-16px"></div>




        </div>




        <div class="h-16px"></div>

    </div>




</div>