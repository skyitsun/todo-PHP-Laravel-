<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos</title>
</head>
<style>
.delBtn {
    border : 0px;
    background-color : #ffffff;
    cursor: pointer;
    margin : auto;
    font-size : 20px;
}

.modBtn {
    border : 0px;
    background-color : #ffffff;
    cursor: pointer;
    margin : auto;
    font-size : 20px;
}

.statBtn {
    border : 0px;
    background-color : #ffffff;
    cursor: pointer;
    margin : auto;
}

tr {
    border : 1px solid #333;
    /* background-color : #FDF5E6; */
}

table {
    margin : auto;
    border-collapse : collapse;
    width : 50%;
}

</style>
<body>
    <h1 style="font-size:50px; text-align : center;">Todo List</h1>
    <div style="text-align : center;">
        <!-- <select id="refer">

        </select> -->
        <!-- <button onclick="popOpen();">참고할 TodoList</button> -->
        <input type="text" id="tdIns" value=""> <button id="subBtn" onclick="insertTodoList();" value="0">등록</button>
    </div>
    <div style="margin : 20px;"></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>no</th>
                <th>내용</th>
                <th>수정일</th>
                <th>등록일</th>
                <th>상태</th>
                <th>수정</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody id="tbody">
            <!-- api 연결안했을때 테이블 확인용 -->
            <!-- <tr>
                <td>
                    <input type="checkbox" id="check_1">
                </td>
                <td>
                    <p>TodoList 1</p>
                </td>
                <td>
                    <div style="text-align:center;">
                        <button type="button" class="delBtn"> X </button>
                    </div>
                </td>
            </tr> -->
        </tbody>
    </table>
    <div style="text-align:center;">
        <select id="searchCategory">
            <option value="1">no</option>
            <option value="2">내용</option>
            <option value="3">수정일</option>
            <option value="4">등록일</option>
            <option value="5">상태</option>
        </select>
        <input type="text" id="searchStr" value=""> <button id="searBtn" onclick="serchTodoList();" value="0">검색</button>
    </div>

    <div id="page" style="text-align:center;">

    </div>
    
    
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        showTodoList(1);
    });

    function showTodoList(page) {
        $.ajax({
            url : "http://localhost:8000/api/todos?page="+page,
            type : "GET",
            success : function (s) {
                //  console.log(s); // 데이터 전달여부 확인 콘솔
                // var data = JSON.parse(s); // 데이터 JSON 파싱
                // var todoList = data['data']; // 전달된 데이터에서 todoList 가져오기
                // console.log(s);
                // var todoList = s['data']; // 전달된 데이터에서 todoList 가져오기
                var todoList = s['data']['data']; // 기존 방법이 아닌 라라벨 방식에 맞게 변경
                // var totalPage = s['data']['last_page'];

                var html = ""; // table 의 tr 추가용
                var opt = ""; // select->option 추가용
                var stat = ""; // status 처리를 위한 변수
                var page = "";
                var perPage = s['data']['per_page'];
                var lastPage = s['data']['last_page'];
                var currentPage = s['data']['current_page'];
                var pageList = s['data']['links']; // page 처리를 위한 변수
                console.log(perPage);
                console.log(pageList);
                console.log("currentPage : " + currentPage);
                console.log("lastPage : " + lastPage);
                for(var i=0; i < todoList.length; i++) {
                    // console.log(todoList[$i]); // todoList 가져온게 맞는지 확인하는 콘솔
                    // var beTime = new Date(todoList[$i]['updated_at']);
                    // var time = timeConvert(beTime);
                    // console.log(time);
                    html += "<tr>";
                    html += "<td><input type='checkbox' name='checkId' id='check_"+todoList[i]['id']+"' value='"+todoList[i]['id']+"'></td>";
                    html += "<td style='text-align:center;'><p>"+todoList[i]['id'];+"</p></td>";
                    if(!todoList[i]['reference']) {
                        html += "<td style='text-align:center;'><p>"+todoList[i]['title']+"</p></td>";
                    } else {
                        html += "<td style='text-align:center;'><p>"+todoList[i]['title']+" @참조 : "+todoList[i]['reference'].substr(1)+"</p></td>";
                    }
                    if(!todoList[i]['status']) {
                        stat = "<td><button class='statBtn' style='color:red;' value='"+todoList[i]['id']+"-"+todoList[i]['status']+"' onclick='statusTodo(this.value);'>미완료</button></td>";
                    } else {
                        stat = "<td><button class='statBtn' value='"+todoList[i]['id']+"-"+todoList[i]['status']+"' onclick='statusTodo(this.value);'>완료</button></td>";
                    }
                    html += "<td> 수정일 : "+timeConvert(new Date(todoList[i]['updated_at']))+"</td>";
                    html += "<td> 작성일 : "+timeConvert(new Date(todoList[i]['created_at']))+"</td>";
                    html += stat;
                    html += "<td><div style='text-align:center;'><button type='button' class='modBtn' value='"+todoList[i]['id']+"' onclick='modTodo(this.value);'> 수정 </button></div></td>";
                    html += "<td><div style='text-align:center;'><button type='button' class='delBtn' value='"+todoList[i]['id']+"' onclick='delTodo(this.value);'> 삭제 </button></div></td>";
                    html += "</tr>";

                    // opt += "<option value='"+todoList[$i]['id']+"'>"+todoList[$i]['title']+"</option>";
                }


                if(currentPage < perPage) {
                    for(var i=0; i < perPage; i++) {
                        console.log(perPage-i);
                        if(i+1 == currentPage) {
                            page += "<button style='font-weight:bold;' onclick='showTodoList("+(i+1)+")'>"+(i+1)+"</button>";
                        } else {
                            page += "<button onclick='showTodoList("+(i+1)+")'>"+(i+1)+"</button>";
                        }
                        
                    }
                } else if(lastPage - currentPage < (perPage - 1)) {
                    for(var i=perPage-1; i >= 0; i--) {
                        if(currentPage == (lastPage - i)) {
                            page += "<button style='font-weight:bold;' onclick='showTodoList("+(lastPage-i)+")'>"+(lastPage-i)+"</button>";
                        } else {
                            page += "<button onclick='showTodoList("+(lastPage-i)+")'>"+(lastPage-i)+"</button>";
                        }
                    }
                } else {
                    for(var i=-parseInt(perPage / 2); i < parseInt(perPage / 2)+1; i++) {
                        console.log(i);
                        if(currentPage == (currentPage + i)) {
                            page += "<button style='font-weight:bold;' onclick='showTodoList("+(currentPage+i)+")'>"+(currentPage+i)+"</button>";
                        } else {
                            page += "<button onclick='showTodoList("+(currentPage+i)+")'>"+(currentPage+i)+"</button>";
                        }
                        
                    }
                }

                $("#tbody").html(html);
                $("#page").html(page);
            },
            error : function (e) {
                console.log(e);
            }
        });
    }

    function insertTodoList() {
        var todoInsert = $("#tdIns").val();
        var chbox = checkBoxValue();
        console.log(typeof(chbox));
        if(chbox.length == 0) {
            var data = {"title" : todoInsert};
        } else {
            var data = {"title" : todoInsert, "reference" : chbox};
        }
        console.log(data);
        var status = $("#subBtn").val();
        // status 가 있을때는 수정
        if(status != 0) {
            $.ajax({
            url : "http://localhost:8000/api/todos/"+status,
            type : "PUT",
            // data : {"title" : todoInsert, "reference" : chbox},
            data : data,
            success : function (s) {
                // console.log("성공?");
                // console.log(s);
                location.reload();
            },
            error : function (e) {
                console.log(e);
            }
        });
        } else {
            // status 가 0일때 혹은 없을때는 TodoList 등록
            $.ajax({
            url : "http://localhost:8000/api/todos",
            type : "POST",
            // data : {"title" : todoInsert, "reference" : chbox},
            data : data,
            success : function (s) {
                // console.log("성공?");
                location.reload();
            },
            error : function (e) {
                console.log(e);
            }
        });
        }

    }

    // 시간을 YYYY-MM-DD 형시긍로 변환하는 함수
    function timeConvert(time) {
        var year = time.getFullYear();
        var month = time.getMonth()+1;
        var date = time.getDate();

        return year+"-"+month+"-"+date;
    }

    // 체크한 todolist의 value 가져오기
    function checkBoxValue() {
        var len = $("input[name='checkId']:checked").length;
        var checkStr = "";

        if(len > 0) {
            $("input[name='checkId']:checked").each(function(e) {
                var re = $(this).val();
                // if(checkStr) {
                checkStr += ","+re;
                // } else {
                    // checkStr = re;
                // }
            })
        }
        console.log(checkStr);
        return checkStr;
    }

    // TodoList 수정
    function modTodo(chId) {

        // console.log("chId : "+chId);
        $.ajax({
            url : "http://localhost:8000/api/todos/"+chId,
            type : "GET",
            success : function (s) {
                // var data = JSON.parse(s); // 데이터 JSON 파싱
                // console.log(s);
                // var todoList = data['data'];
                var todoList = s['data'];
                $("#tdIns").val(todoList['title']);
                $("#subBtn").val(todoList['id']);
            },
            error : function (e) {
                console.log(e);
            }
        });

        $("#subBtn").text("수정");
    }

    // TodoList 삭제
    function delTodo(chId) {
        $.ajax({
            url : "http://localhost:8000/api/todos/"+chId,
            type : "DELETE",
            success : function (s) {
                // var data = JSON.parse(s);
                var data = s['data'];
                var result = data['result'];
                if(result == 'S000') {
                    alert('TodoList가 정상적으로 삭제되었습니다.');
                    location.reload();
                } else if(result == 'ER001') {
                    alert('TodoList를 참조해제한 후에 삭제해주세요.');
                } else {
                    alert('잠시후에 다시 시도해주세요.');
                }
                // console.log(s);
                // alert("성공적으로 삭제 되었습니다.",true);
                
            },
            error : function (e) {
                console.log(e);
            }
        });
    }

    function statusTodo(chId) {
        // '-' 기준으로 문자열 나누기 0 번인수는 PK값, 1번인수는 상태값
        // type 변수는 TodoList의 상태를 바꾸는 호출인지 확인하기 위한 값
        var chIdArr = chId.split('-');
        var pk = chIdArr[0];
        var status = chIdArr[1];
        if(status == "0") {
            status = "1";
        } else {
            status = "0";
        }
        $.ajax({
            url : "http://localhost:8000/api/todos/"+pk,
            type : "PUT",
            data : {"status" : status, "type" : 1},
            success : function (s) {
                // console.log(s);
                // var data = JSON.parse(s);
                var data = s['data'];
                if(data['result'] == 'S000') {
                    console.log(s);
                    location.reload();
                } else {
                    alert("참조한 TodoList가 미완료 상태입니다.");
                }
            },
            error : function (e) {
                console.log(e);
            }
        });
    }

    function serchTodoList(page) {
        // 검색할 카테고리
        var searchCategory = $("#searchCategory").val();
        // 검색내용
        var searchStr = $("#searchStr").val();

        if((1 > searchCategory) || (5 < searchCategory)) {
            alert("정상적인 값을 입력해주세요");
        }
        
        if(searchCategory == '3') {
            var searchStr = searchTimeConvert(searchStr);
            console.log(searchStr);
            // return;
        } else if(searchCategory == '4') {
            var searchStr = searchTimeConvert(searchStr);
            // console.log(searchStr);
            // return;
        } else if(searchCategory == '5') {
            if(searchStr == '완료') {
                searchStr = '1';
            } else if(searchStr == '미완료') {
                searchStr = '0';
            } else {
                alert("상태검색은 '완료' '미완료' 만 가능합니다.");
                return;
            }
        }

        // console.log("searchCategory : " + searchCategory);
        // console.log("searchStr : "+searchStr);
        if(searchStr == "") {
            alert("검색어를 입력해주세요");
            return;
        }

        $.ajax({
            url : "http://localhost:8000/api/todos/search?page="+page,
            type : "POST",
            data : {"searchCategory": searchCategory,"searchStr":searchStr},
            success : function (s) {
                                //  console.log(s); // 데이터 전달여부 확인 콘솔
                // var data = JSON.parse(s); // 데이터 JSON 파싱
                // var todoList = data['data']; // 전달된 데이터에서 todoList 가져오기
                console.log(s);
                // var todoList = s['data']; // 전달된 데이터에서 todoList 가져오기
                var todoList = s['data']['data']; // 전달된 데이터에서 todoList 가져오기
                // var totalPage = s['data']['last_page'];

                var html = ""; // table 의 tr 추가용
                var opt = ""; // select->option 추가용
                var stat = ""; // status 처리를 위한 변수
                var page = "";
                var perPage = s['data']['per_page'];
                var lastPage = s['data']['last_page'];
                var currentPage = s['data']['current_page'];
                var pageList = s['data']['links']; // page 처리를 위한 변수
                console.log(perPage);
                console.log(pageList);
                console.log("currentPage : " + currentPage);
                console.log("lastPage : " + lastPage);
                for(var i=0; i < todoList.length; i++) {
                    // console.log(todoList[$i]); // todoList 가져온게 맞는지 확인하는 콘솔
                    // var beTime = new Date(todoList[$i]['updated_at']);
                    // var time = timeConvert(beTime);
                    // console.log(time);
                    html += "<tr>";
                    html += "<td><input type='checkbox' name='checkId' id='check_"+todoList[i]['id']+"' value='"+todoList[i]['id']+"'></td>";
                    html += "<td style='text-align:center;'><p>"+todoList[i]['id'];+"</p></td>";
                    if(!todoList[i]['reference']) {
                        html += "<td style='text-align:center;'><p>"+todoList[i]['title']+"</p></td>";
                    } else {
                        html += "<td style='text-align:center;'><p>"+todoList[i]['title']+" @참조 : "+todoList[i]['reference'].substr(1)+"</p></td>";
                    }
                    if(!todoList[i]['status']) {
                        stat = "<td><button class='statBtn' style='color:red;' value='"+todoList[i]['id']+"-"+todoList[i]['status']+"' onclick='statusTodo(this.value);'>미완료</button></td>";
                    } else {
                        stat = "<td><button class='statBtn' value='"+todoList[i]['id']+"-"+todoList[i]['status']+"' onclick='statusTodo(this.value);'>완료</button></td>";
                    }
                    html += "<td> 수정일 : "+timeConvert(new Date(todoList[i]['updated_at']))+"</td>";
                    html += "<td> 작성일 : "+timeConvert(new Date(todoList[i]['created_at']))+"</td>";
                    html += stat;
                    html += "<td><div style='text-align:center;'><button type='button' class='modBtn' value='"+todoList[i]['id']+"' onclick='modTodo(this.value);'> 수정 </button></div></td>";
                    html += "<td><div style='text-align:center;'><button type='button' class='delBtn' value='"+todoList[i]['id']+"' onclick='delTodo(this.value);'> 삭제 </button></div></td>";
                    html += "</tr>";

                    // opt += "<option value='"+todoList[$i]['id']+"'>"+todoList[$i]['title']+"</option>";
                }

                if(lastPage < perPage) {
                    for(var i=0; i<lastPage; i++) {
                        if(i+1 == currentPage) {
                            page += "<button style='font-weight:bold;' onclick='serchTodoList("+(i+1)+")'>"+(i+1)+"</button>";
                        } else {
                            page += "<button onclick='serchTodoList("+(i+1)+")'>"+(i+1)+"</button>";
                        }
                    }
                } else if(currentPage < perPage) {
                    for(var i=0; i < perPage; i++) {
                        console.log(perPage-i);
                        if(i+1 == currentPage) {
                            page += "<button style='font-weight:bold;' onclick='serchTodoList("+(i+1)+")'>"+(i+1)+"</button>";
                        } else {
                            page += "<button onclick='serchTodoList("+(i+1)+")'>"+(i+1)+"</button>";
                        }
                        
                    }
                } else if(lastPage - currentPage < (perPage - 1)) {
                    for(var i=perPage-1; i >= 0; i--) {
                        if(currentPage == (lastPage - i)) {
                            page += "<button style='font-weight:bold;' onclick='serchTodoList("+(lastPage-i)+")'>"+(lastPage-i)+"</button>";
                        } else {
                            page += "<button onclick='serchTodoList("+(lastPage-i)+")'>"+(lastPage-i)+"</button>";
                        }
                    }
                } else {
                    for(var i=-parseInt(perPage / 2); i < parseInt(perPage / 2)+1; i++) {
                        console.log(i);
                        if(currentPage == (currentPage + i)) {
                            page += "<button style='font-weight:bold;' onclick='serchTodoList("+(currentPage+i)+")'>"+(currentPage+i)+"</button>";
                        } else {
                            page += "<button onclick='serchTodoList("+(currentPage+i)+")'>"+(currentPage+i)+"</button>";
                        }
                        
                    }
                }
                $("#tbody").html(html);
                $("#page").html(page);
            },
            error : function (e) {
                console.log(e);
            }
        });

    }

    // 검색날짜 변환
    function searchTimeConvert(time) {
        var timestamp = new Date(time);
        var year = timestamp.getFullYear();
        var month = timestamp.getMonth()+1;
        var date = timestamp.getDate();
        var today = year+"-"+month+"-"+date;

        return today;
    }

</script>
</html>