<?php

namespace Whiteki\SimpleApiResponse\Commands;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/repository.stub';
    }


    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $dummyModel = $this->getNameInput();
        $dummyModelNamespace = 'App\\Models\\' . $dummyModel;
        $temp = explode('\\', $dummyModelNamespace);
        $dummyModel = array_pop($temp);
        $stub = str_replace(
            ['DummyNamespace', 'DummyModelNamespace', 'DummyModel', 'DummyValue'],
            [$this->getNamespace($name), $dummyModelNamespace, $dummyModel, lcfirst($dummyModel)],
            $stub
        );
        return $this;
    }
}
