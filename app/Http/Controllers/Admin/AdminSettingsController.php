<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Settings;

use File;

class AdminSettingsController extends AdminBaseController
{
    public function getIndex(){

        $title = 'Настройки';
        $settings = Settings::first();

        return view('admin.settings', compact(['settings', 'title']));
    }


    public function postIndex( Request $request ){
        if( $image = $request->file('logo') )
        {
            File::delete( public_path('uploads/logo/'. Settings::find(1)->logo ));
            $icoid = Settings::saveSettings( $image );
            Settings::where('id', '=', 1)->update(['logo' => $icoid]);
        }        
         if( $image = $request->file('baner') )
        {
            File::delete( public_path('uploads/baner/'. Settings::find(1)->baner ));
            $icoid = Settings::saveSettingsBaner( $image );
            Settings::where('id', '=', 1)->update(['baner' => $icoid]);
        }
        Settings::first()->update( array_merge($request->except(['_token', 'logo', 'baner', 'logo2'])) );

        return redirect()->back()->with('success', 'Настройки сохранены');
    }
}
