<?php
include("config.php");
$content_per_page = 12;  
$group_no  = strtolower(trim(str_replace("/","",$_REQUEST['group_no'])));
$start = ceil($group_no * $content_per_page);
$sql= "SELECT distinct * FROM `products` WHERE category_id = '1'";
    if(isset($_REQUEST['brand']) && $_REQUEST['brand']!="") :
        $brand = explode(',',url_clean($_REQUEST['brand']));    
        $sql.=" AND brand IN ('".implode("','",$brand)."')";
    endif;

    if(isset($_GET['primary_material']) && $_GET['primary_material']!="") :
        $primary_material = explode(',',url_clean($_REQUEST['primary_material']));  
        $sql.=" AND primary_material IN ('".implode("','",$primary_material)."')";
    endif;

    if(isset($_GET['size']) && $_GET['size']!="") :
        $size = explode(',',$_REQUEST['size']); 
        $sql.=" AND size IN (".implode(',',$size).")";
    endif;
    
     $sql.=" LIMIT $start, $content_per_page";

    $all_product=$db->query($sql);


     $rowcount=$all_product->num_rows;
    
    // echo $sql; exit;

    function url_clean($String)
    {
        return str_replace('_',' ',$String); 
    }
?>

<!-- listing -->
<?php if(isset($all_product) && $rowcount > 0) : $i = 0; ?>
    <?php foreach ($all_product as $key => $products) : ?>
        <article class="col-md-4 col-sm-6">
            <div class="thumbnail product">
                <figure>
                    <a href="#"><img src="product_images/<?php echo $products['image']; ?>" /></a>
                </figure>
                <div class="caption">
                    <a href="" class="product-name"><?php echo $products['name']; ?></a>
                    <div class="price">Rs.<?php echo $products['sale_price']; ?>/-</div>
                    <h6>Brand : <?php echo $products['brand']; ?></h6>
                    <h6>primary_material : <?php echo $products['primary_material']; ?></h6>
                    <h6>Size : <?php echo $products['size']; ?></h6>
                </div>
            </div>
        </article>
    <?php $i++; endforeach; ?> 
<?php endif; ?>
                        
<!-- /.listing -->