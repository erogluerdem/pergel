<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BimClashTest;
use App\Models\BimModel;

class RunClashTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $testId;

    public function __construct($testId)
    {
        $this->testId = $testId;
    }

    public function handle()
    {
        $t = BimClashTest::find($this->testId);
        if (!$t) return;

        $t->status = 'running'; $t->save();
        $results = [];
        try {
            $aIds = is_array($t->models_a) ? $t->models_a : json_decode($t->models_a, true) ?? [];
            $bIds = is_array($t->models_b) ? $t->models_b : json_decode($t->models_b, true) ?? [];

            $models = BimModel::whereIn('id', array_values(array_unique(array_merge($aIds,$bIds))))->get()->keyBy('id');

            foreach($aIds as $ai){ foreach($bIds as $bi){
                if (!isset($models[$ai]) || !isset($models[$bi])) continue;
                $ma = $models[$ai]->metadata ?? null;
                $mb = $models[$bi]->metadata ?? null;
                $clash = false; $desc = '';
                if ($ma && $mb && isset($ma['bbox']) && isset($mb['bbox'])){
                    $clash = $this->bboxIntersects($ma['bbox'],$mb['bbox']);
                    $desc = $clash ? 'BBox intersects' : 'No intersection';
                } else {
                    $desc = 'Insufficient metadata';
                }
                $results[] = ['id'=>uniqid('clash_'),'a'=>$ai,'b'=>$bi,'desc'=>$desc,'status'=>$clash ? 'new':'ok'];
            }}

            $t->result = $results;
            $t->status = 'done';
            $t->save();
        } catch (\Exception $e) {
            $t->status = 'failed';
            $t->result = ['error'=>$e->getMessage()];
            $t->save();
        }
    }

    private function bboxIntersects($A, $B)
    {
        // A and B are ['min'=>[x,y,z],'max'=>[x,y,z]]
        for ($i=0;$i<3;$i++){
            if ($A['max'][$i] < $B['min'][$i] || $B['max'][$i] < $A['min'][$i]) return false;
        }
        return true;
    }
}
