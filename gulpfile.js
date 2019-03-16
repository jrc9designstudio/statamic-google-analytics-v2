// include the required packages.
const autoprefixer = require('gulp-autoprefixer');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
const eslint = require('gulp-eslint');
const gulp = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const stylus = require('gulp-stylus');
const uglify = require('gulp-uglify');

gulp.task('styl', function () {
  return gulp.src('./resources/assets/src/styl/styles.styl')
    .pipe(sourcemaps.init())
    .pipe(stylus({
      compress: true,
    }))
    .pipe(autoprefixer('last 2 version'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./resources/assets/css'));
});

gulp.task('eslint', () => {
  return gulp.src([
      './resources/assets/src/js/**/*.js',
      '!./node_modules/**',
    ])
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failOnError());
});

gulp.task('js', () => {
  return gulp.src([
      './resources/assets/src/js/scripts.js',
      './resources/assets/src/js/fieldtype.js',
      './resources/assets/src/js/mixins/**/*.js',
      './resources/assets/src/js/components/**/*.js',
    ])
    .pipe(sourcemaps.init())
    .pipe(babel())
    .pipe(concat('scripts.js'))
    .pipe(uglify())
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./resources/assets/js/'));
});

// gulp.task('default', ['styl', 'eslint', 'js']);
gulp.task('default', gulp.parallel('styl', 'eslint', 'js'));

gulp.task('watch', function(){
  gulp.watch('./resources/assets/src/styl/**/*.styl', ['styl']);
  gulp.watch('./resources/assets/src/js/**/*.js', ['eslint', 'js']);
});
