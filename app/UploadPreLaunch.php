<?php
/**
 * Created by PhpStorm.
 * User: plastics1509moore
 * Date: 9/13/16
 * Time: 4:03 AM
 */

namespace App;

use Illuminate\Http\Request;

class UploadPreLaunch
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function set()
    {
        return $this->deleteDuplicate()
            ->createMaterial();
    }
    protected function deleteDuplicate()
    {
        if($this->request->position == 'about' || $this->request->position == 'work' || $this->request->position == 'launch'){
            $duplicate = PreLaunch::where('position', $this->request->position)
                ->first();
            if($duplicate){
                $duplicate->delete();
            }
        }
        return $this;
    }

    protected function createMaterial()
    {
        $obj = new PreLaunch;
        $obj->fill(array('position' => $this->request->position,
            'text' => $this->request->text,
            'mobile' => $this->request->mobile,
            'link' => $this->request->link,
            'launch' => $this->request->launch)
        );
        $obj->save();
        return $obj;
    }
}