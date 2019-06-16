<?php
    include 'controller-he-thong.php';
    $he_thong = new HeThong();
    $menu = $he_thong->kiem_tra_quyen_menu_trai();
    $arr_quyen = $_SESSION['phan_quyen'];
//    $nhom_phan_quyen_id = $_SESSION['nhom_nguoi_dung_id'];
//    echo "<pre>";
//    echo print_r($arr_quyen);
?>

<style>
    .menu-item {
        text-decoration: none;
        display: block;
        line-height: 2;
        color: black;
        background: #f5f5f5
    }

    .icon-menu {
        top: 0.5px;
        margin-right: 5px;
        font-size: 12px
    }
</style>

<aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
    <div class="main-navbar">
        <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
            <a class="navbar-brand w-100 mr-0" href="#" style="line-height: 25px;">
                <div class="d-table m-auto">
                    <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 25px;"
                         src="../images/shards-dashboards-logo.svg" alt="Shards Dashboard">
                    <span class="d-none d-md-inline ml-1">Trang Quản Lý</span>
                </div>
            </a>
            <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons">&#xE5C4;</i>
            </a>

        </nav>
    </div>
    <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
        <div class="input-group input-group-seamless ml-3">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <input class="navbar-search form-control" type="text" placeholder="Search for something..." aria-label="Search"></div>
    </form>
    <div class="nav-wrapper">
        <ul class="nav flex-column">
            <style>
                @font-face {
                    font-family: "Glyphicons Halflings";
                    src: url("glyphicons-halflings-regular.eot");
                    src: url("glyphicons-halflings-regular.eot?#iefix") format("embedded-opentype"), url("glyphicons-halflings-regular.woff2") format("woff2"), url("glyphicons-halflings-regular.woff") format("woff"), url("glyphicons-halflings-regular.ttf") format("truetype"), url("glyphicons-halflings-regular.svg#glyphicons_halflingsregular") format("svg");
                }

                .panel-heading {
                    cursor: pointer;
                    user-select: none;
                    text-indent: 3px;
                    transition: 0.3s ease;
                }

                .panel-heading:hover {
                    background: darkgrey;
                    color: white;
                    box-shadow: 0 0 30px 0 rgba(158, 163, 167, 0.5);
                }

                .panel-heading[aria-expanded=true] {
                    background: -webkit-linear-gradient(left, #66a7cf 5%, #66bcca);
                    color: white;
                    border-left: 6px solid skyblue;
                    border-bottom-left-radius: initial;
                    border-top-left-radius: initial;
                    text-indent: 6px;
                }

                .panel-heading[aria-expanded=false] {
                    color: black;
                }

                .cus-collapse-item {
                    display: block;
                    text-indent: 6px;
                    color: black;
                    text-decoration: none !important;
                    position: relative;
                    transition: 0.2s ease;
                }

                .cus-collapse-item:hover {
                    color: white;
                    background-color: skyblue;
                }

                .cus-collapse-item.cus-active:hover {
                    color: white;
                }

                .cus-collapse-item.cus-active:hover:after {
                    color: skyblue;
                }

                .cus-collapse-item.cus-active {
                    text-indent: 12px;
                    font-weight: 500;
                    color: skyblue;
                    z-index: 10;
                    border-bottom: 1px solid skyblue !important;
                    border-top: 1px solid skyblue;
                    border-left: 1px solid skyblue;
                    border-right: 1px solid skyblue;
                }

                .cus-collapse-item.cus-active:after {
                    content: '\e092';
                    position: absolute;
                    top: 2px;
                    right: 13%;
                    font-family: "Glyphicons Halflings";
                    line-height: 2;
                    font-size: 15pt;
                    -webkit-font-smoothing: antialiased;
                    -webkit-text-stroke: 3px white;
                }

                .parent-active {
                    position: relative;
                }

                .parent-active.parent-selected:after {
                    content: '\e092';
                    position: absolute;
                    top: 6px;
                    right: 15%;
                    font-family: "Glyphicons Halflings";
                    line-height: 2;
                    font-size: 15pt;
                    color: white;
                    -webkit-font-smoothing: antialiased;
                    -webkit-text-stroke: 3px #66baca;
                }
            </style>

            <div class="panel-group" id="accordion">
                <?php foreach ($menu as $key => $item):?>
                    <?php
                        $dem = 0;
                        if($item->nhom_con){
                            foreach ($item->nhom_con as $value) {
                                $idx = 0;

                                foreach ($arr_quyen as $k => $q) {
                                    if($q->id_chuc_nang == $value->id) {
                                       $idx = $k;
                                    }
                                }

                                if($idx > 0) {
                                    $quyen = $arr_quyen[$idx];
                                    if($quyen->allaction == 1){
                                        $dem = 1;
                                    }
                                    else {
                                        if($quyen->xem  == 1) $dem = 1;
                                        if($quyen->them == 1) $dem = 1;
                                        if($quyen->sua  == 1) $dem = 1;
                                        if($quyen->xoa  == 1) $dem = 1;
                                    }
                                }
                            }
                        }
                        else{
                            $idx = -1;

                            foreach ($arr_quyen as $k => $q) {
                                if($q->id_chuc_nang == $item->id) {
                                    $idx = $k;
                                }
                            }
                            if($idx >= 0) {
                                $quyen = $arr_quyen[$idx];
                                if($quyen->allaction == 1){
                                    $dem = 1;
                                }
                                else {
                                    if($quyen->xem  == 1) $dem = 1;
                                    if($quyen->them == 1) $dem = 1;
                                    if($quyen->sua  == 1) $dem = 1;
                                    if($quyen->xoa  == 1) $dem = 1;
                                }
                            }
                        }
                     ?>

                    <?php if($dem > 0):?>
                    <div class="panel panel-default" id="heading<?php echo $key+1;?>">
                        <a href="<?php if($item->link) echo $item->link; else echo '#';?>" class="parent-active menu-item">
                            <div class="panel-heading" data-toggle="collapse" data-target="#collapse<?php echo $key+1;?>"
                                 aria-controls="collapse<?php echo $key+1;?>">
                                <div class="panel-title">
                                    <span class="<?php echo $item->icon;?>" style="margin-right: 6px; top: 2px"></span>
                                    <?php echo $item->ten_nhom;?>
                                </div>
                            </div>
                        </a>
                        <div id="collapse<?php echo $key+1;?>" class="panel-collapse collapse" aria-labelledby="heading<?php echo $key+1;?>" data-parent="#accordion">
                            <ul class="list-group">

                                <?php if($item->nhom_con):?>
                                    <?php foreach ($item->nhom_con as $idx => $value):?>

                                        <?php
                                            $count = 0;
                                            $idx = -1;

                                            foreach ($arr_quyen as $k => $q) {
                                                if($q->id_chuc_nang == $value->id) {
                                                    $idx = $k;
                                                }
                                            }

                                            if($idx >= 0) {
                                                $quyen = $arr_quyen[$idx];
                                                if($quyen->allaction == 1){
                                                    $count = 1;
                                                }
                                                else {
                                                    if($quyen->xem  == 1) $count = 1;
                                                    if($quyen->them == 1) $count = 1;
                                                    if($quyen->sua  == 1) $count = 1;
                                                    if($quyen->xoa  == 1) $count = 1;
                                                }
                                            }
                                        ?>

                                        <?php if($count > 0):?>
                                            <a class="list-group-item cus-collapse-item" href="<?php echo $value->link;?>">
                                                <span class="glyphicon glyphicon-menu-right icon-menu"></span> <?php echo $value->ten_nhom;?>
                                            </a>
                                        <?php endif;?>

                                    <?php endforeach;?>
                                <?php endif;?>
                            </ul>
                        </div>
                    </div>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        </ul>
    </div>
</aside>