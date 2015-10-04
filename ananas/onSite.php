<?php
include_once 'header.php';

if($loggedin == FALSE)
{
    header("Location: index.php?reason=Access+denied");
    exit();
}

$query = "SELECT a.ID as id, a.title, a.content, a.price, a.pricecurrency, a.created_at, a.status, a.statusChangeDate, a.idCategory,
a.idSubCategory, a.idSubSubCategory, a.hitcount, a.city, a.country, a.idUser, u.name, u.username, u.email, u.phone, u.status,
cat.ID as cat_id, cat.name as cat_name, subcat.ID as subcat_id, subcat.name as subcat_name, subsubcat.ID as subsubcat_id, subsubcat.name as subsubcat_name
FROM posts AS a LEFT JOIN users as u ON a.idUser = u.ID LEFT JOIN category as cat ON a.idCategory = cat.ID LEFT JOIN subcategory as subcat ON a.idSubCategory = subcat.ID LEFT JOIN subsubcategory as subsubcat ON a.idSubSubCategory = subsubcat.ID WHERE a.status=1 OR a.status=2 ORDER BY a.city";

$advQ = queryMysql($query);

$numOfAdvs = mysql_num_rows($advQ);

include ("head.php");
?>

    <!-- datatable -->
    <script src="gebo3/lib/datatables/jquery.dataTables.min.js"></script>
    <script src="gebo3/lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
    <!-- datatable functions -->
    <script src="gebo3/js/gebo_datatables.js"></script>

    <div id="contentwrapper">
        <div class="main_content">
            <div class="row-fluid">
                <h3 class="heading">Объявления</h3>
                <div class="heading_space"></div>
            </div>
            <div class="row-fluid" id="adv_res">
                <? if ($numOfAdvs > 0) { ?>
                    <table class="table table-striped" data-rowlink="a" id="dt_d">
                        <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                Category
                            </th>
                            <th>
                                Sub
                            </th>
                            <th>
                                SubSub
                            </th>
                            <th>
                                Заголовок
                            </th>
                            <th>
                                Дата
                            </th>
                            <th>
                                Имя пользователя
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Phone
                            </th>
                            <th>
                                Статус
                            </th>
                            <th>
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="adv_body">
                        <? while ($adv = mysql_fetch_object($advQ)) { ?>
                            <tr>
                                <td><?=$adv->id?></td>
                                <td><?=$adv->cat_name?></td>
                                <td><?=$adv->subcat_name?></td>
                                <td><?=$adv->subsubcat_name?></td>
                                <td><?=$adv->title?></td>
                                <td><?=$adv->created_at?></td>
                                <td><?=$adv->username?></td>
                                <td><?=$adv->email?></td>
                                <td><?=$adv->phone?></td>
                                <td type="select">
                                    <select name="adv_status_<?=$adv->id?>" id="adv_status_<?=$adv->id?>" class="textInput">
                                        <option value="0" <?if ($adv->status == 0) echo "selected='selected'";?>>На модерации</option>
                                        <option value="1" <?if ($adv->status == 1) echo "selected='selected'";?>>Активный</option>
                                        <option value="2" <?if ($adv->status == 2) echo "selected='selected'";?>>Срочно продают</option>
                                    </select>
                                </td>
                                <td type="nav" row_id="<?=$adv->id?>"><i class="splashy-fish prod_assign_btn"></i></td>
                                <td type="edit" row_id="<?=$adv->id?>"><i data-toggle="modal" href="#edit_member" class="splashy-contact_blue_edit"></i></td>
                            </tr>
                        <? } ?>
                        </tbody>
                    </table>
                <? } else { ?>
                    <div id="not_found_text">No records found</div>
                <? } ?>
            </div>
        </div>
    </div>
    <div class="modal hide fade in" id="edit_member" style="display: none;">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>Редактирование</h3>
        </div>
        <div class="modal-body" id="edit_body">
            <div class="row-fluid">
                <div id="edit_member_cont" class="span12">
                    <form id="edit_member_form" class="form-horizontal">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label">Категория</label>
                                <div class="controls">
                                    <select style="color: #231F20" id="category1" name="category1" class="textInput" onchange="showCategories(this, 1)">
                                        <option value=""></option>
                                        <option value="1">Мебель для дома</option>
                                        <option value="3">Мебель для офиса</option>
                                        <option value="5">Профильная для бизнеса</option>
                                        <option value="2">Всё для интерьера</option>
                                        <option value="4">Услуги</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Заголовок</label>
                                <div class="controls">
                                    <input id="title" name="title" type="text" value="" class="span8">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Цвет</label>
                                <div class="controls">
                                    <input id="color" name="color" type="text" value="" class="span8">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Материал</label>
                                <div class="controls">
                                    <input id="material" name="material" type="text" value="" class="span8">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Производство</label>
                                <div class="controls">
                                    <select id="manufacturer" name="manufacturer" class="span8">
                                        <option value=''></option>
                                        <option value='it'>Италия</option>
                                        <option value='ru'>Россия</option>
                                        <option value='by'>Беларусь</option>
                                        <option value='ua'>Украина</option>
                                        <option value='tr'>Турция</option>
                                        <option value='pl'>Польша</option>
                                        <option value='kg'>Кыргызстан</option>
                                        <option value='kz'>Казахстан</option>
                                        <option value='other'>Другое</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Размер</label>
                                <div class="controls">
                                    <input id="length" name="length" type="text" value="" class="span8">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Текст объявления</label>
                                <div class="controls">
                                    <textarea name="text" style="color: #231F20" id="text" cols="50" rows="3" class="span8"></textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Имя</label>
                                <div class="controls">
                                    <input id="name" name="name" type="text" value="" class="span8">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Email</label>
                                <div class="controls">
                                    <input id="email" name="email" type="text" value="" class="span8">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Номер телефона 1</label>
                                <div class="controls">
                                    <input id="phone1" name="phone1" type="text" value="" class="span8">
                                </div>
                            </div>
                            <div class="control-group formSep">
                                <label class="control-label">Номер телефона 2</label>
                                <div class="controls">
                                    <input id="phone2" name="phone2" type="text" value="" class="span8">
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
    <!-- enhanced select -->
    <link rel="stylesheet" href="gebo3/lib/chosen/chosen.css" />

    <script src="gebo3/lib/multiselect/js/jquery.multi-select.min.js"></script>
    <script src="gebo3/lib/chosen/chosen.jquery.min.js"></script>

    <script type="text/javascript">
        $(".chzn_b").chosen();

        gebo_datatbles.dt_d();

        function showCategories(select, level)
        {
            $(".addedRow").remove();

            var subParent = 0;

            if(level == 1)
            {
                $("#category3").remove();
                $("#category2").remove();
                subParent = 0;
            }
            else if(level == 2)
            {
                $("#category3").remove();
                subParent = $("#category1").val();
            }

            if($(select).val() != "")
            {
                var loading = $('<img src="../content/images/loading.gif" align="absmiddle" style="margin-left: 2em" />');
                $(select).after(loading);

                $.get(
                    '../content/ajax/categories.php',
                    {parentId: $(select).val(),
                        level: level,
                        subParent: subParent},
                    function(data)
                    {
                        loading.remove();
                        if(data.length > 0)
                        {
                            var selectNew = $('<select style="margin-left: 2em; color: #231F20" id="category' + (level + 1) + '" name="category' + (level + 1) + '" class="textInput" onchange="showCategories(this, ' + (level + 1) + ')"><option value=""></option></select>');

                            $(data).each(function(key, value)
                            {
                                selectNew.append('<option value="' + value.id + '"' + ('' == value.id || -1 == value.id ? ' selected="selected"' : '') + '>' + value.name + '</option>')
                            });

                            $(select).after(selectNew);

                            if($(selectNew).val() != '')
                            {
                                showCategories(selectNew, level + 1);
                            }
                        }
                    },
                    'json');
            }
        }

        $('#face_value, #fee_amount').bind('keydown', function(event) {
            // Allow: backspace, delete, tab, escape, and enter
            if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
                    // Allow: Ctrl+A
                (event.keyCode == 65 && event.ctrlKey === true) ||
                    // Allow: home, end, left, right
                (event.keyCode >= 35 && event.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            else {
                // Ensure that it is a number and stop the keypress
                if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                    if (!(event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 190))
                        event.preventDefault();
                }
            }
        });

        $('#seq, #lot_id, #every, #after').bind('keydown', function(event) {
            // Allow: backspace, delete, tab, escape, and enter
            if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
                    // Allow: Ctrl+A
                (event.keyCode == 65 && event.ctrlKey === true) ||
                    // Allow: home, end, left, right
                (event.keyCode >= 35 && event.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            else {
                // Ensure that it is a number and stop the keypress
                if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                    event.preventDefault();
                }
            }
        });

        $('#dt_d tbody tr td[type=edit]').live('click', function()
        {
            var value = $(this).closest('tr').children(':last').attr('row_id');

            $('#loader').css('display', 'block');

            $.ajax({
                type	: 'POST',
                cache	: false,
                url		: 'ajax/update_ajax.php',
                data	: {id : value},
                success : function(res) {
                    $('#edit_body').html(res);
                    $('#loader').css('display', 'none');
                },
                error : function() {
                    alert('Произошла внутренняя ошибка');
                    $('#loader').css('display', 'none');
                }
            });

            $('#save_member').live('click', function(e){
                e.preventDefault();

                $('.close').click();
                $('#loader').css('display', 'block');

                var cat1 = "";
                var cat2 = "";
                var cat3 = "";
                if($('#category1').val() != null && $('#category1').val() != "")
                    cat1 = $('#category1').val();
                if($('#category2').val() != null && $('#category2').val() != "")
                    cat2 = $('#category2').val();
                if($('#category3').val() != null && $('#category3').val() != "")
                    cat3 = $('#category3').val();

                var mode = "site";

                $.ajax({
                    type	: 'POST',
                    cache	: false,
                    url		: 'ajax/update_ajax.php',
                    data	: {member_id : value,
                        category1 : cat1,
                        category2 : cat2,
                        category3 : cat3,
                        title : $('#title').val(),
                        color : $('#color').val(),
                        material : $('#material').val(),
                        manufacturer : $('#manufacturer').val(),
                        length : $('#length').val(),
                        text : $('#text').val(),
                        name : $('#name').val(),
                        email : $('#email').val(),
                        phone1 : $('#phone1').val(),
                        phone2 : $('#phone2').val(),
                        mode:mode
                    },
                    success : function(res)
                    {
                        $('#adv_body').html(res);
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

        $('#dt_d tbody tr td[type=nav]').live('click', function()
        {
            var value = $(this).closest('tr').children(':last').attr('row_id');
            window.location = "../content/show.php?type=goods&id="+ value;
            return false;
        });


        $('#dt_d tbody tr td[type=select]').live('click', function()
        {
            var value = $(this).closest('tr').children(':last').attr('row_id');
            var status = $('#adv_status_' + value).val();

            $('#loader').css('display', 'block');
            $.ajax({
                type	: 'POST',
                cache	: false,
                url		: 'ajax/onSite_ajax.php',
                data	: {id : value, status : status},
                success : function(res) {
                    $('#adv_body').html(res);
                    $('#loader').css('display', 'none');
                },
                error : function() {
                    alert('Internal error while creating Maintenance Fee');
                    $('#loader').css('display', 'none');
                }
            });
        });

    </script>

<?php include ("footer.php"); ?>