<?php
require ('checklogin.php');
require ('datefunc.php');
require ('databasecon.php');
$thisyear=substr($date,0,4);
$year1=intval($thisyear-1);
$year2=intval($thisyear-2);
$year3=intval($thisyear-3);
$thismenu='c';
$err=0;
$showdiv=0;
$mahsool='';
if(isset($_POST['regbut'])){
            if(strlen(trim($_POST['fcrop']))==0){
                $err=1;
                $errmgs='لطفا محصول را انتخاب نمایید!';
    }
    if($err==0) {
        $showdiv=1;
        $mahsool=$_POST['fcrop'];
        $ttt="select sum(croparea) as c23 from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and (year='".$thisyear."')";
        $sql =$mysqli->query($ttt);
        $c23=0;
        while ($row =mysqli_fetch_array($sql)) {
            $c23 = $row['c23'];
        }
        $ttt="select avg(croparea) as d23 from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and ((year='".$year1."') or (year='".$year2."') or (year='".$year3."')) group BY usermobimenumber";
        $sql =$mysqli->query($ttt);
        $d23=0;
        while ($row =mysqli_fetch_array($sql)) {
            $d23 = $d23+$row['d23'];
        }
        if($d23==0){$e12='تعریف نشده';}else{$e12=round(((($c23-$d23)/$d23)*100),2);}
        if($e12>0){$e12color='green';}else if($e12<0){$e12color='red';} else {$e12color='orange';}

        $ttt="select avg(amount/croparea) as g23 from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and ((year='".$year1."') or (year='".$year2."') or (year='".$year3."')) group BY usermobimenumber";
        $sql =$mysqli->query($ttt);
        $g23=0;
        while ($row =mysqli_fetch_array($sql)) {
            $g23 = $g23+$row['g23'];
        }
        $ttt="select avg(amount/croparea) as myfield from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (usermobimenumber='".$_SESSION['lotususerid']."') and ((year='".$year1."') or (year='".$year2."') or (year='".$year3."')) group BY usermobimenumber";
        $sql =$mysqli->query($ttt);
        $myfield=0;
        while ($row =mysqli_fetch_array($sql)) {
            $myfield = $myfield+$row['myfield'];
        }
        $ttt23="select avg(totalamalkard) as h23 from tolid where (product='".$_POST['fcrop']."') and (shahr='".$_SESSION['city']."') and (ostan2='".$_SESSION['state']."') and ((toyear='".$year1."') or (toyear='".$year2."') or (toyear='".$year3."'))";
        $sql =$mysqli->query($ttt23);
        $h23=0;
        while ($row =mysqli_fetch_array($sql)) {
            $h23 = $h23+$row['h23'];
        }
        if($h23==0){$i12='تعریف نشده';}else{$i12=round((($e12*$g23)/($h23)),3);}
        if($i12>0){$i12color='green';}else if($i12<0){$i12color='red';} else {$i12color='orange';}
        $ttt="select * from crops where (name='".$_POST['fcrop']."')";
        $sql =$mysqli->query($ttt);
        $k13=0;
        while ($row =mysqli_fetch_array($sql)) {
            $k13 = $row['keshesh'];
        }
        $ttt="select * from tavarom where (year='".$year1."')";
        $sql =$mysqli->query($ttt);
        $l13=0;
        while ($row =mysqli_fetch_array($sql)) {
            $l13 = $row['mizan'];
        }
        $m12=($k13*($i12/100))+$l13;
        $m12=round($m12,2);
        if($m12>0){$m12color='green';}else if($m12<0){$m12color='red';} else {$m12color='orange';}
        $ttt="select count(distinct usermobimenumber) as farmernumbers from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and (year='".$thisyear."')";
        $sql =$mysqli->query($ttt);
        $farmernumbers=0;
        while ($row =mysqli_fetch_array($sql)) {
            $farmernumbers = $row['farmernumbers'];
        }
        $ttt="select distinct usermobimenumber as farmer from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and ((year='".$year1."') or (year='".$year2."') or (year='".$year3."'))";
        $sql =$mysqli->query($ttt);
        $farmernum=0;
        $p12=0;
        while ($row =mysqli_fetch_array($sql)) {
            $farmer = $row['farmer'];
            $farmernum=$farmernum+1;
            $ttt2="select sum(croparea) as c12 from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and ((year='".$year1."') or (year='".$year2."') or (year='".$year3."'))and (usermobimenumber='".$farmer."')";
            $sql2 =$mysqli->query($ttt2);
            $c12=0;
            while ($row2 =mysqli_fetch_array($sql2)) {
                $c12 = $row2['c12'];
            }
            $ttt2="select sum(croparea) as o12 from cropfield where (status<>'del') and (cropname<>'".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and ((year='".$year1."') or (year='".$year2."') or (year='".$year3."'))and (usermobimenumber='".$farmer."')";
            $sql2 =$mysqli->query($ttt2);
            $o12=0;
            while ($row2 =mysqli_fetch_array($sql2)) {
                $o12 = $row2['o12'];
            }
            $p12=$p12+($c12/($o12+$c12));
        }
        $p23=$p12/$farmernum;
        $u13=$p23;
        $ttt="select distinct usermobimenumber as farmer from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and ((year='".$year1."') or (year='".$year2."') or (year='".$year3."'))";
        $sql =$mysqli->query($ttt);
        $p12=0;
        $fVariance = 0.0;
        while ($row =mysqli_fetch_array($sql)) {
            $farmer = $row['farmer'];
            $ttt2="select sum(croparea) as c12 from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and ((year='".$year1."') or (year='".$year2."') or (year='".$year3."'))and (usermobimenumber='".$farmer."')";
            $sql2 =$mysqli->query($ttt2);
            $c12=0;
            while ($row2 =mysqli_fetch_array($sql2)) {
                $c12 = $row2['c12'];
            }
            $ttt2="select sum(croparea) as o12 from cropfield where (status<>'del') and (cropname<>'".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and ((year='".$year1."') or (year='".$year2."') or (year='".$year3."'))and (usermobimenumber='".$farmer."')";
            $sql2 =$mysqli->query($ttt2);
            $o12=0;
            while ($row2 =mysqli_fetch_array($sql2)) {
                $o12 = $row2['o12'];
            }
            $aValues=($c12/($o12+$c12));
            $fMean = $u13;
            //print_r($fMean);
            $fVariance += pow($aValues - $fMean, 2);
        }
        $size = $farmernum - 1;
        $v13=(float) sqrt($fVariance)/sqrt($size);
        $s13=$farmernumbers;
        $t13=$s13;
        $r13=1.96;
$w12==(pow($r13,2)*((($s13-1)/$s13)/pow($u13,2))*pow($v13,2))/$t13;
$deghat=100-$w12;
        $ttt="select * from tavarom where (year='".$thisyear."')";
        $sql =$mysqli->query($ttt);
        $aa25=0;
        while ($row =mysqli_fetch_array($sql)) {
            $aa25 = $row['mizan'];
        }
        $ttt="select * from tavarom where (year='".$year1."')";
        $sql =$mysqli->query($ttt);
        $z25=0;
        while ($row =mysqli_fetch_array($sql)) {
            $z25 = $row['mizan'];
        }
        $ttt="select * from tavarom where (year='".$year2."')";
        $sql =$mysqli->query($ttt);
        $y25=0;
        while ($row =mysqli_fetch_array($sql)) {
            $y25 = $row['mizan'];
        }
        $ttt="select * from tavarom where (year='".$year3."')";
        $sql =$mysqli->query($ttt);
        $x25=0;
        while ($row =mysqli_fetch_array($sql)) {
            $x25 = $row['mizan'];
        }
        $ttt="select avg(fprice) as x23 from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and (year='".$year3."')";
        $sql =$mysqli->query($ttt);
        $x23=0;
        while ($row =mysqli_fetch_array($sql)) {
            $x23 = $row['x23'];
        }
        $ttt="select avg(fprice) as y23 from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and (year='".$year2."')";
        $sql =$mysqli->query($ttt);
        $y23=0;
        while ($row =mysqli_fetch_array($sql)) {
            $y23 = $row['y23'];
        }
        $ttt="select avg(fprice) as z23 from cropfield where (status<>'del') and (cropname='".$_POST['fcrop']."') and (city='".$_SESSION['city']."') and (state='".$_SESSION['state']."') and (year='".$year1."')";
        $sql =$mysqli->query($ttt);
        $z23=0;
        while ($row =mysqli_fetch_array($sql)) {
            $z23 = $row['z23'];
        }
        $aa12=((1+$m12/100)*($z23+$y23*(1+$z25/100)+$x23*(1+$z25/100)*(1+$y25/100))/3)*(1+$w12);
        $aa13=((1+$m12/100)*($z23+$y23*(1+$z25/100)+$x23*(1+$z25/100)*(1+$y25/100))/3)*(1-$w12);
        $ad13=$e12;
        $ttt="select avg(totalarea) as ae13 from tolid where (product='".$_POST['fcrop']."') and (shahr='".$_SESSION['city']."') and (ostan2='".$_SESSION['state']."') and ((fromyear='".$year1."') or (fromyear='".$year2."') or (fromyear='".$year3."'))";
        $sql =$mysqli->query($ttt);
        $ae13=0;
        while ($row =mysqli_fetch_array($sql)) {
            $ae13 = $row['ae13'];
        }
        $af12=0;
        $aj12=0;
        $af12==(1+$ad13/100)*$ae13;
        $ah13=$i12;
        $ai13==$h23*$ae13;
        $aj12=$ah13/100*$ai13;
        if($aj12=='-0'){$aj12=0;}
    }
    $_SESSION['af12']=$af12;
    $_SESSION['aj12']=$aj12;
    $_SESSION['aa12']=$aa12;
    $_SESSION['aa13']=$aa13;
    $_SESSION['deghat']=$deghat;
    $_SESSION['farmernumbers']=$farmernumbers;
    $_SESSION['g23']=round($g23,2);
    $_SESSION['myfield']=round($myfield,2);
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>سامانه مزرعه</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="Admin Dashboard" name="description" />
    <meta content="ThemeDesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="assets/images/favicon.png">

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="assets/plugins/morris/morris.css">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">

</head>

<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <?php
    require ('topmenu.php');
    ?>
    <!-- Top Bar End -->

    <!-- ========== Left Sidebar Start ========== -->

    <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft">

            <!--- Divider -->

            <?php
            require ('sidemenu.php');
            ?>
            <div class="clearfix"></div>
        </div>
        <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->

    <!-- Start right Content here -->

    <div class="content-page">
        <!-- Start content -->
        <div class="content">

            <div class="">
                <div class="page-header-title">
                    <h4 class="page-title">الگوی کشت هوشمند</h4>
                </div>
            </div>

            <div class="page-content-wrapper ">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form method="post" action="future.php" id="myform" enctype="multipart/form-data">

                                    <div class="wrapper-page">
                                        <?php if($showdiv!=1) { ?>
                                        <div class="card card-pages" style="border: 4px solid #69af5d !important;">

                                            <div class="card-body">

                                                <div class="form-group">
                                                    <div class="col-12">
                                                        <p style="margin-top: 10px;font-size: smaller;color:#7fbb75;text-align: justify;">برای دیدن نتایج این بخش باید تاریخچه کشت همه مزارع کاربر برای سه سال به همراه الگوی کشت سال جاری مشخص شود.</p>
                                                    </div>
                                                </div>
                                                <a href="index.php"><button type="button" style="margin-bottom: 10px;" class="btn btn-info waves-effect waves-light">ثبت تاریخچه</button></a>
                                                    <div class="form-group">
                                                    <div class="col-12">
                                                        <select name="fcrop" id="fcrop" class="form-control">
                                                            <option value="">انتخاب محصول</option>
                                                            <?php
                                                            $ttt="select DISTINCT cropname from cropfield where usermobimenumber='".$_SESSION['lotususerid']."' ORDER BY CONVERT(cropname USING utf8) COLLATE utf8_persian_ci";
                                                            $sql =$mysqli->query($ttt);
                                                            while ($row =mysqli_fetch_array($sql)) {
                                                                $id = $row['id'];
                                                                $name = $row['cropname'];
                                                                ?>

                                                                <option <?php if((isset($_POST['fcrop']))&&($_POST['fcrop']==$name)){echo 'selected';} ?> value="<?php echo $name;?>"><?php echo $name;?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <?php if($err==1) {?>
                                                    <div class="alert alert-danger">
                                                        <?php echo $errmgs; ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <div class="form-group text-center m-t-40">
                                                    <div class="col-12">
                                                        <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" name="regbut" id="regbut"   type="submit">نتیجه پیش بینی</button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <?php } ?>
                                        <?php if($showdiv==1) { ?>

                                            <div class="card card-pages" style="border: 4px solid #69af5d !important;">
                                                <h5><p style="color: red;text-align: center;margin-top: 10px;"><?php echo $mahsool;?></p></h5>
                                                <div class="card-body">
                                                   <p> <h10>درصد تغییر سطح زیرکشت</h10><h10 style="float: left;color: <?php echo $e12color;?>;"><?php echo abs($e12); ?><?php if(abs($e12)!=$e12){echo '-';} ?></h10></p>
                                                    <p> <h10>درصد تغییر تولید محصول</h10><h10 style="float: left;color: <?php echo $i12color;?>;"><?php echo abs($i12); ?><?php if(abs($i12)!=$i12){echo '-';} ?></h10></p>
                                                    <p> <h10>درصد تغییر قیمت با احتساب تورم</h10><h10 style="float: left;color: <?php echo $m12color;?>;"><?php echo abs($m12); ?><?php if(abs($m12)!=$m12){echo '-';} ?></h10></p>
                                                    <p>   <h10>قیمت احتمالی محصول</h10><h10 style="float: left;color: blue;"><?php $d=' بین '.round($aa13,2).' و '.round($aa12,2).' ریال '; echo $d; ?></h10></p>
                                                    <p>    <h10>دقت پیش بینی</h10><h10 style="float: left;color: orange;"><?php $e=$deghat.' درصد'; echo $e; ?></h10></p>
                                                    <p>    <h10>با مشارکت</h10><h10 style="float: left;color: orange;"><?php echo $farmernumbers.' نفر '; ?></h10></p>
                                                    <p>    <h10>مجموع سطح زیر کشت</h10><h10 style="float: left;color: orange;"><?php echo $c23.' هکتار '; ?></h10></p>
                                                    <p>    <h10>تغییر تولید</h10><h10 style="float: left;color: orange;"><?php echo $aj12; ?></h10></p>
                                                    <p style="margin-top: 10px;font-size: smaller;color:#7fbb75;text-align: justify;">افزایش مشارکت کشاورزان در ارائه اطلاعات کشت این محصول بر دقت نتایج می‌افزاید. لازم به یادآوری نتایج پیش‌بینی پویاست و با تغییرات پیرامونی بخش کشاورزی نظیر آفات و بیماری و رخدادهای اقلیمی، خروجی‌های این بخش به هنگام می‌شود.</p>
                                                    <div class="form-group text-center m-t-40">
                                                        <div class="col-12">
                                                            <a class="btn btn-primary btn-block btn-lg waves-effect waves-light" href="future2.php">مشاهده جزییات پیش بینی</a>
                                                        </div>
                                                    </div>
                                                    <div class="form-group text-center m-t-40">
                                                        <div class="col-12">
                                                            <a class="btn btn-primary btn-block btn-lg waves-effect waves-light" href="invite.php">دعوت از دیگر کشاورزان</a>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                    <!-- end row -->

                </div><!-- container -->

            </div> <!-- Page content Wrapper -->

        </div>
        <!-- content -->

        <?php
        require ('footer.php');
        ?>

    </div>
    <!-- End Right content here -->

</div>
<!-- END wrapper -->

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/modernizr.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>

<!--Morris Chart-->
<script src="assets/plugins/morris/morris.min.js"></script>
<script src="assets/plugins/raphael/raphael.min.js"></script>

<!-- KNOB JS -->
<script src="assets/plugins/jquery-knob/excanvas.js"></script>
<script src="assets/plugins/jquery-knob/jquery.knob.js"></script>

<script src="assets/plugins/flot-chart/jquery.flot.min.js"></script>
<script src="assets/plugins/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="assets/plugins/flot-chart/jquery.flot.resize.js"></script>
<script src="assets/plugins/flot-chart/jquery.flot.pie.js"></script>
<script src="assets/plugins/flot-chart/jquery.flot.selection.js"></script>
<script src="assets/plugins/flot-chart/jquery.flot.stack.js"></script>
<script src="assets/plugins/flot-chart/jquery.flot.crosshair.js"></script>

<script src="assets/pages/dashboard.js"></script>

<script src="assets/js/app.js"></script>

</body>

</html>