FROM wordpress:latest

# install unzip
RUN apt-get update && apt-get install -y unzip

COPY issuu-panel.zip /var/www/html/wp-content/plugins/
# unzip the plugin
RUN unzip -o /var/www/html/wp-content/plugins/issuu-panel.zip -d /var/www/html/wp-content/plugins/
