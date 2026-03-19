<?php

namespace Modules\Theme\Http\Controllers;



use App\Models\Category;
use App\Models\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Image;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Promotion;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\ServiceCategory;



class FrontendController extends Controller
{
    public function __construct()
    {
        $mainMenus = $this->menu(1);
        $settings = Setting::allConfigsKeyValue();
        $logo = Setting::where('key', 'company_logo_frontend')->value('value');
        $company_phone = Setting::where('key', 'company_phone')->value('value');
        $service_categories = ServiceCategory::active()
            ->orderBy('order', 'ASC')
            ->with('services')
            ->get();
        $post_categories = PostCategory::root()->active()
            ->with(['children' => function ($q) {
                $q->active()->orderBy('order');
            }])
            ->orderBy('order')
            ->get();
        $images_about_us = Image::whereHas('category', function ($q) {
            $q->where('slug', 've-chung-toi');
        })
            ->orderBy('order')
            ->get();
        $images_featured_service =  Image::whereHas('category', function ($q) {
            $q->where('slug', 'dich-vu-noi-bat');
        })
            ->orderBy('order')
            ->get();

        $images_team_photo =  Image::whereHas('category', function ($q) {
            $q->where('slug', 'doi-ngu');
        })
            ->orderBy('order')
            ->get();
        $branches = Branch::where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        $fb_link = Setting::where('key' , 'follow_facebook')->value('value');
        \View::share([
            'mainMenus' => $mainMenus,
            'settings' => $settings,
            'logo' => $logo,
            'service_categories' => $service_categories,
            'post_categories' => $post_categories,
            'company_phone' => $company_phone,
            'images_about_us' => $images_about_us,
            'images_featured_service' => $images_featured_service,
            'images_team_photo' => $images_team_photo,
            'branches' => $branches,
            'fb_link' => $fb_link,
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
            ->whereHas('category', function ($q) {
                $q->where('slug', 'khoanh-khac-khach-hang')
                    ->where('is_active', 1);
            })
            ->orderBy('order', 'DESC')
            ->get(['image']);

        $customer_google_review_images = Image::with([
            'category' => function ($q) {
                $q->where('is_active', 1);
            }
        ])
            ->where('is_active', 1)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'google-review')
                    ->where('is_active', 1);
            })
            ->orderBy('order', 'DESC')
            ->get(['image']);


        $now = now();

        $promotion_images = Promotion::where('is_active', 1)
            ->where('is_visible', 1)
            ->whereNotNull('image')
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            })
            ->orderBy('created_at', 'DESC')
            ->get(['image', 'title']);
        $posts = Post::published()->orderBy('published_at', 'DESC')->take(5)->get();


        return view('theme::front-end.pages.home', compact('slides', 'customer_review_images', 'promotion_images', 'posts', 'customer_google_review_images'));
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
            ->get(['image']);
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

    public function renderService($service_category_slug, $slug, Request $request)
    {
        $service = Service::active()->where('slug', $slug)->first();
        $services = Service::active()->where('service_category_id', $service->service_category_id)->where('id', '!=', $service->id)->orderBy('created_at', 'DESC')->get();
        $service_category = ServiceCategory::active()->where('slug', $service_category_slug)->first();
        $branches = Branch::active()->get();
        return view('theme::front-end.pages.services.detail', compact('service', 'services', 'service_category', 'branches'));
    }

    public function showBookingPage(Request $request)
    {
        $branches_available = Branch::where('is_active', 1)->get();
        $room_types_available = RoomType::active()->with('branches')->get();
        $serviceCategories = ServiceCategory::active()->orderBy('order')->get();

        return view('theme::front-end.pages.booking', compact('branches_available', 'room_types_available', 'serviceCategories'));
    }

    // public function getListParents(Request $request, $slugParent)
    // {
    //     switch ($slugParent) {
    //         case 'tin-tuc':
    //             $events = 
    //             return view('theme::front-end.pages.event', compact('events', 'new_posts', 'post_category'));

    //         case 'uu-dai':

    //             return view('theme::front-end.pages.event', compact('events', 'new_posts', 'post_category'));
    //         default:
    //             return view("theme::front-end.404", compact('slugParent'));
    //     }
    // }
    public function getListParents(Request $request, $slugParent)
    {
        switch ($slugParent) {

            case 'tin-tuc':
            case 'uu-dai':

                $post_category = PostCategory::with('children')
                    ->active()
                    ->where('slug', $slugParent)
                    ->firstOrFail();

                $categoryIds = collect([$post_category->id])
                    ->merge($post_category->children->pluck('id'))
                    ->toArray();

                $events = Post::with('category')
                    ->published()
                    ->whereIn('post_category_id', $categoryIds)
                    ->orderByDesc('published_at')
                    ->paginate(10);

                $new_posts = Post::published()
                    ->latest()
                    ->take(5)
                    ->get();

                return view(
                    'theme::front-end.pages.event',
                    compact('events', 'new_posts', 'post_category')
                );

            default:
                return view("theme::front-end.404", compact('slugParent'));
        }
    }

    public function getDetail($slugParent, $slugDetail, Request $request)
    {
        $post_category = PostCategory::active()
            ->where('slug', $slugDetail)
            ->first();

        $posts = collect();

        if ($post_category) {
            $posts = Post::published()
                ->where('post_category_id', $post_category->id)
                ->orderBy('published_at', 'DESC')
                ->get();
        }

        switch ($slugParent) {
            case "dich-vu":
                // dd($post_category, $posts);
                $service_category = ServiceCategory::active()->where('slug', $slugDetail)->first();
                if (!$service_category) {
                    return view("theme::front-end.404", compact('slugParent', 'slugDetail'));
                }
                $services = Service::active()->where('service_category_id', $service_category->id)->get();

                return view("theme::front-end.pages.services.page_list", compact('service_category', 'services'));
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

    public function getChildDetail($slugParent, $slugDetail, $slugChild, Request $request)
    {
        switch ($slugParent) {
            case "dich-vu":
                $service = Service::active()->where('slug', $slugChild)->first();
                return view("theme::front-end.pages.services.detail", compact('service'));
            default:
                return view("theme::front-end.404", compact('slugParent', 'slugDetail', 'slugChild'));
        }
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
