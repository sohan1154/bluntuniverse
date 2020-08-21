<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Page;
use App\Models\Category;
use App\Models\BreakingNews;
use App\Models\News;

class WebsiteController extends Controller
{
    private $categories = [];
    private $breakingNews = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');

        $this->categories = Category::where('status', 1)->pluck('name', 'slug');
        $results = BreakingNews::where('status', 1)->pluck('title', 'id');
        foreach($results as $key=>$value) {
            $this->breakingNews .= $value . ' | ';
        }
    }

    public function news(Request $request)
    {
        try {
            $urlName = 'news';
            $categories = $this->categories;
            $breakingNews = $this->breakingNews;

            // ajax search
            if ($request->ajax()) {                
                
                $results = self::search($request);

                return $results;
            }

            return view('website.news', compact('categories', 'breakingNews', 'urlName'));
            
        } catch (\Exception $e) {
			return $errorMsg = $e->getMessage();
			// return abort('404', $errorMsg);
        }
    }

    public static function search($request = null)
    {
        $query = News::query();

        $query->where('status', 1)->where('is_verified', 1);

        // category filter
        if (!empty($request->slug)) {
            $category = Category::where('slug', $request->slug)->first();

            $query->where('category_id', $category->id);
        }

        // search
        if (!empty($request->search)) {
            $query->where(function ($subquery) use ($request) {
                $subquery->where('title', 'like', "%$request->search%");
                $subquery->orWhere('slug', 'like', "%$request->search%");
                $subquery->orWhere('author', 'like', "%$request->search%");
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

        $results = $query->paginate(config('siteconstants.WEBSITE_PER_PAGE_LIMIT'));
        // dd($results);

        $formatedResults = [];
        foreach($results->getCollection() as $key => $value) {
            $formatedResults[$key] = [
                'id' => $value->id,
                'title' => getLimitString($value->title, 50),
                'slug' => $value->slug,
                'description' => getLimitString($value->description, 100),
                'image' => getImage($value->image, 'news/'.$value->id.'/medium')
            ];
        }

        $results->setCollection(collect($formatedResults));

        return $results;
        // return $formatedResults;
        // return response()->json(['status'=>true,'message'=>'News listing','data'=>$formatedResults], 200);
    }

    public function newsDetail($slug)
    {
        try {
            $categories = $this->categories;
            $breakingNews = $this->breakingNews;

            $newsDetail = News::where('status', 1)->where('is_verified', 1)->where('slug', $slug)->first();

            if (empty($newsDetail)) {
                return redirect()->route('news-listing')->with('error', 'Record not found');
            }

            return view('website.news-detail', compact('newsDetail', 'categories', 'breakingNews'));
        } catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return redirect()->route('news-listing')->with('error', $errorMsg);
        }
    }

    public function contactUs()
    {
        try {
            $title = 'Contact Us';
            $categories = $this->categories;
            $breakingNews = $this->breakingNews;

			$pageInfo = Page::where('page_key', 'contact-us')->first();

			return view('website.contact-us', compact('pageInfo', 'title', 'categories', 'breakingNews'));

		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return abort('404', $errorMsg);
        }
    }

    public function aboutUs()
    {
        try {
            $title = 'About Us';
            $categories = $this->categories;
            $breakingNews = $this->breakingNews;

			$pageInfo = Page::where('page_key', 'about-us')->first();

			return view('website.about-us', compact('pageInfo', 'title', 'categories', 'breakingNews'));

		} catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            die($errorMsg);
			return abort('404', $errorMsg);
        }
    }

    public function termsAndConditions()
    {
        try {
            $title = 'Terms of Use';
            $categories = $this->categories;
            $breakingNews = $this->breakingNews;

			$pageInfo = Page::where('page_key', 'terms-of-use')->first();

			return view('website.terms-and-conditions', compact('pageInfo', 'title', 'categories', 'breakingNews'));

		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return abort('404', $errorMsg);
        }
    }

    public function privacyPolicy()
    {
        try {
            $title = 'Privecy Policy';
            $categories = $this->categories;
            $breakingNews = $this->breakingNews;

			$pageInfo = Page::where('page_key', 'privacy-policy')->first();

			return view('website.privacy-policy', compact('pageInfo', 'title', 'categories', 'breakingNews'));

		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return abort('404', $errorMsg);
        }
    }
    
}
