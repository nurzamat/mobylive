<?php
include_once 'header.php';

if($loggedin == FALSE)
{
    header("Location: index.php?reason=Access+denied");
    exit();
}

$query = "SELECT a.ID as id, a.title, a.catalog, a.cat, a.subcat, a.text, a.name, a.city, a.email, a.phone1, a.phone2, a.style,
a.carcase, a.facade, a.tabletop, a.priceIs, a.price, a.pricecurrency, a.pricefor, a.color, a.material, a.manufacturer,
a.length, a.height, a.width, a.shipment, a.feature, a.createddate, a.status, a.statusChangeDate, a.type, a.upholstery,
a.mechanism, a.foundation, a.enablecomments, a.idUser, u.user_login, u.user_pass, u.user_nicename, u.user_email, u.user_phone,
u.user_registered, u.user_city, u.user_status, u.user_salonname, u.user_salonstatus FROM advertisements AS a LEFT JOIN users as u ON a.idUser = u.ID WHERE a.status=2 ORDER BY a.city";

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
                <h3 class="heading">Объявления (срочно продают)</h3>
                <div class="heading_space"></div>
            </div>
            <div class="row-fluid" id="adv_res">
                <? if ($numOfAdvs > 0) { ?>
                    <table class="table table-striped" data-rowlink="a" id="dt_d">
                        <thead>
                        <tr>
                            <th>
                                Категория
                            </th>
                            <th>
                                Подкатегория
                            </th>
                            <th>
                                Заголовок
                            </th>
                            <th>
                                Дата
                            </th>
                            <th>
                                Автор
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
                        </tr>
                        </thead>
                        <tbody id="adv_body">
                        <? while ($adv = mysql_fetch_object($advQ)) { ?>
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
                        <? } ?>
                        </tbody>
                    </table>
                <? } else { ?>
                    <div id="not_found_text">No records found</div>
                <? } ?>
            </div>
        </div>
    </div>
    <!-- enhanced select -->
    <link rel="stylesheet" href="gebo3/lib/chosen/chosen.css" />

    <script src="gebo3/lib/multiselect/js/jquery.multi-select.min.js"></script>
    <script src="gebo3/lib/chosen/chosen.jquery.min.js"></script>

    <script type="text/javascript">

        gebo_datatbles.dt_d();

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
                url		: 'ajax/hotAds_ajax.php',
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