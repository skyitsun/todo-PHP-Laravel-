<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // 데이터 목록 가져오기
    public function index()
    {
        // $todos = DB::tables('todos')->paginate(5);
        $todoList = Todo::all();

        $data = array();
        $data['result'] = "S000";
        $data['data'] = $todoList;
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        return;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // 새 데이터를 만드는 화면을 반환
    // public function create()
    // {
        //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // 새 데이터 추가
    public function store(Request $request)
    {   
        $request->validate([ // 꼭 전달되어야 하는 param 검사
            'title' => 'required'
        ]);

        $todoList = Todo::create($request->all());

        $data = array();
        $data['result'] = "S000";
        $data['data'] = $todoList;

        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        return ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 특정 데이터를 가져오기(하나의 데이터)
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 기존데이터를 수정하는 화면을 반환
    // public function edit($id)
    // {
        // 
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 기존데이터를 수정 -> 수정된 데이터를 반환
    public function update(Request $request, $id)
    {
        $todoList = Todo::find($id);
        $todoList->update($request->all());
        return $todoList;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 기존 데이터 삭제
    public function destroy($id)
    {
        return Todo::destroy($id);
    }
}
