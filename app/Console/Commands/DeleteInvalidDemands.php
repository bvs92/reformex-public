<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteInvalidDemands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:invaliddemands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete demands marked as INVALID and where STATE is inactive.';

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
        $demands = \App\Demand::where('status', '2')->where('state', '0')->get();

        if($demands->count() > 0){
            foreach($demands as $demand){
                if($demand->winner && $demand->winner()->count() >= 1){
                    $demand->winner->delete();
                }
        
        
                
                if($demand->quotes && $demand->quotes->count() > 0){
                    // dd($demand->quotes);
                    // $demand->quotes()->delete();
                    foreach($demand->quotes as $quote){
        
                        if($quote->files && $quote->files->count() > 0){
                            foreach($quote->files as $file){
                                $pathToFile = public_path() . '/storage\/quotes\/' . $file->name;
                                if(file_exists($pathToFile)){
                                    unlink($pathToFile);
                                }
                
                                $file->delete();
                            }
                        }
        
                        $quote->delete();
                    }
                }
        
                if($demand->reports && $demand->reports()->count() > 0){
                    foreach($demand->reports as $report){
                        $report->delete();
                    }
                }

                if($demand->prospects && $demand->prospects()->count() > 0){
                    $demand->prospects()->delete();
                }



                $demand->delete();
            }
            // \App\Demand::where('status', '2')->where('state', '0')->delete();
        }
    }
}
