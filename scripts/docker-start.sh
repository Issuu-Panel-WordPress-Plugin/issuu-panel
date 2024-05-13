sh scripts/build.sh
docker-compose up --build -d --remove-orphans
rm issuu-panel.zip
open http://localhost:8080