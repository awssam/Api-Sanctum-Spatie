@extends('eav::layouts.master')

@section('content')

<h1>
        $products = Product::with('comments')->withAttributes('title','size','meta-title')->having('meta-title','LIKE','meta-title5%')->having('size',">",'12')->get(); // return an std class without content
	
</h1>
results:
<p>
{{$products}}
</p>    
    <style type="text/css">
    	p {
    		font-size: .7rem;
    margin: 0;
    max-width: 900px
;
    background: #d6d6d6;
    padding: 9px
;
    overflow: auto;
    max-height: 210px
;
}

    </style>
@endsection
