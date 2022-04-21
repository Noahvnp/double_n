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

        public function show_brand(){
            $query = "SELECT * FROM tbl_brand ORDER BY brandId DESC";
            $result = $this->db->select($query);

            return $result;
        }

        public function getbrandbyId($id){
            $query = "SELECT * FROM tbl_brand WHERE brandId = '$id'";
            $result = $this->db->select($query);
            
            return $result;
        }

        public function update_brand($brandName, $Id){
            $brandName = $this->fm->validation($brandName);

            $brandName = mysqli_real_escape_string($this->db->link, $brandName);
            $Id = mysqli_real_escape_string($this->db->link, $Id);

            if (empty($brandName)) {
                $msg = "<span class='error'>Brand must not be empty!>";
                return $msg;
            } else {
                $query = "UPDATE tbl_brand SET brandName = '$brandName' WHERE brandId = '$Id'";
                $result = $this->db->update($query);

                if($result){
                    $alert = "<span class='success'>Brand updated successfully!</span>";
                    return $alert;
                } else {
                    $alert = "<span class='error'>Brand not updated!</span>";
                    return $alert;
                }
            }
        }

        public function del_brand($id){
            $query = "DELETE FROM tbl_brand WHERE brandId = '$id'";
            $result = $this->db->delete($query);
            if($result){
                $alert = "<span class='success'>Brand deleted successfully!</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Brand not deleted!</span>";
                return $alert;
            }
        }        

       
    }
?>