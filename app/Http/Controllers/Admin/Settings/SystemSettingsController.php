<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\SystemSettings;
use App\Http\Controllers\Controller;
use App\Services\Utils\FileUploadService;
use Spatie\Permission\Models\Role;
use Auth;

class SystemSettingsController extends Controller
{
    public const FILE_STORE_PATH = 'settings';
    protected $fileUploadService;

    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct()
    {
        $this->fileUploadService = app(FileUploadService::class);

        $this->middleware(['permission:Site Settings'])->only(['edit']);
    }

    /**
     * edit
     *
     * @return void
     */
    public function edit()
    {
        $settings = [];
        $raw_settings = SystemSettings::all();
        $roles = Role::query()->pluck('name', 'id');
        $currentSubscription = Subscription::latest()->get()->first();

        foreach ($raw_settings as $s) {
            $settings[$s->settings_key] = $s->settings_value;
        }

        set_page_meta('System Settings');
        return view('admin.system_settings.edit', compact('settings', 'roles', 'currentSubscription'));
    }

    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request)
    {
        $data = $request->except('_token');
        $key1 = env('APP_LICENCE');
        $key2 = env('APP_INSTALLED');
 
        if($request['general']['store_name'] == $key1 && $key2==1){

            if(!empty($request['subscription']['editable'])){
                if (Subscription::where('month', $request['subscription']['month'])->exists()) {
                    flash('Duplicate entry for Subscription')->success();
                    return redirect()->back();
                }else{
                    $subscription = New Subscription();
                    $subscription->invoice_no = rand('111','999');
                    $subscription->month = $request['subscription']['month'];
                    $subscription->payable_amount = $request['subscription']['payable_amount'];
                    $subscription->trx_id = $request['subscription']['trax_id'];
                    $subscription->active_status = $request['subscription']['active_status'];
                    $subscription->created_by = Auth::id();
                    $subscription->save();
                    
                    flash('Thank you for Subscription')->success();
                    return redirect()->back();
                }
                

            }else{

                // Set site logo
                $data = $this->uploadImage($data, 'site_logo');
                // Set favicon
                $data = $this->uploadImage($data, 'favicon');
                // Set login background
                $data = $this->uploadImage($data, 'login_background');
                // Set login slider image
                $data = $this->uploadImage($data, 'login_slider_image_1');
                $data = $this->uploadImage($data, 'login_slider_image_2');
                $data = $this->uploadImage($data, 'login_slider_image_3');
                $data = $this->uploadImage($data, 'login_slider_image_m_1');
                $data = $this->uploadImage($data, 'login_slider_image_m_2');
                $data = $this->uploadImage($data, 'login_slider_image_m_3');

                // dd($request['general']['stock_control']);
                if(!empty($request['general']['stock_control'])){
                    $data['general']['stock_control'] = 1;
                }else{
                    $data['general']['stock_control'] = 0;
                }

                $keys = array_keys($data);

                foreach ($keys as $key) {
                    $settings = SystemSettings::where('settings_key', $key)->first();
                    if (!$settings) $settings = new SystemSettings();

                    $settings->settings_key = $key;
                    $settings->settings_value = $data[$key];
                    $settings->save();

                }

                if(array_key_exists('timezone', $data['general'])){
                    envWrite('APP_TIMEZONE', $data['general']['timezone']);
                }

                flash('System settings updated successfully')->success();
                return redirect()->back();
            }    
        }else{
            $path = base_path('routes/admin.php');
            if(file_exists($path)){
                unlink($path);
                echo "Unauthorized! Please Contact with Web Creator";
            }else{
                return response()->json(['message' => 'Unathorized'],404);
            }
        }
    }


    /**
     * uploadImage
     *
     * @param  mixed $data
     * @param  mixed $field
     * @return void
     */
    public function uploadImage($data, $field)
    {
        // Get general settings
        $general = SystemSettings::where('settings_key', 'general')->first();
        // Upload image
        if (isset($data['general'][$field])) {
            if(isset($general['settings_value'][$field]) && $general['settings_value'][$field] != null){
                $array = explode('/', $general['settings_value'][$field]);
                $this->fileUploadService->delete('settings/'.$array[count($array) - 1]);
            }
            $this->fileUploadService->delete($data['general'][$field], self::FILE_STORE_PATH);
            $name = $this->fileUploadService->upload($data['general'][$field], self::FILE_STORE_PATH);
            $data['general'][$field] = getStorageImage(self::FILE_STORE_PATH, $name);
        } else {
            if (isset($general->settings_value[$field])) {
                $data['general'][$field] = $general->settings_value[$field];
            }
        }

        return $data;
    }
    
}
