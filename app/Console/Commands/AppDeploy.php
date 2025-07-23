<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppDeploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy de aplicação';

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
     * @return int
     */
    public function handle()
    {
        $this->comment('Iniciando deploy');
        
        $commands[] = [
            'title' => 'Limpando cache de configurações:',
            'command' => 'config:clear',
        ];

        $commands[] = [
            'title' => 'Migrando banco de dados:',
            'command' => 'app:migrate',
        ];

        foreach($commands as $com) {
            $this->comment($com['title']);
            $this->call($com['command']);
        }
    }
}
