<?php
    include '../lib/database.php';
    include '../helpers/format.php';
?>

<?php
    class brand
    {
        private $db;
        private $fm;

        public function __construct()
        {
            $this->db = new Database();
            $this->fm = new Format();
        }

        public function insert_brand($brandName){
            $brandName = $this->fm->validation($brandName);

            $brandName = mysqli_real_escape_string($this->db->link, $brandName);

            if (empty($brandName)) {
                $alert = "<span class='error'>Brand must not be empty!</span>";
                return $alert;
            } else {
                $query = "INSERT INTO tbl_brand(brandName) VALUES('$brandName')";
                $result = $this->db->insert($query);

                if($result){
                    $alert = "<span class='success'>Brand inserted successfully!</span>";
                    return $alert;
                } else {
                    $alert = "<span class='error'>Brand not inserted!</span>";
                    return $alert;
                }
            }
        }

        public function show_category(){
            $query = "SELECT * FROM tbl_category ORDER BY catId DESC";
            $result = $this->db->select($query);

            return $result;
        }

        public function update_category($catName, $catId){
            $catName = $this->fm->validation($catName);

            $catName = mysqli_real_escape_string($this->db->link, $catName);
            $catId = mysqli_real_escape_string($this->db->link, $catId);

            if (empty($catName)) {
                $msg = "<span class='error'>Category must not be empty!>";
                return $msg;
            } else {
                $query = "UPDATE tbl_category SET catName = '$catName' WHERE catId = '$catId'";
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

        public function del_category($id){
            $query = "DELETE FROM tbl_category WHERE catId = '$id'";
            $result = $this->db->delete($query);
            if($result){
                $alert = "<span class='success'>Category deleted successfully!</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Category not deleted!</span>";
                return $alert;
            }
        }        

        public function getcatbyId($id){
            $query = "SELECT * FROM tbl_category WHERE catId = '$id'";
            $result = $this->db->select($query);
            
            return $result;
        }
    }
?>