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
2-1)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306 // DB_PORT 는 xampp를 설치할때 설정해줬던 PORT로 변경
DB_DATABASE=todos // todos 라는 새로은 DB 생성
DB_USERNAME=root // 로컬 DB에 접속하는 root관리자 말고 다른 계정이 있을 경우 계정 및 비밀번호 입력
DB_PASSWORD= //

3. artisan 명령어 사용 및 composer.json의 라이브러리 설치를 위해 prompt에 composer install 명령 입력
4. APP_KEY 생성을 위해 php artisan key:generate 명령 입력
5. php artisan migrate 명령어 입력으로 todos TABLE 생성
6. php artisan serve 명령어로 서버 실행
7. http://localhost:8000/todo 로 접속 후 기능확인한다.
