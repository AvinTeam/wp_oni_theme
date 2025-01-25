<?php

use oniclass\ONIDB;

 global $exam;

    $all_user_questions   = absint(get_user_meta(get_current_user_id(), 'questions', true));
    $all_user_count_true  = absint(get_user_meta(get_current_user_id(), 'count_true', true));
    $all_user_count_match = absint(get_user_meta(get_current_user_id(), 'count_match', true));

    $matchdl = new ONIDB('match');

    $this_data = date('Y-m-d');

    $all_count_true_today      = $matchdl->sum('count_true', [ 'iduser' => get_current_user_id() ], "DATE(`created_at`) = '$this_data'");
    $all_count_questions_today = $matchdl->sum('count_questions', [ 'iduser' => get_current_user_id() ], "DATE(`created_at`) = '$this_data'");
    $all_count_match_today     = $matchdl->num([ 'iduser' => get_current_user_id() ], "DATE(`created_at`) = '$this_data'");
?>



<div class="d-flex justify-content-center align-content-center mt-3">

    <div class="w-75 bg-white p-3 rounded-3 border border-1">
        <p class="text-center"><?php echo get_user_meta(get_current_user_id(), 'mobile', true) ?></p>

        <table class="table table-bordered p-0 m-0 ">
            <thead>
                <tr>
                    <th class="text-center" scope="col"></th>
                    <th class="text-center" scope="col">تعداد شرکت در مسابقه </th>
                    <th class="text-center" scope="col">مجموع امتیاز کسب شده</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="text-center" scope="row">کل</th>
                    <td class="text-center"><?php echo $all_user_count_match ?></td>
                    <td class="text-center"><?php echo $all_user_count_true ?> از<?php echo $all_user_questions ?> </td>
                </tr>
                <tr>
                    <th class="text-center" scope="row">امروز</th>
                    <td class="text-center"><?php echo $all_count_match_today ?></td>
                    <td class="text-center"><?php echo $all_count_true_today ?>
                        از<?php echo $all_count_questions_today ?> </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>












<?php if (isset($_GET[ 'eid' ]) && ! empty($_GET[ 'eid' ])):

        $deta_eid = [
            'eid'    => $_GET[ 'eid' ],
            'iduser' => get_current_user_id(),

         ];

        $num_eid = $matchdl->num($deta_eid);

        if (! $num_eid) {exit;}

        $end_match = $matchdl->get($deta_eid);

    ?>
				<div class="d-flex flex-column justify-content-center align-content-center rounded-3">
				    <div class="card w-75 oni_row text-center">
				        <div class="card-body">

				            <img src="<?php echo oni_panel_image('box.svg') ?>">

				            <div class=" pt-5">


				                <p class="mt-5 color-gray text-info">
				                    با تشکر از شرکت شما در مسابقه زندگی با آیه ها

				                </p>
				                <h6>
				                    پاسخ های درست:				                                             			                                             		                                             	                                              <?php echo $end_match->count_true ?>
				                </h6>
				                <h5 class="text-success">
				                    شما با				                               			                               		                               	                                <?php echo $all_count_true_today ?> امتیاز در قرعه کشی امروز شرکت می کنید.
				                </h5>
				                <p class="mt-5 color-gray text-info">
				                    در صورت تمایل می توانید باز هم در مسابقه شرکت و امتیاز های خود را افزایش دهید
				                </p>
				                <a href="/" class="btn btn-primary btn-lg bg-gradiant" style="border-radius:25px">شرکت مجدد در
				                    مسابقه</a>
				                <br>
				                <button href="https://zendegibaayeha.ir" class="btn btn-outline-primary outlined mt-2"
				                    style="border-radius:25px; border: 1px solid; min-width: 222px; " id="oni-logout">خروج از
				                    مسابقه</button>
				            </div>
				        </div>
				    </div>

				</div>
				<?php else:$question_list = ''; ?>
				<form method="post" action="" novalidate="novalidate" id="form-question">
				    <div class="d-flex justify-content-center align-content-center">

				        <div class="w-75 py-3">
				            <div class="alert alert-info text-center m-0 ">
				                <a href="https://zendegibaayeha.ir/ayeha/" target="_blank" class="text-primary">مشاهده محتوای مسابقه</a>
				            </div>
				        </div>

				    </div>

				    <div class="d-flex flex-column justify-content-center align-content-center rounded-3">

				        <?php foreach ($exam as $index => $ayeh): $question_list .= $ayeh->id . ','; ?>
								        <div class="card w-75 oni_row">
								            <h5 class="card-header text-center  bg-white">
								                سوال								                        						                        				                        		                         <?php echo $index + 1 ?> از<?php echo $oni_option[ 'count_questions' ] ?>
								            </h5>
								            <div class="card-body">
								                <h5 class="card-title text-center" style="font-family: 'osmantaha'; "><?php echo $ayeh->question ?></h5>


								                <div class="mt-5">
								                    <label class="form-check form-control form-check-label w-100" for="<?php echo $ayeh->id ?>_1">
								                        <input class="form-check-input" id="<?php echo $ayeh->id ?>_1" type="radio"
								                            data-i="<?php echo $index + 1 ?>" data-id="<?php echo $ayeh->id ?>" value="1"
								                            name="Q<?php echo $ayeh->id ?>">
								                        <?php echo $ayeh->option1 ?>
								                    </label>


								                    <label class="form-check form-control form-check-label w-100" for="<?php echo $ayeh->id ?>_2">
								                        <input class="form-check-input" id="<?php echo $ayeh->id ?>_2" type="radio"
								                            data-i="<?php echo $index + 1 ?>" data-id="<?php echo $ayeh->id ?>" value="2"
								                            name="Q<?php echo $ayeh->id ?>">
								                        <?php echo $ayeh->option2 ?>
								                    </label>

								                    <label class="form-check form-control form-check-label w-100" for="<?php echo $ayeh->id ?>_3">
								                        <input class="form-check-input" id="<?php echo $ayeh->id ?>_3" type="radio"
								                            data-i="<?php echo $index + 1 ?>" data-id="<?php echo $ayeh->id ?>" value="3"
								                            name="Q<?php echo $ayeh->id ?>">
								                        <?php echo $ayeh->option3 ?>
								                    </label>

								                    <label class="form-check form-control form-check-label w-100" for="<?php echo $ayeh->id ?>_4">
								                        <input class="form-check-input" id="<?php echo $ayeh->id ?>_4" type="radio"
								                            data-i="<?php echo $index + 1 ?>" data-id="<?php echo $ayeh->id ?>" value="4"
								                            name="Q<?php echo $ayeh->id ?>">
								                        <?php echo $ayeh->option4 ?>
								                    </label>
								                </div>
								            </div>
								        </div>
								        <?php endforeach; ?>
				    </div>
				    <div class="d-flex justify-content-center align-content-center">
				        <div class="w-75 py-3">

				            <input type="hidden" name="question_list" value="<?php echo $question_list ?>">




				            <button type="submit" name="oni_activation" value="question"
				                class="btn btn-primary bg-gradiant btn-block w-100 py-3" disabled="disabled">تایید و ارسال</button>
				        </div>
				    </div>
				</form>
				<?php endif; ?>