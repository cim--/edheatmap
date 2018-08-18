mkdir -p public/fonts
mkdir -p public/js

cat node_modules/jquery/dist/jquery.js node_modules/three/build/three.js node_modules/moment/moment.js node_modules/chart.js/dist/Chart.js resources/assets/js/app.js > public/js/heatmap.js

cp node_modules/three/examples/fonts/helvetiker_regular.typeface.json public/fonts/
