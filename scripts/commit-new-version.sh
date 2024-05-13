cd ..
# Add files to svn repo
cp -R plugin-src/* issuu-panel/trunk/
cp -R plugin-src/*.png issuu-panel/assets/
# Commit to svn
cd issuu-panel
svn add trunk/* --force
svn add assets/* --force
svn commit -m "New version"
# Make it to wordpress
svn up