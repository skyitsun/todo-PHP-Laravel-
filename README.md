# todo

개발환경

- PHP version 7.4.22
- PHP Laravel 8.53.1
- MariaDB version 10.4.20
 * PHP 및 MariaDB는 xampp로 설치
- composer 설치

빌드 및 실행방법

1. git clone repository 생성
2. root 디렉토리의 .env.example 파일을 복사 붙여넣기 후 .env로 파일명 변경

2-1.
- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306 // DB_PORT 는 xampp를 설치할때 설정해줬던 PORT로 변경
- DB_DATABASE=todos // todos 라는 새로은 DB 생성
- DB_USERNAME=root // 로컬 DB에 접속하는 root관리자 말고 다른 계정이 있을 경우 계정 및 비밀번호 입력
- DB_PASSWORD= //

3. artisan 명령어 사용 및 composer.json의 라이브러리 설치를 위해 prompt에 composer install 명령 입력
4. APP_KEY 생성을 위해 php artisan key:generate 명령 입력
5. php artisan migrate 명령어 입력으로 todos TABLE 생성
6. php artisan serve 명령어로 서버 실행
7. http://localhost:8000/todo 로 접속 후 기능확인

8. 기능

8-1. Todo 입력
![image](https://user-images.githubusercontent.com/59412658/128730191-f9553b52-83d0-4132-9ddf-1805165e1de4.png)

8-1-1. input에 Todo 입력후 등록을 누르면 데이터 저장. 참조하고 싶은 Todo가 있다면 체크박스에 체크 후 등록

8-2. Todo 수정

8-2-1. 수정글씨를 클릭할 경우
![image](https://user-images.githubusercontent.com/59412658/128730476-ac1bfa20-c537-4cea-a631-ac39f19d412c.png)

8-2-2. input 에 수정하고 싶은 Todo의 title이 자동입력. 그리고 등록버튼이 수정으로 변경.
8-2-3. 참조하고 싶은 Todo가 있다면 체크박스에 체크 후 수정

8-3. Todo 삭제

8-3-1. 삭제글씨 클릭할 경우 삭제
* 단, 삭제할 Todo를 참조하는 다른 Todo가 있을 경우 이미지와 같이 alert창이 뜨며 삭제불가. 참조 해제 후 삭제 가능
![image](https://user-images.githubusercontent.com/59412658/128731247-ba64517d-d7cd-4c77-aa78-41b61d604518.png)

8-4. Todo 상태 변화

8-4-1. 미완료 or 완료 버튼 클릭할 경우 상태변화
* 단, 참조중인 Todo가 미완료 상태일 경우 이미지와 같이 alert 창이 뜨며 상태변화 불가. 참조한 Todo의 상태가 완료 일때만 완료로 상태변화 가능
![image](https://user-images.githubusercontent.com/59412658/128731646-e6461625-38d8-458f-b005-c18ddf4e985c.png)

8-5. Todo 검색

8-5-1. select의 option을 컬럼명으로 할 경우 데이터 탈취당했을때 컬럼명이 밝혀지므로 숫자로 value를 지정한 다음 BackEnd 부분에서 switch로 분류
8-5-2. 수정일 및 등록일 검색을 할 경우 BackEnd 부분에서 strtotime을 이용해 하루의 데이터를 검색
ex) 한국에서 2021-08-10을 검색할 경우 DB에는 UTC 기준으로 저장이 되니 변환하여 2021-08-09 09:00:00 부터 2021-08-12 09:00:00 까지 검색되게 작업


