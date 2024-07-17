<?php

namespace App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\Dashboard\Service\StoreServiceRequest;
use App\Http\Requests\Dashboard\Service\UpdateServiceRequest;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

use App\Models\Service;
use App\Models\AdvantageService;
use App\Models\Tagline;
use App\Models\AdvantageUser;
use App\Models\ThumbnailService;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('users_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('pages.dashboard.service.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.dashboard.service.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->all();
        $data['users_id'] = Auth::user()->id;

        //add to service
        $service = Service::create($data);

        //add to advantage service
        foreach ($data['advantage-service'] as $key => $value) {
            $advantage_service = new AdvantageService;
            $advantage_service->service_id = $service->id;
            $advantage_service->advantage_id = $value;
            $advantage_service->save();
        }

        // add to advantage user
        foreach ($data['advanage-user'] as $key => $value) {
            $advantage_user = new AdvantageUser;
            $advantage_user->service_id = $service->id;
            $advantage_user->advantage_id = $value;
            $advantage_user->save();
        }
        
        //add to thumbnail service
        if ($request->hasFile('thumbail')) {
            foreach ($request->file('thumbnail') as $file) {
                $path = $file->store(
                    'assets/service/thumbnail', 'public'
                );

                $thumbnail_service = new ThumbnailService;
                $thumbnail_service->service_id = $service['id'];
                $thumbnail_service->thumbnail = $path;
                $thumbnail_service->save();
            }
        }

        //add to tagline
        foreach ($data['tagline'] as $key => $value) {
            $tagline = new Tagline;
            $tagline->service_id = $service->id;
            $tagline->tagline = $value;
            $tagline->save();
        }

        toast()->success('Save has been success');
        return redirect()->route('member.service.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $advantage_service = AdvantageService::where('service_id', $service['id'])->get();
        $tagline = Tagline::where('service_id', $service['id'])->get();
        $advantage_user = AdvantageUser::where('service_id', $service['id'])->get();
        $thumbnail_service = ThumbnailService::where('service_id', $service['id'])->get();

        return view('pages.dashboard.service.edit', compact('service', 'advantage_service', 'tagline',
        'advantage_user', 'thumbnail_service'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $data = $request->all();

        //update to service
        $service->update($data);

        //update to advantage service
        foreach($data['advantage-service'] as $key => $value){
            $advantage_service = AdvantageService::find($key);            
            $advantage_service->advantage = $value;
            $advantage_service->save();
        }

        //add new advantage service
        if(isset($data['advantage_service'])){
            foreach($data['advantage-service'] as $key => $value){
                $advantage_service = AdvantageService::find($key);            
                $advantage_service->service_id = $service['id'];
                $advantage_service->advantage = $value;
                $advantage_service->save();
            }
        }

         //update to advantage user
         foreach($data['advantage-user'] as $key => $value){
            $advantage_user = AdvantageUser::find($key);            
            $advantage_user->advantage = $value;
            $advantage_user->save();
        }

        //add new advantage user
        if(isset($data['advantage_user'])){
            foreach($data['advantage-user'] as $key => $value){
                $advantage_user = AdvantageUser::find($key);            
                $advantage_user->service_id = $service['id'];
                $advantage_user->advantage = $value;
                $advantage_user->save();
            }
        }

         //update to tagline
         foreach($data['tagline'] as $key => $value){
            $tagline = Tagline::find($key);            
            $tagline->tagline = $value;
            $tagline->save();
        }

        //add new tagline
        if(isset($data['tagline'])){
            foreach($data['tagline-service'] as $key => $value){
                $tagline = Tagline::find($key);            
                $tagline->service_id = $service['id'];
                $tagline->tagline = $value;
                $tagline->save();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
