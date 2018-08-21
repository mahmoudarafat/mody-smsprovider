<?php

namespace mody\smsprovider\commands;

use Illuminate\Console\Command;
use mody\smsprovider\traits\DatabaseConfig;

class tableCommand extends Command
{
    use DatabaseConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smsprovider:tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create smsprovider tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $this->configProviderTable();
        $this->configAdditionalParams();
        $this->configMessagesTable();
        $this->configSavedMessages();
        $this->configTrackMessages();


    }
}
