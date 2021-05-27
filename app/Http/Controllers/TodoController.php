<?php

namespace App\Http\Controllers;

use App\Libraries\WebApiResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Todo;
use Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Todo::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" onclick="editTask('.$row->id.')" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" onclick="deleteTask('.$row->id.')" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|',
        ]);

        if ($validator->fails()) {
            return WebApiResponse::validationError($validator, $request);
        }
        try {
            $title              = $request->title;

            $todoObj            = new Todo;
            $todoObj->title     = $title;

            $todoObj->save();
            return WebApiResponse::success(200, $todoObj->toArray(), 'Created Successfully');
        } catch (\Throwable $th) {
            return WebApiResponse::error(500, [$th->getMessage()], 'Something Went Wrong');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $todo = Todo::findOrFail($id);
            return WebApiResponse::success(200, $todo->toArray(), 'Fetched Successfully');
        } catch (\Throwable $th) {
            return WebApiResponse::error(500, [$th->getMessage()], 'Something Went Wrong');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|',
        ]);

        if ($validator->fails()) {
            return WebApiResponse::validationError($validator, $request);
        }
        try {
            $todo = Todo::findOrFail($id);
            $todo->title = $request->title;

            $todo->save();

            return WebApiResponse::success(200, $todo->toArray(), 'Updated Successfully');
        } catch (\Throwable $th) {
            return WebApiResponse::error(500, [$th->getMessage()], 'Something Went Wrong');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $todo = Todo::findOrFail($id);

            $todo->delete();

            return WebApiResponse::success(200, [], 'Deleted Successfully');
        } catch (\Throwable $th) {
            return WebApiResponse::error(500, [$th->getMessage()], 'Something Went Wrong');
        }
    }
}
