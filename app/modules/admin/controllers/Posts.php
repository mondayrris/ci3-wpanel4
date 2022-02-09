<?php /** @noinspection PhpUnused */

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Posts class
 *
 * @property Post $post
 * @property Wpanel $wpanel
 * @property Post_categoria $post_categoria
 * @property Category $category
 * @property Widget $widget
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 */
class Posts extends Authenticated_admin_controller
{
    const PAGE_INDEX = 0;

    /**
     * Class constructor.
     */
    function __construct()
    {
        $this->model_file = array('post', 'category', 'post_categoria');
        $this->language_file = 'wpn_post_lang';
        parent::__construct();
    }

    /**
     * List posts.
     */
    public function index()
    {
        list($query, $total_rows) = parent::get_post_query_result(self::PAGE_INDEX);

        foreach ($query as $row)
        {
            $this->table->add_row(
                    $row->id, $row->title . '<br/><small>' . $this->widget->load('wpncategoryfrompost', array('post_id' => $row->id)) . '</small>', mdate(config_item('user_date_format'), strtotime($row->created_on)), status_post($row->status),
                    // Ícones de ações
                    div(array('class' => 'btn-group btn-group-xs')) .
                    anchor('admin/posts/edit/' . $row->id, glyphicon('edit'), array('class' => 'btn btn-default')) .
                    anchor('admin/posts/delete/' . $row->id, glyphicon('trash'), array('class' => 'btn btn-default', 'data-confirm' => wpn_lang('wpn_message_confirm'))).
                    div(null, true)
            );
        }

        $this->set_var('pagination_links', $this->pagination->create_links());
        $this->set_var('total_rows', $total_rows);
        $this->set_var('listagem', $this->table->generate());
        $this->render();
    }

    /**
     * Insert post.
     */
    public function add()
    {
        $this->form_validation->set_rules('title', wpn_lang('field_title'), 'required');
        if ($this->form_validation->run() == FALSE)
        {
            // Prepara a lista de categorias.
            $query = $this->category->select('id, title')->find_all();
            $categorias = array();
            foreach ($query as $row)
            {
                $categorias[$row->id] = $row->title;
            }
            $this->set_var('categorias', $categorias);
            $this->render();
        } else
        {
            $save_result = parent::get_add_post_result(self::PAGE_INDEX);
            if ($save_result)
            {
                // Salva o relacionamento das categorias
                foreach ($this->input->post('category_id') as $cat_id)
                {
                    $cat_save = array();
                    $cat_save['post_id'] = $save_result;
                    $cat_save['category_id'] = $cat_id;
                    $this->post_categoria->insert($cat_save);
                }
                $this->set_message(wpn_lang('wpn_message_save_success'), 'success', 'admin/posts');
            } else
                $this->set_message(wpn_lang('wpn_message_save_error'), 'danger', 'admin/posts');
        }
    }

    /**
     * Edit a post.
     * 
     * @param int $id
     */
    public function edit($id = null)
    {
        $this->form_validation->set_rules('title', wpn_lang('field_title'), 'required');
        if ($this->form_validation->run() == FALSE)
        {
            if ($id == null)
                $this->set_message(wpn_lang('wpn_message_inexistent'), 'info', 'admin/posts');
            $query = $this->post->find_by(array('id' => $id, 'page' => 0));
            if(empty($query))
                $this->set_message(wpn_lang('wpn_message_inexistent'), 'info', 'admin/posts');
            // Prepara a lista de categorias.
            $query_cat = $this->category->select('id, title')->find_all();
            $categorias = array();
            foreach ($query_cat as $row)
            {
                $categorias[$row->id] = $row->title;
            }
            // Prepara as categorias selecionadas.
            $query_selected_cat = $this->post_categoria->select('category_id')->find_many_by('post_id', $id);
            $cat_select = array();
            foreach ($query_selected_cat as $x => $row)
            {
                $cat_select[$x] = $row->category_id;
            }
            $this->set_var('id', $id);
            $this->set_var('categorias', $categorias);
            $this->set_var('cat_select', $cat_select);
            $this->set_var('row', $query);
            $this->render();
        } else
        {
            $save_result = parent::get_update_post_result($id, self::PAGE_INDEX);
            if ($save_result)
            {
                // Apaga os relacionamentos anteriores.
                $this->post_categoria->delete_by_post($id);
                // Cadastra as alterações.
                foreach ($this->input->post('category_id') as $cat_id)
                {
                    $cat_save = array();
                    $cat_save['post_id'] = $id;
                    $cat_save['category_id'] = $cat_id;
                    $this->post_categoria->insert($cat_save);
                }
                $this->set_message(wpn_lang('wpn_message_update_success'), 'success', 'admin/posts');
            } else
                $this->set_message(wpn_lang('wpn_message_update_success'), 'danger', 'admin/posts');
        }
    }

    /**
     * Delete a post.
     * 
     * @param int $id
     */
    public function delete($id = null)
    {
        if ($id == null)
            $this->set_message(wpn_lang('wpn_message_inexistent'), 'info', 'admin/posts');
        $postagem = $this->post->find($id);
        $this->wpanel->remove_media('capas/' . $postagem->image);
        if ($this->post->delete($id))
            $this->set_message(wpn_lang('wpn_message_delete_success'), 'success', 'admin/posts');
        else
            $this->set_message(wpn_lang('wpn_message_delete_success'), 'danger', 'admin/posts');
    }

}
