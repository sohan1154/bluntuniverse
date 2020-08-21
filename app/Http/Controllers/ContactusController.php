<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use URL;
use DB;
use Image;
use Hash;
use App\User;
use App\Models\Contactus;

class ContactusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        
        try {
            $title = 'Contact us';
            $url = route('contactus-index');
            
            $results = self::search($request);

            // ajax search
            if ($request->ajax()) {                
                return view('contactus.partials.listing', compact('results'));
            }

            $imageTypes = getImageTypes();

            // on page load            
            return view('contactus.index', compact('results', 'title', 'url', 'imageTypes'));

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return redirect()->route('dashboard')->with('error', $errorMsg);
        }
    }

    /**
     * find records into database
     * @param object $request
     * @return row
     */
    public static function search($request = null)
    {

        $query = Contactus::query();

        // ajax search
        if (!empty($request->search)) {
            $query->where(function ($subquery) use ($request) {
                $subquery->where('name', 'like', "%$request->search%");
                $subquery->orWhere('email', 'like', "%$request->search%");
                $subquery->orWhere('mobile', 'like', "%$request->search%");
                $subquery->orWhere('subject', 'like', "%$request->search%");
            });
        }

        $sort  = 'id';
        $order = 'DESC';
        if ((isset($request->sort) && $request->sort != '') || (isset($request->order)
            && $request->order != '')) {
            $sort  = $request->sort;
            $order = $request->order;
        }
        $query->orderBy($sort, $order);

        // on page load
        if(isset($request->export)) {
            $results =  $query->get();
        } else {
            $results = $query->paginate(config('siteconstants.PER_PAGE_LIMIT'));
        }

        return $results;
    }

    /**
     * Show the view of specified resource.
     * @return Response
     */
    public function view($id) {
        
        try {
            $title = 'Contact us:View';
            $sub_title = 'Contact us View';
            
            $rowInfo = Contactus::findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('contactus-index')->with('error', 'Record not found');
            }

            return view('contactus.view', compact('rowInfo', 'title', 'sub_title'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }
	
}
