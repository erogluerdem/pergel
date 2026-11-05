<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ErpController extends Controller
{
    public function index()
    {
        // Provide sample or real data for dashboard
        $connectionOk = true;
        $lastSync = null;
        $pending = 0;
        $failed24 = 0;
        $activity = [];

        if (Schema::hasTable('erp_logs')) {
            $lastSync = DB::table('erp_logs')->where('status','success')->orderBy('created_at','desc')->value('created_at');
            $pending = DB::table('erp_logs')->where('status','pending')->count();
            $failed24 = DB::table('erp_logs')->where('status','failed')->where('created_at','>=',now()->subDay())->count();
            $activity = DB::table('erp_logs')->select(DB::raw('DATE(created_at) as day'), DB::raw('sum(status="success") as success'), DB::raw('sum(status="failed") as failed'))->groupBy(DB::raw('DATE(created_at)'))->orderBy('day','desc')->limit(7)->get();
        } else {
            // sample data
            $lastSync = now()->subMinutes(5)->toDateTimeString();
            $pending = 3;
            $failed24 = 1;
            $activity = \App\Models\ErpLog::latest()->take(5)->get();
        }

        // mappings and settings
        $mappings = Schema::hasTable('erp_mappings') ? DB::table('erp_mappings')->get() : collect();
        $settings = Schema::hasTable('erp_settings') ? DB::table('erp_settings')->pluck('value','key') : collect();

        return view('cost.erp.index', compact('connectionOk','lastSync','pending','failed24','activity','mappings','settings'));
    }

    public function sync(Request $request)
    {
        // For demo: write a log row if table exists, otherwise simulate
        if (Schema::hasTable('erp_logs')) {
            DB::table('erp_logs')->insert(['direction'=>'platform->erp','data_count'=>5,'status'=>'success','message'=>'Manual sync executed','created_at'=>now(),'updated_at'=>now()]);
            return redirect()->route('cost.erp.index')->with('success','Senkronizasyon tamamlandı (log eklendi).');
        }
        return redirect()->route('cost.erp.index')->with('success','Senkronizasyon (simüle) tamamlandı.');
    }

    public function saveMapping(Request $request)
    {
        $data = $request->validate(['platform_code'=>'required','erp_account'=>'required']);
        if (Schema::hasTable('erp_mappings')) {
            DB::table('erp_mappings')->insert(['platform_code'=>$data['platform_code'],'erp_account'=>$data['erp_account'],'created_at'=>now(),'updated_at'=>now()]);
        }
        return redirect()->route('cost.erp.index')->with('success','Eşleme kaydedildi');
    }

    public function saveSettings(Request $request)
    {
        $data = $request->only(['api_url','username','token','cron']);
        if (Schema::hasTable('erp_settings')) {
            foreach($data as $k=>$v) {
                DB::table('erp_settings')->updateOrInsert(['key'=>$k],['value'=>$v,'updated_at'=>now()]);
            }
        }
        return redirect()->route('cost.erp.index')->with('success','Ayarlar kaydedildi');
    }
}
