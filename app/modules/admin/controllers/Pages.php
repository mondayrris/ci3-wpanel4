<?php /** @noinspection PhpUnused */

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Page class.
 *
 * @property Post $post
 * @property Wpanel $wpanel
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 */
class Pages extends Authenticated_admin_controller
{
    const PAGE_INDEX = 1;

    /**
     * Class constructor.
     */
    function __construct()
    {
        $this->model_file = 'post';
        $this->language_file = 'wpn_page_lang';
        parent::__construct();
    }

    /**
     * List pages.
     */
    public function index()
    {
        list($query, $total_rows) = parent::get_post_query_result(self::PAGE_INDEX);
        
        foreach ($query as $row)
        {
            $this->table->add_row(
                    $row->id, $row->title, mdate(config_item('user_date_format'), strtotime($row->created_on)), status_post($row->status),
                    // Ícones de ações
                    div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/pages/edit/' . $row->id, glyphicon('edit'), array('class' => 'btn btn-default')) .
                    anchor('admin/pages/delete/' . $row->id, glyphicon('trash'), array('class' => 'btn btn-default', 'data-confirm' => wpn_lang('wpn_message_confirm'))).
                    div(null, true)
            );
        }
        
        $this->set_var('pagination_links', $this->pagination->create_links());
        $this->set_var('total_rows', $total_rows);
        $this->set_var('listagem', $this->table->generate());
        $this->render();
    }

    /**
     * Insert page.
     */
    public function add()
    {
        $this->form_validation->set_rules('title', wpn_lang('field_title'), 'required');
        if ($this->form_validation->run() == FALSE)
        {
            $this->render();
        } else
        {
            $save_result = parent::get_add_post_result(self::PAGE_INDEX);
            if ($save_result)
            {
                $this->set_message(wpn_lang('wpn_message_save_success'), 'success', 'admin/pages');
            } else
                $this->set_message(wpn_lang('wpn_message_save_error'), 'danger', 'admin/pages');
        }
    }

    /**
     * Edit a page.
     * 
     * @param int $id
     */
    public function edit($id = null)
    {
        $this->form_validation->set_rules('title', wpn_lang('field_title'), 'required');
        if ($this->form_validation->run() == FALSE)
        {
            if ($id == null)
                $this->set_message('Página inexistente.', 'info', 'admin/pages');
            $query = $this->post->find_by(array('id' => $id, 'page' => 1));
            if(empty($query))
                $this->set_message(wpn_lang('wpn_message_inexistent'), 'info', 'admin/pages');
            $this->set_var('id', $id);
            $this->set_var('row', $query);
            $this->render();
        } else
        {
            $save_result = parent::get_update_post_result($id, self::PAGE_INDEX);
            if ($save_result)
            {
                $this->set_message(wpn_lang('wpn_message_update_success'), 'success', 'admin/pages');
            } else
                $this->set_message(wpn_lang('wpn_message_update_error'), 'danger', 'admin/pages');
        }
    }

    /**
     * Delete a page.
     * 
     * @param int $id
     */
    public function delete($id = null)
    {
        if ($id == null)
            $this->set_message(wpn_lang('wpn_message_inexistent'), 'info', 'admin/pages');
        $postagem = $this->post->find($id);
        $this->wpanel->remove_media('capas/' . $postagem->image);
        if ($this->post->delete($id))
        {
            $this->set_message(wpn_lang('wpn_message_delete_success'), 'success', 'admin/pages');
        } else
            $this->set_message(wpn_lang('wpn_message_delete_error'), 'danger', 'admin/pages');
    }

}
