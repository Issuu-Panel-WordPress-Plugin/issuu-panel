sh scripts/build.sh \
&& docker cp issuu-panel.zip wordpress:/var/www/html/wp-content/plugins/ \
&& docker exec wordpress unzip -o /var/www/html/wp-content/plugins/issuu-panel.zip -d /var/www/html/wp-content/plugins/ \
&& rm issuu-panel.zip
