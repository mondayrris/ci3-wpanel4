<?php 

/**
 * WPanel CMS
 *
 * An open source Content Manager System for blogs and websites using CodeIgniter and PHP.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     WpanelCms
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @copyright   Copyright (c) 2008 - 2016, Eliel de Paula. (https://elieldepaula.com.br/)
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://wpanelcms.com.br
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Main Controller Class
 *
 * This class maintain the methods of the basic website. It was thinked that
 * you add more resources to your project creating new Controller Classes
 * extending MY_Controller Class to get the common features.
 *
 * @package     WpanelCms
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @link        https://wpanelcms.com.br
 * @version     0.0.1
 */
class Main extends MY_Controller 
{

    /**
     * Class constructor.
     *
     * @return void
     */
    function __construct() 
    {
        parent::__construct();
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * Método 'custom', onde o desenvolvedor cria uma página inicial
     * personalizada.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function custom() 
    {

        /*
         * Este método é chamado caso seja configurado o uso de um
         * página inicial personaizada no painel de configurações.
         *
         * Para informações sobre como implementar um método personalizado
         * confira a documentação ou entre em contto com dev@elieldepaula.com.br>
         */

        echo '<meta charset="UTF-8">';
        echo '<title>Página inicial personalizada</title>';
        echo '<h1>Página inicial personalizada do wPanel.</h1>';
        echo '<p>Você pode alterar esta página pelo painel de controle indo em Configurações >
                Página inicial.</p>';
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * Método index() que faz o funcionamento da página inicial do site.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function index() 
    {
        // Seleciona a página inicial de acordo com as configurações.
        //------------------------------------------------------------------------------------------
        switch ($this->wpanel->get_config('home_tipo')) {
            case 'page':
                $this->post($this->wpanel->get_config('home_id'), true);
                break;
            case 'category':
                $this->posts($this->wpanel->get_config('home_id'));
                break;
            default:
                return $this->custom();
                break;
        }
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método posts() gera uma listagem das postagens disponíveis
     * para exibição no site.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @param $category_id Int ID da categoria para listagem.
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function posts($category_id = null) 
    {

        $view_title = '';
        $view_description = '';

        // Carrega os models necessários.
        //------------------------------------------------------------------------------------------
        $this->load->model('post');
        $this->load->model('categoria');

        // Envia os dados para a view de acordo com a categoria.
        //------------------------------------------------------------------------------------------
        if ($category_id == null) {
            $this->data_content['posts'] = $this->post->get_by_field(
                ['page' => '0', 'status' => '1'], 
                null, 
                ['field' => 'created', 'order' => 'desc'], 
                null, 
                'id, title, description, content, link, image, created'
            )->result();
            $view_title = 'Todas as postagens';
        } else {

            $qry_category = $this->categoria->get_by_id($category_id, null, null, 'title, description, view')->row();
            $this->data_content['posts'] = $this->post->get_by_category($category_id, 'desc')->result();
            $this->data_content['view_title'] = $qry_category->title;
            $this->data_content['view_description'] = $qry_category->description;
            $view_title = $qry_category->title;

            // Configurações da view estilo mosaico:
            //--------------------------------------------------------------------------------------
            $this->wpn_posts_view = strtolower($qry_category->view);
            $this->data_content['max_cols'] = $this->wpn_cols_mosaic;
        }

        // Seta as variáveis 'meta'.
        //------------------------------------------------------------------------------------------
        $this->wpanel->set_meta_title($view_title);

        // Exibe o wpn_template.
        //------------------------------------------------------------------------------------------
        $this->render('posts_' . $this->wpn_posts_view);
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método post() faz a exibição de uma postagem ou página que for
     * indicada pelo parametro $link.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @param $link String Link para exibição da postagem.
     * @param $use_id boolean Indica se busca o post pelo link ou pelo ID.
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function post($link = '', $use_id = false) 
    {

        // Verifica se foi informado um link.
        //------------------------------------------------------------------------------------------
        if ($link == '')
            show_404();

        // Prepara e envia os dados para a view.
        //------------------------------------------------------------------------------------------
        $this->load->model('post');
        if($use_id)
            $query = $this->post->get_by_id($link, null, null, 'id, title, description, content, link, image, tags, created, page, status')->row();
        else 
            $query = $this->post->get_by_field('link', $link, null, null, 'id, title, description, content, link, image, tags, created, page, status')->row();
        

        $this->data_content['post'] = $query;

        // Verifica a existência e disponibilidade do post.
        //------------------------------------------------------------------------------------------
        if (count($query) <= 0)
            show_404();

        if ($query->status == 0)
            show_error('Esta página foi suspensa temporariamente', 404);

        // Seta as variáveis 'meta'.
        //------------------------------------------------------------------------------------------
        $this->wpanel->set_meta_description($query->description);
        $this->wpanel->set_meta_keywords($query->tags);
        $this->wpanel->set_meta_title($query->title);

        if(file_exists('./media/capas/'.$query->image))
            $this->wpanel->set_meta_image(base_url('media/capas/'.$query->image));

        // Seleciona a view específica de cada tipo de post.
        //------------------------------------------------------------------------------------------
        switch ($query->page) {
            case '1':
                $this->render('page');
                break;
            case '2':
                $this->render('event');
                break;
            default:
                $this->render('post');
                break;
        }
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método events() faz uma listagem dos eventos disponíveis para
     * exibição no site.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function events() 
    {

        $view_title = 'Eventos';

        // Carrega os models necessários.
        //------------------------------------------------------------------------------------------
        $this->load->model('post');

        // Recupera a lista de eventos.
        //------------------------------------------------------------------------------------------
        $query = $this->post->get_by_field(
                ['page' => '2', 'status' => '1'], null, ['field' => 'created', 'order' => 'desc']
        )->result();

        // Seta as variáveis 'meta'.
        //------------------------------------------------------------------------------------------
        $this->wpanel->set_meta_title($view_title);
        $this->wpanel->set_meta_description('Lista de eventos');
        $this->wpanel->set_meta_keywords(' eventos, agenda');

        // Envia os dados para a view.
        //------------------------------------------------------------------------------------------
        $this->data_content['events'] = $query;

        // Exibe o wpn_template.
        //------------------------------------------------------------------------------------------
        $this->render('events');
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método search() realiza uma busca por termos indicados no formulário
     * no título, descrição e conteúdo das postagens independente do seu tipo.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function search() 
    {

        // Recebe os termos da busca.
        //------------------------------------------------------------------------------------------
        $search_terms = $this->input->post('search');

        // Carrega os models necessários.
        //------------------------------------------------------------------------------------------
        $this->load->model('post');

        // Envia os dados para a view.
        //------------------------------------------------------------------------------------------
        $this->data_content['search_terms'] = $search_terms;
        $this->data_content['results'] = $this->post->busca_posts($search_terms)->result();

        // Seta as variáveis 'meta'.
        //------------------------------------------------------------------------------------------
        $this->wpanel->set_meta_title('Resultados da busca por ' . $search_terms);

        // Exibe o wpn_template.
        //------------------------------------------------------------------------------------------
        $this->render('search');
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método albuns() faz uma listagem de albuns de foto disponíveis
     * para exibição no site.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function albuns() 
    {

        // Carrega os models necessários.
        //------------------------------------------------------------------------------------------
        $this->load->model('album');

        $query = $this->album->get_by_field(
            'status', 1, ['field'=>'created', 'order'=>'desc'], 'titulo, capa, created'
        )->result();

        // Seta as variáveis 'meta'.
        //------------------------------------------------------------------------------------------
        $this->wpanel->set_meta_description('Álbuns de fotos');
        $this->wpanel->set_meta_keywords(' album, fotos');
        $this->wpanel->set_meta_title('Álbuns de fotos');

        // Envia os dados para a view.
        //------------------------------------------------------------------------------------------
        $this->data_content['albuns'] = $query;
        $this->data_content['max_cols'] = $this->wpn_cols_mosaic;

        // Exibe o wpn_template.
        //------------------------------------------------------------------------------------------
        $this->render('albuns');
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método album() faz a exibição das fotos de um determinado álbum
     * indicado pelo parametro $album_id
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @param $album_id Int ID do álbum para exibição.
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function album($album_id = null) 
    {

        if ($album_id == null)
            show_404();

        // Carrega os models necessários.
        //------------------------------------------------------------------------------------------
        $this->load->model('album');
        $this->load->model('foto');

        // Recupera os detalhes do álbum.
        //------------------------------------------------------------------------------------------
        $query_album = $this->album->get_by_id(
            $album_id, 
            null, 
            null, 
            'id, titulo, descricao, capa, created, status'
        )->row();

        if (count($query_album) <= 0)
            show_404();

        if ($query_album->status == 0)
            show_error('Este álbum foi suspenso temporariamente', 404);

        $query_pictures = $this->foto->get_by_field(
            ['album_id'=>$album_id, 'status'=>1], 
            null, 
            ['field' => 'created', 'order' => 'desc'],
            null,
            'id, filename, descricao'
        )->result();

        // Seta as variáveis 'meta'.
        //------------------------------------------------------------------------------------------
        $this->wpanel->set_meta_description($query_album->descricao);
        $this->wpanel->set_meta_keywords(' album, fotos');
        $this->wpanel->set_meta_title($query_album->titulo);
        if(file_exists('./media/capas/'.$query_album->capa))
            $this->wpanel->set_meta_image(base_url('media/capas'.'/'.$query_album->capa));

        // Envia os dados para a view.
        //------------------------------------------------------------------------------------------
        $this->data_content['album']    = $query_album;
        $this->data_content['pictures'] = $query_pictures;
        $this->data_content['max_cols'] = $this->wpn_cols_mosaic;

        // Exibe o wpn_template.
        //------------------------------------------------------------------------------------------
        $this->render('album');
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O metodo foto() faz a exibição de uma foto de algum ámbum, indicada
     * pelo parâmmetro $query_picture_id
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @param $picture_id Int ID da foto para exibição.
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function foto($picture_id = null)
    {

        if ($picture_id == null)
            show_404();

        // Carrega os models necessários.
        //------------------------------------------------------------------------------------------
        $this->load->model('album');
        $this->load->model('foto');

        // Recupera os detalhes da foto.
        //------------------------------------------------------------------------------------------
        $query_picture = $this->foto->get_by_id($picture_id, null, null, 'id, album_id, filename, descricao, status')->row();

        $query_album = $this->album->get_by_id(
            $query_picture->album_id, 
            null, 
            null, 
            'id, titulo, descricao, capa, created, status'
        )->row();

        if (count($query_picture) <= 0)
            show_404();

        if ($query_picture->status == 0)
            show_error('Esta foto foi suspensa temporariamente', 404);

        // Seta as variáveis 'meta'.
        //------------------------------------------------------------------------------------------
        $this->wpanel->set_meta_description($query_picture->descricao);
        $this->wpanel->set_meta_keywords('album, fotos');
        $this->wpanel->set_meta_title($query_picture->descricao);
        if(file_exists('./media/albuns/'.$query_picture->album_id.'/'.$query_picture->filename))
            $this->wpanel->set_meta_image(base_url('media/albuns/'.$query_picture->album_id.'/'.$query_picture->filename));

        // Envia os dados para a view.
        //------------------------------------------------------------------------------------------
        $this->data_content['album']    = $query_album;
        $this->data_content['picture']  = $query_picture;

        // Exibe o wpn_template.
        //------------------------------------------------------------------------------------------
        $this->render('foto');
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método videos() faz a exibição da lista de vídeos de um canal do
     * Youtube(®) pelo método de RSS.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function videos() 
    {

        // Recupera a lista de vídeos.
        //------------------------------------------------------------------------------------------
        $this->load->model('video');
        $query_videos = $this->video->get_by_field(
            'status', 
            1, 
            ['field' => 'created', 'order' => 'desc'],
            null,
            'link, titulo'
        )->result();

        // Seta as variáveis 'meta'.
        //------------------------------------------------------------------------------------------
        $this->wpanel->set_meta_description('Lista de vídeos');
        $this->wpanel->set_meta_keywords('videos, filmes');
        $this->wpanel->set_meta_title('Vídeos');

        // Envia os dados para a view.
        //------------------------------------------------------------------------------------------
        $this->data_content['videos']   = $query_videos;
        $this->data_content['max_cols'] = $this->wpn_cols_mosaic;

        // Exibe o wpn_template.
        //------------------------------------------------------------------------------------------
        $this->render('videos');
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método video() faz a exibição do vídeo indicado pelo parametro $code.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @param $code string Código do vídeo no youtube.
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function video($code = null) 
    {

        if ($code == null)
            show_404();

        $this->load->model('video');

        // Recupera os dados do vídeo.
        //------------------------------------------------------------------------------------------
        $query_video = $this->video->get_by_field(
            ['link'=>$code,'status'=>1], 
            null,
            null,
            null,
            'titulo, descricao, link, status'
        )->row();

        if (count($query_video) <= 0)
            show_404();

        if ($query_video->status == 0)
            show_error('Este vídeo foi suspenso temporariamente', 404);

        // Envia os dados para a view.
        //------------------------------------------------------------------------------------------
        $this->data_content['video'] = $query_video;

        // Seta as variáveis 'meta'.
        //------------------------------------------------------------------------------------------
        $this->wpanel->set_meta_description($query_video->titulo);
        $this->wpanel->set_meta_keywords('videos, filmes');
        $this->wpanel->set_meta_title($query_video->titulo);
        $this->wpanel->set_meta_image('http://img.youtube.com/vi/'.$code.'/0.jpg');

        // Exibe o wpn_template.
        //------------------------------------------------------------------------------------------
        $this->render('video');
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método contato() faz o funcionamento da página de contato do site.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function contato() 
    {

        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('captcha', 'Confirmação', 'required|captcha');
        $this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');

        if ($this->form_validation->run() == FALSE) {

            // Seta as variáveis 'meta'.
            //--------------------------------------------------------------------------------------
            $this->wpanel->set_meta_description('Formulário de contato');
            $this->wpanel->set_meta_keywords(' Contato, Fale Conosco');
            $this->wpanel->set_meta_title('Contato');

            // Recupera a imagem de captcha.
            //--------------------------------------------------------------------------------------
            $this->data_content['contact_content'] = $this->wpanel->get_config('texto_contato');
            $this->data_content['captcha'] = $this->form_validation->get_captcha();

            // Exibe o wpn_template.
            //--------------------------------------------------------------------------------------
            $this->render('contact');

        } else {

            $nome = $this->input->post('nome');
            $email = $this->input->post('email');
            $telefone = $this->input->post('telefone');
            $mensagem = $this->input->post('mensagem');

            $msg = "";
            $msg .= "Mensagem enviada pelo site.\n\n";
            $msg .= "Nome: $nome\n";
            $msg .= "Email: $email\n";
            $msg .= "Telefone: $telefone\n\n";
            $msg .= "Mensagem\n";
            $msg .= "------------------------------------------------------\n\n";
            $msg .= "$mensagem";
            $msg .= "\n\n";
            $msg .= "Enviado pelo WPanel CMS\n";

            $this->load->library('email');
            // Verifica se usa SMTP ou não
            if ($this->wpanel->get_config('usa_smtp') == 1) {
                $conf_email = array();
                $conf_email['protocol'] = 'smtp';
                $conf_email['smtp_host'] = $this->wpanel->get_config('smtp_servidor');
                $conf_email['smtp_port'] = $this->wpanel->get_config('smtp_porta');
                $conf_email['smtp_user'] = $this->wpanel->get_config('smtp_usuario');
                $conf_email['smtp_pass'] = $this->wpanel->get_config('smtp_senha');
                $this->email->initialize($conf_email);
                $this->email->from($this->wpanel->get_config('smtp_usuario'), 'Formulário de contato');
            } else {
                $this->email->from($email, $nome);
            }

            // Envia o email
            $this->email->to($this->wpanel->get_config('site_contato'));
            $this->email->subject('Mensagem do site - [' . $this->wpanel->get_titulo() . ']');
            $this->email->message($msg);

            if ($this->email->send()) {
                $this->session->set_flashdata('msg_contato', 'Sua mensagem foi enviada com sucesso!');
                redirect('contato');
            } else {
                $this->session->set_flashdata('msg_contato', 'Erro, sua mensagem não pode ser enviada, tente novamente mais tarde.');
                redirect('contato');
            }
        }
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método rss() gera a página padrão XML para os leitores de RSS
     * com as postagens disponíveis no site.
     *
     * @todo Criar o metodo de categorizar esta lista de feed.
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function rss()
    {

        $this->load->model('post');
        $query = $this->post->get_list(['field'=>'created', 'order'=>'desc'], null, '')->result();

        $available_languages = config_item('available_languages');
        $locale = $available_languages[wpn_config('language')]['locale'];

        $rss = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $rss .= "<rss version=\"2.0\">\n";
        $rss .= "\t<channel>\n";
        $rss .= "\t\t<title>" . wpn_config('site_titulo') . "</title>\n";
        $rss .= "\t\t<description>" . $this->wpanel->get_config('site_desc') . "</description>\n";
        $rss .= "\t\t<link>" . site_url() . "</link>\n";
        $rss .= "\t\t<language>".$locale."</language>\n";

        foreach ($query as $row) {
            $rss .= "\t\t<item>\n";
            $rss .= "\t\t\t<title>".$row->title."</title>\n";
            $rss .= "\t\t\t<description>".$row->description."</description>\n";
            $rss .= "\t\t\t<lastBuildDate>".$row->created."</lastBuildDate>\n";
            $rss .= "\t\t\t<link>" . site_url('post/' . $row->link) . "</link>\n";
            $rss .= "\t\t</item>\n";
        }

        $rss .= "\t</channel>\n</rss>\n";

        echo $rss;
    }

    /**
     * ---------------------------------------------------------------------------------------------
     * O método newsletter() faz o cadastro de um email para o coletor
     * de contatos do WPanel.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @return void
     * ---------------------------------------------------------------------------------------------
     */
    public function newsletter()
    {

        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[newsletter_email.email]');
        $this->form_validation->set_error_delimiters('<p><span class="label label-danger">', '</span></p>');

        if ($this->form_validation->run() == FALSE) {

            // Seta as variáveis 'meta'.
            //--------------------------------------------------------------------------------------
            $this->wpanel->set_meta_description('Newsletter');
            $this->wpanel->set_meta_keywords('Cadastro, Newsletter');
            $this->wpanel->set_meta_title('Newsletter');

            // Exibe o wpn_template.
            //--------------------------------------------------------------------------------------
            $this->render('newsletter');
            
        } else {
            $this->load->model('newsletter');
            $dados_save = array(
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'created' => date('Y-m-d H:i:s')
            );
            if ($this->newsletter->save($dados_save)) {
                $this->session->set_flashdata('msg_newsletter', 'Seus dados foram salvos com sucesso, obrigado!');
                redirect('newsletter');
            } else {
                $this->session->set_flashdata('msg_newsletter', 'Não foi possível salvar seus dados, verifique os erros e tente novamente.');
                redirect('newsletter');
            }
        }
    }
}