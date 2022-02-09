<?php /** @noinspection PhpUnused */
/** @noinspection PhpUnused */

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Migration_Removeevents class.
 *
 * This class remove events from modules.
 *
 * @property CI_DB_query_builder $db
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 */
class Migration_Removeevents extends CI_Migration
{

    /**
     * Execute migration.
     */
    public function up()
    {
        $this->deleteModuleActions();
        $this->deleteModule();
    }

    /**
     * Rollback migration.
     */
    public function down()
    {
        /**
         * Module data.
         */
        $modules_data = array(
            'author_name' => 'Wpanel Core Team',
            'author_email' => 'wpanel@wpanel.org',
            'author_website' => 'https://wpanel.org',
            'version' => '0.0.1',
            'status' => 1,
            'name_id' => 'eventos',
            'name' => 'Eventos',
            'description' => 'Gerencaidor de eventos',
            'icon' => '',
            'show_in_menu' => '1',
            'order' => '0',
        );
        $this->db->insert('modules', $modules_data);
        $module_id = $this->db->insert_id();
        /**
         * Module itens data.
         */
        $moduleactions_data = array(
            array(
                'module_id' => $module_id,
                'description' => 'Listar eventos',
                'link' => 'admin/events',
                'whitelist' => '0'
            ),
            array(
                'module_id' => $module_id,
                'description' => 'Adicionar evento',
                'link' => 'admin/events/add',
                'whitelist' => '0'
            ),
            array(
                'module_id' => $module_id,
                'description' => 'Alterar evento',
                'link' => 'admin/events/edit/*',
                'whitelist' => '0'
            ),
            array(
                'module_id' => $module_id,
                'description' => 'Excluir evento',
                'link' => 'admin/events/delete/*',
                'whitelist' => '0'
            )
        );
        $this->db->insert_batch('modules_actions', $moduleactions_data);
    }

    /**
     * Get module id.
     * @return int
     */
    private function getModuleId()
    {
        $this->db->where('name_id', 'eventos');
        $module = $this->db->get('modules')->row();
        return $module->id;
    }

    /**
     * Delete module data.
     * @return void
     */
    private function deleteModule()
    {
        $this->db->where('id', $this->getModuleId());
        $this->db->delete('modules');
    }

    /**
     * Delete module actions
     * @return void
     */
    private function deleteModuleActions()
    {
        $this->db->where('module_id', $this->getModuleId());
        $this->db->delete('modules_actions');
    }

}