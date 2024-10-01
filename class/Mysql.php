<?php
class DB
{
    public $cn = NULL;

    public function connect()
    {
        $this->cn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if (!$this->cn) {
            echo "Không Thể Kết Nối Tới Máy Chủ " . APP_NAME . " với DB " . DB_DATABASE . "!";
            exit;
        }
        mysqli_set_charset($this->cn, "utf8");
        return $this->cn;
    }

    // Hàm chống sql injection
    public function real_escape_string($sql = null)
    {
        if ($this->cn)
            return mysqli_real_escape_string($this->cn, $sql);
    }

    // Hàm ngắt kết nối
    public function close()
    {
        if ($this->cn) {
            mysqli_close($this->cn);
        }
    }

    public function format($t){
        return mysqli_real_escape_string($this->cn, $t);
    }

    // Hàm truy vấn
    public function query($sql = null)
    {
        if ($this->cn) {
            if (!mysqli_query($this->cn, $sql)) {
                return mysqli_error($this->cn);
            }
        }
    }

    // Hàm đếm số hàng
    public function num_rows($sql = null)
    {
        if ($this->cn) {
            $query = mysqli_query($this->cn, $sql);
            if ($query) {
                $row = mysqli_num_rows($query);
                return $row;
            }
        }
    }

    // Hàm đếm tổng số hàng
    public function fetch_row($sql = null)
    {
        if ($this->cn) {
            $query = mysqli_query($this->cn, $sql);
            if ($query) {
                $row = $query->fetch_row();
                return $row[0];
            }
        }
    }

    // Hàm lấy dữ liệu
    public function fetch_assoc($sql = null, $type = null)
    {
        if ($this->cn) {
            $query = mysqli_query($this->cn, $sql);
            if ($query) {
                if ($type == 0) {
                    $data = array();
                    // Lấy nhiều dữ liệu gán vào mảng
                    while ($row = mysqli_fetch_assoc($query)) {
                        $data[] = $row;
                    }
                    return $data;
                } else if ($type == 1) {
                    // Lấy một hàng dữ liệu gán vào biến
                    $data = mysqli_fetch_assoc($query);
                    return $data;
                }
            }
        }
    }

    //Thêm dữ liệu
    public function insert($table, $data)
    {
        $field_list = '';
        $value_list = '';
        foreach ($data as $key => $value) {
            $field_list .= ",$key";
            $value_list .= ",'" . mysqli_real_escape_string($this->cn, $value) . "'";
        }
        $sql = 'INSERT INTO ' . $table . ' (' . trim($field_list, ',') . ') VALUES (' . trim($value_list, ',') . ')';

        $result = mysqli_query($this->cn, $sql);
        if (!$result) {
            // Báo lỗi nếu câu truy vấn không thành công
            return $error_message = mysqli_error($this->cn);
        }
        return $result;
    }

    //Xoá dữ liệu
    function remove($table, $where)
    {
        $sql = "DELETE FROM $table WHERE $where";
        return mysqli_query($this->cn, $sql);
    }

    //update dữ liệu
    public function update($table, $data, $where)
    {
        $sql = '';
        foreach ($data as $key => $value) {
            $sql .= "$key = '" . mysqli_real_escape_string($this->cn, $value) . "',";
        }
        $sql = 'UPDATE ' . $table . ' SET ' . trim($sql, ',') . ' WHERE ' . $where;
        return mysqli_query($this->cn, $sql);
    }

    function cong($table, $data, $sotien, $where)
    {
        if ($this->cn) {
            $sql = "UPDATE `$table` SET `$data` = `$data` + '$sotien' WHERE $where";
            return mysqli_query($this->cn, $sql);
        }
        return $this->cn;
    }

    function tru($table, $data, $sotien, $where)
    {
        if ($this->cn) {
            $sql = "UPDATE `$table` SET `$data` = `$data` - '$sotien' WHERE $where";
            return mysqli_query($this->cn, $sql);
        }
        return $this->cn;
    }

    // Hàm lấy ID cao nhất
    public function insert_id()
    {
        if ($this->cn) {
            $count = mysqli_insert_id($this->cn);
            if ($count == '0') {
                $count = '1';
            }
            return $count;
        }
    }

    // Hàm charset cho database
    public function set_char($uni)
    {
        if ($this->cn) {
            mysqli_set_charset($this->cn, $uni);
        }
    }

    public function TRUNCATE($table){
        if ($this->cn) {
            $sql = "TRUNCATE TABLE `$table`";
            return mysqli_query($this->cn, $sql);
        }
        return $this->cn;
    }
}
$db = new DB();
$db->connect();