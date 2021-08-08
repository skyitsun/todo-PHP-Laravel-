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
        <select id="refer">

        </select>
        <input type="text" id="tdIns"> <button onclick="insertTodoList();">등록</button>
    </div>
    <div style="margin : 20px;"></div>
    <table>
        <!-- <thead>
            <tr>
                <th colspan="2">The table header</th>
            </tr>
        </thead> -->
        <tbody id="tbody">
            <tr>
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
            </tr>
        </tbody>
    </table>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        showTodoList();
    });

    function showTodoList() {
        $.ajax({
            url : "http://localhost:8000/api/todos",
            type : "GET",
            success : function (s) {
                //  console.log(s); // 데이터 전달여부 확인 콘솔
                 var data = JSON.parse(s); // 데이터 JSON 파싱
                 var todoList = data['data']; // 전달된 데이터에서 todoList 가져오기
                 var html = ""; // table 의 tr 추가용
                 var opt = ""; // select->option 추가용
                 var stat = "";
                 for($i=0; $i < todoList.length; $i++) {
                    // console.log(todoList[$i]); // todoList 가져온게 맞는지 확인하는 콘솔
                    // var beTime = new Date(todoList[$i]['updated_at']);
                    // var time = timeConvert(beTime);
                    // console.log(time);
                    html += "<tr>";
                    html += "<td><input type='checkbox' id='check_"+todoList[$i]['id']+"'></td>";
                    if(!todoList[$i]['reference']) {
                        html += "<td><p>"+todoList[$i]['title']+"</p></td>";
                    } else {
                        html += "<td><p>"+todoList[$i]['title']+" 참조 : "+todoList[$i]['reference']+"</p></td>";
                    }
                    if(!todoList[$i]['status']) {
                        stat = "<td><button class='statBtn' style='color:red;'>미완료</button></td>";
                    } else {
                        stat = "<td><button class='statBtn'>완료</button></td>";
                    }
                    html += "<td> 수정일 : "+timeConvert(new Date(todoList[$i]['updated_at']))+"</td>";
                    html += "<td> 작성일 : "+timeConvert(new Date(todoList[$i]['created_at']))+"</td>";
                    html += stat;
                    html += "<td><div style='text-align:center;'><button type='button' class='delBtn'> X </button></div></td>";
                    html += "</tr>";

                    opt += "<option value='"+todoList[$i]['id']+"'>"+todoList[$i]['title']+"</option>";
                 }

                 $("#tbody").html(html);
                 $("#refer").html(opt);
            },
            error : function (e) {
                conosle.log(e);
            }
        });
    }

    function insertTodoList() {
        var todoInsert = $("#tdIns").val();
        $.ajax({
            url : "http://localhost:8000/api/todos",
            type : "POST",
            data : {"title" : todoInsert},
            success : function (s) {
                console.log("성공?");
                location.reload();
            },
            error : function (e) {
                conosle.log(e);
            }
        });
    }

    // 시간을 YYYY-MM-DD 형시긍로 변환하는 함수
    function timeConvert(time) {
        var year = time.getFullYear();
        var month = time.getMonth();
        var date = time.getDate();

        return year+"-"+month+"-"+date;
    }
</script>
</html>