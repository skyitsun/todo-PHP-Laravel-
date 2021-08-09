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
        // $todoList = Todo::all();
        $todoList = Todo::paginate(5);

        $data = array();
        $data['result'] = "S000";
        $data['data'] = $todoList;
        // $data['page'] = (string) $todoList->links();

        // echo json_encode($data,JSON_UNESCAPED_UNICODE);
        return response()->json($data, 200);
        // return;
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

        // echo json_encode($data,JSON_UNESCAPED_UNICODE);
        return response()->json($data, 200);;
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
        $todoList = Todo::find($id);
        $data['result'] = "S000";
        $data['data'] = $todoList;
        // echo json_encode($data,JSON_UNESCAPED_UNICODE);
        return response()->json($data, 200);;
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
        // 참조가 있을경우
        $refer = $todoList['reference'];
        $type = $request->input('type'); // 단순 데이터 수정인지 TodoList의 상태를 바꾸는지 구분하는 값
        if($type) {
            if($refer) {
                // 문자열나누기
                $referArr = explode(',', $refer);
                $data['arr'] = $referArr;
                for($i=1; $i<count($referArr); $i++) {
                    $rfId = $referArr[$i];
                    $referList = Todo::find($rfId);
                    $data['referlist'] = $referList;
                    // 참조한 todoList가 미완료 상태일경우 break;
                    if($referList['status'] == 0) {
                        $data['result'] = 'ER000';
                        echo json_encode($data,JSON_UNESCAPED_UNICODE);
                        return ;
                    }
                }
            }
            // 참조한 todoList가 완료 상태일경우
            $todoList->update($request->all());
            // $data['request'] = $request;
            $data['result'] = 'S000';
        } else {
            $refer = $request->input('reference');
            if(!$refer) {
                $request['reference'] = '';
            }
            $todoList->update($request->all());
            $data['request'] = $request;
            $data['result'] = 'S000';
        }
        $data['data'] = $todoList;
        // echo json_encode($data,JSON_UNESCAPED_UNICODE);
        return response()->json($data, 200);;
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
        $todoList = Todo::where('reference', 'like', ','.$id)->get();

        if($todoList) {
            $data['result'] = 'ER001'; // 삭제하려는 TodoList를 참조한 TodoList가 존재할 경우 리턴값
        } else {   
            $result = Todo::destroy($id);
            if($result == 1) {
                $data['result'] = 'S000'; // TodoList가 정상적으로 삭제 되었을때
            } else {
                $data['result'] = 'ER000'; // TodoList가 삭제되지 않았을때
            }
        }
        // echo json_encode($data,JSON_UNESCAPED_UNICODE);
        return response()->json($data, 200);;
    }

        /**
     * search the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // 기존 데이터 삭제
    public function search(Request $request)
    {   
        $category = $request['searchCategory'];
        $searchStr = $request['searchStr'];
        switch ($category) {
            case '1':
                $todoList = Todo::where('id', $searchStr)->paginate(5);
                $data['result'] = 'S000';
                $data['data'] = $todoList;
                // pk 검색
                break;
            case '2':
                // 내용 검색
                $todoList = Todo::where('title', 'like', '%'.$searchStr.'%')->paginate(5);
                $data['result'] = 'S000';
                $data['data'] = $todoList;
                break;
            case '3':
                // 수정일 검색
                $tomorrow = Date('Y-m-d', strtotime($searchStr.' +1 day'));
                $todoList = Todo::whereBetween('updated_at', [$searchStr, $tomorrow])->paginate(5);
                $data['result'] = 'S000';
                $data['data'] = $todoList;
                break;
            case '4':
                // 등록일 검색
                $tomorrow = Date('Y-m-d', strtotime($searchStr.' +1 day'));
                $todoList = Todo::whereBetween('created_at', [$searchStr, $tomorrow])->paginate(5);
                $data['result'] = 'S000';
                $data['data'] = $todoList;
                break;
            case '5':
                //  상태 검색
                $todoList = Todo::where('status', $searchStr)->paginate(5);
                $data['result'] = 'S000';
                $data['data'] = $todoList;
                break;
            default:
                
                break;
        }

        // echo json_encode($data,JSON_UNESCAPED_UNICODE);
        return response()->json($data, 200);
    }
}
