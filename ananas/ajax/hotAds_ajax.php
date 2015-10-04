<?php
include_once '../header.php';

if($loggedin == FALSE)
{
    header("Location: ../index.php?reason=Access+denied");
    exit();
}


if(isset($_POST['id'])) {

    $id = $_POST['id'];
    $status = $_POST['status'];

    queryMysql("UPDATE advertisements SET status=$status WHERE ID = $id");

    $query = "SELECT a.ID as id, a.title, a.catalog, a.cat, a.subcat, a.text, a.name, a.city, a.email, a.phone1, a.phone2, a.style,
a.carcase, a.facade, a.tabletop, a.priceIs, a.price, a.pricecurrency, a.pricefor, a.color, a.material, a.manufacturer,
a.length, a.height, a.width, a.shipment, a.feature, a.createddate, a.status, a.statusChangeDate, a.type, a.upholstery,
a.mechanism, a.foundation, a.enablecomments, a.idUser, u.user_login, u.user_pass, u.user_nicename, u.user_email, u.user_phone,
u.user_registered, u.user_city, u.user_status, u.user_salonname, u.user_salonstatus FROM advertisements AS a LEFT JOIN users as u ON a.idUser = u.ID WHERE a.status=2 ORDER BY a.city";

    $advQ = queryMysql($query);

    $numOfAdvs = mysql_num_rows($advQ);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><? if($adv->catalog == "1") echo "Мебель для дома"; if($adv->catalog == "2") echo "Всё для интерьера"; if($adv->catalog == "3") echo "Мебель для офиса"; if($adv->catalog == "4") echo "Услуги"; if($adv->catalog == "5") echo "Профильная для бизнеса";?></td>
            <td><?=$adv->cat?></td>
            <td><?=$adv->title?></td>
            <td><?=$adv->createddate?></td>
            <td><?if($adv->user_status == 0 || $adv->user_status == 1) echo $adv->user_nicename; if($adv->user_status == 2) echo "салон ". $adv->user_salonname;?></td>
            <td><?=$adv->user_login?></td>
            <td><?=$adv->user_phone?></td>
            <td type="select">
                <select name="adv_status_<?=$adv->id?>" id="adv_status_<?=$adv->id?>" class="textInput">
                    <option value="0" <?if ($adv->status == 0) echo "selected='selected'";?>>Неактивный</option>
                    <option value="1" <?if ($adv->status == 1) echo "selected='selected'";?>>Активный</option>
                    <option value="2" <?if ($adv->status == 2) echo "selected='selected'";?>>Срочно продают</option>
                </select>
            </td>
            <td type="nav" row_id="<?=$adv->id?>"><i class="splashy-fish prod_assign_btn"></i></td>
        </tr>
    <? }


    exit();
}