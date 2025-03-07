<?php
    use oniclass\ONIDB;
    $all_user_questions   = absint(get_user_meta(get_current_user_id(), 'questions', true));
    $all_user_count_true  = absint(get_user_meta(get_current_user_id(), 'count_true', true));
    $all_user_count_match = absint(get_user_meta(get_current_user_id(), 'count_match', true));

    $all_score_total = absint(get_user_meta(get_current_user_id(), 'score_total', true));

    $matchdl               = new ONIDB('match');
    $this_data             = date('Y-m-d');
    $all_count_match_today = $matchdl->num([ 'iduser' => get_current_user_id() ], "DATE(`created_at`) = '$this_data'");
    $all_score_today       = $matchdl->sum("score", [ 'iduser' => get_current_user_id() ], "DATE(`created_at`) = '$this_data'");
?>

<div class="oni-body mx-auto w-100 pb-5 rounded-3 h-100  position-relative">
    <header id="login" style="margin-top: -60px; padding-top: 60px; height: 388px;"
        class="w-100 rounded-24px  mx-auto d-flex flex-column justify-content-around align-items-center">
        <a class="w-50" href="/"><img class="w-100" src="<?php echo oni_panel_image('zendegibaayeha_logo.svg') ?> "></a>

        <div class="d-flex flex-row justify-content-between align-items-center w-75 p-12px bg-white rounded-16px">
            <span class="text-primary f-14px"><?php echo get_user_meta(get_current_user_id(), 'mobile', true) ?></span>
            <div>
                <a href="/" style="line-height: 2;" class="btn btn-secondary rounded-8px my-4px f-14px">آزمون جدید</a>
                <a id="oni-logout" style=" line-height: 2;  " class="btn btn-outline-primary my-4px f-14px"><img
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
        class="count-match d-flex flex-row justify-content-around align-items-center w-100 mx-auto rounded-16px gap-3 ">

        <div
            class="all-match w-100 p-10px text-white rounded-8px text-center d-flex flex-column justify-content-center align-items-center ">
            <span class="fw-heavy f-28px"><?php echo $all_user_count_match ?></span>
            <div class="h-8px"></div>
            <p class="fw-bold f-12px">تعداد شرکت در مسابقه</p>
        </div>
        <div
            class="all-count w-100 p-10px text-white rounded-8px text-center d-flex flex-column justify-content-center align-items-center ">
            <span class="fw-heavy f-28px"><?php echo $all_score_total ?></span>
            <div class="h-12px"></div>
            <p class="fw-bold f-12px">مجموع امتیاز کسب شده</p>
        </div>
    </div>

    <div id="count-today-match"
        class="count-match d-flex flex-row justify-content-around align-items-center w-100 mx-auto p-12px rounded-16px gap-3 d-none ">

        <div
            class="today-match w-100 p-10px text-white rounded-8px text-center d-flex flex-column justify-content-center align-items-center ">
            <span class="fw-heavy f-28px"><?php echo $all_count_match_today ?></span>
            <div class="h-8px"></div>
            <p class="fw-bold f-12px">تعداد شرکت در مسابقه امروز</p>
        </div>
        <div
            class="today-count w-100 p-10px text-white rounded-8px text-center d-flex flex-column justify-content-center align-items-center ">
            <span class="fw-heavy f-28px"><?php echo $all_score_today ?></span>
            <div class="h-8px"></div>
            <p class="fw-bold f-12px">مجموع امتیاز کسب شده امروز</p>
        </div>
    </div>
    <div class="h-12px"></div>

    <div class="bg-white rounded-8px border-1 border-primary-200 p-16px">
        <div class="h-16px"></div>
        <img class="mx-auto w-100" src="<?php echo oni_panel_image('match-history.svg') ?>">
        <div class="h-24px"></div>
        <div class="d-flex flex-row justify-content-between align-items-center">


            <div> <label id="select-date" for="date-input"
                    class="btn btn-outline-primary h-48px text-center d-flex flex-row justify-content-between align-items-center w-100 gap-2 ">
                    <img src="<?php echo oni_panel_image('calendar.svg') ?>">
                    <span>انتخاب روز</span>
                    <input id="date-input" class="opacity-0" style="width: 0px;" type="text" data-jdp=""
                        data-jdp-max-date="today" data-jdp-only-date="">
                </label></div>


            <style>

            </style>

            <label for="sort-input" class="this_sort">
                <img src="<?php echo oni_panel_image('sort.svg') ?>">
                <select id="sort-input"
                    class=" text-primary border-primary h-48px text-center d-flex flex-row justify-content-between align-items-center form-select "
                    aria-label="Default select example">
                    <option class="bg-white" value="0" selected>ترتیب</option>
                    <option class="bg-white" value="DESC">بیشتر به کمتر</option>
                    <option class="bg-white" value="ASC">کمتر به بیشتر</option>
                </select>

            </label>
        </div>
        <div class="h-32px"></div>
        <div id="all_result_match"></div>
    </div>



    <div class="text-center py-5">
        طراحی و پشتیبانی: گروه هنری رسانه ای آوین
    </div>

</div>