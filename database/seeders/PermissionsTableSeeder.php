<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arPermissions = [
            "1" => ["HomeController@index", "Trang chủ"],

            "2" => ["SettingController", "Cấu hình công ty"],

            "3" => ["RolesController@index", "Quản lý Vai trò"],
            "4" => ["RolesController@show", "Quản lý Vai trò"],
            "5" => ["RolesController@store", "Quản lý Vai trò"],
            "6" => ["RolesController@update", "Quản lý Vai trò"],
            "7" => ["RolesController@destroy", "Quản lý Vai trò"],
            "8" => ["RolesController@active", "Quản lý Vai trò"],

            "9" => ["UsersController@index", "Quản lý nhân viên"],
            "10" => ["UsersController@show", "Quản lý nhân viên"],
            "11" => ["UsersController@store", "Quản lý nhân viên"],
            "12" => ["UsersController@update", "Quản lý nhân viên"],
            "13" => ["UsersController@destroy", "Quản lý nhân viên"],
            "14" => ["UsersController@active", "Quản lý nhân viên"],

            //Trường hợp cho phép người dùng sửa, thì cho phép sửa profile của người dùng đó
            "15" => ["UsersController@postProfile", "Quản lý nhân viên"],

            "16" => ["CategoryController@index", "Quản lý danh mục"],
            "17" => ["CategoryController@show", "Quản lý danh mục"],
            "18" => ["CategoryController@store", "Quản lý danh mục"],
            "19" => ["CategoryController@update", "Quản lý danh mục"],
            "20" => ["CategoryController@destroy", "Quản lý danh mục"],
            "21" => ["CategoryController@active", "Quản lý danh mục"],

            "22" => ["NewsController@index", "Quản lý tin tức"],
            "23" => ["NewsController@show", "Quản lý tin tức"],
            "24" => ["NewsController@store", "Quản lý tin tức"],
            "25" => ["NewsController@update", "Quản lý tin tức"],
            "26" => ["NewsController@destroy", "Quản lý tin tức"],
            "27" => ["NewsController@active", "Quản lý tin tức"],


            "28" => ["ProvinceController@index", "Quản lý tỉnh thành phố"],
            "29" => ["ProvinceController@show", "Quản lý tỉnh thành phố"],
            "30" => ["ProvinceController@store", "Quản lý tỉnh thành phố"],
            "31" => ["ProvinceController@update", "Quản lý tỉnh thành phố"],
            "32" => ["ProvinceController@destroy", "Quản lý tỉnh thành phố"],

            "33" => ["DistrictController@index", "Quản lý quận huyện"],
            "34" => ["DistrictController@show", "Quản lý quận huyện"],
            "35" => ["DistrictController@store", "Quản lý quận huyện"],
            "36" => ["DistrictController@update", "Quản lý quận huyện"],
            "37" => ["DistrictController@destroy", "Quản lý quận huyện"],

            "38" => ["WardController@index", "Quản lý phường xã"],
            "39" => ["WardController@show", "Quản lý phường xã"],
            "40" => ["WardController@store", "Quản lý phường xã"],
            "41" => ["WardController@update", "Quản lý phường xã"],
            "42" => ["WardController@destroy", "Quản lý phường xã"],

            // Wellbe controller below here
            "43" => ["ServiceController@index", "Quản lý dịch vụ"],
            "44" => ["ServiceController@show", "Quản lý dịch vụ"],
            "45" => ["ServiceController@store", "Quản lý dịch vụ"],
            "46" => ["ServiceController@update", "Quản lý dịch vụ"],
            "47" => ["ServiceController@destroy", "Quản lý dịch vụ"],
            "48" => ["ServiceController@active", "Quản lý dịch vụ"],

            "49" => ["ServiceCategoryController@show", "Quản lý loại dịch vụ"],
            "51" => ["ServiceCategoryController@store", "Quản lý loại dịch vụ"],
            "52" => ["ServiceCategoryController@update", "Quản lý loại dịch vụ"],
            "53" => ["ServiceCategoryController@destroy", "Quản lý loại dịch vụ"],
            "54" => ["ServiceCategoryController@active", "Quản lý loại dịch vụ"],
            "55" => ["ServiceCategoryController@index", "Quản lý loại dịch vụ"],

            "56" => ["PromotionController@index", "Quản lý khuyến mãi"],
            "57" => ["PromotionController@show", "Quản lý khuyến mãi"],
            "58" => ["PromotionController@store", "Quản lý khuyến mãi"],
            "59" => ["PromotionController@update", "Quản lý khuyến mãi"],
            "60" => ["PromotionController@destroy", "Quản lý khuyến mãi"],
            "61" => ["PromotionController@active", "Quản lý khuyến mãi"],

            "62" => ["PostCategoryController@index", "Quản lý loại bài viết"],
            "63" => ["PostCategoryController@show", "Quản lý loại bài viết"],
            "64" => ["PostCategoryController@store", "Quản lý loại bài viết"],
            "65" => ["PostCategoryController@update", "Quản lý loại bài viết"],
            "66" => ["PostCategoryController@destroy", "Quản lý loại bài viết"],
            "67" => ["PostCategoryController@active", "Quản lý loại bài viết"],

            "68" => ["PostController@index", "Quản lý bài viết"],
            "69" => ["PostController@show", "Quản lý bài viết"],
            "70" => ["PostController@store", "Quản lý bài viết"],
            "71" => ["PostController@update", "Quản lý bài viết"],
            "72" => ["PostController@destroy", "Quản lý bài viết"],
            "73" => ["PostController@active", "Quản lý bài viết"],

            "74" => ["PostCommentController@index", "Quản lý bình luận bài viết"],
            "75" => ["PostCommentController@show", "Quản lý bình luận bài viết"],
            "76" => ["PostCommentController@approve", "Quản lý bình luận bài viết"],
            "77" => ["PostCommentController@spam", "Quản lý bình luận bài viết"],
            "78" => ["PostCommentController@destroy", "Quản lý bình luận bài viết"],

            "79" => ["BranchController@index", "Quản lý chi nhánh"],
            "80" => ["BranchController@show", "Quản lý chi nhánh"],
            "81" => ["BranchController@store", "Quản lý chi nhánh"],
            "82" => ["BranchController@update", "Quản lý chi nhánh"],
            "83" => ["BranchController@destroy", "Quản lý chi nhánh"],
            "84" => ["BranchController@active", "Quản lý chi nhánh"],

            "85" => ["MembershipController@index", "Quản lý hạng thành viên"],
            "86" => ["MembershipController@show", "Quản lý hạng thành viên"],
            "87" => ["MembershipController@store", "Quản lý hạng thành viên"],
            "88" => ["MembershipController@update", "Quản lý hạng thành viên"],
            "89" => ["MembershipController@destroy", "Quản lý hạng thành viên"],
            "90" => ["MembershipController@active", "Quản lý hạng thành viên"],

            "91" => ["BookingController@index", "Quản lý đặt lịch"],
            "92" => ["BookingController@show", "Quản lý đặt lịch"],
            "93" => ["BookingController@store", "Quản lý đặt lịch"],
            "94" => ["BookingController@update", "Quản lý đặt lịch"],
            "95" => ["BookingController@destroy", "Quản lý đặt lịch"],
            "96" => ["BookingController@active", "Quản lý đặt lịch"],

            "97" => ["EmployeeController@index", "Quản lý nhân viên"],
            "98" => ["EmployeeController@show", "Quản lý nhân viên"],
            "99" => ["EmployeeController@store", "Quản lý nhân viên"],
            "100" => ["EmployeeController@update", "Quản lý nhân viên"],
            "101" => ["EmployeeController@destroy", "Quản lý nhân viên"],
            "102" => ["EmployeeController@active", "Quản lý nhân viên"],

            "103" => ["RoomController@index", "Quản lý phòng"],
            "104" => ["RoomController@show", "Quản lý phòng"],
            "105" => ["RoomController@store", "Quản lý phòng"],
            "106" => ["RoomController@update", "Quản lý phòng"],
            "107" => ["RoomController@destroy", "Quản lý phòng"],
            "108" => ["RoomController@active", "Quản lý phòng"],

            "109" => ["RoomTypeController@index", "Quản lý loại phòng"],
            "110" => ["RoomTypeController@show", "Quản lý loại phòng"],
            "111" => ["RoomTypeController@store", "Quản lý loại phòng"],
            "112" => ["RoomTypeController@update", "Quản lý loại phòng"],
            "113" => ["RoomTypeController@destroy", "Quản lý loại phòng"],
            "114" => ["RoomTypeController@active", "Quản lý loại phòng"],

            "115" => ["ImageCategoryController@index", "Quản lý loại hình ảnh"],
            "116" => ["ImageCategoryController@show", "Quản lý loại hình ảnh"],
            "117" => ["ImageCategoryController@store", "Quản lý loại hình ảnh"],
            "118" => ["ImageCategoryController@update", "Quản lý loại hình ảnh"],
            "119" => ["ImageCategoryController@destroy", "Quản lý loại hình ảnh"],
            "120" => ["ImageCategoryController@active", "Quản lý loại hình ảnh"],

            "121" => ["ImageController@index", "Quản lý hình ảnh"],
            "122" => ["ImageController@show", "Quản lý hình ảnh"],
            "123" => ["ImageController@store", "Quản lý hình ảnh"],
            "124" => ["ImageController@update", "Quản lý hình ảnh"],
            "125" => ["ImageController@destroy", "Quản lý hình ảnh"],
            "126" => ["ImageController@active", "Quản lý hình ảnh"],

            "127" => ["GoogleReviewController@index", "Quản lý đánh giá từ Google"],
            "128" => ["GoogleReviewController@show", "Quản lý đánh giá từ Google"],
            "129" => ["GoogleReviewController@store", "Quản lý đánh giá từ Google"],
            "130" => ["GoogleReviewController@update", "Quản lý đánh giá từ Google"],
            "131" => ["GoogleReviewController@destroy", "Quản lý đánh giá từ Google"],
            "132" => ["GoogleReviewController@active", "Quản lý đánh giá từ Google"],

            "133" => ["BranchRoomTypeController@index",   "Quản lý chi nhánh - loại phòng"],
            "134" => ["BranchRoomTypeController@show",    "Quản lý chi nhánh - loại phòng"],
            "135" => ["BranchRoomTypeController@store",   "Quản lý chi nhánh - loại phòng"],
            "136" => ["BranchRoomTypeController@update",  "Quản lý chi nhánh - loại phòng"],
            "137" => ["BranchRoomTypeController@destroy", "Quản lý chi nhánh - loại phòng"],
            "138" => ["BranchRoomTypeController@active",  "Quản lý chi nhánh - loại phòng"],

            "139" => ["EmployeeServiceController@index",   "Quản lý nhân sự - dịch vụ"],
            "140" => ["EmployeeServiceController@show",    "Quản lý nhân sự - dịch vụ"],
            "141" => ["EmployeeServiceController@store",   "Quản lý nhân sự - dịch vụ"],
            "142" => ["EmployeeServiceController@update",  "Quản lý nhân sự - dịch vụ"],
            "143" => ["EmployeeServiceController@destroy", "Quản lý nhân sự - dịch vụ"],
            "144" => ["EmployeeServiceController@active",  "Quản lý nhân sự - dịch vụ"],

            "145" => ["WorkingShiftController@index",   "Quản lý danh sách lịch làm việc"],
            "146" => ["WorkingShiftController@show",    "Quản lý danh sách lịch làm việc"],
            "147" => ["WorkingShiftController@store",   "Quản lý danh sách lịch làm việc"],
            "148" => ["WorkingShiftController@update",  "Quản lý danh sách lịch làm việc"],
            "149" => ["WorkingShiftController@destroy", "Quản lý danh sách lịch làm việc"],
            "150" => ["WorkingShiftController@active",  "Quản lý danh sách lịch làm việc"],

            "151" => ["EmployeeWorkingShiftController@index",   "Quản lý phân ca cho nhân viên"],
            "152" => ["EmployeeWorkingShiftController@show",    "Quản lý phân ca cho nhân viên"],
            "153" => ["EmployeeWorkingShiftController@store",   "Quản lý phân ca cho nhân viên"],
            "154" => ["EmployeeWorkingShiftController@update",  "Quản lý phân ca cho nhân viên"],
            "155" => ["EmployeeWorkingShiftController@destroy", "Quản lý phân ca cho nhân viên"],
            "156" => ["EmployeeWorkingShiftController@active",  "Quản lý phân ca cho nhân viên"],

            "157" => ["BookingController@confirmPayment",  "Quản lý đặt lịch"],
            "158" => ["BookingController@complete",  "Quản lý đặt lịch"],
            "159" => ["BookingController@cancel",  "Quản lý đặt lịch"],
            "160" => ["BookingController@printInvoice", "In / xuất hóa đơn booking"],

        ];

        //ADD PERMISSIONS - Thêm các quyền
        DB::table('permissions')->delete(); //empty permission
        $addPermissions = [];
        foreach ($arPermissions as $name => $label) {
            $addPermissions[] = [
                'id' => $name,
                'name' => $label[0],
                'label' => $label[1],
            ];
        }
        \DB::table('permissions')->insert($addPermissions);

        //ADD ROLE - Them vai tro
        DB::table('roles')->delete(); //empty permission
        $datenow = date('Y-m-d H:i:s');
        $role = [
            ['id' => 1, 'name' => 'admin', 'label' => 'Admin', 'created_at' => $datenow, 'updated_at' => $datenow],
            ['id' => 2, 'name' => 'user', 'label' => 'User', 'created_at' => $datenow, 'updated_at' => $datenow],
            ['id' => 3, 'name' => 'company', 'label' => 'Company', 'created_at' => $datenow, 'updated_at' => $datenow],
        ];
        $addRoles = [];
        foreach ($role as $key => $label) {
            $addRoles[] = [
                'id' => $label['id'],
                'name' => $label['name'],
                'label' => $label['label'],
                'created_at' => $datenow,
                'updated_at' => $datenow,
            ];
        }
        //KIỂM TRA VÀ THÊM CÁC VAI TRÒ TRUYỀN VÀO NẾU CÓ
        DB::table('roles')->insert($addRoles);

        //BỔ SUNG ID QUYỀN NẾU CÓ
        //Full quyền Admin công ty
        $persAdmin = \App\Models\Permission::pluck('id');

        //Quyền cộng tác viên (vendor)
        $persVendor = [
            1,
            2,
            3
        ];

        //Quyền khách hàng
        $persCustomer = [
            1,
            2,
            3
        ];

        //Gán quyền vào Vai trò Admin
        $rolePerAdminCompany = \App\Models\Role::findOrFail(1);
        $rolePerAdminCompany->permissions()->sync($persAdmin);

        //Gán quyền vào Vai trò User
        $rolePerAgentEmployee = \App\Models\Role::findOrFail(2);
        $rolePerAgentEmployee->permissions()->sync($persVendor);

        //Gán quyền vào Vai trò Company
        $rolePerCustomer = \App\Models\Role::findOrFail(3);
        $rolePerCustomer->permissions()->sync($persCustomer);

        //Set tài khoản ID=1 và ID=2 là Admin
        $roleAdmin = User::findOrFail(2);
        $roleAdmin->roles()->sync([1]);
    }
}
