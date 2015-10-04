<?
/*
$queryAds = "SELECT * FROM advertisements WHERE status=1 OR status=2";
$queryNews = "SELECT * FROM news WHERE status=1 OR status=2";
$queryPrivates = "SELECT * FROM users WHERE user_status=0 OR user_status=1";
$querySalons = "SELECT * FROM users WHERE user_status=2 AND user_salonstatus=0";

$numOfAdvs = mysql_num_rows(queryMysql($queryAds));
$numOfNews = mysql_num_rows(queryMysql($queryNews));
$numOfPrivates = mysql_num_rows(queryMysql($queryPrivates));
$numOfSalons = mysql_num_rows(queryMysql($querySalons));
*/
$numOfAdvs = $numOfNews = $numOfPrivates = $numOfSalons = 0;
?>
</div>
<!-- sidebar -->
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
<div class="sidebar">
    <div class="antiScroll">
        <div class="antiscroll-inner">
            <div class="antiscroll-content">
                <div class="sidebar_inner">
                    <form action="customService.php" method="POST" id="form_search" class="input-append" >
                        <div class="control-group">
                            <label class="control-label">Search by</label>
                            <div class="controls">
                                <select id="stype" name="stype" style="width: 195px;">
                                    <option val="ID">Ads ID</option>
                                    <option val="Name">Ads Name</option>
                                    <option val="Code">Ads Code</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="search_field_labels" class="control-label">LOT</label>
                            <div class="controls">
                                <input autocomplete="off" name="sf1" id="search_field_1" class="search_query input-medium" size="16" type="text" placeholder="Search..." /><button id="search_field_btn" type="submit" class="btn"><i class="icon-search"></i></button>
                            </div>
                        </div>

                        <div class="control-group search_field_labels">
                            <label class="search_field_labels" class="control-label">Sequence</label>
                            <div class="controls">
                                <input autocomplete="off" name="sf2" id="search_field_2" class="search_query input-medium" size="16" type="text" placeholder="Search..." /><button type="submit" class="btn"><i class="icon-search"></i></button>
                            </div>
                        </div>
                    </form>

                    <div id="side_accordion" class="accordion">
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapse_adv" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                    <img src="gebo3/img/gCons/addressbook.png" style="margin-top:-5px;" width="20px" height="20px" alt="" /> Ads
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapse_adv">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li><a id="onSite" href="javascript:void(0)">Ads</a></li>
                                    </ul>
                                    <ul class="nav nav-list">
                                        <li><a id="onModeration" href="javascript:void(0)">On moderation</a></li>
                                    </ul>
                                    <ul class="nav nav-list">
                                        <li><a id="hotAds" href="javascript:void(0)">Hot ads</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapse_cats" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                    <img src="gebo3/img/gCons/tree.png" style="margin-top:-5px;" width="20px" height="20px" alt="" /> Categories
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapse_cats">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li><a id="rubrica" href="javascript:void(0)">Categories</a></li>
                                    </ul>
                                    <ul class="nav nav-list">
                                        <li><a id="category" href="javascript:void(0)">Subcategories</a></li>
                                    </ul>
                                    <ul class="nav nav-list">
                                        <li><a id="subcategory" href="javascript:void(0)">SubSubcategories</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapse_types" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                    <img src="gebo3/img/gCons/leaves.png" style="margin-top:-5px;" width="20px" height="20px" alt="" /> Types
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapse_types">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li><a id="types" href="javascript:void(0)">Types</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseCalc" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                    <img src="gebo3/img/gCons/calculator.png" style="margin-top:-5px;" width="20px" height="20px" alt="" /> Calculator
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapseCalc">
                                <div class="accordion-inner">
                                    <form name="Calc" id="calc">
                                        <div class="formSep control-group input-append">
                                            <input type="text" style="width:142px" name="Input" /><button type="button" class="btn" style="height:28px;" name="clear" value="c" onclick="Calc.Input.value = ''"><i class="icon-remove"></i></button>
                                        </div>
                                        <div class="control-group">
                                            <input type="button" class="btn btn-large" name="seven" value="7" onclick="Calc.Input.value += '7'" />
                                            <input type="button" class="btn btn-large" name="eight" value="8" onclick="Calc.Input.value += '8'" />
                                            <input type="button" class="btn btn-large" name="nine" value="9" onclick="Calc.Input.value += '9'" />
                                            <input type="button" class="btn btn-large" name="div" value="/" onclick="Calc.Input.value += ' / '">
                                        </div>
                                        <div class="control-group">
                                            <input type="button" class="btn btn-large" name="four" value="4" onclick="Calc.Input.value += '4'" />
                                            <input type="button" class="btn btn-large" name="five" value="5" onclick="Calc.Input.value += '5'" />
                                            <input type="button" class="btn btn-large" name="six" value="6" onclick="Calc.Input.value += '6'" />
                                            <input type="button" class="btn btn-large" name="times" value="x" onclick="Calc.Input.value += ' * '" />
                                        </div>
                                        <div class="control-group">
                                            <input type="button" class="btn btn-large" name="one" value="1" onclick="Calc.Input.value += '1'" />
                                            <input type="button" class="btn btn-large" name="two" value="2" onclick="Calc.Input.value += '2'" />
                                            <input type="button" class="btn btn-large" name="three" value="3" onclick="Calc.Input.value += '3'" />
                                            <input type="button" class="btn btn-large" name="minus" value="-" onclick="Calc.Input.value += ' - '" />
                                        </div>
                                        <div class="formSep control-group">
                                            <input type="button" class="btn btn-large" name="dot" value="." onclick="Calc.Input.value += '.'" />
                                            <input type="button" class="btn btn-large" name="zero" value="0" onclick="Calc.Input.value += '0'" />
                                            <input type="button" class="btn btn-large" name="DoIt" value="=" onclick="Calc.Input.value = Math.round( eval(Calc.Input.value) * 1000)/1000" />
                                            <input type="button" class="btn btn-large" name="plus" value="+" onclick="Calc.Input.value += ' + '" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="push"></div>
                </div>
                <div class="sidebar_info">
                    <ul class="unstyled">
                        <li>
                            <span class="act act-warning"><?=$numOfAdvs?></span>
                            <strong>Объявления</strong>
                        </li>
                        <li>
                            <span class="act act-success"><?=$numOfNews?></span>
                            <strong>Новости</strong>
                        </li>
                        <li>
                            <span class="act act-danger"><?=$numOfSalons?></span>
                            <strong>Салоны</strong>
                        </li>
                        <li>
                            <span class="act act-danger"><?=$numOfPrivates?></span>
                            <strong>Частники</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="gebo3/js/jquery.min.js"></script>
<script src="gebo3/js/jquery-migrate.min.js"></script>
<script src="gebo3/lib/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
<!-- touch events for jquery ui-->
<script src="gebo3/js/forms/jquery.ui.touch-punch.min.js"></script>
<!-- easing plugin -->
<script src="gebo3/js/jquery.easing.1.3.min.js"></script>
<!-- smart resize event -->
<script src="gebo3/js/jquery.debouncedresize.min.js"></script>
<!-- js cookie plugin -->
<script src="gebo3/js/jquery_cookie_min.js"></script>
<!-- main bootstrap js -->
<script src="gebo3/bootstrap/js/bootstrap.min.js"></script>
<!-- bootstrap plugins -->
<script src="gebo3/js/bootstrap.plugins.min.js"></script>
<!-- typeahead -->
<script src="gebo3/lib/typeahead/typeahead.min.js"></script>
<!-- code prettifier -->
<script src="gebo3/lib/google-code-prettify/prettify.min.js"></script>
<!-- sticky messages -->
<script src="gebo3/lib/sticky/sticky.min.js"></script>
<!-- lightbox -->
<script src="gebo3/lib/colorbox/jquery.colorbox.min.js"></script>
<!-- jBreadcrumbs -->
<script src="gebo3/lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
<!-- hidden elements width/height -->
<script src="gebo3/js/jquery.actual.min.js"></script>
<!-- custom scrollbar -->
<script src="gebo3/lib/slimScroll/jquery.slimscroll.js"></script>
<!-- fix for ios orientation change -->
<script src="gebo3/js/ios-orientationchange-fix.js"></script>
<!-- to top -->
<script src="gebo3/lib/UItoTop/jquery.ui.totop.min.js"></script>
<!-- mobile nav -->
<script src="gebo3/js/selectNav.js"></script>
<!-- moment.js date library -->
<script src="gebo3/lib/moment/moment.min.js"></script>

<!-- common functions -->
<script src="gebo3/js/pages/gebo_common.js"></script>

<!-- multi-column layout -->
<script src="gebo3/js/jquery.imagesloaded.min.js"></script>
<script src="gebo3/js/jquery.wookmark.js"></script>
<!-- responsive table -->
<script src="gebo3/js/jquery.mediaTable.min.js"></script>
<!-- small charts -->
<script src="gebo3/js/jquery.peity.min.js"></script>
<!-- charts -->
<script src="gebo3/lib/flot/jquery.flot.min.js"></script>
<script src="gebo3/lib/flot/jquery.flot.resize.min.js"></script>
<script src="gebo3/lib/flot/jquery.flot.pie.min.js"></script>
<script src="gebo3/lib/flot.tooltip/jquery.flot.tooltip.min.js"></script>
<!-- calendar -->
<script src="gebo3/lib/fullcalendar/fullcalendar.min.js"></script>
<!-- sortable/filterable list -->
<script src="gebo3/lib/list_js/list.min.js"></script>
<script src="gebo3/lib/list_js/plugins/paging/list.paging.min.js"></script>
<!-- dashboard functions -->
<script src="gebo3/js/pages/gebo_dashboard.js"></script>


<script>
    $(document).ready(function() {
        //* jQuery.browser.mobile (http://detectmobilebrowser.com/)
        //* jQuery.browser.mobile will be true if the browser is a mobile device
        (function(a){jQuery.browser.mobile=/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);
        //replace themeforest iframe
        if(jQuery.browser.mobile) {
            if (top !== self) top.location.href = self.location.href;
        }
    });
</script>
</body>
</html>