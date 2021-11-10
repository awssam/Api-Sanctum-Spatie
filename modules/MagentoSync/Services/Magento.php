<?php 
namespace Modules\MagentoSync\Services;

use Illuminate\Support\Facades\Http;
use Modules\MagentoSync\Models\MagentoConnect;


class Magento
{
	
	const API_CLIENT = 'https://www.1001couches.com/rest/';


	static function getToken($account_type = 'admin')
	{
		$user = MagentoConnect::where('account_type',$account_type)->first();
		if(!$user) throw new \Exception("No account with type \"".$account_type.'" found', 1);
		if(!empty($user->token)) return $user->token;
		$request = Http::post(self::API_CLIENT.'V1/integration/admin/token',['username' => $user->user,'password' => $user->pass]);
		if($request->status() == 200){
			$user->update(['token' => trim($request->body(),'"')]);
			return $request->body();
		}
	}



	public function getProducts()
	{
		return self::getProductBy('sku','SPAMP04ABD0%');
	}


	public function getProductBy($field,$value,$condition_type = 'like')
	{
		$token = self::getToken('admin');
		$request = Http::withHeaders([
		    'Authorization' => 'Bearer '.$token
		])->get(self::API_CLIENT.'allocouches_fr/V1/products',
			[
				'searchCriteria[filterGroups][0][filters][0][field]' => $field,
				'searchCriteria[filterGroups][0][filters][0][condition_type]'=>$condition_type,
				'searchCriteria[filterGroups][0][filters][0][value]' =>$value
		]);

		if ($request->status() == 200) {
		    dd(json_decode($request->body()));
		}
	}

	public function getAllProducts(array $fields = ['name','sku'],$pageSize = 1)
	{	
		$params = [];
		if($fields){
			$fields = implode(',', $fields);
			$params['fields'] = 'items[id,'.$fields.'],total_count,search_criteria';
		}
		$params['searchCriteria[pageSize]'] = $pageSize;
		$token = self::getToken('admin');
		$request = Http::withHeaders([
		    'Authorization' => 'Bearer '.$token
		])->get(self::API_CLIENT.'allocouches_fr/V1/products',$params);
		// dd($request->body());
		if ($request->status() == 200) {
		    dd(json_decode($request->body()));
		}
	}
}

