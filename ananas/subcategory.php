<?php
include_once 'header.php';

if($loggedin == FALSE)
{
    header("Location: index.php?reason=Access+denied");
    exit();
}

$queryR = "SELECT * FROM rubrica";
$advQR = queryMysql($queryR);

$query = "SELECT a.ID as id, a.Name, a.IdRubrica, u.Name as RubricaName FROM category AS a LEFT JOIN rubrica as u ON a.IdRubrica = u.ID ORDER BY a.IdRubrica";

$advQ = queryMysql($query);
$numOfAdvs = mysql_num_rows($advQ);

include ("head.php");
?>
    <!-- enhanced select -->
    <link rel="stylesheet" href="gebo3/lib/chosen/chosen.css" />
    <script src="gebo3/lib/chosen/chosen.jquery.min.js"></script>
    <!-- datatable -->
    <script src="gebo3/lib/datatables/jquery.dataTables.min.js"></script>
    <script src="gebo3/lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
    <!-- datatable functions -->
    <script src="gebo3/js/gebo_datatables.js"></script>

    <div id="contentwrapper">
        <div class="main_content">
            <div class="row-fluid">
                <h3 class="heading">Подкатегории</h3>
                <div class="heading_space"></div>
                <a data-toggle="modal" style="float:right;" href="#add_cat" class="btn btn-inverse">Добавить категорию</a>
            </div>
            <div class="row-fluid" id="adv_res">
                <? if ($numOfAdvs > 0) { ?>
                    <table class="table table-striped" data-rowlink="a" id="dt_d">
                        <thead>
                        <tr>
                            <th>
                                Id
                            </th>
                            <th>
                                Рубрика
                            </th>
                            <th>
                                Категория
                            </th>
                            <th>
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="adv_body2">
                        <? while ($adv = mysql_fetch_object($advQ)) { ?>
                            <tr>
                                <td><?=$adv->id?></td>
                                <td><?=$adv->RubricaName?></td>
                                <td><?=$adv->Name?></td>
                                <td type="edit" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
                                <td type="delete" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#delete_member" class="splashy-contact_blue_remove"></i></td>
                            </tr>
                        <? } ?>
                        </tbody>
                    </table>
                <? } else { ?>
                    <div id="not_found_text">Нет записей</div>
                <? } ?>
            </div>
        </div>
    </div>
    <div class="modal hide fade in" id="add_cat" style="display: none;">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>Добавление</h3>
        </div>
        <div class="modal-body" id="add_body2">
            <div class="row-fluid">
                <div id="add_cat_cont" class="span14">
                    <form id="add_cat_form" class="form-horizontal">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label">Рубрика</label>
                                <div class="controls">
                                    <select id="rubrica2" name="rubrica2">
                                        <option selected value=""></option>
                                        <? while($rub = mysql_fetch_object($advQR)) { ?>
                                        <option value="<?=$rub->ID?>"><?=$rub->Name?></option>
                                        <?}?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Категория</label>
                                <div class="controls">
                                    <input id="catName" name="catName" type="text" value="" class="span10">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" id="add_categ" class="btn btn-inverse">Добавить</a>
            <button type="button" data-dismiss="modal" class="btn">Отмена</button>
        </div>
    </div>
    <div class="modal hide fade in" id="edit_member" style="display: none;">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>Редактирование</h3>
        </div>
        <div class="modal-body" id="edit_body2">
            <div class="row-fluid">
                <div id="edit_member_cont" class="span14">
                    <form id="edit_member_form" class="form-horizontal">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label">Рубрика</label>
                                <div class="controls">
                                    <input id="rubricaName" name="rubricaName" type="text" value="" class="span12">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Категория</label>
                                <div class="controls">
                                    <input id="catName2" name="catName2" type="text" value="" class="span12">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" id="save_member" class="btn btn-inverse">Сохранить</a>
            <button type="button" data-dismiss="modal" class="btn">Отмена</button>
        </div>
    </div>
    <div class="modal hide fade in" id="delete_member" style="display: none; width: 400px">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>Удаление</h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
                <div id="delete_member_cont" class="span10">
                    <p>Вы действительно хотите удалить эту рубрику?</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" id="remove_member" class="btn btn-inverse">Удалить</a>
            <button type="button" data-dismiss="modal" class="btn">Отмена</button>
        </div>
    </div>
    <!-- enhanced select -->
    <link rel="stylesheet" href="gebo3/lib/chosen/chosen.css" />
    <script src="gebo3/lib/multiselect/js/jquery.multi-select.min.js"></script>
    <script src="gebo3/lib/chosen/chosen.jquery.min.js"></script>

    <script type="text/javascript">

        gebo_datatbles.dt_d();

        $(".chzn_a").chosen({
            allow_single_deselect: true
        });

        $('#dt_d tbody tr td[type=edit]').live('click', function()
        {
            var value = $(this).closest('tr').children(':last').attr('row_id');

            $('#loader').css('display', 'block');

            $.ajax({
                type	: 'POST',
                cache	: false,
                url		: 'ajax/category_ajax.php',
                data	: {id : value},
                success : function(res) {
                    $('#edit_body2').html(res);
                    $('#loader').css('display', 'none');
                },
                error : function() {
                    alert('Произошла внутренняя ошибка');
                    $('#loader').css('display', 'none');
                }
            });

            $('#save_member').live('click', function(e){
                e.preventDefault();
                var checker = true;

                if ($('#catName2').val().trim() == "")
                {
                    checker = false;
                    $('#catName2').parent().addClass('f_error');
                }

                if(checker)
                {
                    $('.close').click();
                    $('#loader').css('display', 'block');

                    $.ajax({
                        type	: 'POST',
                        cache	: false,
                        url		: 'ajax/category_ajax.php',
                        data	: {cat_id : value,
                                   catName : $('#catName2').val()
                        },
                        success : function(res)
                        {
                            $('#adv_body2').html(res);
                            $('#loader').css('display', 'none');
                        },
                        error : function()
                        {
                            alert('Произошла ошибка');
                            $('#loader').css('display', 'none');
                        }
                    });
                }

                return false;
            });

        });

        $('#add_categ').live('click', function(e){
            e.preventDefault();
            var checker = true;
            if ($('#catName').val().replace(/\s*/g,'') == "") {
                checker = false;
                $('#catName').parent().addClass('f_error');
            }
            if ($('#rubrica2').val().replace(/\s*/g,'') == "") {
                checker = false;
                $('#rubrica2').parent().addClass('f_error');
            }
            if(checker)
            {
                $('.close').click();
                $('#loader').css('display', 'block');

                $.ajax({
                    type	: 'POST',
                    cache	: false,
                    url		: 'ajax/category_ajax.php',
                    data	: { rubrica : $('#rubrica2').val(),
                                catName : $('#catName').val()},
                    success : function(res)
                    {
                        $('#adv_body2').html(res);
                        $('#loader').css('display', 'none');
                    },
                    error : function()
                    {
                        alert('Произошла ошибка');
                        $('#loader').css('display', 'none');
                    }
                });
            }

            return false;
        });

        $('#dt_d tbody tr td[type=delete]').live('click', function()
        {
            var value = $(this).closest('tr').children(':last').attr('row_id');

            $('#remove_member').live('click', function()
            {
                $('.close').click();
                $('#loader').css('display', 'block');

                $.ajax({
                    type	: 'POST',
                    cache	: false,
                    url		: 'ajax/category_ajax.php',
                    data	: {delete_id : value},
                    success : function(res)
                    {
                        $('#adv_body2').html(res);
                        $('#loader').css('display', 'none');
                    },
                    error : function()
                    {
                        alert('Произошла ошибка');
                        $('#loader').css('display', 'none');
                    }
                });

                return false;
            });
        });

    </script>

<?php include ("footer.php"); ?>