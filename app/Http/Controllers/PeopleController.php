<?php

namespace App\Http\Controllers;

use App\People;
use App\Http\Requests\StorePeoplePost;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = People::orderBy('created_at', 'desc')->get();
//dd($people);
        return response()->json($people); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \app\Http\Requests\StorePeoplePost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeoplePost $request)
    {
        $validated = $request->validated();
       
        $emails = $this->parseEmails($validated['data']);
        $people = $this->parsePeople($validated['data']);

        $data = People::firstOrCreate([
            'emails' => json_encode($emails), 
            'person' => json_encode($people),
            'ip_address' => $request->getClientIp(),
        ]);
        
        return response()->json(array('success' => true, 'last_insert_id' => $data->id), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\People  $people
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $people = People::find($id);
        $people->delete();
        return (['message' => 'record deleted']);
    }

    protected function parseEmails($data)
    {
        $emails = [];
        foreach($data as $person) {
            $emails[] = $person['email'];
        }
        return $emails;
    }

    protected function parsePeople($data)
    {
        $people = [];
        foreach($data as $person) {
            $person['name'] = $person['first_name'] . ' ' . $person['last_name'];
            $person['secret'] = md5($person['secret']);
            $people[] = $person;
        }
        //dd($people);
        $age = array_column($people, 'age');

        array_multisort($age, SORT_DESC, $people);
        //dd($people);
        return $people;
    }
}
