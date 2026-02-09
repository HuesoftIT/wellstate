<?php

namespace Modules\Theme\Http\Controllers;



use App\Models\Category;
use App\Models\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Image;
use App\Models\News;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Promotion;
use App\Models\RoomType;
use App\Models\ServiceCategory;
use App\Models\Slide;
use Modules\Theme\Entities\Menu;


class FrontendController extends Controller
{
    public function __construct()
    {
        $mainMenus = $this->menu(1);
        $settings = Setting::allConfigsKeyValue();
        $logo = Setting::where('key', 'company_logo')->first()->value;
        $service_categories = ServiceCategory::active()
            ->orderBy('order', 'ASC')
            ->with('services')
            ->get();
        $post_categories = PostCategory::root()
            ->with(['children' => function ($q) {
                $q->active()->orderBy('order');
            }])
            ->orderBy('order')
            ->get();
        $company_phone = Setting::where('key', 'company_phone')->first()->value;
        $branches = Branch::active()->get();
        \View::share([
            'mainMenus' => $mainMenus,
            'settings' => $settings,
            'logo' => $logo,
            'service_categories' => $service_categories,
            'post_categories' => $post_categories,
            'company_phone' => $company_phone,
        ]);
    }


    public function index()
    {
        $slides = Image::with([
            'category' => function ($q) {
                $q->where('is_active', 1);
            }
        ])
            ->where('is_active', 1)
            ->where('image_category_id', 1)
            ->whereHas('category', function ($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('order', 'ASC')
            ->get();



        $customer_review_images = Image::with([
            'category' => function ($q) {
                $q->where('is_active', 1);
            }
        ])
            ->where('is_active', 1)
            ->where('image_category_id', 2)
            ->whereHas('category', function ($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('order', 'DESC')
            ->get(['image', 'title']);

        $customer_google_review_images = Image::with([
            'category' => function ($q) {
                $q->where('is_active', 1);
            }
        ])
            ->where('is_active', 1)
            ->where('image_category_id', 3)
            ->whereHas('category', function ($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('order', 'DESC')
            ->get(['image', 'title']);



        $promotion_images = Promotion::where('is_active', 1)->whereNotNull('image')->orderBy('created_at', 'DESC')->take(5)->get('image', 'name');
        $branches = Branch::where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        $posts = Post::published()->orderBy('published_at', 'DESC')->take(5)->get();


        return view('theme::front-end.pages.home', compact('slides', 'customer_review_images', 'promotion_images', 'branches', 'posts', 'customer_google_review_images'));
    }

    public function getIntroduce(Request $request)
    {
        $culture_images = Image::with([
            'category' => function ($q) {
                $q->where('is_active', 1);
            }
        ])
            ->where('is_active', 1)
            ->where('image_category_id', 5)
            ->whereHas('category', function ($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('order', 'DESC')
            ->get(['image', 'title']);
        return view('theme::front-end.pages.introduce', compact('culture_images'));
    }

    public function listServices(Request $request)
    {
        $service_categories = ServiceCategory::active()
            ->orderBy('order', 'ASC')
            ->with('services')
            ->get();
        return view('theme::front-end.pages.service', compact('service_categories'));
    }

    public function serviceDetail(Request $request, $slug) {}

    public function renderPromotionList(Request $request, $slug)
    {
        if ($slug === 'chuong-trinh-uu-dai') {
            $type = 'promotion';
        } else if ($slug === 'chuong-trinh-thanh-vien') {
            $type = 'membership';
        }
        $promotions = Promotion::where('is_active', 1)->where('type', $type)->get();
        return view('theme::front-end.pages.promotion', compact('promotions'));
    }

    public function contact(Request $request)
    {
        $branches = Branch::where('is_active', 1)->orderBy('created_at', 'DESC')->get();

        return view('theme::front-end.pages.contact', compact('branches'));
    }

    public function menu($position = 1)
    {
        $menus = Category::with('parent')->whereNull('parent_id')->orderBy('arrange', 'ASC')->select(['id', 'name', 'slug', 'parent_id'])->get();
        return $menus;
    }


    public function getDetailPost($slug, Request $request)
    {
        $data = Post::where('slug', $slug)->where('is_active', 1)->first();
        if (!$data) {
            $data = Promotion::where('discount_code', $slug)->where('is_active', 1)->first();
        }

        return view('theme::front-end.pages.posts.detail', compact('data'));
    }

    public function renderService(Request $request, $slug)
    {
        $post = Post::published()->where('slug', $slug)->first();
        $posts = Post::published()->where('post_category_id', $post->post_category_id)->where('id', '!=', $post->id)->orderBy('published_at', 'DESC')->get();
        $post_category = PostCategory::active()->where('id', $post->post_category_id)->first();
        $branches = Branch::active()->get();
        return view('theme::front-end.pages.services.detail', compact('post', 'posts', 'post_category', 'branches'));
    }

    public function showBookingPage(Request $request)
    {
        $branches_available = Branch::where('is_active', 1)->get();
        $room_types_available = RoomType::active()->with('branches')->get();
        $serviceCategories = ServiceCategory::active()->orderBy('order')->get();

        return view('theme::front-end.pages.booking', compact('branches_available', 'room_types_available', 'serviceCategories'));
    }

    public function getListParents(Request $request, $slugParent)
    {
        dd($slugParent);
        switch ($slugParent) {
            default:
                dd('Xin chào');
                return view("theme::front-end.404", compact('slugParent'));
        }
    }


    public function getDetail($slugParent, $slugDetail, Request $request)
    {
        $post_category = PostCategory::active()->where('slug', $slugDetail)->first();
        $posts = Post::published()->where('post_category_id', $post_category->id)->orderBy('published_at', 'DESC')->get();
        switch ($slugParent) {
            case "dich-vu":
                // dd($post_category, $posts);
                return view("theme::front-end.pages.services.page_list", compact('post_category', 'posts'));
            case "uu-dai":
                $promotions = $posts;

                $new_posts = Post::published()->orderBy('published_at', 'DESC')->take(5)->get();
                return view('theme::front-end.pages.promotion', compact('promotions', 'new_posts', 'post_category'));
            case 'tin-tuc':
                $events = $posts;
                $new_posts = Post::published()->orderBy('published_at', 'DESC')->take(5)->get();

                return view('theme::front-end.pages.event', compact('events', 'new_posts', 'post_category'));

            default:
                return view("theme::front-end.404", compact('slugParent', 'slugDetail'));
        }


        // switch ($slugParent) {
        //     case "tin-tuc":
        //         $news = News::with(['category'])->where(['active' => config('settings.active'), ['slug', $slugDetail]])->first();
        //         $otherNews = News::with('category')->where([['active', '=', config('settings.active')], ['id', '<>', $news->id]])->orderByDesc('created_at')->take(3)->get();
        //         return view("theme::front-end.news.detail", compact('news', 'otherNews'));
        //     default:
        //         return view("theme::front-end.404", compact('slugParent', 'slugDetail'));
        // }
    }

    public function getPage($slug, Request $request)
    {
        switch ($slug) {
            case "dang-ky":
                return view('theme::front-end.pages.register');
            default:
                return view('theme::front-end.pages.page', compact('page', 'menu'));
        }
    }
}
