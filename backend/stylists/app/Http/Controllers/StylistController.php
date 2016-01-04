<?php

namespace App\Http\Controllers;

use App\Models\Lookups\Lookup;
use App\Models\Lookups\Status;
use App\Stylist;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;

class StylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id){
            $this->resource_id = $id;
        }
        return $this->$method($request);
    }

    public function getList(Request $request){
        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $status_list = Status::all()->keyBy('id');
        $status_list[0] = new Status();

        $stylists =
            Stylist::
                orderBy('stylish_id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['stylists'] = $stylists;
        $view_properties['status_list'] = $status_list;

        return view('stylist.list', $view_properties);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getView()
    {
        $stylist = Stylist::find($this->resource_id);
        $view_properties = null;
        if($stylist){
            $status_list = Status::all()->keyBy('id');
            $status_list[0] = new Status();

            $view_properties['stylist'] = $stylist;
            $view_properties['status_list'] = $status_list;
            $view_properties['looks'] = $stylist->looks;
        }
        else{
            return view('404', array('title' => 'Stylist not found'));
        }

        return view('stylist.view', $view_properties);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit()
    {
        $stylist = Stylist::find($this->resource_id);
        $view_properties = null;
        if($stylist){
            $lookup = new Lookup();

            $view_properties['stylist'] = $stylist;
            $view_properties['gender_id'] = $stylist->gender_id;
            $view_properties['genders'] = $lookup->type('gender')->get();
            $view_properties['status_id'] = $stylist->status_id;
            $view_properties['statuses'] = $lookup->type('status')->get();
            $view_properties['expertise_id'] = $stylist->expertise_id;
            $view_properties['expertises'] = $lookup->type('expertise')->get();
        }
        else{
            return view('404', array('title' => 'Stylist not found'));
        }

        return view('stylist.edit', $view_properties);
    }

    public function postImage(Request $request)
    {
        $stylist = Stylist::find($this->resource_id);

        if($stylist) {

            $imageValidator =  Validator::make($request->all(), [
                'image' => 'required|image',
            ]);
            if($imageValidator ->fails()){
                return redirect('stylist/edit/' . $this->resource_id)
                    ->withErrors($imageValidator)
                    ->withInput();
            }

            if ($request->file('image')->isValid()) {
                $destinationPath = public_path() . '/' . env('STYLIST_IMAGE_PATH');
                $filename = $request->file('image')->getClientOriginalName();
                $request->file('image')->move($destinationPath, $filename);
                $stylist->image = 'stylish/' . $filename;
                $stylist->save();
            }
        }
        else{
            return view('404', array('title' => 'Stylist not found'));
        }

        return redirect('stylist/edit/' . $this->resource_id);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails()){
            return redirect('stylist/edit/' . $this->resource_id)
                ->withErrors($validator)
                ->withInput();
        }

        $stylist = Stylist::find($this->resource_id);
        $stylist->name = isset($request->name) && $request->name != '' ? $request->name : '';
        $stylist->email = isset($request->email) && $request->email != '' ? $request->email : '';
        $stylist->description = isset($request->description) && $request->description != '' ? $request->description : '';
        $stylist->age = isset($request->age) && $request->age != '' ? $request->age : '';
        $stylist->profile = isset($request->profile) && $request->profile != '' ? $request->profile : '';
        $stylist->code = isset($request->code) && $request->code != '' ? $request->code : '';
        $stylist->status_id = isset($request->status_id) && $request->status_id != '' ? $request->status_id : '';;
        $stylist->expertise_id = isset($request->expertise_id) && $request->expertise_id != '' ? $request->expertise_id : '';
        $stylist->gender_id = isset($request->gender_id) && $request->gender_id != '' ? $request->gender_id : '';
        $stylist->save();

        return redirect('stylist/view/' . $this->resource_id);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|min:5',
            'email' => 'required|email|max:255',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
