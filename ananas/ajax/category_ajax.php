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

    $query = "SELECT * FROM rubrica WHERE ID=$id";

    $member = mysql_fetch_object(queryMysql($query));

    ?>
    <div class="row-fluid">
        <div id="edit_member_cont" class="span14">
            <form id="edit_member_form" class="form-horizontal">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">Название</label>
                        <div class="controls">
                            <input id="rubricaName2" name="rubricaName2" type="text" value="<?=$member->Name?>" class="span12">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <?
    exit();
}

if(isset($_POST['rubrica_id']))
{
    $id = sanitizeString($_POST['rubrica_id']);
    $rubName = sanitizeString($_POST['rubName']);

    queryMysql("UPDATE rubrica SET Name='$rubName' WHERE ID = $id");

    $query = "SELECT * FROM rubrica";
    $advQ = queryMysql($query);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><?=$adv->ID?></td>
            <td><?=$adv->Name?></td>
            <td type="edit" row_id="<?=$adv->ID?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
            <td type="delete" row_id="<?=$adv->ID?>"><i data-toggle="modal" href="#delete_member" class="splashy-contact_blue_remove"></i></td>
        </tr>
    <? }

    exit();
}

if(isset($_POST['rubricaName']))
{

    $rubricaName = sanitizeString($_POST['rubricaName']);

    queryMysql("INSERT INTO rubrica VALUES (NULL,'$rubricaName')");

    $query = "SELECT * FROM rubrica";
    $advQ = queryMysql($query);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><?=$adv->ID?></td>
            <td><?=$adv->Name?></td>
            <td type="edit" row_id="<?=$adv->ID?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
            <td type="delete" row_id="<?=$adv->ID?>"><i data-toggle="modal" href="#delete_member" class="splashy-contact_blue_remove"></i></td>
        </tr>
    <? }

    exit();
}

if(isset($_POST['delete_id']))
{
    $id = sanitizeString($_POST['delete_id']);
    queryMysql("DELETE FROM rubrica WHERE ID = $id");

    $query = "SELECT * FROM rubrica";
    $advQ = queryMysql($query);

    while ($adv = mysql_fetch_object($advQ)) { ?>
        <tr>
            <td><?=$adv->ID?></td>
            <td><?=$adv->Name?></td>
            <td type="edit" row_id="<?=$adv->ID?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
            <td type="delete" row_id="<?=$adv->ID?>"><i data-toggle="modal" href="#delete_member" class="splashy-contact_blue_remove"></i></td>
        </tr>
    <? }

    exit();
}