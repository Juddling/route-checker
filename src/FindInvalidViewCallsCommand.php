<?php

namespace Juddling\RouteChecker;

use Juddling\RouteChecker\Commands\BaseCommand;

class FindInvalidViewCallsCommand extends BaseCommand
{
    protected $signature = 'runtime-errors:view-calls';
    protected $description = 'Checks your view calls to see if they map to a file that exists.';
    protected $routeCalls;

    public function __construct(FindInvalidViewCalls $views)
    {
        $this->views = $views;

        parent::__construct();
    }

    public function handle()
    {
        foreach ($this->getAllFilesInDir(getcwd(), 'php') as $file) {
            if (!$this->blacklisted($file)) {
                $this->views->findFunctionCalls($file);
            }
        }

        $this->views->renderTable($this->getOutput());
    }

    protected function blacklisted($file)
    {
        return strpos($file, 'vendor/')
            || strpos($file, 'storage/framework/views/');
    }
}
