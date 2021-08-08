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
        <input type="text"> <button>등록</button>
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
                 var html = "";
                 for($i=0; $i < todoList.length; $i++) {
                    // console.log(todoList[$i]); // todoList 가져온게 맞는지 확인하는 콘솔
                    // var beTime = new Date(todoList[$i]['updated_at']);
                    // var time = timeConvert(beTime);
                    // console.log(time);
                    html += "<tr>";
                    html += "<td><input type='checkbox' id='check_'"+todoList[$i]['id']+"></td>";
                    html += "<td><p>"+todoList[$i]['title']+"</p></td>";
                    html += "<td> 수정일 : "+timeConvert(new Date(todoList[$i]['updated_at']))+"</td>";
                    html += "<td> 작성일 : "+timeConvert(new Date(todoList[$i]['created_at']))+"</td>";
                    html += "<td><div style='text-align:center;'><button type='button' class='delBtn'> X </button></div></td>";
                    html += "</tr>";
                 }

                 $("#tbody").html(html);
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