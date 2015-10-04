<?php
include_once '../header.php';

if($loggedin == FALSE)
{
    header("Location: ../index.php?reason=Access+denied");
    exit();
}


if(isset($_POST['id']))
{

    $id = $_POST['id'];

    $query = "SELECT * FROM advertisements WHERE ID=$id";

    $member = mysql_fetch_object(queryMysql($query));

    ?>
    <div class="row-fluid">
        <div id="edit_member_cont" class="span12">
            <form id="edit_member_form" class="form-horizontal">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">Категория</label>
                        <div class="controls">
                            <select style="color: #231F20" id="category1" name="category1" class="textInput" onchange="showCategories(this, 1)">
                                <option value=""></option>
                                <option <?if($member->catalog == "1") echo "selected";?> value="1">Мебель для дома</option>
                                <option <?if($member->catalog == "3") echo "selected";?> value="3">Мебель для офиса</option>
                                <option <?if($member->catalog == "5") echo "selected";?> value="5">Профильная для бизнеса</option>
                                <option <?if($member->catalog == "2") echo "selected";?> value="2">Всё для интерьера</option>
                                <option <?if($member->catalog == "4") echo "selected";?> value="4">Услуги</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Заголовок</label>
                        <div class="controls">
                            <input id="title" name="title" type="text" value="<?=$member->title?>" class="span8">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Цвет</label>
                        <div class="controls">
                            <input id="color" name="color" type="text" value="<?=$member->color?>" class="span8">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Материал</label>
                        <div class="controls">
                            <input id="material" name="material" type="text" value="<?=$member->material?>" class="span8">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Производство</label>
                        <div class="controls">
                            <select id="manufacturer" name="manufacturer" class="span8">
                                <option value=''></option>
                                <option <?if($member->manufacturer == "it") echo "selected";?> value='it'>Италия</option>
                                <option <?if($member->manufacturer == "ru") echo "selected";?> value='ru'>Россия</option>
                                <option <?if($member->manufacturer == "by") echo "selected";?> value='by'>Беларусь</option>
                                <option <?if($member->manufacturer == "ua") echo "selected";?> value='ua'>Украина</option>
                                <option <?if($member->manufacturer == "tr") echo "selected";?> value='tr'>Турция</option>
                                <option <?if($member->manufacturer == "pl") echo "selected";?> value='pl'>Польша</option>
                                <option <?if($member->manufacturer == "kg") echo "selected";?> value='kg'>Кыргызстан</option>
                                <option <?if($member->manufacturer == "kz") echo "selected";?> value='kz'>Казахстан</option>
                                <option <?if($member->manufacturer == "other") echo "selected";?> value='other'>Другое</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Размер</label>
                        <div class="controls">
                            <input id="length" name="length" type="text" value="<?=$member->length?>"  class="span8">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Текст объявления</label>
                        <div class="controls">
                            <textarea name="text" style="color: #231F20" id="text" cols="50" rows="3" class="span8"><?=$member->text?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Имя</label>
                        <div class="controls">
                            <input id="name" name="name" type="text" value="<?=$member->name?>" class="span8">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Email</label>
                        <div class="controls">
                            <input id="email" name="email" type="text" value="<?=$member->email?>" class="span8">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Номер телефона 1</label>
                        <div class="controls">
                            <input id="phone1" name="phone1" type="text" value="<?=$member->phone1?>" class="span8">
                        </div>
                    </div>
                    <div class="control-group formSep">
                        <label class="control-label">Номер телефона 2</label>
                        <div class="controls">
                            <input id="phone2" name="phone2" type="text" value="<?=$member->phone2?>" class="span8">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <?
    exit();
}

if(isset($_POST['member_id']))
{
    $exist1 = $exist2 = $exist3 = false;
    $mode = $category1 = $category2 = $category3 = "";

    $id = sanitizeString($_POST['member_id']);

    if (isset($_POST['category1']))
    {
        $category1 = sanitizeString($_POST['category1']);
        $exist1 = true;
    }
    if (isset($_POST['category2']))
    {
        $category2 = sanitizeString($_POST['category2']);
        $exist2 = true;
    }
    if (isset($_POST['category3']))
    {
        $category3 = sanitizeString($_POST['category3']);
        $exist3 = true;
    }
    if (isset($_POST['mode']))
    {
        $mode = sanitizeString($_POST['mode']);
    }

    $title = sanitizeString($_POST['title']);
    $color = sanitizeString($_POST['color']);
    $material = sanitizeString($_POST['material']);
    $manufacturer = sanitizeString($_POST['manufacturer']);
    $length = sanitizeString($_POST['length']);
    $text = sanitizeString($_POST['text']);
    $name = sanitizeString($_POST['name']);
    $email = sanitizeString($_POST['email']);
    $phone1 = sanitizeString($_POST['phone1']);
    $phone2 = sanitizeString($_POST['phone2']);

    if (($exist1 == true && ($category1 == "" || $category1 == NULL)) || ($exist2 == true && ($category2 == "" || $category2 == NULL)))
    {
        //$error = "Обязательные поля не заполнены";
        queryMysql("UPDATE advertisements SET title='$title', color='$color', material='$material', manufacturer='$manufacturer', length='$length', text='$text', name='$name', email='$email', phone1='$phone1', phone2='$phone2' WHERE ID = $id");
    }
    else
    {
        queryMysql("UPDATE advertisements SET catalog='$category1', cat='$category2', subcat='$category3', title='$title', color='$color', material='$material', manufacturer='$manufacturer', length='$length', text='$text', name='$name', email='$email', phone1='$phone1', phone2='$phone2' WHERE ID = $id");
    }

    $query = "SELECT a.ID as id, a.title, a.catalog, a.cat, a.subcat, a.text, a.name, a.city, a.email, a.phone1, a.phone2, a.style,
a.carcase, a.facade, a.tabletop, a.priceIs, a.price, a.pricecurrency, a.pricefor, a.color, a.material, a.manufacturer,
a.length, a.height, a.width, a.shipment, a.feature, a.createddate, a.status, a.statusChangeDate, a.type, a.upholstery,
a.mechanism, a.foundation, a.enablecomments, a.idUser, u.user_login, u.user_pass, u.user_nicename, u.user_email, u.user_phone,
u.user_registered, u.user_city, u.user_status, u.user_salonname, u.user_salonstatus FROM advertisements AS a LEFT JOIN users as u ON a.idUser = u.ID WHERE a.status=0 OR a.status=1 OR a.status=2 ORDER BY a.city";

    if($mode == "site")
    {
        $query = "SELECT a.ID as id, a.title, a.catalog, a.cat, a.subcat, a.text, a.name, a.city, a.email, a.phone1, a.phone2, a.style,
a.carcase, a.facade, a.tabletop, a.priceIs, a.price, a.pricecurrency, a.pricefor, a.color, a.material, a.manufacturer,
a.length, a.height, a.width, a.shipment, a.feature, a.createddate, a.status, a.statusChangeDate, a.type, a.upholstery,
a.mechanism, a.foundation, a.enablecomments, a.idUser, u.user_login, u.user_pass, u.user_nicename, u.user_email, u.user_phone,
u.user_registered, u.user_city, u.user_status, u.user_salonname, u.user_salonstatus FROM advertisements AS a LEFT JOIN users as u ON a.idUser = u.ID WHERE a.status=0 OR a.status=1 OR a.status=2 ORDER BY a.city";
    }
    if($mode == "moderation")
    {
        $query = "SELECT a.ID as id, a.title, a.catalog, a.cat, a.subcat, a.text, a.name, a.city, a.email, a.phone1, a.phone2, a.style,
a.carcase, a.facade, a.tabletop, a.priceIs, a.price, a.pricecurrency, a.pricefor, a.color, a.material, a.manufacturer,
a.length, a.height, a.width, a.shipment, a.feature, a.createddate, a.status, a.statusChangeDate, a.type, a.upholstery,
a.mechanism, a.foundation, a.enablecomments, a.idUser, u.user_login, u.user_pass, u.user_nicename, u.user_email, u.user_phone,
u.user_registered, u.user_city, u.user_status, u.user_salonname, u.user_salonstatus FROM advertisements AS a LEFT JOIN users as u ON a.idUser = u.ID WHERE a.status=0 ORDER BY a.city";

    }

    $advQ = queryMysql($query);

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
            <td type="edit" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
        </tr>
    <? }

    exit();
}