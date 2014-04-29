var gulp = require('gulp');
var less = require('gulp-less');
var css_min = require('gulp-minify-css');
var concat = require('gulp-concat');
var js_min = require('gulp-uglify');
var gzip = require('gulp-gzip');

var root = 'app/assets/';
var components = root + 'components/';
var larapress = root + 'larapress/';
var destination = 'public/larapress/assets/';

var bootstrap = components + 'bootstrap/';
var jquery = components + 'jquery/dist/jquery.js';
var html5shiv = components + 'html5shiv/dist/html5shiv.js';
var respond = components + 'respond/src/respond.js';

var paths = {
    less: [
        bootstrap + 'less/bootstrap.less',
        larapress + 'less/app.less'
    ],
    less_per_page: larapress + 'less/pages/**/*.less',
    js: [
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
    fallback: [html5shiv, respond],
    fonts: [bootstrap + 'fonts/*']
};

gulp.task('less', function() {
    return gulp.src(paths.less)
        .pipe(concat('larapress.less'))
        .pipe(less())
        .pipe(css_min())
        .pipe(gulp.dest(destination + 'css'))
        .pipe(gzip({threshold: true, gzipOptions: {level: 9}}))
        .pipe(gulp.dest(destination + 'css'));
});

gulp.task('less-per-page', function() {
    return gulp.src(paths.less_per_page)
        .pipe(less())
        .pipe(css_min())
        .pipe(gulp.dest(destination + 'css/pages'))
        .pipe(gzip({threshold: true, gzipOptions: {level: 9}}))
        .pipe(gulp.dest(destination + 'css/pages'));
});

gulp.task('js', function() {
    return gulp.src(paths.js)
        .pipe(concat('larapress.js'))
        .pipe(js_min())
        .pipe(gulp.dest(destination + 'js'))
        .pipe(gzip({threshold: true, gzipOptions: {level: 9}}))
        .pipe(gulp.dest(destination + 'js'));
});

gulp.task('fallback', function() {
    return gulp.src(paths.fallback)
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

gulp.task('watch', function() {
    gulp.watch(paths.less, ['less']);
    gulp.watch(larapress + 'less/app/**/*.less', ['less']);
    gulp.watch(paths.less_per_page, ['less-per-page']);
    gulp.watch(paths.js, ['js']);
    gulp.watch(paths.fallback, ['fallback']);
    gulp.watch(paths.fonts, ['fonts']);
});

gulp.task('default',
    [
        'less',
        'less-per-page',
        'js',
        'fallback',
        'fonts'
    ]
);
