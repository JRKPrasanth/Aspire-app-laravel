<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Trigger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sl:trigger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $data=array();
        
  \Mail::send('purchaseorder.welcome',$data, function($message)  
        {
        
$to="isacnaveen@gmail.com";
               
        $message->to($to);
        

            $message->from('Saipavan9010@gmail.com');
                        
        });
    }
}
