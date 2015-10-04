<?php
include_once 'header.php';

if($loggedin == FALSE)
{
    header("Location: index.php?reason=Access+denied");
    exit();
}

$queryR = "SELECT * FROM rubrica";
$advQR = queryMysql($queryR);

$query = "SELECT a.ID as id, a.Name, a.IdCategory, c.Name as CategoryName, c.IdRubrica, u.Name as RubricaName FROM subcategory AS a LEFT JOIN category as c ON a.IdCategory = c.ID LEFT JOIN rubrica as u ON c.IdRubrica = u.ID";

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
                <a data-toggle="modal" style="float:right;" href="#add_subcat" class="btn btn-inverse">Добавить подкатегорию</a>
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
                                Category
                            </th>
                            <th>
                                SubCategory
                            </th>
                            <th>
                                SubSubCategory
                            </th>
                            <th>
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="adv_body3">
                        <? while ($adv = mysql_fetch_object($advQ)) { ?>
                            <tr>
                                <td><?=$adv->id?></td>
                                <td><?=$adv->RubricaName?></td>
                                <td><?=$adv->CategoryName?></td>
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
    <div class="modal hide fade in" id="add_subcat" style="display: none;">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>Добавление</h3>
        </div>
        <div class="modal-body" id="add_body3">
            <div class="row-fluid">
                <div id="add_subcat_cont" class="span14">
                    <form id="add_subcat_form" class="form-horizontal">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label">Рубрика</label>
                                <div class="controls">
                                    <select id="rubrica3" name="rubrica3">
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
                                    <select id="category2" name="category2">
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
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" id="add_subcateg" class="btn btn-inverse">Добавить</a>
            <button type="button" data-dismiss="modal" class="btn">Отмена</button>
        </div>
    </div>
    <div class="modal hide fade in" id="edit_member" style="display: none;">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>Редактирование</h3>
        </div>
        <div class="modal-body" id="edit_body3">
            <div class="row-fluid">
                <div id="edit_member_cont" class="span14">
                    <form id="edit_member_form" class="form-horizontal">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label">Рубрика</label>
                                <div class="controls">
                                    <input id="rubricaName3" name="rubricaName3" type="text" value="" class="span12">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Категория</label>
                                <div class="controls">
                                    <input id="catName3" name="catName3" type="text" value="" class="span12">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Подкатегория</label>
                                <div class="controls">
                                    <input id="subcatName3" name="subcatName3" type="text" value="" class="span12">
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

        //oncahnge
        $('#rubrica3').change(function()
        {
            if($('#rubrica3').val() != "")
            {
                $.ajax({
                    type	: 'POST',
                    cache	: false,
                    url		: 'ajax/subcategory_ajax.php',
                    data	: {select_id : $('#rubrica3').val()},
                    success : function(res) {
                        $('#add_body3').html(res);
                        $('#loader').css('display', 'none');
                    },
                    error : function() {
                        alert('Произошла внутренняя ошибка');
                        $('#loader').css('display', 'none');
                    }
                });
            }
        })

        $('#rubrica3').change();

        $('#dt_d tbody tr td[type=edit]').live('click', function()
        {
            var value = $(this).closest('tr').children(':last').attr('row_id');

            $('#loader').css('display', 'block');

            $.ajax({
                type	: 'POST',
                cache	: false,
                url		: 'ajax/subcategory_ajax.php',
                data	: {id : value},
                success : function(res) {
                    $('#edit_body3').html(res);
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

                if ($('#subcatName3').val().trim() == "")
                {
                    checker = false;
                    $('#subcatName3').parent().addClass('f_error');
                }

                if(checker)
                {
                    $('.close').click();
                    $('#loader').css('display', 'block');

                    $.ajax({
                        type	: 'POST',
                        cache	: false,
                        url		: 'ajax/subcategory_ajax.php',
                        data	: {subcat_id : value,
                            subcatName : $('#subcatName3').val()
                        },
                        success : function(res)
                        {
                            $('#adv_body3').html(res);
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

        $('#add_subcateg').live('click', function(e){
            e.preventDefault();
            var checker = true;
            if ($('#rubrica3').val().replace(/\s*/g,'') == "") {
                checker = false;
                $('#rubrica3').parent().addClass('f_error');
            }
            if ($('#category2').val().replace(/\s*/g,'') == "") {
                checker = false;
                $('#category2').parent().addClass('f_error');
            }
            if ($('#subcatName').val().replace(/\s*/g,'') == "") {
                checker = false;
                $('#subcatName').parent().addClass('f_error');
            }
            if(checker)
            {
                $('.close').click();
                $('#loader').css('display', 'block');

                $.ajax({
                    type	: 'POST',
                    cache	: false,
                    url		: 'ajax/subcategory_ajax.php',
                    data	: { rubrica : $('#rubrica3').val(),
                              category : $('#category2').val(),
                                subcatName : $('#subcatName').val(),
                                subcatName2 : $('#subcatName2').val(),
                                subcatName3 : $('#subcatName3').val(),
                                subcatName4 : $('#subcatName4').val(),
                                subcatName5 : $('#subcatName5').val(),
                                subcatName6 : $('#subcatName6').val(),
                                subcatName7 : $('#subcatName7').val()
                    },
                    success : function(res)
                    {
                        $('#adv_body3').html(res);
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
                    url		: 'ajax/subcategory_ajax.php',
                    data	: {delete_id : value},
                    success : function(res)
                    {
                        $('#adv_body3').html(res);
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