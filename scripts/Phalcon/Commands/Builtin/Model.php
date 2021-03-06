<?php

/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2016 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/

namespace Phalcon\Commands\Builtin;

use Phalcon\Text;
use Phalcon\Builder;
use Phalcon\Script\Color;
use Phalcon\Commands\Command;
use Phalcon\Builder\Model as ModelBuilder;

/**
 * Model Command
 *
 * Create a model from command line
 *
 * @package Phalcon\Commands\Builtin
 */
class Model extends Command
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getPossibleParams()
    {
        return array(
            'name=s'          => 'Table name',
            'schema=s'        => 'Name of the schema [optional]',
            'namespace=s'     => "Model's namespace [optional]",
            'get-set'         => 'Attributes will be protected and have setters/getters [optional]',
            'extends=s'       => 'Model extends the class name supplied [optional]',
            'excludefields=l' => 'Excludes fields defined in a comma separated list [optional]',
            'doc'             => 'Helps to improve code completion on IDEs [optional]',
            'directory=s'     => 'Base path on which project is located [optional]',
            'output=s'        => 'Folder where models are located [optional]',
            'force'           => 'Rewrite the model [optional]',
            'camelize'        => 'Properties is in camelCase [optional]',
            'trace'           => 'Shows the trace of the framework in case of exception [optional]',
            'mapcolumn'       => 'Get some code for map columns [optional]',
            'abstract'        => 'Abstract Model [optional]',
            'annotate'        => 'Annotate Attributes [optional]'
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param array $parameters
     * @return mixed
     */
    public function run(array $parameters)
    {
        $name = $this->getOption(array('name', 1));

        $className = Text::camelize(isset($parameters[1]) ? $parameters[1] : $name);
        $fileName = Text::uncamelize($className);

        $schema = $this->getOption('schema');

        $modelBuilder = new ModelBuilder(
            array(
                'name'              => $name,
                'schema'            => $schema,
                'className'         => $className,
                'fileName'          => $fileName,
                'genSettersGetters' => $this->isReceivedOption('get-set'),
                'genDocMethods'     => $this->isReceivedOption('doc'),
                'namespace'         => $this->getOption('namespace'),
                'directory'         => $this->getOption('directory'),
                'modelsDir'         => $this->getOption('output'),
                'extends'           => $this->getOption('extends'),
                'excludeFields'     => $this->getOption('excludefields'),
                'camelize'          => $this->isReceivedOption('camelize'),
                'force'             => $this->isReceivedOption('force'),
                'mapColumn'         => $this->isReceivedOption('mapcolumn'),
                'abstract'          => $this->isReceivedOption('abstract'),
                'annotate'          => $this->isReceivedOption('annotate')
            )
        );

        $modelBuilder->build();
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getCommands()
    {
        return array('model', 'create-model');
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function getHelp()
    {
        print Color::head('Help:') . PHP_EOL;
        print Color::colorize('  Creates a model') . PHP_EOL . PHP_EOL;

        print Color::head('Usage:') . PHP_EOL;
        print Color::colorize('  model [table-name] [options]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        print Color::head('Arguments:') . PHP_EOL;
        print Color::colorize('  help', Color::FG_GREEN);
        print Color::colorize("\tShows this help text") . PHP_EOL . PHP_EOL;

        $this->printParameters($this->getPossibleParams());
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     */
    public function getRequiredParams()
    {
        return 1;
    }
}
