require('es6-promise').polyfill();
// Load plugins
var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    clean = require('gulp-clean'),
    concat = require('gulp-concat'),
    cache = require('gulp-cache'),
    browserSync = require('browser-sync').create(),
    reload = browserSync.reload;

// Styles
gulp.task('styles', function() {
  return gulp.src('assets/src/styles/main.scss')
      .pipe(sass())
      .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
      .pipe(gulp.dest('assets/dist/styles'));
});

// Scripts
gulp.task('scripts', function() {
  return gulp.src('assets/src/scripts/**/*.js')
      .pipe(jshint('.jshintrc'))
      .pipe(jshint.reporter('default'))
      .pipe(concat('main.js'))
      .pipe(gulp.dest('assets/dist/scripts'));
});

// Images
gulp.task('images', function() {
  return gulp.src('assets/src/images/**/*')
      .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
      .pipe(gulp.dest('assets/dist/images'));
});

// Clean
gulp.task('clean', function() {
  return gulp.src(['assets/dist/styles', 'assets/dist/scripts', 'assets/dist/images'], {read: false})
      .pipe(clean());
});

// Default task
gulp.task('default', ['clean', 'styles', 'scripts', 'images']);

// Watch
gulp.task('watch', function() {

  browserSync.init({
    proxy: 'bobderrico.dev',
    files: ['**/*.php'],
    open: false
  });

  // Watch .scss files
  gulp.watch('assets/src/styles/**/*.scss', ['styles']).on('change', reload );

  // Watch .js files
  gulp.watch('assets/src/scripts/**/*.js', ['scripts']).on('change', reload);

  // Watch image files
  gulp.watch('assets/src/images/**/*', ['images']).on('change', reload);

});