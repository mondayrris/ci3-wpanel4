<?php

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Foto
 * 
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 */
class Foto extends MY_Model
{

    public $table_name = 'fotos';
    public $primary_key = 'id';

    /**
     * This method removes all images and directory from an album.
     * 
     * @param int $album_id
     * @return int
     */
    public function delete_by_album($album_id)
    {
        // FIXME
        $query = $this->get_by_field('album_id', $album_id)->result();
        foreach ($query as $row) {
            // FIXME
            $this->remove_media('albuns/' . $album_id . '/' . $row->filename);
        }

        rmdir(FCPATH . 'media/albuns/' . $album_id);

        $this->db->where('album_id', $album_id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }

}
