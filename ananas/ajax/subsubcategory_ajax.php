<?php
include_once '../header.php';

if($loggedin == FALSE)
{
    header("Location: ../index.php?reason=Access+denied");
    exit();
}

//selecting
if(isset($_POST['select_id']))
{

    $id = $_POST['select_id'];

    $queryR = "SELECT * FROM rubrica";
    $advQR = queryMysql($queryR);

    $query = "SELECT * FROM category WHERE IdRubrica = $id";
    $cats = queryMysql($query);

    ?>
    <div class="row-fluid">
        <div id="add_subcat_cont" class="span14">
            <form id="add_subcat_form" class="form-horizontal">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">Рубрика</label>
                        <div class="controls">
                            <select id="rubrica3" name="rubrica3">
                                <? while($rub = mysql_fetch_object($advQR)) { ?>
                                    <option <?if($rub->ID == $id) echo "selected";?> value="<?=$rub->ID?>"><?=$rub->Name?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Категория</label>
                        <div class="controls">
                            <select id="category2" name="category2">
                                <option selected value=""></option>
                                <? while($cat = mysql_fetch_object($cats)) { ?>
                                    <option value="<?=$cat->ID?>"><?=$cat->Name?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Подкатегория</label>
                        <div class="controls">
                            <input id="subcatName" name="subcatName" type="text" value="" class="span10">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Подкатегория 2</label>
                        <div class="controls">
                            <input id="subcatName2" name="subcatName2" type="text" value="" class="span10">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Подкатегория 3</label>
                        <div class="controls">
                            <input id="subcatName3" name="subcatName3" type="text" value="" class="span10">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Подкатегория 4</label>
                        <div class="controls">
                            <input id="subcatName4" name="subcatName4" type="text" value="" class="span10">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Подкатегория 5</label>
                        <div class="controls">
                            <input id="subcatName5" name="subcatName5" type="text" value="" class="span10">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Подкатегория 6</label>
                        <div class="controls">
                            <input id="subcatName6" name="subcatName6" type="text" value="" class="span10">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Подкатегория 7</label>
                        <div class="controls">
                            <input id="subcatName7" name="subcatName7" type="text" value="" class="span10">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <?
    exit();
}

//adding
if(isset($_POST['category']) && isset($_POST['rubrica']) && isset($_POST['subcatName']))
{
    $category = sanitizeString($_POST['category']);
    $subcatName = sanitizeString($_POST['subcatName']);
    $subcatName2 = sanitizeString($_POST['subcatName2']);
    $subcatName3 = sanitizeString($_POST['subcatName3']);
    $subcatName4 = sanitizeString($_POST['subcatName4']);
    $subcatName5 = sanitizeString($_POST['subcatName5']);
    $subcatName6 = sanitizeString($_POST['subcatName6']);
    $subcatName7 = sanitizeString($_POST['subcatName7']);

    if($subcatName != "")
    {
        queryMysql("INSERT INTO subcategory VALUES (NULL,'$subcatName', '$category')");
    }
    if($subcatName2 != "")
    {
        queryMysql("INSERT INTO subcategory VALUES (NULL,'$subcatName2', '$category')");
    }
    if($subcatName3 != "")
    {
        queryMysql("INSERT INTO subcategory VALUES (NULL,'$subcatName3', '$category')");
    }
    if($subcatName4 != "")
    {
        queryMysql("INSERT INTO subcategory VALUES (NULL,'$subcatName4', '$category')");
    }
    if($subcatName5 != "")
    {
        queryMysql("INSERT INTO subcategory VALUES (NULL,'$subcatName5', '$category')");
    }
    if($subcatName6 != "")
    {
        queryMysql("INSERT INTO subcategory VALUES (NULL,'$subcatName6', '$category')");
    }
    if($subcatName7 != "")
    {
        queryMysql("INSERT INTO subcategory VALUES (NULL,'$subcatName7', '$category')");
    }

    $query = "SELECT a.ID as id, a.Name, a.IdCategory, c.Name as CategoryName, c.IdRubrica, u.Name as RubricaName FROM subcategory AS a LEFT JOIN category as c ON a.IdCategory = c.ID LEFT JOIN rubrica as u ON c.IdRubrica = u.ID";

    $advQ = queryMysql($query);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><?=$adv->id?></td>
            <td><?=$adv->RubricaName?></td>
            <td><?=$adv->CategoryName?></td>
            <td><?=$adv->Name?></td>
            <td type="edit" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
            <td type="delete" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#delete_member" class="splashy-contact_blue_remove"></i></td>
        </tr>
    <? }

    exit();
}
//editing
if(isset($_POST['id']))
{

    $id = $_POST['id'];

    $query = "SELECT a.ID as id, a.Name, a.IdCategory, c.Name as CategoryName, c.IdRubrica, u.Name as RubricaName FROM subcategory AS a LEFT JOIN category as c ON a.IdCategory = c.ID LEFT JOIN rubrica as u ON c.IdRubrica = u.ID WHERE a.ID=$id";

    $advQ = mysql_fetch_object(queryMysql($query));
    ?>
    <div class="row-fluid">
        <div id="edit_member_cont" class="span14">
            <form id="edit_member_form" class="form-horizontal">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">Рубрика</label>
                        <div class="controls">
                            <input id="rubricaName3" name="rubricaName3" disabled="disabled" type="text" value="<?=$advQ->RubricaName?>" class="span12">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Категория</label>
                        <div class="controls">
                            <input id="catName3" name="catName3" disabled="disabled" type="text" value="<?=$advQ->CategoryName?>" class="span12">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Подкатегория</label>
                        <div class="controls">
                            <input id="subcatName3" name="subcatName3" type="text" value="<?=$advQ->Name?>" class="span12">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <?
    exit();
}


if(isset($_POST['subcatName']))
{
    $id = sanitizeString($_POST['subcat_id']);
    $subcatName = sanitizeString($_POST['subcatName']);

    queryMysql("UPDATE category SET Name='$subcatName' WHERE ID = $id");

    $query = "SELECT a.ID as id, a.Name, a.IdCategory, c.Name as CategoryName, c.IdRubrica, u.Name as RubricaName FROM subcategory AS a LEFT JOIN category as c ON a.IdCategory = c.ID LEFT JOIN rubrica as u ON c.IdRubrica = u.ID";

    $advQ = queryMysql($query);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><?=$adv->id?></td>
            <td><?=$adv->RubricaName?></td>
            <td><?=$adv->CategoryName?></td>
            <td><?=$adv->Name?></td>
            <td type="edit" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
            <td type="delete" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#delete_member" class="splashy-contact_blue_remove"></i></td>
        </tr>
    <? }

    exit();
}

//deleting

if(isset($_POST['delete_id']))
{
    $id = sanitizeString($_POST['delete_id']);
    queryMysql("DELETE FROM subcategory WHERE ID = $id");

    $query = "SELECT a.ID as id, a.Name, a.IdCategory, c.Name as CategoryName, c.IdRubrica, u.Name as RubricaName FROM subcategory AS a LEFT JOIN category as c ON a.IdCategory = c.ID LEFT JOIN rubrica as u ON c.IdRubrica = u.ID";

    $advQ = queryMysql($query);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><?=$adv->id?></td>
            <td><?=$adv->RubricaName?></td>
            <td><?=$adv->CategoryName?></td>
            <td><?=$adv->Name?></td>
            <td type="edit" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
            <td type="delete" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#delete_member" class="splashy-contact_blue_remove"></i></td>
        </tr>
    <? }

    exit();
}
