# TODO: Correct Dockerfile and Related Files

- [x] Edit docker-compose.yml: Change app service expose from 80 to 9000
- [x] Edit nginx.conf: Change listen from 8080 to 80, change fastcgi_pass from php:9000 to app:9000
- [x] Edit Dockerfile: Remove COPY tailwind.config.js ./ since the file is not present in the project
- [ ] Test the build with docker-compose build
- [ ] Run the containers and verify the app serves correctly
