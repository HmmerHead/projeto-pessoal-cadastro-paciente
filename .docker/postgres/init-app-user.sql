\getenv app_user DB_USERNAME
\getenv app_password DB_PASSWORD
\getenv database POSTGRES_DB

CREATE ROLE :"app_user" LOGIN PASSWORD :'app_password';
GRANT CONNECT ON DATABASE :"database" TO :"app_user";
GRANT USAGE, CREATE ON SCHEMA public TO :"app_user";
