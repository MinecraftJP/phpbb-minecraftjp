<?php
namespace minecraftjp\phpbb\model;

class user_minecraftjp extends \minecraftjp\phpbb\model\model {
    public function getUserIdBySub($sub) {
        $sql = sprintf('SELECT user_id FROM %s WHERE sub = %d', $this->table, $sub);
        $result = $this->db->sql_query($sql);
        if ($result->num_rows == 1) {
            $row = $this->db->sql_fetchrow($result);
            return $row['user_id'];
        }
        return false;
    }

    public function read($userId) {
        $sql = sprintf('SELECT * FROM %s WHERE user_id = %d', $this->table, $userId);
        $result = $this->db->sql_query($sql);
        if ($result->num_rows == 1) {
            $row = $this->db->sql_fetchrow($result);
            return $row;
        }
        return false;
    }

    public function updateUser($userId, $mcjpUser) {
        $data = array(
            'username' => $mcjpUser['preferred_username'],
            'uuid' => $mcjpUser['uuid'],
        );
        if ($this->getUserIdBySub($mcjpUser['sub'])) {
            $sql = sprintf('UPDATE %s SET %s WHERE user_id = %d', $this->table, $this->db->sql_build_array('UPDATE', $data), $userId);
        } else {
            $data['user_id'] = $userId;
            $data['sub'] = $mcjpUser['sub'];
            $sql = sprintf('INSERT INTO %s %s', $this->table, $this->db->sql_build_array('INSERT', $data));
        }
        return $this->db->sql_query($sql);
    }

    public function deleteByUserId($userId) {
        if (is_array($userId)) {
            $sql = sprintf('DELETE FROM %s WHERE %s', $this->table, $this->db->sql_in_set('user_id', $userId));
        } else {
            $sql = sprintf('DELETE FROM %s WHERE user_id = %d', $this->table, $userId);
        }
        $this->db->sql_query($sql);
        return $this->db->sql_affectedrows() > 0;
    }
}