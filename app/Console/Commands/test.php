<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\User;


class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:eav';

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
     * @return int
     */
    public function handle()
    {
        // return 0;

        // todo
        // check if attribute already created then add a new one
        // check if attribute not existed in other column otherwise duplicated attribute
        // get attribute type
        // User::createAttribute('title','varchar');
        
        // Product::createAttribute('title','varchar');
        // Product::createAttribute('meta-title','varchar');
        // Product::createAttribute('meta-description','varchar');
        // Product::createAttribute('description','text');
        // Product::createAttribute('size','integer');
        // Product::createAttribute('price','decimal');

        $i = 0;

        // for ($i=10; $i < 1000 ; $i++) { 
        //     Product::create([
        //         'name' => 'name'.$i,
        //         'title' => 'title'.$i,
        //         'meta-title' => 'meta-title'.$i,
        //         'meta-description' => 'meta-description'.$i,
        //         'description' => 'description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' description'.$i.' ',
        //         'size' => 12+$i,
        //         'price' => 12.90 + $i,
        //     ]);
        // }

            // $product = Product::find(1);
            // $product = Product::withAttributes(['title','meta-title','description'])->find(1);
            // $product = Product::whereAttribute('id','=',3)->withAttributes()->orderByAttributeDesc('size')->get();
            // $product = Product::whereAttribute('size','=',14)->withAttributes()->get();
            $product = Product::whereAttribute('size','<',19)->orderBy('id','desc')->get();
            // dd($product->joins);
            // $product = Product::find(1);
            // $product->description = 'dsds sd s s sd';
            // $product->update();
            dd($product->toArray());
        
    }
}
