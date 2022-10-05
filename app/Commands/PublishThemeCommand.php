<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Publisher\Publisher;

class PublishThemeCommand extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'CodeIgniter';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'publish:theme';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Publish theme (css, js, other content) in public/assets';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'make:assets <name> [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'name' => 'Theme name will be publish on public/assets.',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        /**
         * 
         * List Theme Name
         * 
         */
        $themes = [
            'adminlte' => [
                'source' => ROOTPATH . 'vendor/almasaeed2010/adminlte/',
                'folders' => ['dist', 'plugins'],
            ],
            'sbadmin' => [
                'source' => ROOTPATH . 'vendor/sbadmin',
                'folders' => ['dist'],
            ],
            'mazer' => [
                'source' => ROOTPATH . 'vendor/mazer',
                'folders' => ['dist'],
            ],
        ];
        $listThemes = array_keys($themes);
        $name = (count($params) < 1) ? CLI::prompt('Select your desired theme', $listThemes, ['required', 'in_list[' . implode(',', $listThemes) . ']']) : $params[0];
        CLI::write("You choose {$name}", 'green');
        CLI::wait(1, false);

        /**
         * 
         * Make Assets Folder
         * 
         */
        CLI::write("Check assets path ...", 'green');
        CLI::wait(2, false);
        if (!is_dir(FCPATH . 'assets')) {

            CLI::write("Assets Folder Not Found", 'green');
            CLI::wait(2, false);

            CLI::write("Creating Assets Folder ...", 'green');
            CLI::wait(3, false);

            mkdir('assets');
            CLI::write("Assets Folder Created", 'green');
            CLI::wait(2, false);
        } else {

            CLI::write("Assets Folder Already Exist", 'green');
            CLI::wait(2, false);
        }

        /**
         * 
         * Make Theme Folder
         * 
         */
        CLI::write("Access assets path ...", 'green');
        CLI::wait(2, false);
        CLI::write("Check " . ucfirst($name) . " path ...", 'green');
        CLI::wait(2, false);
        if (!is_dir(FCPATH . 'assets' . DIRECTORY_SEPARATOR . $name)) {
            CLI::write(ucfirst($name) . " Folder Not Found", 'green');
            CLI::wait(2, false);

            CLI::write("Creating " . ucfirst($name) . " Folder ...", 'green');
            CLI::wait(3, false);

            mkdir('assets' . DIRECTORY_SEPARATOR . $name);
            CLI::write(ucfirst($name) . " Folder Created", 'green');
            CLI::wait(2, false);
        } else {

            CLI::write(ucfirst($name) . " Folder Already Exist", 'green');
            CLI::wait(2, false);
        }

        /**
         * 
         * Publish
         * 
         */
        helper('array');
        $source = dot_array_search($name . '.source', $themes);
        $folders = dot_array_search($name . '.folders', $themes);
        $destination = FCPATH . 'assets' . DIRECTORY_SEPARATOR . $name;
        $publisher = new Publisher($source, $destination);

        $process = $publisher->addPaths($folders);

        try {
            CLI::write('Progress ...', 'green');

            $process->merge(true);

            CLI::write('Completed', 'green');
        } catch (\Throwable $e) {
            $this->showError($e);

            return;
        }

        CLI::wait(10, false);
        CLI::clearScreen();
    }
}
