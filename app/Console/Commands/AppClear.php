<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpar caches';

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
        $commands[] = [
            'title' => 'Limpando cache de configurações',
            'command' => 'config:clear',
        ];

        $commands[] = [
            'title' => 'Limpando cache de rotas',
            'command' => 'route:clear',
        ];

        $commands[] = [
            'title' => 'Limpando cache de views',
            'command' => 'view:clear',
        ];

        $commands[] = [
            'title' => 'Optimize clear',
            'command' => 'optimize:clear',
        ];

        $commands[] = [
            'title' => 'Criando link de storage',
            'command' => 'storage:link',
        ];

        foreach($commands as $com) {
            $this->comment($com['title']);
            \Artisan::call($com['command']);
        }
        
        $this->comment('Concluído');
    }
}
