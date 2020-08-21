<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;
use App\User;
use App\Models\Page;
use Validator;
use URL;
use DB;
use Helper;

class HomeController extends Controller
{

	public function pages(Request $request){

		try {
			$input = $request->all();

			$page_slug = $input['page_slug'];

			$page = Page::where('page_key', $page_slug)->first();

			return ['status' => true, 'message' => "", 'data' => $page];

		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => $errorMsg];
		}
	}	
	
	
}