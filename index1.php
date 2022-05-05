<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>On Scroll</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>
<body>

    <?php 
include("config.php");
$all_brand=$db->query("SELECT distinct brand FROM `products` WHERE category_id = '1' GROUP BY brand");
// $all_primary_material=$db->query("SELECT distinct primary_material FROM `products` WHERE category_id = '1' GROUP BY primary_material");

$all_primary_material=$db->query("SELECT distinct primary_material FROM `products` WHERE 1 = 1 GROUP BY primary_material");

$all_size=$db->query("SELECT distinct size FROM `products` WHERE category_id = '1' GROUP BY size");
// Filter query
    $sql= "SELECT distinct id FROM `products` WHERE category_id = '1'";
    if(isset($_GET['brand']) && $_GET['brand']!="") :
        $brand = $_GET['brand'];
        $sql.=" AND brand IN ('".implode("','",$brand)."')";
    endif;

    if(isset($_GET['primary_material']) && $_GET['primary_material']!="") :
        $primary_material = $_GET['primary_material'];
        $sql.=" AND primary_material IN ('".implode("','",$primary_material)."')";
    endif;

    if(isset($_GET['size']) && $_GET['size']!="") :
        $size = $_GET['size'];
        $sql.=" AND size IN (".implode(',',$size).")";
    endif;
    $all_product=$db->query($sql);
    $content_per_page = 3;
    $rowcount=mysqli_num_rows($all_product);
    $total_data = ceil($rowcount / $content_per_page);
    function data_clean($str)
    {
        return str_replace(' ','_',$str);
    }

?>


<div class="container-fluid">
<form method="get" id="search_form">                
    <div class="row">
        <!-- sidebar -->
        <aside class="col-lg-3 col-md-4">
            <div class="panel list">
                <div class="panel-heading"><h3 class="panel-title">Shop by Brand</h3></div>
                <div class="panel-body collapse in" id="panelOne">
                    <ul class="list-group">
                    <?php foreach ($all_brand as $key => $new_brand) :
                        if(isset($_GET['brand'])) :
                            if(in_array(data_clean($new_brand['brand']),$_GET['brand'])) : 
                                $check='checked="checked"';
                            else : $check="";
                            endif;
                        endif;
                    ?>
                        <li class="list-group-item">
                            <div class="checkbox"><label>
                            <input type="checkbox" value="<?=data_clean($new_brand['brand']);?>" <?=@$check?> name="brand[]" class="sort_rang brand">
                            <?=ucfirst($new_brand['brand']); ?></label></div>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="panel list">
                <div class="panel-heading"><h3 class="panel-title">Shop by Primary Material</h3></div>
                <div class="panel-body collapse in" id="panelOne">
                    <ul class="list-group">
                    <?php foreach ($all_primary_material as $key => $new_primary_material) :
                        if(isset($_GET['primary_material'])) :
                            if(in_array(data_clean($new_primary_material['primary_material']),$_GET['primary_material'])) : 
                                $check='checked="checked"';
                            else : $check="";
                            endif;
                        endif;
                    ?>
                        <li class="list-group-item">
                            <div class="checkbox"><label>
                            <input type="checkbox" value="<?=data_clean($new_primary_material['primary_material']);?>" <?=@$check?> name="primary_material[]" class="sort_rang primary_material">
                            <?=ucfirst($new_primary_material['primary_material']); ?></label></div>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </aside> <!-- /.sidebar -->
        <section class="col-lg-9 col-md-8">
            <div class="row">
                <div id="results"></div>
            </div>
        </section>
    </div>
</form>
</div>

<script type="text/javascript">
$(document).ready(function() {
    var total_record = 0;
    var brand=check_box_values('brand');
    var primary_material=check_box_values('primary_material');
    var size=check_box_values('size');
    var total_groups = <?php echo $total_data; ?>;
    $('#results').load("autoload.php?group_no="+total_record+"&brand="+brand+"&primary_material="+primary_material+"&size="+size,  function() {
        total_record++;
    });
    $(window).scroll(function() {       
        if($(window).scrollTop() + $(window).height() == $(document).height())  
          
        {    
            if(total_record <= total_groups)
            {
                loading = true;
                $('.loader').show();
                $.get("autoload.php?group_no="+total_record+"&brand="+brand+"&primary_material="+primary_material+"&size="+size,
                function(data){ 
                if (data != "") {                               
                    $("#results").append(data);
                    $('.loader').hide();                  
                    total_record++;
                }
                });     
            }
                // total_record ++;
        }
    });
    function check_box_values(check_box_class){
        var values = new Array();
            $("."+check_box_class+":checked").each(function() {
               values.push($(this).val());
            });
        return values;
    }
    $('.sort_rang').change(function(){
        $("#search_form").submit();
        return false;
    });
});
</script>


</body>
</html>