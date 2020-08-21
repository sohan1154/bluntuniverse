<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Page;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use URL;

class PagesController extends Controller
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
            $title = 'Pages';
            $url = route('pages-index');
            
            $query = Page::query();
            $results = $query->paginate(config('siteconstants.PER_PAGE_LIMIT'));
            
            // on page load            
            return view('pages.index', compact('results', 'title', 'url'));

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return redirect()->route('dashboard')->with('error', $errorMsg);
        }
    }

    /**
     * Show the view of specified resource.
     * @return Response
     */
    public function view($id) {
        
        try {
            $title = 'Page:View';
            $sub_title = 'Page View';
            
            $rowInfo = Page::findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('pages-index')->with('error', 'Record not found');
            }

            return view('pages.view', compact('rowInfo', 'title', 'sub_title'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function add() {

        try {
            $title = 'Page:Add';
            $url = url('pages/create');
            $rowInfo = new Page;

            return view('pages.create', compact('rowInfo', 'url', 'title'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Create a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function create(Request $request) {
        
        try {
            $input = $request->all();
            
            $validator = validator::make($input, [
                'title' => 'required|max:255', 
                'page_key' => 'required|max:100',
                'description' => 'required',
                'meta_key' => 'nullable|max:255',
                'meta_description' => 'nullable',
                'status' => 'required',
            ]);
            
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }

            if($record = Page::create($input)) {

                return redirect()->route('pages-index')->with('success', 'Record Saved Successfully');
            } else {
                return redirect()->route('pages-index')->with('error', 'Error in record saving time please try again');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id) {
        
        try {
            $title = 'Page:Edit';
            $url = url('pages/update');

            $rowInfo = Page::findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('pages-index')->with('error', 'Record not found');
            }

            return view('pages.create', compact('rowInfo', 'url', 'title'));

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request) {

        try {
            $input = $request->all();
            $id = $input['id'];
            
            $record = Page::findOrFail($id);
            
            if (empty($record)) {
                return redirect()->route('pages-index')->with('error', 'Record not found');
            }
            
            $validator = validator::make($input, [
                'title' => 'required|max:255', 
                'page_key' => 'required|max:100',
                'description' => 'required',
                'meta_key' => 'nullable|max:255',
                'meta_description' => 'nullable',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }

            if($record->fill($input)->save()) {
                
                return redirect()->route('pages-index')->with('success', 'Record Updated Successfully');
            } else {
                return redirect()->route('pages-index')->with('error', 'Error in record update, Please try again.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete(Request $request) {
        try {

            $input = $request->all();
            $id = $input['id'];

            $record = Page::findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }
            
            if ($record->delete()) {

                $result = array(
                    'status' => 'success',
                    'message' => 'Record deleted sucessfully.',
                );
            } else {

                $result = array('status' => 'error', 'message' =>'Error at delete time please try agian.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $result = array('status' => 'error', 'message' => $errorMsg);
        }

        return $result;
    }

    /**
     * active/deactive the specified resource.
     * @return Response
     */
    public function status(Request $request) {
        
        try {

            $input = $request->all();
            $id = $input['id'];

            $record = Page::findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }

            $record->status = (empty($record->status)) ? true : false;

            if ($record->save()) {

                $status = ($record->status) ? 
                '<a href="javascript:;" class="btn btn-success btn-circle btn-sm" title="Disable"><i class="fas fa-check"></i></a>'
                : '<a href="javascript:;" class="btn btn-warning btn-circle btn-sm" title="Enable"><i class="fas fa-times"></i></a>';

                $result = array(
                    'status' => 'success',
                    'message' => 'Status updated successfully.',
                    'text' => $status
                );
            } else {

                $result = array('status' => 'error', 'message' => 'Error in status update please try again.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $result = array('status' => 'error', 'message' => $errorMsg);
        }

        return $result;
    }

}