<?php
session_start();
ob_start();
if (!isset($_SESSION['mycart'])) $_SESSION['mycart'] = [];

include_once "../model/hamsanpham.php";
include_once "../model/hamnguoidung.php";
include_once "../model/hamtintuc.php";
include_once '../model/global.php';

if(isset($_GET['act']))
    $action = $_GET['act'];
else
    $action = 'home';


switch ($action) {
    case 'home':
        if (isset($_GET['id_source']) && ($_GET['id_source']) > 0) {
            $id_pro = $_GET['id_source'];
            $detai_pro = get_detail_product_id($id_pro);
        }
        $sanpham_hot = get_All_Products_HOT();
        $sanpham_bc = get_All_Products_BC();
        // $sanpham_mv=get_All_Products_mv();
        $tin_nb=get_All_list_news_nb();
        $main = '../view/home.php';
        include_once '../view/header.php';
        break;
    
    case 'product_type':

        if(!isset($_GET['id_category']))
            die('Đường dẫn của bạn bị sai rồi :(((');

        $id_category = (int)$_GET['id_category'];

        $name_page = get_name_category($id_category);
        if($name_page == false)
            die('Đường dẫn của bạn sai hoặc doanh mục đã bị xóa khỏi hệ thống!!!');

        # phân trang trong loại
        $page_size = 6; // số sản phẩm hiển thị
        $page_num = 1;
        if (isset($_GET['page_num'])) $page_num = $_GET['page_num']+0;
        if ($page_num<=0) $page_num=1;
        $base_url = Get_current_link('notQuery');

        $total_rows = get_All_product_by_category_COUNT($id_category);
        
        echo $total_rows;
        
        # lấy loại sp và danh sách của sp theo loại
        $danhmuc = get_All_category_product();
        $sanpham = get_All_product_by_category($id_category,$page_num, $page_size);

        # tên để hiển thị 
        $name_page = $name_page['tenLoai'];

        $main = '../view/product_all.php';
        include_once '../view/header.php';
        break;


    case 'login_account':

        // controller xử lý login - regist
        include_once 'LoginController.php';

        if(!isset($_SESSION['login_user'])){

            $main = '../view/user/login.php';
            include_once '../view/header.php';

        } else echo "Bạn đã đăng nhập rồi mà nhỉ?";

        break;
    
    case 'view_account':

        // xử lý trang thông tin người dùng
        include_once 'LoginController.php';

        # không đăng nhập không được vào xem
        if(isset($_SESSION['login_user']))

            include_once 'UserController.php';
        else 

            echo "Bạn chưa đăng nhập mà xem gì v b?";
        
        break;

    case 'my_cart':
        if(isset($_POST['addtocart']) && ($_POST['addtocart'])){
            $id_pro = $_POST['id_prod'];
            $price = $_POST['gia'];
            $ten=$_POST['tensp'];
            $anh= $_POST['hinhanh'];
            $sl= $_POST['soluong'];
            $pro_cart=[$id_pro,$price,$ten,$anh,$sl];
            array_push($_SESSION['mycart'],$pro_cart);
            header("location:" . $_SERVER['HTTP_REFERER']);
            
        }
        $main = '../view/cart.php';
        include_once '../view/header.php';
        break;

    case 'delete_prod_cart':
        if (isset($_GET['id'])) {
            array_splice($_SESSION['mycart'],($_GET['id']) , 1);
        }else{
             $_SESSION['mycart']=[];
        }
        header("location:" . $_SERVER['HTTP_REFERER']);
        break; 
    
    case 'delete_all_prod_cart':
        unset($_SESSION['mycart']);
        header("location:" . $_SERVER['HTTP_REFERER']);
        break; 
    case 'list_news':
        $new = get_All_list_news();

        $main = '../view/blog.php';
        include_once '../view/header.php';
        break;

    case 'detail_news':
        if (isset($_GET['id_source']) && ($_GET['id_source']) > 0) {
            $id_post = $_GET['id_source'];
            $detail_news = get_Detail_new($id_post);
        }

        $main = '../view/view_blog.php';
        include_once '../view/header.php';
        break;

    case 'all_prod':
        $sanpham = get_All_Products();
        $danhmuc = get_All_category_product();

        $name_page = 'Tất cả sản phẩm';
        $main = '../view/product_all.php';
        include_once '../view/header.php';
        break;

    case 'detail_prod':
        if (isset($_GET['id_source']) && ($_GET['id_source']) > 0) {
            $id_pro = $_GET['id_source'];
            $detai_pro = get_detail_product_id($id_pro);

            if(!isset($detai_pro['idSP']))
                die('Sản phẩm không tồn tại');

            $current_link =  Get_current_link();
            
            #thêm lượt xem cho sản phẩm
            set_view_product($id_pro);
            $main = '../view/product_view.php';
            include_once '../view/header.php';
        } else echo "Đường dẫn sai nè!";
        
        break;
}

