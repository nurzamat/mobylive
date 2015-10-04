<?php
include_once '../header.php';

if($loggedin == FALSE)
{
    header("Location: ../index.php?reason=Access+denied");
    exit();
}
//adding
if(isset($_POST['catName']) && isset($_POST['rubrica']))
{

    $catName = sanitizeString($_POST['catName']);
    $rubrica = sanitizeString($_POST['rubrica']);

    queryMysql("INSERT INTO category VALUES (NULL,'$catName', '$rubrica')");

    $query = "SELECT a.ID as id, a.Name, a.IdRubrica, u.Name as RubricaName FROM category AS a LEFT JOIN rubrica as u ON a.IdRubrica = u.ID ORDER BY a.IdRubrica";

    $advQ = queryMysql($query);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><?=$adv->id?></td>
            <td><?=$adv->RubricaName?></td>
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

    $query = "SELECT a.ID as id, a.Name, a.IdRubrica, u.Name as RubricaName FROM category AS a LEFT JOIN rubrica as u ON a.IdRubrica = u.ID WHERE a.ID = $id";

    $member = mysql_fetch_object(queryMysql($query));

    ?>
    <div class="row-fluid">
        <div id="edit_member_cont" class="span14">
            <form id="edit_member_form" class="form-horizontal">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">Рубрика</label>
                        <div class="controls">
                            <input id="rubricaName" name="rubricaName" disabled="disabled" type="text" value="<?=$member->RubricaName?>" class="span12">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Категория</label>
                        <div class="controls">
                            <input id="catName2" name="catName2" type="text" value="<?=$member->Name?>" class="span12">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <?
    exit();
}


if(isset($_POST['cat_id']))
{
    $id = sanitizeString($_POST['cat_id']);
    $catName = sanitizeString($_POST['catName']);

    queryMysql("UPDATE category SET Name='$catName' WHERE ID = $id");

    $query = "SELECT a.ID as id, a.Name, a.IdRubrica, u.Name as RubricaName FROM category AS a LEFT JOIN rubrica as u ON a.IdRubrica = u.ID ORDER BY a.IdRubrica";

    $advQ = queryMysql($query);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><?=$adv->id?></td>
            <td><?=$adv->RubricaName?></td>
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
    queryMysql("DELETE FROM category WHERE ID = $id");

    $query = "SELECT a.ID as id, a.Name, a.IdRubrica, u.Name as RubricaName FROM category AS a LEFT JOIN rubrica as u ON a.IdRubrica = u.ID ORDER BY a.IdRubrica";

    $advQ = queryMysql($query);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><?=$adv->id?></td>
            <td><?=$adv->RubricaName?></td>
            <td><?=$adv->Name?></td>
            <td type="edit" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
            <td type="delete" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#delete_member" class="splashy-contact_blue_remove"></i></td>
        </tr>
    <? }

    exit();
}
