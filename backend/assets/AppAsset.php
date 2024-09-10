<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot/theme';
    public $baseUrl = '@web/theme';
    public $css = [
        // <!-- Morris Charts CSS -->
        'vendors/bower_components/morris.js/morris.css',
//        'vendors/bower_components/datatables/media/css/jquery.dataTables.min.css',
        // switchery CSS
		'vendors/bower_components/switchery/dist/switchery.min.css',
        // <!-- Data table CSS -->
        'vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.css',
        // Sweet-Alert
        'vendors/bower_components/sweetalert/dist/sweetalert.css',
        // Pretty print json
        'dist/css/pretty-print-json.css',
        // Site styles
        'dist/css/style.css',
        // Custom styles
        'dist/css/common.css',
        'dist/css/custom.css',
    ];
    public $js = [
        'vendors/bower_components/jquery/dist/jquery.min.js',
        // Bootstrap Core JavaScript
        'vendors/bower_components/bootstrap/dist/js/bootstrap.min.js',
        // Data table JavaScript
        'vendors/bower_components/datatables/media/js/jquery.dataTables.min.js',
        // Switchery JavaScript
        'vendors/bower_components/switchery/dist/switchery.min.js',
        // Slimscroll JavaScript
        'dist/js/jquery.slimscroll.js',
        // simpleWeather JavaScript
        'vendors/bower_components/moment/min/moment.min.js',
        'vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js',
        'dist/js/simpleweather-data.js',
        // Progressbar Animation JavaScript
        'vendors/bower_components/waypoints/lib/jquery.waypoints.min.js',
        'vendors/bower_components/jquery.counterup/jquery.counterup.min.js',
        // Fancy Dropdown JS
        'dist/js/dropdown-bootstrap-extended.js',
        // Sparkline JavaScript
        'vendors/jquery.sparkline/dist/jquery.sparkline.min.js',
        // Owl JavaScript
        'vendors/bower_components/owl.carousel/dist/owl.carousel.min.js',
        // ChartJS JavaScript
        'vendors/chart.js/Chart.min.js',
        // Morris Charts JavaScript
        'vendors/bower_components/raphael/raphael.min.js',
        'vendors/bower_components/morris.js/morris.min.js',
        'vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js',
        // Switchery JavaScript
        'vendors/bower_components/switchery/dist/switchery.min.js',
        // Sweet-Alert
        'vendors/bower_components/sweetalert/dist/sweetalert.min.js',
        'dist/js/sweetalert-data.js',
        // Init JavaScript
        'dist/js/init.js',
//        'dist/js/dashboard-data.js',
        // Pretty print json
        'dist/js/pretty-print-json.min.js',
        'dist/js/toast-custom.js',
        'dist/js/shadow.js',
        'dist/js/common.js',
        'dist/js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
/*

<!-- jQuery -->
    <script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

	<!-- Data table JavaScript -->
	<script src="vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>

	<!-- Slimscroll JavaScript -->
	<script src="dist/js/jquery.slimscroll.js"></script>

	<!-- simpleWeather JavaScript -->
	<script src="vendors/bower_components/moment/min/moment.min.js"></script>
	<script src="vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js"></script>
	<script src="dist/js/simpleweather-data.js"></script>

	<!-- Progressbar Animation JavaScript -->
	<script src="vendors/bower_components/waypoints/lib/jquery.waypoints.min.js"></script>
	<script src="vendors/bower_components/jquery.counterup/jquery.counterup.min.js"></script>

	<!-- Fancy Dropdown JS -->
	<script src="dist/js/dropdown-bootstrap-extended.js"></script>

	<!-- Sparkline JavaScript -->
	<script src="vendors/jquery.sparkline/dist/jquery.sparkline.min.js"></script>

	<!-- Owl JavaScript -->
	<script src="vendors/bower_components/owl.carousel/dist/owl.carousel.min.js"></script>

	<!-- ChartJS JavaScript -->
	<script src="vendors/chart.js/Chart.min.js"></script>

	<!-- Morris Charts JavaScript -->
    <script src="vendors/bower_components/raphael/raphael.min.js"></script>
    <script src="vendors/bower_components/morris.js/morris.min.js"></script>
    <script src="vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js"></script>

	<!-- Switchery JavaScript -->
	<script src="vendors/bower_components/switchery/dist/switchery.min.js"></script>

	<!-- Init JavaScript -->
	<script src="dist/js/init.js"></script>
	<script src="dist/js/dashboard-data.js"></script>


*/
