<?php
    $loginUser = $_SESSION['login_user'];
    if(isset($_POST['btn'])){
        $hoVaTen = $_POST['hoVaTen'];
        $sdt = $_POST['sdt'];
        $hoVaTen = trim(strip_tags($hoVaTen));
        $sdt = trim(strip_tags($sdt));
        updateNameAndPhoneNumber($hoVaTen,$sdt,$loginUser['email']);
        $_SESSION['login_user'] = check_login($loginUser['email'],md5($loginUser['matKhau']));
        echo "<script>alert('Bạn đã cập nhật thông tin thành công')</script>";
    }
?>
<form action="" method="post">
    <div class="card">
        <div class="card-header">
            <h5>Chỉnh sửa thông tin</h5>
        </div>
        <div class="card-divider"></div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="col-12 col-lg-7 col-xl-6">
                    <div class="form-group"><label for="profile-first-name">Họ và tên</label> <input name="hoVaTen" type="text" class="form-control" id="profile-first-name" placeholder="Họ và tên"></div>
                    <div class="form-group"><label for="profile-phone">Số điện thoại</label> <input name="sdt" type="text" class="form-control" id="profile-phone" placeholder="Số điện thoại" pattern="(\+84|0)\d{9,10}" oninvalid="this.setCustomValidity('Số điện thoại không đúng')" oninput="this.setCustomValidity('')"></div>
                    <div class="form-group mt-5 mb-0"><button name="btn" class="btn btn-primary">Lưu</button></div>
                </div>
            </div>
        </div>
    </div>
</form>