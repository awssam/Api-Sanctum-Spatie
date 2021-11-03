<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;


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
        
        // Product::createAttribute('short-description','varchar');
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


            // test 1
            // $product = Product::where('size','<',18)->withAttributes(['title','size','meta-title','description'])->get(); // working good

            // test 2 
            // $product = Product::withAttributes(['title','size','meta-title','description'])->where('products.id','=',1)->first(); // working good

            // test 3
            // $product = Product::with('comments')->where('products.id','>',3)->withAttributes(['title','size','meta-title','description'])->having('size','>',19)->first(); // working good
            // dump(get_class($product));


            // test 4
            // $product = Product::with('comments')->first()->toArray(); // working good

            // test 5
            // $product = Product::with('comments')->having('size','=',12)->withAttributes(['short-description','title','size','meta-title','description'])->first(); // return an std class without content
            // $product = Product::with('comments')->withAttributes('short-description','title','size','meta-title','description')->having('size','=',12)->first(); // return an std class without content
            $product = Product::with('comments')->withAttributes()->having('size','=',12)->first(); // return an std class without content

            // test6
            // $product = Product::where('products.id','=',1)->withAttributes(['title','size','meta-title','description'])->first(); // return an std class without content
            // $product = Product::with('comments')->where('products.id','=',1)->first(); // working fine

            // test 7
            // $product = Product::where('size',12)->withAttributes(['title','size','meta-title','description'])->first();
            // $product->title = 'somt';
            // $product->save();
            // dump(get_class($product));
            dump($product);

            die;
            // $product = Product::where('id','=',1)->withAttributes(['title','size','meta-title','description'])->get();

            // $product = Product::find(1);
            // $product = Product::withAttributes(['title','meta-title','description'])->find(1);
            // $product = Product::whereAttribute('id','=',3)->withAttributes()->orderByAttributeDesc('size')->get();
            // $product = Product::whereAttribute('size','=',14)->withAttributes()->get();
            $product = Product::
            // where('size','<',19)
            // find(1)
            where('id','=',1)
            ->withAttributes(['title','size','meta-title','description'])
            // ->whereNotNull('title')
            // ->orWhere('size','<',18)
            // ->withAttributes(['title','size'])
            // ->wherHas('size','<',12)
            // ->orderBy('id','asc');

            ;
            // dd($product->joins);
            // $product = Product::find(1);
            // $product->description = 'dsds sd s s sd';
            // $product->update();
            // dd($product->toSql());
            // $sql_with_bindings = str_replace_array('?', $product->getBindings(), $product->toSql());

// dd($product->getBindings());
// $queryWithParam = Str::replaceArray('?', $product->getBindings(), $product->toSql());

// $queryWithParam = str_replace_array('?',$product->getBindings(),$product->toSql());
// dd($queryWithParam);

            // $query = str_replace(array('?'), array('\'%s\''), $product->toSql());
            // $query = vsprintf($query, $product->getBindings());
            // dd($product->toSql());
            dd($product->get());
        
    }
}

// SELECT
//     `products`.*,
//     `attribute_varchars`.`v_0` AS `title`,
//     `attribute_integers`.`v_0` AS `size`
// FROM
//     `products`
// LEFT JOIN `attribute_varchars` ON (`products`.`id` = `attribute_varchars`.`entity_id` AND `attribute_varchars`.`attributable_model_id` = 1)
// LEFT JOIN `attribute_integers` ON (`products`.`id` = `attribute_integers`.`entity_id` AND `attribute_integers`.`attributable_model_id` = 1 ) AND `attribute_integers`.`v_0` < 19
// ORDER BY
//     `attribute_integers`.`v_0`
// DESC