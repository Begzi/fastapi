<?php
include $_SERVER['DOCUMENT_ROOT'].'/includes/init.php';
switch ($_REQUEST['act']){
    case 'set_order_studio' :
        set_order_studio();
        break;
    case 'set_order_locations' :
        set_order_locations();
        break;
    case 'del_location' :
        del_location();
        break;
    case 'set_order_podlocation' :
        set_order_podlocation();
        break;
    case 'del_table_podlocation' :
        del_table_podlocation();
        break;
    case 'set_order_interiors' :
        set_order_interiors();
        break;
    case 'del_interior' :
        del_interior();
        break;
    case 'load_interiors' :
        load_interiors();
        break;
    case 'set_order_table_prib' :
        set_order_table_prib();
        break;
    case 'del_table_prib_categ' :
        del_table_prib_categ();
        break;
    case 'set_order_table_prib_categ' :
        set_order_table_prib_categ();
        break;
    case 'del_owners' :
        del_owners();
        break;
    case 'del_dressings' :
        del_dressings();
        break;
    case 'set_order_owners' :
        set_order_owners();
        break;
    case 'get_studios_history' :
        get_studios_history();
        break;
    case 'edt_studios_history' :
        edt_studios_history();
        break;
    case 'save_studios_history' :
        save_studios_history();
        break;
    case 'del_studios_history' :
        del_studios_history();
        break;
    case 'set_newyear_season_summer' :
        set_newyear_season_summer();
        break;
    case 'set_newyear_season_winter' :
        set_newyear_season_winter();
        break;
    case 'save_interior' :
        save_interior();
        break;
    case 'del_all_interiors' :
        del_all_interiors();
        break;
    case 'add_interior_video' :
        add_interior_video();
        break;
    case 'load_interior_item' :
        load_interior_item();
        break;
    case 'save_price_option':
        savePriceOption($_POST['price-option-title'], $_POST['price-option-cost'],
            $_POST['price-option-desc'], $_POST['studio_id'],
            $_POST['has_hourly_pay'], $_POST['hourly_pay_positive'], $_POST['hourly_pay_negative']);
        break;
    case 'edit_price_option':
        editPriceOption($_POST['list-price-item-id'], $_POST['price-option-desc'],
            $_POST['has_hourly_pay'], $_POST['hourly_pay_positive'], $_POST['hourly_pay_negative'], $_POST['studio_id']);
        break;
    case 'delete_price_option':
        deletePriceOption($_POST['id']);
        break;
    case 'search_studios':
        searchStudios();
        break;
    case 'change_owner':
        changeStudioOwner();
        break;
    case 'change_current_price':
        changeCurrentPrice();
        break;
    case 'edit_studio_attachment':
        editStudioAttachment($_POST['id'], $_POST['content']);
    case 'add_studios_main_page':
        addStudiosMainPage($_POST['studio_id']);
        break;
    case 'change_order_studios_main_page':
        changeOrderStudiosMainPage();
        break;
    case 'delete_studios_main_page':
        deleteStudiosMainPage($_POST['studio_id']);
        break;
}

function searchStudios() {
    echo Studios::getDynamicStudios($_POST['s']);
}


function changeCurrentPrice() {
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_0', 0)) {
        // $sql = "SELECT * FROM `studio_history` WHERE `date`>'" . date('Y-m-d') . "' AND `studio_id`=" . intval($_POST['studio_id']) . " ORDER BY date ASC LIMIT 1";
        // $result = mysqli_query($db, $sql);
        

        $set = "
                `cost1`='" . intval($_POST['cost1']) . "'
                , `cost2`='" . intval($_POST['cost2']) . "'
                , `price_holiday`='" . intval($_POST['price_holiday']) . "'
                , `pokr_cikl`='" . intval($_POST['pokr_cikl']) . "'
                , `pokr_cikl_staff`='" . intval($_POST['pokr_cikl_staff']) . "'
            ";

            foreach (Studios::COUNT_PEOPLE_TYPES as $type) {
                if (!$type['always']) {
                    $set .= ', `' . $type['db_column'] . '`="' . !!(intval($_POST[$type['db_column_price_day']]) ?? intval($_POST[$type['db_column_price_night']])) . '"';
                    $set .= ', `' . $type['db_column_price_day'] . '`="' . intval($_POST[$type['db_column_price_day']]). '"';
                    $set .= ', `' . $type['db_column_price_night'] . '`="' . intval($_POST[$type['db_column_price_night']]). '"';
                }
            }

        // if (mysqli_num_rows($result)) {
        //     $studio_history = mysqli_fetch_array($result);
        //     $sql = "UPDATE studio_history SET $set WHERE studio_history_id=" . intval($studio_history["studio_history_id"]);
        // } else {
            $sql = "UPDATE studios SET $set WHERE studio_id=" . intval($_POST['studio_id']);
        // }

        mysqli_query($db, $sql) or die(mysqli_error($db));
        
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}


function changeStudioOwner() {
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_6', 0)) {
        $sql = "UPDATE studios SET `owner_id`=".$_POST['owner_id']." WHERE studio_id=" . intval($_POST['studio_id']);
        mysqli_query($db, $sql) or die(mysqli_error($db));
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}

function set_newyear_season_summer() {
    global $user;
    $arr=Array('status'=>'ok');
    if ($user->order('newyear', 0)) {
        Functions::SetConstByName('newyear_season_summer', intval($_POST['v']));
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }

    echo json_encode_x($arr);
}

function set_newyear_season_winter() {
    global $user;
    $arr=Array('status'=>'ok');
    if ($user->order('newyear', 0)) {
        Functions::SetConstByName('newyear_season_winter', intval($_POST['v']));
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }

    echo json_encode_x($arr);
}

function save_studios_history() {
    global $db, $user;
    if ($user->order('studios_0_main')) {
        $arr = Array();
        $studio = Studios::Get_Studio($_POST['studio_id']);
        $arr['status'] = 'ok';
        $date_from = ($_POST['date_from'] ? strtotime($_POST['date_from']) : 0);
        $date_to = ($_POST['date_to'] ? strtotime($_POST['date_to']) + 3600 * 24 : 0);
        $w = '';
        if ($_POST['studio_history_id'] > 0) {
            $w .= " AND `studio_history_id`!=" . intval($_POST['studio_history_id']);
        }
        if ($_POST['studio_history_id_prev'] > 0) {
            $w .= " AND `studio_history_id`!=" . intval($_POST['studio_history_id_prev']);
        }
        $sql = "SELECT * FROM `studio_history` WHERE `date`='" . date('Y-m-d', $date_to) . "' AND `studio_id`=" . intval($_POST['studio_id']) . " $w";
        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result)) {
            $arr['status'] = 'error';
            $arr['message'] = 'Дата конца уже является датой окончания другого периода';
        }
        $result = mysqli_query($db, "SELECT * FROM `studio_history` WHERE `date`='" . date('Y-m-d', $date_from+3600*24) . "' AND `studio_id`=" . intval($_POST['studio_id']) . " $w");
        if (mysqli_num_rows($result)) {
            $arr['status'] = 'error';
            $arr['message'] = 'Дата начала уже является началом другого периода';
        }

        if ($date_to<=0) {
            $arr['status'] = 'error';
            $arr['message'] = 'Выберите дату завершения';
        }

        if ($_POST['price_holiday_btn'] == 0) {
            $_POST['price_holiday'] = 0;
        }

        if ($arr['status'] == 'ok') {
            if (Stat_month::Check_Closed_Period($date_from, $date_to)) {
                if (
                    ($_POST['studio_history_id'] > 0) ||
                    ($date_to < time()) ||
                    ($studio['cost1'] != floatval($_POST['cost1'])) ||
                    ($studio['cost2'] != floatval($_POST['cost2'])) ||
                    ($studio['price_for_many_on'] != intval($_POST['price_for_many_on'])) ||
                    // ($studio['price_for_many_on15'] != intval($_POST['price_for_many_on15'])) ||
                    ($studio['price_for_many_on20'] != intval($_POST['price_for_many_on20'])) ||
                    ($studio['price_for_many_on50'] != intval($_POST['price_for_many_on50'])) ||
                    ($studio['price_for_many1'] != floatval($_POST['price_for_many1'])) ||
                    ($studio['price_for_many2'] != floatval($_POST['price_for_many2'])) ||
                    ($studio['price_for_many20'] != floatval($_POST['price_for_many20'])) ||
                    ($studio['price_for_many20night'] != floatval($_POST['price_for_many20night'])) ||
                    ($studio['price_for_many50'] != floatval($_POST['price_for_many50'])) ||
                    ($studio['price_for_many50night'] != floatval($_POST['price_for_many50night'])) ||
                    ($studio['admin_cost_1'] != floatval($_POST['admin_cost_1'])) ||
                    ($studio['admin_cost_2'] != floatval($_POST['admin_cost_2'])) ||
					($studio['admin_cost_only_one_hour_rental_weekdays'] != floatval($_POST['admin_cost_only_one_hour_rental_weekdays'])) ||
					($studio['admin_cost_only_one_hour_rental_weekends'] != floatval($_POST['admin_cost_only_one_hour_rental_weekends'])) ||
                    ($studio['price_holiday'] != floatval($_POST['price_holiday'])) ||
					($studio['pokr_cikl'] != floatval($_POST['pokr_cikl'])) ||
                    ($studio['pokr_cikl_staff'] != floatval($_POST['pokr_cikl_staff'])) ||
                    ($studio['table_prib_id'] != intval($_POST['table_prib_id']))
                ) {
                    $arr['status'] = 'error';
                    $arr['message'] = 'Месяц фиксирован';
                }
            }
        }

        if ($arr['status'] == 'ok') {
            $set = "
                , `cost1`='" . intval($_POST['cost1']) . "'
                , `cost2`='" . intval($_POST['cost2']) . "'
                , `admin_cost_1`='" . intval($_POST['admin_cost_1']) . "'
                , `admin_cost_2`='" . intval($_POST['admin_cost_2']) . "'
                , `price_holiday`='" . intval($_POST['price_holiday']) . "'
                , `pokr_cikl`='" . intval($_POST['pokr_cikl']) . "'
                , `admin_cost_only_one_hour_rental_weekdays`='" . intval($_POST['admin_cost_only_one_hour_rental_weekdays']) . "'
				, `admin_cost_only_one_hour_rental_weekends`='" . intval($_POST['admin_cost_only_one_hour_rental_weekends']) . "'
                , `pokr_cikl_staff`='" . intval($_POST['pokr_cikl_staff']) . "'
                , `table_prib_id` = '" . intval($_POST['table_prib_id']) . "'
            ";

            foreach (Studios::COUNT_PEOPLE_TYPES as $type) {
                if (!$type['always']) {
                    $set .= ', `' . $type['db_column'] . '`="' . intval($_POST[$type['db_column']]) . '"';
                    $set .= ', `' . $type['db_column_price_day'] . '`="' . intval($_POST[$type['db_column_price_day']]). '"';
                    $set .= ', `' . $type['db_column_price_night'] . '`="' . intval($_POST[$type['db_column_price_night']]). '"';
                }
            }

            if ($_POST['studio_history_id'] > 0) {
                $sql = "UPDATE studio_history SET `date`='" . date('Y-m-d', $date_to) . "' $set WHERE studio_history_id=" . intval($_POST['studio_history_id']);
                mysqli_query($db, $sql) or die(mysqli_error($db));
            } else {
                mysqli_query($db, "INSERT INTO studio_history SET
                    `studio_id`=" . intval($_POST['studio_id']) . ",
                    `date`='" . date('Y-m-d', $date_to) . "' $set") or die(mysqli_error($db));
            }

            if ($_POST['studio_history_id_prev'] > 0) {
                mysqli_query($db, "UPDATE studio_history SET `date`='" . date('Y-m-d', $date_from) . "' WHERE studio_history_id=" . intval($_POST['studio_history_id_prev'])) or die(mysqli_error($db));
            }
            Job::Add('cassa_history2table_prib');
        }
        echo json_encode_x($arr);
    }
}
function del_studios_history() {
    global $db, $user;
    $arr=Array('status'=>'ok');
    if ($user->order('studios_0_main')) {
        $result=mysqli_query($db, "SELECT * FROM `studio_history` WHERE `studio_history_id`=" . intval($_POST['studio_history_id']));
        $myrow=mysqli_fetch_array($result);
        if (Stat_month::Check_Closed(strtotime($myrow['date']))) {
            $arr['status']='error';
            $arr['message']='Месяц фиксирован';
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    if ($arr['status']=='ok') {
        Job::Add('cassa_history2table_prib');
        mysqli_query($db, "DELETE FROM `studio_history` WHERE `studio_history_id`=" . intval($_POST['studio_history_id']));
    }
    echo json_encode_x($arr);
}
function edt_studios_history() {
    global $db, $user;
    if ($user->order('studios_0_main',1)) {
        $arr=Array();
        ob_start();
        if ($_POST['studio_history_id']) {
            $result = mysqli_query($db, "SELECT * FROM `studio_history` WHERE `studio_history_id`=" . intval($_POST['studio_history_id']));
            if (mysqli_num_rows($result)) {
                $studio_history = mysqli_fetch_array($result);
                $sql = "SELECT * FROM `studio_history` WHERE `studio_id`=" . $studio_history['studio_id'] . " AND `date`<'" . $studio_history['date'] . "' ORDER BY `date` DESC LIMIT 1";
                $result = mysqli_query($db, $sql);
                $studio_history_prev = mysqli_fetch_array($result);
            }
        } else {
            $studio = Studios::Get_Studio($_POST['studio_id']);
            $studio_history_prev['date'] = $_POST['date_from'];
            $studio_history['date'] = $_POST['date_to'];
            $studio_history['table_prib_id'] = $studio['table_prib_id'];
            $studio_history['pokr_cikl'] = $studio['pokr_cikl'];
            $studio_history['pokr_cikl_staff'] = $studio['pokr_cikl_staff'];
            $studio_history['cost1'] = $studio['cost1'];
            $studio_history['cost2'] = $studio['cost2'];

            foreach (Studios::COUNT_PEOPLE_TYPES as $type) {
                if (!$type['always']) {
                    $studio_history[$type['db_column']] = $studio[$type['db_column']];
                    $studio_history[$type['db_column_price_day']] = $studio[$type['db_column_price_day']];
                    $studio_history[$type['db_column_price_night']] = $studio[$type['db_column_price_night']];
                }
            }

            $studio_history['admin_cost_1'] = $studio['admin_cost_1'];
            $studio_history['admin_cost_2'] = $studio['admin_cost_2'];
            $studio_history['price_holiday'] = $studio['price_holiday'];
            $studio_history['admin_cost_only_one_hour_rental_weekdays'] = $studio['admin_cost_only_one_hour_rental_weekdays'];
            $studio_history['admin_cost_only_one_hour_rental_weekends'] = $studio['admin_cost_only_one_hour_rental_weekends'];
        }
        ?>
        <input type="hidden" name="act" value="save_studios_history"/>
        <input type="hidden" name="studio_history_id_prev" value="<?=$studio_history_prev['studio_history_id']?>"/>
        <input type="hidden" name="studio_history_id" value="<?=intval($_POST['studio_history_id'])?>"/>
        <input type="hidden" name="studio_id" value="<?=intval($_POST['studio_id'])?>"/>
        <table class="table table_info">
            <?php if ($studio_history_prev['date']) { ?>
                <tr>
                    <td align="right">c </td>
                    <td><input style="text-align: center;margin: 0;width:120px;" value="<?php if ($studio_history_prev['date']) echo date('d.m.Y', strtotime($studio_history_prev['date'])); ?>" type="text" name="date_from" class="datepicker"/></td>
                </tr>
            <?php } else  { ?>
                <tr>
                    <td align="right">c </td><td>начала работы</td>
                </tr>
            <?php } ?>
            <tr>
                <td width="200" align="right">по </td>
                <td><input style="text-align: center;margin: 0;width:120px;" value="<?php if ($studio_history['date']) echo date('d.m.Y',strtotime($studio_history['date']) - 3600 * 24); ?>" type="text" name="date_to" class="datepicker"/></td>
            </tr>
            <tr>
                <td align="right">Стоимость аренды:</td>
                <td>
                    <div style="margin-bottom: 5px;">Днем (с 9 до 21): <input type="number" value="<?=$studio_history['cost1']?>" name="cost1" style="width:100px;"/> Р</div>
                    <div>Ночью (с 21 до 9): <input type="number" value="<?=$studio_history['cost2']?>" name="cost2" style="width:100px;"/> Р</div>
                </td>
            </tr>

            <?php foreach (Studios::COUNT_PEOPLE_TYPES as $type) : ?>
                <?php if (!$type['always']) : ?>
            <tr>
                <td align="right">Стоимость аренды если <?= custom_lcfirst($type['title']) ?>:</td>
                <td>
                    <input type="checkbox" class="js-switch" value="1" name="<?= $type['db_column'] ?>" <?= $studio_history[$type['db_column']] == 1 ? 'checked="checked"' : '' ?>/>
                    <div class="<?= 'sh_'.$type['class'] ?>">
                        <div style="margin-top: 10px;">Днем (с 9 до 21): <input type="number" value="<?= $studio_history[$type['db_column_price_day']] ?>" name="<?= $type['db_column_price_day'] ?>" style="width:100px;"/> Р</div>
                        <div style="margin-top: 10px;">Ночью (с 21 до 9): <input type="number" value="<?= $studio_history[$type['db_column_price_night']] ?>" name="<?= $type['db_column_price_night'] ?>" style="width:100px;"/> Р</div>
                    </div>
                </td>
            </tr>
                <?php endif; ?>
            <?php endforeach; ?>

            <tr>
                <td align="right">
                    Администратору:
                </td>
                <td>
                    <div>Днем (с 9 до 21): <input type="number" value="<?=$studio_history['admin_cost_1']?>" name="admin_cost_1" style="width:100px;"/> Р</div>
                    <div style="margin-top: 10px;">Ночью (с 21 до 9): <input type="number" value="<?=$studio_history['admin_cost_2']?>" name="admin_cost_2" style="width:100px;"/> Р</div>
					<div style="margin-top: 10px;"><i>За часовую аренду:</i></div>
					<div style="margin-top: 10px;">Будни: <input name="admin_cost_only_one_hour_rental_weekdays" value="<?=$studio_history['admin_cost_only_one_hour_rental_weekdays']?>" type="number" style="width:100px;"/> <i class="fa fa-rub" aria-hidden="true"></i></div>
					<div style="margin-top: 10px;">Выходные (сб, вс): <input name="admin_cost_only_one_hour_rental_weekends" value="<?=$studio_history['admin_cost_only_one_hour_rental_weekends']?>" type="number" style="width:100px;"/> <i class="fa fa-rub" aria-hidden="true"></i></div>
				</td>
            </tr>
            <tr>
                <td align="right">
                    Добавочная стоимость часа в выходные:
                </td>
                <td>
                    <input type="checkbox" class="js-switch" value="1" name="price_holiday_btn" <?= $studio_history['price_holiday'] != 0 ? 'checked="checked"' : '' ?>/>
                    <div class="sh_price_holiday_block" <?php if ($studio_history['price_holiday']==0) echo 'style="display:none;"'; ?>>
                        <div style="margin-top: 10px;">Наценка: <input type="number" value="<?= $studio_history['price_holiday'] ?>" name="price_holiday" style="width:100px;"/> Р</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">Циклорама:</td>
                <td>
                    <input type="checkbox" class="js-switch" value="1" name="pokr_cikl_chb" <?=($studio_history['pokr_cikl']>0 ? 'checked="checked"' : '')?>/>
                    <div class="in_pokr_cikl">
                        <div style="margin-top: 10px;">Стоимость для клиента: <input name="pokr_cikl" value="<?=$studio_history['pokr_cikl']?>" type="number" style="width:100px;"/> Р</div>
                        <div style="margin-top: 10px;">З/п сотруднику за покраску: <input name="pokr_cikl_staff" value="<?=$studio_history['pokr_cikl_staff']?>" type="number" style="width:100px;"/> Р</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">
                    Раздел журнала расчетов:
                </td>
                <td>
                    <select name="table_prib_id">
                        <?php
                        $result3=mysqli_query($db, "SELECT * FROM `table_prib` ORDER BY `index`");
                        while ($myrow3=mysqli_fetch_array($result3)) {
                            ?>
                            <option <?=($myrow3['table_prib_id']==$studio_history['table_prib_id'] ? 'selected="selected"' : '')?> value="<?=$myrow3['table_prib_id']?>"><?=$myrow3['title']?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <div style="clear:both;"></div>
        <div style="float:right;text-align: right;">
            <div class="error"></div>
        </div>
        <div style="clear:both;"></div>
        <?php if ($_POST['studio_history_id']) { ?>
            <div style="float:left;" class="form_button red del_studios_history">Удалить</div>
        <?php } ?>
        <div style="float:right;" class="form_button ok_studios_history">Сохранить</div>
        <div style="clear:both;"></div>
        <?php
        $arr['html'] = ob_get_contents();
        ob_clean();
        echo json_encode_x($arr);
    }
}
function get_studios_history() {
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_0_main',1)) {
        $arr = Array();
        ob_start();
        $last_date = 0;
        $result = mysqli_query($db, "SELECT * FROM `studio_history`
            WHERE `studio_id`=" . intval($_POST['studio_id']) . " ORDER BY `date` ASC");
        if (mysqli_num_rows($result)) {
            while ($myrow = mysqli_fetch_array($result)) {
                if ((!$last_date) || (strtotime($last_date) + 3600 * 24 < strtotime($myrow['date'])))
                    $class_cross_right = 'edt_studios_history';
                else
                    $class_cross_right = '';
                ?>
                <i class="fa fa-long-arrow-right studios_history_right <?= $class_cross_right ?>" rel="0"
                   date_from="<?= $last_date ?>"
                   date_to="<?= date('Y-m-d', strtotime($myrow['date']) - 3600 * 24) ?>"></i>
                <div class="iitem edt_studios_history" rel="<?= $myrow['studio_history_id'] ?>">
                    <?php if ($last_date) { ?>
                        с <?= date('d.m.Y', strtotime($last_date)) ?><br/>
                    <?php } else { ?>
                        с начала<br/>
                    <?php } ?>
                    до <?= date('d.m.Y', strtotime($myrow['date']) - 24*3600) ?>
                </div>
                <?php
                $last_date = $myrow['date'];

            }
        } else {
            ?>
            <h3 style="text-align: left;">Истории изменений нет</h3>
            <?php
        }
        ?>
        <div style="clear: both;"></div>
        <div class="form_button mini edt_studios_history" rel="0" date_from="<?= $last_date ?>" date_to="">Добавить изменение</div>
        <div style="clear: both;"></div>
        <?php
        $arr['html'] = ob_get_contents();
        ob_clean();
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function del_owners()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_5', 0)) {
        mysqli_query($db, "UPDATE owners SET `del`=" . intval($_POST['val']) . " WHERE owner_id=" . intval($_POST['id']));
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}

function set_order_owners()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_5', 0)) {
        $list = explode(';', $_POST['order']);
        $i=1;
        foreach ($list as $index => $value) {
            $sql = "UPDATE owners SET `index`=$i WHERE owner_id=" . intval($value);
            mysqli_query($db, $sql) or die(mysqli_error($db));
            $i=1;
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function del_table_prib_categ()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_4', 0)) {
        mysqli_query($db, "UPDATE table_prib_categ SET `del`=" . intval($_POST['val']) . " WHERE table_prib_categ_id=" . intval($_POST['id']));
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}

function set_order_table_prib_categ()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_4', 0)) {
        $list = explode(';', $_POST['order']);
        $i=1;
        foreach ($list as $index => $value) {
            $index=intval($index);
            $sql = "UPDATE table_prib_categ SET `index`=$i WHERE table_prib_categ_id=" . intval($value);
            mysqli_query($db, $sql) or die(mysqli_error($db));
            $i++;
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function set_order_table_prib()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_3', 0)) {
        $list = explode(';', $_POST['order']);
        $i=1;
        foreach ($list as $index => $value) {
            $sql = "UPDATE table_prib SET `index`=$i WHERE table_prib_id=" . intval($value);
            mysqli_query($db, $sql) or die(mysqli_error($db));
            $i++;
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}

function set_order_studio()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_0',0)) {
        $list=explode(';',$_POST['order']);
        $i=1;
        foreach ($list as $index=>$value) {
            $sql="UPDATE `studios` SET `index2`=$i WHERE `studio_id`=".intval($value);
            mysqli_query($db,$sql) or die(mysqli_error($db));
            $i++;
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function set_order_locations()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_2', 0)) {
        $sorts = Array('index' => 'Основная', 'index1' => 'Интерьеры');
        if (($_POST['sort']) && ($sorts[$_POST['sort']])) {
            $sort = $_POST['sort'];
        } else {
            $sort = 'index';
        }
        $list = explode(';', $_POST['order']);
        $i=1;
        foreach ($list as $index => $value) {
            $sql = "UPDATE locations SET `$sort`=$i WHERE location_id=" . intval($value);
            mysqli_query($db, $sql) or die(mysqli_error($db));
            $i++;
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function del_location()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_2', 0)) {
        mysqli_query($db, "UPDATE `locations` SET `del`=" . intval($_POST['val']) . " WHERE `location_id`=" . intval($_POST['id']));
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function del_table_podlocation()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_2', 0)) {
        mysqli_query($db, "UPDATE podlocation SET `del`=" . intval($_POST['val']) . " WHERE podlocation_id=" . intval($_POST['id']));
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function del_dressings()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('book_1', 0)) {
        mysqli_query($db, "UPDATE `dressings` SET `del`=" . intval($_POST['val']) . " WHERE `dressing_id`=" . intval($_POST['id']));
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function set_order_podlocation()
{
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_2', 0)) {
        $list = explode(';', $_POST['order']);
        $i=1;
        foreach ($list as $index => $value) {
            $sql = "UPDATE `podlocation` SET `index`=$i WHERE `podlocation_id`=" . intval($value);
            mysqli_query($db, $sql) or die(mysqli_error($db));
            $i++;
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function set_order_interiors()
{
    global $user;
    $arr = Array('status'=>'ok');
    if (($user->order('studios_0', 0))||($user->order('newyear', 0))||($user->order('workshop', 0))) {
        $list = explode(';', $_POST['order']);
        $i=1;
        foreach ($list as $index => $value) {
            Studios::UpdateInterior($value, Array('index'=>$i));
            $i++;
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function del_interior(){
    global $user;
    $arr = Array('status'=>'ok');
    $interior = Studios::GetInterior($_POST['interior_id']);
    if (($user->order('studios_0', 0))||(($user->order('newyear', 0))&&($interior['schedule_id']==0))||($user->order('workshop', 0))) {
        Studios::DelInterior($interior['interior_id']);
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function del_all_interiors(){
    global $user;
    $arr = Array('status'=>'ok');
    if (
        (($user->order('studios_0', 0))&&($_POST['gallery_type']==0))
        ||(($user->order('newyear', 0))&&($_POST['gallery_type']==1))
        ||(($user->order('decor', 0))&&($_POST['gallery_type']==2))
        ||(($user->order('workshop', 0))&&($_POST['gallery_type']==3))
    ) {
        $p=Array('gallery_type' => $_POST['gallery_type']);
        if ($_POST['gallery_type']==0 || $_POST['gallery_type']==1) {
            $p['studio_id'] = $_POST['studio_id'];
        }
        $view['Interiors.class'] = Studios::GetInteriorAll($p);
        foreach ($view['Interiors.class']['items'] as $item) {
            Studios::DelInterior($item['interior_id']);
            $arr['i'][]=$item['interior_id'];
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function add_interior_video()
{
    global $user;
    $arr = Array('status'=>'ok');
    if (
        (($user->order('studios_0', 0))&&($_POST['gallery_type']==0))
        ||(($user->order('newyear', 0))&&($_POST['gallery_type']==1))
        ||(($user->order('decor', 0))&&($_POST['gallery_type']==2))
    ) {
        $arr = Video::get_info($_POST['link']);
        if ($arr) {

            $interior_id = Studios::InsertInterior(Array(
                'orientation' => 0,
                'image0' => cstr($arr['thumbnail']),
                'index' => 0,
                'slider_show' => 0,
                'type' => 1,
                'video' => cstr($arr['key']),
                'gallery_type' => intval($_POST['gallery_type']),
                'studio_id' => intval($_POST['studio_id'])
            ));
            $arr['items'][] = Array(
                'image' => $arr['thumbnail'],
                'href' => $arr['link'],
                'interier_id' => $interior_id,
                'orientation' => 0,
                'type' => 1,
                'video' => cstr($arr['key']),
                'slider_show' => 0,
                'gallery_type' => intval($_POST['gallery_type'])
            );
        } else {
            $arr['status'] = 'error';
            $arr['message'] = "Видео не найдено";
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode($arr);
}
function load_interiors() {
    global $user;
    $arr = Array('status'=>'ok');
    if (
        (($user->order('studios_0', 0))&&($_POST['gallery_type']==0))
        ||(($user->order('newyear', 0))&&($_POST['gallery_type']==1))
        ||(($user->order('decor', 0))&&($_POST['gallery_type']==2))
        ||(($user->order('workshop', 0))&&($_POST['gallery_type']==3))
    ) {
        $image_config = Studios::InteriersImagesCfg();
        if ($_FILES['images']) {
            foreach ($_FILES['images']['name'] as $i=>$file) {
                if ($_FILES['images']['tmp_name'][$i]) {
                    $rez = Form::CheckFileName($_FILES['images']['tmp_name'][$i]);
                    if ($rez['status'] == 'ok') {
                        $filename = Form::GetFilename($image_config[0]['dir'], $rez['file']);

                        Form::SaveImage($_FILES['images']['tmp_name'][$i], $filename, $image_config);
                        $size = getimagesize($image_config[0]['dir'].$filename);

                        if ($size[0]>$size[1]) {
                            $orientation = 1;
                        } else {
                            $orientation = 0;
                        }
                        if ($_POST['gallery_type']!=3){
                            $interior_id = Studios::InsertInterior(Array(
                                'orientation' => $orientation,
                                'image0' => $filename,
                                'index' => 0,
                                'slider_show' => 0,
                                'gallery_type' => intval($_POST['gallery_type']),
                                'studio_id' => intval($_POST['studio_id']),
                                'decor_id' => intval($_POST['decor_id'])
                            ));
                            $arr['items'][] = Array(
                                'interier_id' => $interior_id,
                                'type' => 0,
                                'orientation' => $orientation,
                                'href' => $image_config[0]['url'].$filename,
                                'image' => $image_config[1]['url'].$filename,
                                'slider_show' => 0,
                                'gallery_type' => intval($_POST['gallery_type']),
                                'studio_id' => intval($_POST['studio_id'])
                            );
                        }else{
                            $interior_id = Studios::InsertInterior(Array(
                                'orientation' => $orientation,
                                'image0' => $filename,
                                'index' => 0,
                                'slider_show' => 0,
                                'gallery_type' => intval($_POST['gallery_type']),
                                'studio_id' => intval($_POST['studio_id']),
                                'decor_id' => intval($_POST['decor_id']),
                                'workshop_id' => intval($_POST['workshop_id'])
                            ));
                            $arr['items'][] = Array(
                                'interier_id' => $interior_id,
                                'type' => 0,
                                'orientation' => $orientation,
                                'href' => $image_config[0]['url'].$filename,
                                'image' => $image_config[1]['url'].$filename,
                                'slider_show' => 0,
                                'gallery_type' => intval($_POST['gallery_type']),
                                'studio_id' => intval($_POST['studio_id']),
                            );
                        }

                    }
                }
            }
        } else {
            $arr['status'] = 'error';
            $arr['message'] = 'Файлы не загружены. Возможно его размер больше ' . ini_get('upload_max_filesize');
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function save_interior() {
    global $user;
    $arr = Array('status'=>'ok');
    $interior = Studios::GetInterior($_POST['interior_id']);
    if (
        (($user->order('studios_0', 0))&&($interior['gallery_type']==0))
        ||(($user->order('newyear', 0))&&($interior['gallery_type']==1))
        ||(($user->order('decor', 0))&&($interior['gallery_type']==2))
        ||(($user->order('workshop', 0))&&($interior['gallery_type']==3))
    ) {
        $p = [];
        if ($interior['type']==0) {
            $p['slider_show'] = intval($_POST['slider_show']);
        }
        if ($interior['gallery_type']==0) {
            $p['decor'] = intval($_POST['decor']);
            $p['decor_id'] = intval($_POST['decor_id']);
        } else {
            $p['studio_id'] = intval($_POST['studio_id']);
        }
        Studios::UpdateInterior($interior['interior_id'], $p);
        $arr['item'] = Studios::GetInterior($interior['interior_id']);
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
function load_interior_item() {
    global $user;
    $arr = Array('status'=>'ok');
    $interior = Studios::GetInterior($_POST['interior_id']);
    if (
        (($user->order('studios_0', 1))&&($interior['gallery_type']==0))
        ||(($user->order('newyear', 1))&&($interior['gallery_type']==1))
        ||(($user->order('decor', 1))&&($interior['gallery_type']==2))
        ||(($user->order('workshop', 0))&&($interior['gallery_type']==3))
    ) {
        $view['edited'] = (
            (($user->order('studios_0', 0))&&($interior['gallery_type']==0))
            ||(($user->order('newyear', 0))&&($interior['gallery_type']==1))
            ||(($user->order('decor', 0))&&($interior['gallery_type']==2))
            ||(($user->order('workshop', 0))&&($interior['gallery_type']==3))
        );
        $image_config = Studios::InteriersImagesCfg();
        $view['locations']=Studios::GetPodLocArray(Array(
            'del'=>0
        ), '`index1`');

        $decor = new Decors();
        $decor->GetItems();

        ob_start();
        ?>
        <input type="hidden" name="act" value="save_interior"/>
        <input type="hidden" name="interior_id" value="<?=$interior['interior_id']?>"/>
        <?php
        if ($interior['type']==0) {
            ?>
            <div href="<?=$image_config[0]['url'] . $interior['image0']?>" class="lightview preview" style="background-image: url('<?=$image_config[0]['url'] . $interior['image0']?>');"></div>
            <?php
        }
        if ($interior['type']==1) {
            ?>
            <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?=$interior['video']?>" frameborder="0" allowfullscreen></iframe>
            <?php
        }
        if (($interior['type']==0)&&($interior['orientation']==1) &&($interior['gallery_type']!=3)) { ?>
            <div class="form_item">
                <label class="form_control">
                    <input type="checkbox" class="js-switch" name="slider_show" value="1" <?=($interior['slider_show']==1 ? 'checked="chedcked"' : '')?>/>
                    Показывать в слайдере
                </label>
            </div>
        <?php } ?>
        <?php if ($interior['gallery_type']==0) { ?>
            <div class="form_item">
                <label class="form_control">
                    <input type="checkbox" class="js-switch" name="decor" value="1" <?=($interior['decor']==1 ? 'checked="chedcked"' : '')?>/>
                    Cross+Decor
                </label>
            </div>
            <div class="form_item decor_select" style="<?=($interior['decor']==1 ? '' : 'display:none;')?>">
                <label class="form_control">Декор:</label>
                <select name="decor_id">
                    <option value="0">Не выбрано</option>
                    <?php foreach ($decor->items as $item) { ?>
                        <option value="<?=$item['decor_id']?>" <?=($item['decor_id']==$interior['decor_id'] ? 'selected="selected"' : '')?>><?=$item['title']?></option>
                    <?php } ?>
                </select>
            </div>
        <?php } else { if ($interior['gallery_type']!=3){ ?>
            <div class="form_item">
                <label class="form_control">Студия</label>
                <select class="form_control" name="studio_id">
                    <option value="0">Не выбрана</option>
                    <?php
                    foreach ($view['locations'] as $i_loc=>$loc)
                    {
                        if ($loc['has_studios']) {
                            ?>
                            <option disabled="disabled"><?= $loc['title1'] ?></option>
                            <?php
                            foreach ($loc['items'] as $i_podloc => $podloc) {
                                if (count($loc['items']) > 1) {
                                    ?>
                                    <option disabled="disabled">&nbsp;&nbsp;<?= $podloc['title'] ?></option>
                                    <?php
                                }
                                foreach ($podloc['items'] as $i_std => $std) {
                                    ?>
                                    <option <?=($interior['studio_id']==$std['studio_id'] ? 'selected="selected"' : '')?> value="<?= $std['studio_id'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?= $std['title'] ?></option>
                                    <?php
                                }
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        <?php }} ?>
        <?php if ($view['edited']) { ?>
            <div class="form_button red del" style="float:left;">Удалить</div>
            <?php  if ($interior['gallery_type']!=3){ ?>
            <div class="form_button save" style="float:right;">Сохранить</div>
            <?php } ?>
        <?php } ?>
        <div style="clear:both;"></div>
        <div class="error"></div>
        <?php
        $arr['html'] = ob_get_contents();
        ob_clean();
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }

    echo json_encode_x($arr);
}

function savePriceOption($title, $cost, $desc, $studio_id, $has_hourly_pay, $positive_option_text, $negative_option_text) {
    // Запрет создавать больше чем одной почасовой ценовой опции
    if ($has_hourly_pay) {
        $horlyPriceOption = (new StudioPriceOption())->getBy('`studio_id`="'.$studio_id.'" AND `has_hourly_pay`=1', ' AND `del`=0');
        if ($horlyPriceOption['status'] === 'ok' && count($horlyPriceOption['items'])) {
            $result = [
                'status' => 'error',
                'error' => 'Нельзя создавать больше одной почасовой ценовой опции!'
            ];
            echo json_encode_x($result);
            return;
        }
    }

    $result = (new StudioPriceOption())->save($title, $cost, $desc, $studio_id, $has_hourly_pay, $positive_option_text, $negative_option_text);
    echo json_encode_x($result);
}

function editPriceOption($id, $desc, $has_hourly_pay, $positive_option_text, $negative_option_text, $studio_id) {
    if ($has_hourly_pay) {
        $horlyPriceOption = (new StudioPriceOption())->getBy('`studio_id`="'.$studio_id.'" AND `has_hourly_pay`=1', ' AND `del`=0');
        if ($horlyPriceOption['status'] === 'ok' && count($horlyPriceOption['items'])
                && $horlyPriceOption['items'][0]['id'] != $id) {
            $result = [
                'status' => 'error',
                'error' => 'Нельзя создавать больше одной почасовой ценовой опции!'
            ];
            echo json_encode_x($result);
            return;
        }
    }

    $params = '`option_desc`="'.$desc.'", ';
    $params .= '`has_hourly_pay`="'.$has_hourly_pay.'", ';
    $params .= '`positive_option_text`="'.$positive_option_text.'", ';
    $params .= '`negative_option_text`="'.$negative_option_text.'"';

    $result = (new StudioPriceOption())->updateByParams($params, $id);
    $result['info'] = 'Опция успешно сохранена!';
    $result['error'] = 'Ошибка сохранения!';
    echo json_encode_x($result);
}

function deletePriceOption($id) {
    $result = (new StudioPriceOption())->updateByParams('`del`=1', $id);
    echo json_encode_x($result);
}

function editStudioAttachment($id, $content)
{
    global $db, $user;
    $arr = array('status' => 'ok');

    if ($user->order('studios_6', 0)) {
        if (!empty($content)) {
            $content = mysqli_real_escape_string($db, $content);

            $result = mysqli_query($db, "SELECT * FROM studio_attachment WHERE id=$id");
            if ($result && mysqli_num_rows($result) > 0) {
                mysqli_query($db, "UPDATE studio_attachment SET content='$content' WHERE id=1");
            } else {
                mysqli_query($db, "INSERT INTO studio_attachment (id, content) VALUES ($id, '$content')");
            }
        } else {
            mysqli_query($db, "DELETE FROM studio_attachment WHERE id=1");
        }
    } else {
        $arr['status'] = 'error';
        $arr['message'] = 'Нет доступа';
    }

    echo json_encode($arr);
}

function addStudiosMainPage($studio_id) {
    global $db, $user;
    
    $arr = Array('status'=>'ok');
    if ($user->order('studios_0', 0)) {

        $result = mysqli_query($db, $sql = "SELECT COUNT(*) FROM `studios_main_page` WHERE `studio_id`=".intval($studio_id));
        $myrow = mysqli_fetch_array($result);

        if ($myrow[0]) {
            $arr['status']='error';
            $arr['message']='Данная студия уже добавлена на главный экран';
        } else {
            $sql="INSERT INTO `studios_main_page` SET `studio_id`=".intval($studio_id);
            mysqli_query($db, $sql) or die($sql.' '.mysqli_error($db));
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    
    echo json_encode_x($arr);
    
}

function changeOrderStudiosMainPage() {
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_0', 0)) {
        $list = explode(';', $_POST['order']);
        $i=1;
        foreach ($list as $index => $value) {
            $sql = "UPDATE `studios_main_page` SET `index`=$i WHERE `studio_id`=" . intval($value);
            mysqli_query($db, $sql) or die(mysqli_error($db));
            $i++;
        }
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}

function deleteStudiosMainPage($studio_id) {
    global $db, $user;
    $arr = Array('status'=>'ok');
    if ($user->order('studios_0', 0)) {
        mysqli_query($db, "DELETE FROM `studios_main_page` WHERE `studio_id`=" . intval($studio_id));
    } else {
        $arr['status']='error';
        $arr['message']='Нет доступа';
    }
    echo json_encode_x($arr);
}
