var gulp = require('gulp');
var less = require('gulp-less');
var css_min = require('gulp-minify-css');
var concat = require('gulp-concat');
var js_min = require('gulp-uglify');
var rename = require('gulp-rename');
var gzip = require('gulp-gzip');

var root = 'app/assets/';
var components = root + 'components/';
var destination = 'public/larapress/assets/';

var bootstrap = components + 'bootstrap/';
var jquery = components + 'jquery/dist/jquery.js';
var html5shiv = components + 'html5shiv/dist/html5shiv.js';
var respond = components + 'respond/src/respond.js';

var paths = {
    less: [bootstrap + 'less/bootstrap.less'],
    scripts: [
        jquery,
        bootstrap + 'js/transition.js',
        bootstrap + 'js/alert.js',
        bootstrap + 'js/modal.js',
        bootstrap + 'js/dropdown.js',
        bootstrap + 'js/scrollspy.js',
        bootstrap + 'js/tab.js',
        bootstrap + 'js/tooltip.js',
        bootstrap + 'js/popover.js',
        bootstrap + 'js/button.js',
        bootstrap + 'js/collapse.js',
        bootstrap + 'js/carousel.js',
        bootstrap + 'js/affix.js'
    ],
    fallbacks: [html5shiv, respond],
    fonts: [bootstrap + 'fonts/*']
};

gulp.task('less', function() {
    return gulp.src(paths.less)
        .pipe(less())
        .pipe(css_min())
        .pipe(rename('larapress.css'))
        .pipe(gulp.dest(destination + 'css'))
        .pipe(gzip({threshold: true, gzipOptions: {level: 9}}))
        .pipe(gulp.dest(destination + 'css'));
});

gulp.task('js', function() {
    return gulp.src(paths.scripts)
        .pipe(concat('larapress.js'))
        .pipe(js_min())
        .pipe(gulp.dest(destination + 'js'))
        .pipe(gzip({threshold: true, gzipOptions: {level: 9}}))
        .pipe(gulp.dest(destination + 'js'));
});

gulp.task('fallback', function() {
    return gulp.src(paths.fallbacks)
        .pipe(concat('fallback.js'))
        .pipe(js_min())
        .pipe(gulp.dest(destination + 'js'))
        .pipe(gzip({threshold: true, gzipOptions: {level: 9}}))
        .pipe(gulp.dest(destination + 'js'));
});

gulp.task('fonts', function() {
    return gulp.src(paths.fonts)
        .pipe(gulp.dest(destination + 'fonts'));
});

gulp.task('default', ['less', 'js', 'fallback', 'fonts']);