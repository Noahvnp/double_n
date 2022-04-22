<?php
   $filepath = realpath(dirname(__FILE__));
   include_once ($filepath.'/../lib/database.php');
   include_once ($filepath.'/../helpers/format.php');
?>

<?php
    class product
    {
        private $db;
        private $fm;

        public function __construct()
        {
            $this->db = new Database();
            $this->fm = new Format();
        }

        public function insert_product($data,$files){
           
            $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
            $brand = mysqli_real_escape_string($this->db->link, $data['brand']);
            $category = mysqli_real_escape_string($this->db->link, $data['category']);
            $product_desc = mysqli_real_escape_string($this->db->link, $data['product_desc']);
            $price = mysqli_real_escape_string($this->db->link, $data['price']);
            $type = mysqli_real_escape_string($this->db->link, $data['type']);
           
            //kiểm tra hình ảnh
            $permited = array('jpg', 'jpeg', 'png', 'gif');
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_temp = $_FILES['image']['tmp_name'];

            $div = explode('.', $file_name); //tách chuỗi lấy tên và định dạng file
            $file_ext = strtolower(end($div)); //chuyển chuỗi thành chữ thường
            $unique_image = substr(md5(time()), 0, 10). '.' .$file_ext; //tạo tên file mới
            $uploaded_image= "uploads/" .$unique_image; 

            if ($productName=="" || $brand=="" ||$category=="" ||$product_desc=="" ||$price=="" ||$type=="" ||$file_name=="") {
                $alert = "<span class='error'>Fields must be not empty </span>";
                return $alert;
            } else {
                move_uploaded_file($file_temp,$uploaded_image);
                $query = "INSERT INTO tbl_product(productName,brandId,catId,product_desc,price,type,image ) VALUES('$productName','$brand','$category','$product_desc','$price','$type','$unique_image')";
                $result = $this->db->insert($query);

                if($result){
                    $alert = "<span class='success'> Inserted product successfully!</span>";
                    return $alert;
                } else {
                    $alert = "<span class='error'>Inserted product not success</span>";
                    return $alert;
                }
            }
        }

        public function show_product(){
            $query = "SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
                        FROM tbl_product 
                        INNER JOIN tbl_category ON tbl_product.catId = tbl_category.catId
                        INNER JOIN tbl_brand ON tbl_product.brandId = tbl_brand.brandId
                        ORDER BY tbl_product.productId DESC";
            $result = $this->db->select($query);
            return $result;
        }

        public function update_product($data, $file, $id){
            $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
            $brand = mysqli_real_escape_string($this->db->link, $data['brand']);
            $category = mysqli_real_escape_string($this->db->link, $data['category']);
            $product_desc = mysqli_real_escape_string($this->db->link, $data['product_desc']);
            $price = mysqli_real_escape_string($this->db->link, $data['price']);
            $type = mysqli_real_escape_string($this->db->link, $data['type']);
           
            //kiểm tra hình ảnh
            $permited = array('jpg', 'jpeg', 'png', 'gif');
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_temp = $_FILES['image']['tmp_name'];

            $div = explode('.', $file_name); //tách chuỗi lấy tên và định dạng file
            $file_ext = strtolower(end($div)); //chuyển chuỗi thành chữ thường
            $unique_image = substr(md5(time()), 0, 10). '.' .$file_ext; //tạo tên file mới
            $uploaded_image= "uploads/" .$unique_image;

            if ($productName=="" || $brand=="" || $category=="" ||
                $product_desc==""|| $price=="" || $type==""  ) {
                    $alert = "<span class='error'>Fields must be not empty </span>";
                    return $alert;
            }else{
                if(!empty($file_name)){
                    // Nếu người dùng chọn ảnh => tồn tại file
                    if($file_size > 1048567){
                        $alert = "<span class='error'>Image size should be less than 10MB! </span>";
                        return $alert;
                    }
                    elseif (in_array($file_ext, $permited) === false){ //kiểm tra định dạng file có hợp lệ
                        $alert = "<span class='error'>You can upload only:-".implode(', ', $permited)."</span>";
                        return $alert;
                    }

                    move_uploaded_file($file_temp, $uploaded_image);
                    $query = "UPDATE tbl_product SET
                                productName = '$productName',
                                brandId = '$brand',
                                catId = '$category',
                                product_desc = '$product_desc',
                                price = '$price',
                                type = '$type',
                                image = '$unique_image'
                            WHERE productId = '$id'";
                } else {
                    // Nếu người dùng không chọn ảnh => file chưa tồn tại
                    $query = "UPDATE tbl_product SET
                                productName = '$productName',
                                brandId = '$brand',
                                catId = '$category',
                                product_desc = '$product_desc',
                                price = '$price',
                                type = '$type'
                            WHERE productId = '$id'";
                }

                $result = $this->db->update($query);
                if($result){
                    $alert = "<span class='success'>Category updated successfully!</span>";
                    return $alert;
                } else {
                    $alert = "<span class='error'>Category not updated!</span>";
                    return $alert;
                }
            }
        }

        public function del_product($id){
            $query = "DELETE FROM tbl_product WHERE productId = '$id'";
            $result = $this->db->delete($query);
            if($result){
                $alert = "<span class='success'>Product deleted successfully!</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Product not deleted!</span>";
                return $alert;
            }
        }        

        public function getproductbyid($id){
            $query = "SELECT * FROM tbl_product WHERE productId = '$id'";
            $result = $this->db->select($query);
            return $result;
        }

        public function getproduct_featured() {
            $query = "SELECT * FROM tbl_product WHERE type = '1' ORDER BY productId DESC LIMIT 4";
            $result = $this->db->select($query);
            return $result;
        }
    }


?>