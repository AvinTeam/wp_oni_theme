<?php global $exam;
$all_user_count_true = absint(get_user_meta(get_current_user_id(), 'count_true', true)); ?>
<div class="oni-body mx-auto  pb-5 rounded-3  w-100">
    <div class="h-32px"></div>
    <header class="w-100 rounded-8px mx-auto d-flex flex-row justify-content-between align-items-center ">
        <img class="img-fluid" src="<?php echo oni_panel_image('zendegibaayeha.svg') ?> ">

        <a href="/?profile"><img class="img-fluid" style="height: 48px; width: 48px; "
                src="<?php echo oni_panel_image('profile.svg') ?> "></a>
    </header>
    <div class="h-32px"></div>
    <div id="user-info"
        class="w-100 rounded-8px  mx-auto border border-1  d-flex flex-row justify-content-between align-items-center p-12px ">
        <span class="text-primary f-14px">خوش آمدید کاربر
            <b><?php echo get_user_meta(get_current_user_id(), 'mobile', true) ?></b></span>
        <div class="d-flex flex-row justify-content-center align-items-center">
            <div class="cont-bg d-flex flex-row justify-content-center align-items-center text-white">
                <?php echo number_format($all_user_count_true) ?> امتیاز
            </div>
            <img class="img-fluid" src="<?php echo oni_panel_image('next-cont.svg') ?>">

        </div>
    </div>
    <div class="h-32px"></div>
    <div class="w-100 rounded-8px  mx-auto d-flex flex-row justify-content-around align-items-center">
        <a id="start-match" href="#"> <img class="img-fluid" src="<?php echo oni_panel_image('start-match.svg') ?>"></a>
        <a href="/?profile"> <img class="img-fluid" src="<?php echo oni_panel_image('statistics-match.svg') ?>"></a>
        <a href="https://zendegibaayeha.ir" target="_blank"> <img class="img-fluid"
                src="<?php echo oni_panel_image('context-match.svg') ?>"></a>
    </div>
    <div class="h-48px"></div>
    <div
        class="w-100 rounded-8px  mx-auto d-flex  row-cols-5 justify-content-between align-items-center bg-white p-12px all-number-question position-sticky top-0 z-3 shadow ">

        <div id="qn-1"
            class="number-question col d-flex flex-column justify-content-center align-items-center text-center">
            <div class="number f-22px text-white h-48px">1</div>
            <div class="h-12px"></div>
            <span class="f-12px">سوال اول</span>

        </div>
        <div id="qn-2"
            class="number-question col d-flex flex-column justify-content-center align-items-center text-center">
            <div class="number f-22px text-white h-48px">2</div>
            <div class="h-12px"></div>
            <span class="f-12px">سوال دوم</span>

        </div>
        <div id="qn-3"
            class="number-question col d-flex flex-column justify-content-center align-items-center text-center">
            <div class="number f-22px text-white h-48px">3</div>
            <div class="h-12px"></div>
            <span class="f-12px">سوال سوم</span>

        </div>
        <div id="qn-4"
            class="number-question ذcol d-flex flex-column justify-content-center align-items-center text-center">
            <div class="number f-22px text-white h-48px">4</div>
            <div class="h-12px"></div>
            <span class="f-12px">سوال چهارم</span>

        </div>

        <div id="qn-5"
            class="number-question col d-flex flex-column justify-content-center align-items-center text-center">
            <div class="number f-22px text-white h-48px">5</div>
            <div class="h-12px"></div>
            <span class="f-12px">سوال پنجم</span>

        </div>
    </div>

    <form method="post" action="" novalidate="novalidate" id="form-question">


        <div class="h-32px"></div>

        <?php foreach ($exam as $index => $ayeh): $question_list .= $ayeh->id . ','; ?>
        <section id="question-<?php echo $index + 1 ?>"
            class="w-100 rounded-8px  mx-auto d-flex flex-column bg-white p-24px ">

            <div class="d-flex flex-row justify-content-between align-items-center">
                <span class="text-primary-400">سوال <?php echo q_name_row(($index + 1), 1) ?></span>
                <span class="text-primary-400"><?php echo q_name_row(($index + 1)) ?> از پنج</span>
            </div>
            <div class="h-16px"></div>

            <div class="d-flex flex-column border-top border-top-1 border-primary">
                <div class="h-24px"></div>
                <div class="ayeh-question text-center text-primary f-16px fw-bold"><?php echo $ayeh->question ?></div>
                <div class="h-16px"></div>
                <div class="text-center">
                    <img src="<?php echo oni_panel_image('line-question.svg') ?>">
                </div>
                <div class="h-24px"></div>

                <div class="">
                    <label
                        class=" label-answer h-96px border border-1 w-100 rounded-12px p-12px d-flex flex-row align-items-center gap-2 "
                        for="<?php echo $ayeh->id ?>_1">
                        <div style=" width: 32px ;">
                            <div class="check-icon"></div>
                        </div>
                        <span class="text-justify f-16px"><?php echo $ayeh->option1 ?></span>
                        <input class="opacity-0" id="<?php echo $ayeh->id ?>_1" type="radio"
                            data-i="<?php echo $index + 1 ?>" data-id="<?php echo $ayeh->id ?>" value="1"
                            name="Q<?php echo $ayeh->id ?>">
                    </label>
                    <div class="h-16px"></div>
                    <label
                        class=" label-answer h-96px border border-1 w-100 rounded-12px p-12px d-flex flex-row align-items-center gap-2 "
                        for="<?php echo $ayeh->id ?>_2">
                        <div style=" width: 32px ;">
                            <div class="check-icon"></div>
                        </div>
                        <span class="text-justify f-16px"><?php echo $ayeh->option2 ?></span>
                        <input class="opacity-0" id="<?php echo $ayeh->id ?>_2" type="radio"
                            data-i="<?php echo $index + 1 ?>" data-id="<?php echo $ayeh->id ?>" value="2"
                            name="Q<?php echo $ayeh->id ?>">
                    </label>
                    <div class="h-16px"></div>
                    <label
                        class=" label-answer h-96px border border-1 w-100 rounded-12px p-12px d-flex flex-row align-items-center gap-2 "
                        for="<?php echo $ayeh->id ?>_3">
                        <div style=" width: 32px ;">
                            <div class="check-icon"></div>
                        </div>
                        <span class="text-justify f-16px"><?php echo $ayeh->option3 ?></span>
                        <input class="opacity-0" id="<?php echo $ayeh->id ?>_3" type="radio"
                            data-i="<?php echo $index + 1 ?>" data-id="<?php echo $ayeh->id ?>" value="3"
                            name="Q<?php echo $ayeh->id ?>">
                    </label>
                    <div class="h-16px"></div>


                    <label
                        class=" label-answer h-96px border border-1 w-100 rounded-12px p-12px d-flex flex-row align-items-center gap-2 "
                        for="<?php echo $ayeh->id ?>_4">
                        <div style=" width: 32px ;">
                            <div class="check-icon"></div>
                        </div>
                        <span class="text-justify f-16px"><?php echo $ayeh->option4 ?></span>
                        <input class="opacity-0" id="<?php echo $ayeh->id ?>_4" type="radio"
                            data-i="<?php echo $index + 1 ?>" data-id="<?php echo $ayeh->id ?>" value="4"
                            name="Q<?php echo $ayeh->id ?>">
                    </label>
                </div>

            </div>
        </section>
        <div class="h-24px"></div>
        <?php endforeach; ?>
        <div class="w-100 rounded-8px  mx-auto d-flex flex-column">

            <input type="hidden" name="question_list" value="<?php echo $question_list ?>">

            <button type="submit" disabled id="verifyCode"
                class="btn btn-primary h-48px w-100 text-center d-flex flex-row justify-content-center align-items-center gap-3 rounded-8px ">
                <img src="<?php echo oni_panel_image('btn-icon.png') ?>">
                <span>پایان</span>
            </button>
        </div>
    </form>


    <!-- Modal -->
    <div class="modal fade rounded-8px w-75 mx-auto" id="endMatch" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="endMatchLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">

                <div class="modal-body d-flex flex-column align-items-center rounded-8px">
                    <img class="mx-auto" src="<?php echo oni_panel_image('endMatch-logo.svg') ?>">
                    <div class="h-12px"></div>

                    <p id="q-true" class="f-16px fw-heavy text-secondary "></p>
                    <div class="h-12px"></div>

                    <p id="all-count" class="f-16px fw-heavy text-secondary "></p>
                    <div class="h-12px"></div>

                    <a href="/?profile"
                        class="btn btn-secondary h-48px w-100 text-center d-flex flex-row justify-content-center align-items-center gap-3 ">
                        <img src="<?php echo oni_panel_image('btn-icon.png') ?>">
                        <span>مشاهده نتایج اخیر</span>
                    </a>
                    <div class="h-12px"></div>
                    <button href="" onclick="location.reload()"
                        class="btn btn-outline-primary  h-48px w-100 text-center d-flex flex-row justify-content-center align-items-center gap-3 ">
                        <img src="<?php echo oni_panel_image('btn-icon.png') ?>">
                        <span>بازگشت</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>